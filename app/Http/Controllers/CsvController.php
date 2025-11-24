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
use App\Models\Setting;           // tabel laporan
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
        // Validasi CSV
        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|mimes:csv,txt|max:40960',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');

        // Baca header
        $headerLine = fgets($handle);
        $header = str_getcsv($headerLine, ';');

        // Daftar kolom WAJIB
        if (
            !in_array('idtransaksi', $header) ||
            !in_array('id_menu', $header) ||
            !in_array('total_pesanan', $header) ||
            !in_array('tanggal_transaksi', $header)
        ) {
            fclose($handle);
            return back()->withErrors("❌ Header CSV wajib: idtransaksi; id_menu; tanggal_transaksi; total_pesanan");
        }

        // Ambil index
        $idx_trans  = array_search('idtransaksi', $header);
        $idx_menu   = array_search('id_menu', $header);
        $idx_tanggal = array_search('tanggal_transaksi', $header);
        $idx_qty    = array_search('total_pesanan', $header);

        // Penampung
        $lapDetail = [];
        $stokPerProduk = [];
        $uniqueTrans = [];

        DB::beginTransaction();

        try {

            while (($line = fgets($handle)) !== false) {

                $row = str_getcsv($line, ';');

                if (!isset($row[$idx_trans]) || !isset($row[$idx_menu])) continue;

                $idtrans  = trim($row[$idx_trans]);
                $id_menu  = trim($row[$idx_menu]);
                $qty      = (int) trim($row[$idx_qty]);
                $tglCsv   = trim($row[$idx_tanggal]);

                if ($idtrans === '' || $id_menu === '') continue;

                // Format tanggal CSV dd/mm/YYYY → YYYY-mm-dd
                $tanggalFix = Carbon::createFromFormat('d/m/Y', $tglCsv)->format('Y-m-d');

                // Ambil menu dr tabel cart
                $menu = Cart::where('id_menu', $id_menu)->first();
                if (!$menu) {
                    throw new \Exception("❌ ID Menu '$id_menu' tidak ditemukan di tabel cart!");
                }

                // Buat order jika belum ada
                $order = Order::firstOrCreate(
                    ['idtransaksi' => $idtrans],
                    [
                        'tanggal_transaksi' => $tanggalFix,
                        'total_pesanan'     => 0,
                        'total_harga'       => 0
                    ]
                );

                // Insert detail pesanan
                DetailPesanan::create([
                    'idtransaksi' => $idtrans,
                    'id_menu'     => $id_menu,
                    'quantity'    => $qty,
                    'harga'       => $menu->harga,
                    'subtotal'    => $menu->harga * $qty,
                ]);

                // Hitung stok berdasarkan resep
                $resepList = ResepMenu::where('id_menu', $id_menu)->get();

                foreach ($resepList as $r) {
                    $pakai = $r->takaran * $qty;
                    Product::where('id', $r->id_produk)->decrement('jumlah_stok', $pakai);

                    $produk = Product::find($r->id_produk);

                    if (!isset($stokPerProduk[$r->id_produk])) {
                        $stokPerProduk[$r->id_produk] = [
                            'nama'   => $produk->nama_stok,
                            'jumlah' => 0,
                            'satuan' => $produk->satuan
                        ];
                    }

                    $stokPerProduk[$r->id_produk]['jumlah'] += $pakai;
                }

                // Simpan laporan detail grouped by transaksi
                $lapDetail[$idtrans][] = [
                    'id_menu'   => $id_menu,
                    'nama_menu' => $menu->nama_menu,
                    'quantity'  => $qty,
                    'subtotal'  => $menu->harga * $qty,
                ];

                $uniqueTrans[$idtrans] = true;
            }

            // Update total order
            foreach (array_keys($uniqueTrans) as $trx) {

                $details = DetailPesanan::where('idtransaksi', $trx)->get();

                $totalQty   = $details->sum('quantity');
                $totalHarga = $details->sum('subtotal');

                Order::where('idtransaksi', $trx)->update([
                    'total_pesanan' => $totalQty,
                    'total_harga'   => $totalHarga,
                ]);
            }

            // Buat laporan harian
            $laporan = Setting::firstOrCreate(
                ['tanggal_laporan' => Carbon::today()->toDateString()],
                ['jumlah_transaksi' => 0, 'total_stok' => 0]
            );

            $laporan->increment('jumlah_transaksi', count($uniqueTrans));

            // Insert laporan_detail
            foreach ($lapDetail as $trx => $rows) {
                foreach ($rows as $d) {
                    LaporanDetail::create([
                        'laporan_id' => $laporan->id_laporan,
                        'id_menu'    => $d['id_menu'],
                        'nama_menu'  => $d['nama_menu'],
                        'quantity'   => $d['quantity'],
                        'subtotal'   => $d['subtotal'],
                    ]);
                }
            }

            // Insert laporan_stok
            $totalStok = 0;
            foreach ($stokPerProduk as $s) {
                LaporanStok::create([
                    'laporan_id'       => $laporan->id_laporan,
                    'nama_produk'      => $s['nama'],
                    'jumlah_berkurang' => $s['jumlah'],
                    'satuan'           => $s['satuan'],
                ]);

                $totalStok += $s['jumlah'];
            }

            $laporan->increment('total_stok', $totalStok);

            DB::commit();
            fclose($handle);

            return redirect()->route('orders.index')
                ->with('success', "CSV berhasil diimport & laporan harian berhasil diperbarui.");

        } catch (\Throwable $e) {

            DB::rollBack();
            fclose($handle);

            return back()->withErrors("❌ Error saat import CSV: " . $e->getMessage());
        }
    }
}
