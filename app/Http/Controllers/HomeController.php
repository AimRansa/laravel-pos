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

        // ALL PRODUCTS
        $products = Product::all();

        /* =======================
           LOW STOCK THRESHOLD
        ======================= */
        $thresholdMap = [
            'pcs' => 300,
            'kg' => 20,
            'liter' => 30,
            'pack' => 50,
            'dus' => 10,
            'ml' => 500,
            'gr' => 1000,
            'slice' => 100,
        ];

        $low_stock_products = $products->filter(function ($p) use ($thresholdMap) {
            $threshold = $thresholdMap[$p->satuan] ?? 100;
            return $p->jumlah_stok < $threshold;
        });

        // PAGINATION
        $low_stock_paginate_collection = $products->filter(function ($p) use ($thresholdMap) {
            $threshold = $thresholdMap[$p->satuan] ?? 100;
            return $p->jumlah_stok < $threshold && $p->jumlah_stok >= 0;
        })->sortBy('jumlah_stok');

        $perPage = 5;
        $page = request()->get('page', 1);
        $low_stock_paginate = new \Illuminate\Pagination\LengthAwarePaginator(
            $low_stock_paginate_collection->forPage($page, $perPage),
            $low_stock_paginate_collection->count(),
            $perPage,
            $page,
            ['path' => url()->current()]
        );

        /* =======================
           HOT PRODUCTS
        ======================= */
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

        /* =======================
           BEST SELLING THIS YEAR
        ======================= */
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

        /* =======================
           CHART DATA
        ======================= */

        // CHART 1: Top 10 Produk Berdasarkan Stok
        $top_products = Product::orderBy('jumlah_stok', 'desc')->limit(10)->get();
        $chart1_labels = $top_products->pluck('nama_stok');
        $chart1_data = $top_products->pluck('jumlah_stok');

        // CHART 2: Low vs Sufficient
        $lowCount = 0;
        $okCount = 0;

        foreach ($products as $p) {
            $threshold = $thresholdMap[$p->satuan] ?? 100;
            if ($p->jumlah_stok < $threshold) $lowCount++;
            else $okCount++;
        }

        $chart2_labels = ['Low Stock', 'Sufficient'];
        $chart2_data = [$lowCount, $okCount];

        // CHART 3 + SALES: last 6 months
        $labels_6 = [];
        $orders_count_6 = [];
        $income_6 = [];

        for ($i = 5; $i >= 0; $i--) {
            $dt = Carbon::now()->subMonths($i);
            $label = $dt->format('M Y');

            $labels_6[] = $label;
            $month = $dt->format('Y-m');

            $orders_count_6[] = Order::where('tanggal_transaksi', 'like', $month.'%')->count();
            $income_6[] = Order::where('tanggal_transaksi', 'like', $month.'%')->sum('total_harga');
        }

        // Need restock: ≤ 1
        $need_restock = Product::where('jumlah_stok', '<=', 1)
            ->orderBy('jumlah_stok', 'asc')
            ->get();

        return view('home', [
            'orders_count' => $orders_count,
            'income' => $total_income,
            'income_today' => $income_today,
            'low_stock_products' => $low_stock_products,
            'low_stock_paginate' => $low_stock_paginate,
            'hot_products' => $hot_products,
            'best_selling_products' => $best_selling_products,

            // charts
            'chart1_labels' => json_encode($chart1_labels),
            'chart1_data' => json_encode($chart1_data),

            'chart2_labels' => json_encode($chart2_labels),
            'chart2_data' => json_encode($chart2_data),

            'chart3_labels' => json_encode($labels_6),
            'chart3_data' => json_encode($orders_count_6),

            'sales_labels' => json_encode($labels_6),
            'sales_data' => json_encode($income_6),

            'need_restock' => $need_restock,
        ]);
    }
}
