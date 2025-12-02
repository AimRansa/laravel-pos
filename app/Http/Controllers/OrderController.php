<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::orderBy('upload_at', 'DESC')
            ->orderBy('idtransaksi', 'DESC')
            ->get();

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
