<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\ResepMenu;
use Illuminate\Http\Request;

class ResepMenuController extends Controller
{
    public function index($id_menu)
    {
        $menu = Cart::where('id_menu', $id_menu)->firstOrFail();
        $resep = ResepMenu::where('id_menu', $id_menu)->with('produk')->get();
        $produk = Product::all();

        return view('resep.index', compact('menu', 'resep', 'produk'));
    }

    public function store(Request $request, $id_menu)
    {
        $request->validate([
            'id_produk' => 'required',
            'takaran' => 'required|numeric|min:0.01',
            'satuan' => 'required|string',
        ]);

        ResepMenu::create([
            'id_menu' => $id_menu,
            'id_produk' => $request->id_produk,
            'takaran' => $request->takaran,
            'satuan' => $request->satuan,
        ]);

        return back()->with('success', 'Bahan berhasil ditambahkan ke resep!');
    }

    public function destroy($id)
    {
        ResepMenu::findOrFail($id)->delete();

        return back()->with('success', 'Bahan berhasil dihapus dari resep!');
    }
}
