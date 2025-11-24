<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;
use App\Models\DetailPesanan;
use App\Models\ResepMenu;
use App\Models\Product;
use App\Models\Cart; // untuk mengambil harga menu

class CsvController extends Controller
{
    public function showForm()
    {
        return view('csv.upload');
    }

    public function upload(Request $request)
    {
        // Validasi file CSV
        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|mimes:csv,txt|max:4096',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $file = $request->file('csv_file');
        $handle = fopen($file, 'r');

        // Ambil header (pakai semicolon)
        $headerLine = fgets($handle);
        $header = str_getcsv($headerLine, ';');

        // Pastikan kolom wajib ada
        if (!in_array('idtransaksi', $header) ||
            !in_array('id_menu', $header) ||
            !in_array('total_pesanan', $header)) 
        {
            return back()->withErrors("❌ CSV harus memiliki kolom: idtransaksi;id_menu;total_pesanan");
        }

        // Ambil index kolom
        $index_idtransaksi   = array_search('idtransaksi', $header);
        $index_id_menu       = array_search('id_menu', $header);
        $index_total_pesanan = array_search('total_pesanan', $header);

        $count = 0;

        // Loop isi CSV
        while (($line = fgets($handle)) !== false) {

            $row = str_getcsv($line, ';');

            // Abaikan baris tidak lengkap
            if (!isset($row[$index_idtransaksi]) ||
                !isset($row[$index_id_menu]) ||
                !isset($row[$index_total_pesanan])) 
            {
                continue;
            }

            $idtransaksi   = trim($row[$index_idtransaksi]);
            $id_menu       = trim($row[$index_id_menu]);
            $total_pesanan = (int) trim($row[$index_total_pesanan]);

            // 1️⃣ Buat order jika belum ada
            $order = Order::firstOrCreate(
                ['idtransaksi' => $idtransaksi],
                [
                    'tanggal_transaksi' => now(),
                    'total_pesanan'     => 0,
                    'total_harga'       => 0,
                ]
            );

            // 2️⃣ Tambahkan detail pesanan
            DetailPesanan::create([
                'idtransaksi' => $idtransaksi,
                'id_menu'     => $id_menu,
                'quantity'    => $total_pesanan,
                'harga'       => 0,
                'subtotal'    => 0,
            ]);

            // 3️⃣ Kurangi stok sesuai resep menu
            $resepMenu = ResepMenu::where('id_menu', $id_menu)->get();

            foreach ($resepMenu as $resep) {

                // total pemakaian = takaran × jumlah pesanan
                $total_pakai = $resep->takaran * $total_pesanan;

                Product::where('id', $resep->id_produk)
                    ->decrement('jumlah_stok', $total_pakai);
            }

            // 4️⃣ HITUNG ULANG total_pesanan dan total_harga
            $detail = DetailPesanan::where('idtransaksi', $idtransaksi)->get();

            // total pesanan seluruh menu
            $total_pesanan_fix = $detail->sum('quantity');

            // hitung total harga seluruh menu
            $total_harga_fix = 0;
            foreach ($detail as $d) {
                $menu_harga = Cart::where('id_menu', $d->id_menu)->value('harga') ?? 0;
                $total_harga_fix += ($menu_harga * $d->quantity);
            }

            // update order
            $order->update([
                'total_pesanan' => $total_pesanan_fix,
                'total_harga'   => $total_harga_fix,
            ]);

            $count++;
        }

        fclose($handle);

        return redirect()->route('orders.index')
            ->with('success', "✅ {$count} baris CSV berhasil diimport! Total pesanan + harga dihitung otomatis dan stok dikurangi.");
    }
}
