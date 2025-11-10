<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     */
    public function index()
    {
        // Ambil semua data order
        $orders = Order::all();

        // Jika sudah tidak pakai kolom quantity, hapus atau sesuaikan di sini.
        // Misalnya kamu pakai kolom "stok" atau "stock":
        // $low_stock_products = Product::where('stok', '<', 10)->get();

        // Kalau tidak pakai sistem stok sama sekali:
        $low_stock_products = collect([]);

        // Placeholder untuk produk terlaris & lainnya
        $bestSellingProducts = collect([]);
        $currentMonthBestSelling = collect([]);
        $pastSixMonthsHotProducts = collect([]);

        return view('home', [
            'orders_count' => $orders->count(),
            'income' => $orders->sum('total'),
            'income_today' => $orders->where('created_at', '>=', date('Y-m-d') . ' 00:00:00')->sum('total'),
            'low_stock_products' => $low_stock_products,
            'best_selling_products' => $bestSellingProducts,
            'current_month_products' => $currentMonthBestSelling,
            'past_months_products' => $pastSixMonthsHotProducts,
        ]);
    }
}