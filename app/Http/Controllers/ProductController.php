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
            $products->where('id_produk', 'LIKE', "%{$request->search}%")
                     ->orWhere('nama_stok', 'LIKE', "%{$request->search}%");
        }

        $products = $products->latest()->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $lastProduct = Product::orderBy('id', 'desc')->first();
        $newId = 'P01';

        if ($lastProduct && strlen($lastProduct->id_produk) === 3 && ctype_alnum($lastProduct->id_produk)) {
            $prefix = substr($lastProduct->id_produk, 0, 1);
            $lastNumber = (int) substr($lastProduct->id_produk, 1);
            $newId = $prefix . str_pad($lastNumber + 1, 2, '0', STR_PAD_LEFT);
        }

        return view('products.create', compact('newId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_produk' => 'required|string|max:3|unique:products,id_produk',
            'nama_stok' => 'required|string|max:255',
            'jumlah_stok' => 'required|integer|min:0',
            'satuan' => 'required|string|max:50',
            'tanggal_masuk' => 'required|date',
            'tanggal_expired' => 'required|date|after_or_equal:tanggal_masuk',
        ]);

        Product::create($request->only([
            'id_produk',
            'nama_stok',
            'jumlah_stok',
            'satuan',
            'tanggal_masuk',
            'tanggal_expired'
        ]));

        return redirect()->route('products.index')->with('success', '✅ Data stok berhasil ditambahkan!');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'nama_stok' => 'required|string|max:255',
            'jumlah_stok' => 'required|integer|min:0',
            'satuan' => 'required|string|max:50',
            'tanggal_masuk' => 'required|date',
            'tanggal_expired' => 'required|date|after_or_equal:tanggal_masuk',
        ]);

        $product->update($request->only([
            'nama_stok',
            'jumlah_stok',
            'satuan',
            'tanggal_masuk',
            'tanggal_expired'
        ]));

        return redirect()->route('products.index')->with('success', '✅ Data stok berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', '🗑️ Data stok berhasil dihapus!');
    }
}
