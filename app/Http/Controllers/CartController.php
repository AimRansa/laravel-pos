<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Menampilkan daftar menu di keranjang
     */
    public function index(Request $request)
    {
        $query = Cart::query();

        if ($request->search) {
            $query->where('id_menu', 'like', "%{$request->search}%")
                  ->orWhere('nama_menu', 'like', "%{$request->search}%");
        }

        $carts = $query->latest()->paginate(10);

        return view('cart.index', compact('carts'));
    }

    /**
     * Form edit menu
     */
    public function edit(Cart $cart)
    {
        return view('cart.edit', compact('cart'));
    }

    /**
     * Update data menu
     */
    public function update(Request $request, Cart $cart)
    {
        $request->validate([
            'nama_menu' => 'required|string|max:100',
            'harga' => 'required|numeric|min:0',
        ]);

        $cart->update([
            'nama_menu' => $request->nama_menu,
            'harga' => $request->harga,
        ]);

        return redirect()->route('cart.index')->with('success', '✅ Data menu berhasil diperbarui!');
    }

    // 🔒 Fungsi hapus dihapus agar data tidak bisa dihapus
}
