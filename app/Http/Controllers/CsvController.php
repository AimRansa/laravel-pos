<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // contoh: upload data produk dari CSV
use Illuminate\Support\Facades\Validator;

class CsvController extends Controller
{
    // Tampilkan form upload
    public function showForm()
    {
        return view('csv.upload');
    }

    // Proses upload dan simpan data
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|mimes:csv,txt|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $file = $request->file('csv_file');
        $handle = fopen($file, 'r');
        $header = fgetcsv($handle); // Ambil header

        $count = 0;
        while (($row = fgetcsv($handle)) !== false) {
            // Sesuaikan kolom dengan header CSV kamu
            Product::updateOrCreate([
                'name' => $row[0],
            ], [
                'price' => $row[1],
                'stock' => $row[2],
            ]);
            $count++;
        }
        fclose($handle);

        return redirect()->route('products.index')->with('success', "{$count} data berhasil diupload dari CSV!");
    }
}
