<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $orders = Order::all();
        $customers_count = Customer::count();
        $low_stock_products = Product::where('quantity', '<', 10)->get();

        $bestSellingProducts = collect([]);
        $currentMonthBestSelling = collect([]);
        $pastSixMonthsHotProducts = collect([]);

        return view('home', [
            'orders_count' => $orders->count(),
            'income' => $orders->sum('total'),
            'income_today' => $orders->where('created_at', '>=', date('Y-m-d') . ' 00:00:00')->sum('total'),
            'customers_count' => $customers_count,
            'low_stock_products' => $low_stock_products,
            'best_selling_products' => $bestSellingProducts,
            'current_month_products' => $currentMonthBestSelling,
            'past_months_products' => $pastSixMonthsHotProducts,
        ]);
    }
}

