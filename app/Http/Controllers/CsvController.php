<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;
use App\Models\DetailPesanan;
use App\Models\ResepMenu;
use App\Models\Product;

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

        // Ambil header CSV
        $header = fgetcsv($handle);

        // Pastikan CSV memiliki 3 kolom
        if (count($header) !== 3) {
            return back()->withErrors("❌ Format CSV harus 3 kolom: idtransaksi,id_menu,qty");
        }

        $count = 0;

        while (($row = fgetcsv($handle)) !== false) {

            // Abaikan baris kosong
            if (count($row) < 3) {
                continue;
            }

            $idtransaksi = $row[0];
            $id_menu     = $row[1];
            $qty         = (int) $row[2];

            // 1️⃣ Buat Order jika belum ada
            $order = Order::firstOrCreate(
                ['idtransaksi' => $idtransaksi],
                [
                    'tanggal_transaksi' => now(),
                    'total_pesanan'     => 0,
                    'total_harga'       => 0,
                ]
            );

            // 2️⃣ Tambah detail pesanan
            DetailPesanan::create([
                'idtransaksi' => $idtransaksi,
                'id_menu'     => $id_menu,
                'quantity'    => $qty,
                'harga'       => 0,
                'subtotal'    => 0,
            ]);

            // 3️⃣ Kurangi stok sesuai resep menu
            $resepMenu = ResepMenu::where('id_menu', $id_menu)->get();

            foreach ($resepMenu as $resep) {

                // total pemakaian = takaran * qty pesanan
                $total_pakai = $resep->takaran * $qty;

                Product::where('id', $resep->id_produk)
                    ->decrement('jumlah_stok', $total_pakai);
            }

            $count++;
        }

        fclose($handle);

        return redirect()->route('orders.index')
            ->with('success', "✅ {$count} baris CSV berhasil diimport! Stok otomatis berkurang.");
    }
}
