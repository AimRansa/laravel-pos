<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\DetailPesanan;
use App\Models\ResepMenu;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Setting;
use App\Models\LaporanDetail;
use App\Models\LaporanStok;
use Carbon\Carbon;

class CsvController extends Controller
{
    public function showForm()
    {
        return view('csv.upload');
    }

    public function upload(Request $request)
    {
        // VALIDASI FILE
        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|mimes:csv,txt|max:40960',
        ]);

        if ($validator->fails()) {
            return back()->withErrors("❌ File CSV wajib format .csv dan maksimal 40MB.");
        }

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');

        // BACA HEADER CSV
        $firstLine = fgets($handle);
        $header = str_getcsv($firstLine, ';');

        // KOLOM WAJIB
        $required = ['idtransaksi', 'id_menu', 'tanggal_transaksi', 'total_pesanan'];

        foreach ($required as $col) {
            if (!in_array($col, $header)) {
                return back()->withErrors("❌ Kolom '$col' tidak ditemukan dalam CSV.");
            }
        }

        // INDEX KOLOM
        $idx_trans    = array_search('idtransaksi', $header);
        $idx_menu     = array_search('id_menu', $header);
        $idx_tanggal  = array_search('tanggal_transaksi', $header);
        $idx_qty      = array_search('total_pesanan', $header);

        // VARIABEL PENAMPUNG
        $stokPerProduk = [];
        $transactions = [];
        $lineNumber = 1;

        DB::beginTransaction();

        try {

            while (($line = fgets($handle)) !== false) {

                $lineNumber++;
                $row = str_getcsv($line, ';');

                // VALIDASI ID TRANSAKSI
                if (!isset($row[$idx_trans]) || trim($row[$idx_trans]) == '') {
                    throw new \Exception("❌ Baris $lineNumber: 'idtransaksi' kosong.");
                }
                $idtrans = trim($row[$idx_trans]);

                // VALIDASI ID MENU
                if (!isset($row[$idx_menu]) || trim($row[$idx_menu]) == '') {
                    throw new \Exception("❌ Baris $lineNumber: 'id_menu' kosong.");
                }

                $id_menu = trim($row[$idx_menu]);
                $menu = Cart::where('id_menu', $id_menu)->first();

                if (!$menu) {
                    throw new \Exception("❌ Baris $lineNumber: ID Menu '$id_menu' tidak ditemukan.");
                }

                // VALIDASI QTY
                if (!is_numeric($row[$idx_qty])) {
                    throw new \Exception("❌ Baris $lineNumber: total_pesanan harus angka.");
                }

                $qty = (int)$row[$idx_qty];
                if ($qty <= 0) {
                    throw new \Exception("❌ Baris $lineNumber: total_pesanan harus lebih dari 0.");
                }

                // VALIDASI TANGGAL (dd/mm/YYYY)
                $tglCsv = trim($row[$idx_tanggal]);
                try {
                    $tanggalFix = Carbon::createFromFormat('d/m/Y', $tglCsv)->format('Y-m-d');
                } catch (\Exception $err) {
                    throw new \Exception("❌ Baris $lineNumber: Format tanggal '$tglCsv' salah (gunakan dd/mm/YYYY).");
                }

                // ===============================================================
                //  UPDATE / INSERT ORDER + CATAT upload_at SETIAP IMPORT
                // ===============================================================
                $order = Order::updateOrCreate(
                    ['idtransaksi' => $idtrans],
                    [
                        'tanggal_transaksi' => $tanggalFix,
                        'total_pesanan'     => 0,
                        'total_harga'       => 0,
                        'upload_at'         => now(), // <── INI PENCATATAN UPLOAD YG KAMU MAU
                    ]
                );

                // SIMPAN DETAIL PESANAN
                DetailPesanan::create([
                    'idtransaksi' => $idtrans,
                    'id_menu'     => $id_menu,
                    'quantity'    => $qty,
                    'harga'       => $menu->harga,
                    'subtotal'    => $menu->harga * $qty,
                ]);

                // RESEP & PENGURANGAN STOK
                $resepList = ResepMenu::where('id_menu', $id_menu)->get();

                if ($resepList->isEmpty()) {
                    throw new \Exception("❌ Menu '$id_menu' tidak memiliki resep.");
                }

                foreach ($resepList as $r) {

                    $pakai = $r->takaran * $qty;
                    $produk = Product::find($r->id_produk);

                    if (!$produk) {
                        throw new \Exception("❌ Produk ID '{$r->id_produk}' tidak ditemukan.");
                    }

                    if ($produk->jumlah_stok < $pakai) {
                        throw new \Exception("❌ Stok '{$produk->nama_stok}' tidak cukup. Dibutuhkan $pakai {$produk->satuan}.");
                    }

                    Product::where('id', $r->id_produk)->decrement('jumlah_stok', $pakai);

                    if (!isset($stokPerProduk[$r->id_produk])) {
                        $stokPerProduk[$r->id_produk] = [
                            'nama'   => $produk->nama_stok,
                            'satuan' => $produk->satuan,
                            'jumlah' => 0
                        ];
                    }

                    $stokPerProduk[$r->id_produk]['jumlah'] += $pakai;
                }

                $transactions[$idtrans] = true;
            }

            // UPDATE TOTAL ORDER
            foreach (array_keys($transactions) as $trx) {
                $details = DetailPesanan::where('idtransaksi', $trx)->get();

                Order::where('idtransaksi', $trx)->update([
                    'total_pesanan' => $details->sum('quantity'),
                    'total_harga'   => $details->sum('subtotal'),
                ]);
            }

            // LAPORAN HARIAN
            $laporan = Setting::firstOrCreate(
                ['tanggal_laporan' => Carbon::today()->toDateString()],
                ['jumlah_transaksi' => 0, 'total_stok' => 0]
            );

            $laporan->increment('jumlah_transaksi', count($transactions));

            foreach ($stokPerProduk as $s) {
                LaporanStok::create([
                    'laporan_id'       => $laporan->id_laporan,
                    'nama_produk'      => $s['nama'],
                    'jumlah_berkurang' => $s['jumlah'],
                    'satuan'           => $s['satuan'],
                ]);

                $laporan->increment('total_stok', $s['jumlah']);
            }

            DB::commit();
            fclose($handle);

            return redirect()->route('orders.index')
                ->with('success', "CSV berhasil diimport!");

        } catch (\Throwable $e) {

            DB::rollBack();
            fclose($handle);

            return back()->withErrors($e->getMessage());
        }
    }
}
