<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query();

        if ($request->search) {
            $products->where('id_produk', 'LIKE', "%{$request->search}%");
        }

        $products = $products->latest()->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        // 🔢 Auto-generate ID produk (maksimal 3 karakter, misal: P01, P02, dst)
        $lastProduct = Product::orderBy('id', 'desc')->first();

        if ($lastProduct && strlen($lastProduct->id_produk) === 3 && ctype_alnum($lastProduct->id_produk)) {
            $prefix = substr($lastProduct->id_produk, 0, 1);
            $lastNumber = (int) substr($lastProduct->id_produk, 1);
            $newNumber = str_pad($lastNumber + 1, 2, '0', STR_PAD_LEFT);
            $newId = $prefix . $newNumber;
        } else {
            $newId = 'P01';
        }

        return view('products.create', compact('newId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_produk' => 'required|string|max:3|unique:products,id_produk',
            'tanggal_keluar' => 'required|date',
            'tanggal_masuk' => 'required|date',
            'tanggal_expired' => 'required|date|after_or_equal:tanggal_masuk',
        ], [
            'id_produk.required' => 'ID produk wajib diisi.',
            'id_produk.max' => 'ID produk maksimal 3 karakter.',
            'id_produk.unique' => 'ID produk sudah digunakan, silakan gunakan ID lain.',
            'tanggal_masuk.required' => 'Tanggal masuk wajib diisi.',
            'tanggal_keluar.required' => 'Tanggal keluar wajib diisi.',
            'tanggal_expired.after_or_equal' => 'Tanggal expired harus setelah atau sama dengan tanggal masuk.',
        ]);

        Product::create([
            'id_produk' => $request->id_produk,
            'tanggal_keluar' => $request->tanggal_keluar,
            'tanggal_masuk' => $request->tanggal_masuk,
            'tanggal_expired' => $request->tanggal_expired,
        ]);

        return redirect()->route('products.index')->with('success', '✅ Data stok berhasil ditambahkan!');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'tanggal_keluar' => 'required|date',
            'tanggal_masuk' => 'required|date',
            'tanggal_expired' => 'required|date|after_or_equal:tanggal_masuk',
        ]);

        $product->update([
            'tanggal_keluar' => $request->tanggal_keluar,
            'tanggal_masuk' => $request->tanggal_masuk,
            'tanggal_expired' => $request->tanggal_expired,
        ]);

        return redirect()->route('products.index')->with('success', '✅ Data stok berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', '🗑️ Data stok berhasil dihapus!');
    }
}
