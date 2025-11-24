<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query();

        // Filter berdasarkan tanggal transaksi
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_transaksi', $request->tanggal);
        }

        // Search ID transaksi
        if ($request->filled('search')) {
            $query->where('idtransaksi', 'LIKE', "%{$request->search}%");
        }

        // Ambil SEMUA transaksi tanpa pagination
        $orders = $query->orderBy('tanggal_transaksi', 'DESC')->get();

        return view('orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with('details.menu')
            ->where('idtransaksi', $id)
            ->firstOrFail();

        return view('orders.show', compact('order'));
    }
}
