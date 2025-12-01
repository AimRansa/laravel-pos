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

        // AMBIL SEMUA PRODUK
        $products = Product::all();


        /* ==========================
           LOW STOCK (THRESHOLD PER SATUAN)
        ========================== */

        $low_stock_products = $products->filter(function ($p) {

            $threshold = match ($p->satuan) {
                'pcs' => 300,
                'kg' => 20,
                'liter' => 30,
                'pack' => 50,
                'dus' => 10,
                'ml' => 500,
                'gr' => 1000,
                'slice' => 100,
                default => 100,
            };

            return $p->jumlah_stok < $threshold;
        });


        // PAGINATION LOW STOCK (TABEL BAWAH)
        $low_stock_paginate = $products->filter(function ($p) {

            $threshold = match ($p->satuan) {
                'pcs' => 300,
                'kg' => 20,
                'liter' => 30,
                'pack' => 50,
                'dus' => 10,
                'ml' => 500,
                'gr' => 1000,
                'slice' => 100,
                default => 100,
            };

            return $p->jumlah_stok < $threshold && $p->jumlah_stok >= 0;
        });

        // UBAH COLLECTION KE PAGINATION
        $perPage = 5;
        $page = request()->get('page', 1);
        $low_stock_paginate = new \Illuminate\Pagination\LengthAwarePaginator(
            $low_stock_paginate->forPage($page, $perPage),
            $low_stock_paginate->count(),
            $perPage,
            $page,
            ['path' => url()->current()]
        );


        /* ==========================
           HOT PRODUCTS 6 BULAN
        ========================== */

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


        /* ==========================
           BEST SELLING TAHUN INI
        ========================== */

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


        /* ==========================
           PRODUK BULAN INI
        ========================== */

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

