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
        // 1. ORDERS COUNT
        $orders_count = Order::count();

        // 2. TOTAL INCOME
        $total_income = Order::sum('total_harga');

        // 3. INCOME TODAY
        $today = Carbon::now()->format('Y-m-d');
        $income_today = Order::where('tanggal_transaksi', $today)->sum('total_harga');

        // 4. LOW STOCK PRODUCTS (stok < 50)
        $low_stock_products = Product::where('jumlah_stok', '<', 50)
            ->orderBy('jumlah_stok', 'asc')
            ->limit(10)
            ->get();

        // 5. HOT PRODUCTS (6 bulan terakhir)
        $six_months_ago = Carbon::now()->subMonths(6)->format('Y-m-d');
        
        $hot_products = DetailPesanan::select(
                'detail_pesanan.id_menu',
                'cart.nama_menu',
                DB::raw('SUM(detail_pesanan.quantity) as total_terjual'),
                DB::raw('SUM(detail_pesanan.subtotal) as total_pendapatan'),
                'cart.harga',
                'cart.takaran'
            )
            ->join('orders', 'detail_pesanan.idtransaksi', '=', 'orders.idtransaksi')
            ->join('cart', 'detail_pesanan.id_menu', '=', 'cart.id_menu')
            ->where('orders.tanggal_transaksi', '>=', $six_months_ago)
            ->groupBy('detail_pesanan.id_menu', 'cart.nama_menu', 'cart.harga', 'cart.takaran')
            ->orderBy('total_terjual', 'desc')
            ->limit(5)
            ->get();

        // 6. BEST SELLING PRODUCTS (year)
        $current_year = Carbon::now()->year;
        
        $best_selling_products = DetailPesanan::select(
                'detail_pesanan.id_menu',
                'cart.nama_menu',
                DB::raw('SUM(detail_pesanan.quantity) as total_terjual'),
                DB::raw('SUM(detail_pesanan.subtotal) as total_pendapatan'),
                'cart.harga',
                'cart.takaran'
            )
            ->join('orders', 'detail_pesanan.idtransaksi', '=', 'orders.idtransaksi')
            ->join('cart', 'detail_pesanan.id_menu', '=', 'cart.id_menu')
            ->whereYear('orders.tanggal_transaksi', $current_year)
            ->groupBy('detail_pesanan.id_menu', 'cart.nama_menu', 'cart.harga', 'cart.takaran')
            ->orderBy('total_terjual', 'desc')
            ->limit(5)
            ->get();

        // 7. CURRENT MONTH BEST SELLING
        $current_month = Carbon::now()->format('Y-m');
        
        $current_month_products = DetailPesanan::select(
                'detail_pesanan.id_menu',
                'cart.nama_menu',
                DB::raw('SUM(detail_pesanan.quantity) as total_terjual'),
                DB::raw('SUM(detail_pesanan.subtotal) as total_pendapatan')
            )
            ->join('orders', 'detail_pesanan.idtransaksi', '=', 'orders.idtransaksi')
            ->join('cart', 'detail_pesanan.id_menu', '=', 'cart.id_menu')
            ->where('orders.tanggal_transaksi', 'like', $current_month . '%')
            ->groupBy('detail_pesanan.id_menu', 'cart.nama_menu')
            ->orderBy('total_terjual', 'desc')
            ->limit(5)
            ->get();

        return view('home', [
            'orders_count' => $orders_count,
            'income' => $total_income,
            'income_today' => $income_today,
            'low_stock_products' => $low_stock_products,
            'hot_products' => $hot_products,
            'best_selling_products' => $best_selling_products,
            'current_month_products' => $current_month_products,
        ]);
    }
}
