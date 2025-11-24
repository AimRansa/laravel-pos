<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Cart;
use App\Models\DetailPesanan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // TOTAL ORDERS
        $orders_count = Order::count();

        // TOTAL INCOME
        $total_income = Order::sum('total_harga');

        // TODAY INCOME
        $today = Carbon::now()->format('Y-m-d');
        $income_today = Order::where('tanggal_transaksi', $today)->sum('total_harga');

        // LOW STOCK collection (untuk bagian atas dan near empty)
        $low_stock_products = Product::where('jumlah_stok', '<', 50)
            ->orderBy('jumlah_stok', 'asc')
            ->get();

        // LOW STOCK PAGINATION (untuk bagian bawah)
        $low_stock_paginate = Product::where('jumlah_stok', '<', 50)
            ->orderBy('jumlah_stok', 'asc')
            ->paginate(5);

        // HOT PRODUCTS 6 BULAN
        $six_months_ago = Carbon::now()->subMonths(6)->format('Y-m-d');

        $hot_products = DetailPesanan::select(
                'detail_pesanan.id_menu',
                'cart.nama_menu',
                DB::raw('SUM(detail_pesanan.quantity) as total_terjual'),
                DB::raw('SUM(detail_pesanan.subtotal) as total_pendapatan'),
                'cart.harga'
            )
            ->join('orders', 'detail_pesanan.idtransaksi', '=', 'orders.idtransaksi')
            ->join('cart', 'detail_pesanan.id_menu', '=', 'cart.id_menu')
            ->where('orders.tanggal_transaksi', '>=', $six_months_ago)
            ->groupBy('detail_pesanan.id_menu', 'cart.nama_menu', 'cart.harga')
            ->orderBy('total_terjual', 'desc')
            ->limit(5)
            ->get();

        // BEST SELLING YEAR
        $current_year = Carbon::now()->year;

        $best_selling_products = DetailPesanan::select(
                'detail_pesanan.id_menu',
                'cart.nama_menu',
                DB::raw('SUM(detail_pesanan.quantity) as total_terjual'),
                DB::raw('SUM(detail_pesanan.subtotal) as total_pendapatan'),
                'cart.harga'
            )
            ->join('orders', 'detail_pesanan.idtransaksi', '=', 'orders.idtransaksi')
            ->join('cart', 'detail_pesanan.id_menu', '=', 'cart.id_menu')
            ->whereYear('orders.tanggal_transaksi', $current_year)
            ->groupBy('detail_pesanan.id_menu', 'cart.nama_menu', 'cart.harga')
            ->orderBy('total_terjual', 'desc')
            ->limit(5)
            ->get();

        // CURRENT MONTH
        $current_month = Carbon::now()->format('Y-m');

        $current_month_products = DetailPesanan::select(
                'detail_pesanan.id_menu',
                'cart.nama_menu',
                DB::raw('SUM(detail_pesanan.quantity) as total_terjual'),
                DB::raw('SUM(detail_pesanan.subtotal) as total_pendapatan')
            )
            ->join('orders', 'detail_pesanan.idtransaksi', '=', 'orders.idtransaksi')
            ->join('cart', 'detail_pesanan.id_menu', '=', 'cart.id_menu')
            ->where('orders.tanggal_transaksi', 'like', $current_month.'%')
            ->groupBy('detail_pesanan.id_menu', 'cart.nama_menu')
            ->orderBy('total_terjual', 'desc')
            ->limit(5)
            ->get();

        return view('home', [
            'orders_count' => $orders_count,
            'income' => $total_income,
            'income_today' => $income_today,
            'low_stock_products' => $low_stock_products,
            'low_stock_paginate' => $low_stock_paginate,
            'hot_products' => $hot_products,
            'best_selling_products' => $best_selling_products,
            'current_month_products' => $current_month_products,
        ]);
    }
}
