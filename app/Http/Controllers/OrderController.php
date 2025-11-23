<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $orders = Order::when($search, function($query, $search) {
                return $query->where('idtransaksi', 'like', "%{$search}%");
            })
            ->orderBy('idtransaksi', 'asc')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }
}
