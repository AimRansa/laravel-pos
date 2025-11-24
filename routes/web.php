<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CsvController;
use App\Http\Controllers\ResepMenuController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect('/admin');
});

Auth::routes();

Route::prefix('admin')->middleware(['auth', 'locale'])->group(function () {

    // ===============================
    // DASHBOARD
    // ===============================
    Route::get('/', [HomeController::class, 'index'])->name('home');


    // ===============================
    // IMPORT CSV
    // ===============================
    Route::get('/upload-csv', [CsvController::class, 'showForm'])->name('csv.upload.form');
    Route::post('/upload-csv', [CsvController::class, 'upload'])->name('csv.upload.process');


    // ===============================
    // RESOURCE MASTER DATA
    // ===============================
    Route::resource('products', ProductController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('cart', CartController::class);

    // ORDER (TRANSAKSI)
    Route::resource('orders', OrderController::class); 
    // index   → /admin/orders
    // show    → /admin/orders/{id}


    // ===============================
    // CART LOGIC
    // ===============================
    Route::post('/cart/change-qty', [CartController::class, 'changeQty'])->name('cart.changeQty');
    Route::delete('/cart/delete', [CartController::class, 'delete'])->name('cart.delete');
    Route::delete('/cart/empty', [CartController::class, 'empty'])->name('cart.empty');


    // ===============================
    // PURCHASE
    // ===============================
    Route::get('/purchase', [PurchaseController::class, 'index'])->name('purchase.cart.index');
    Route::post('/orders/partial-payment', [OrderController::class, 'partialPayment'])->name('orders.partial-payment');


    // ===============================
    // LAPORAN PAGE (LIST)
    // ===============================
    Route::get('/laporan', [SettingController::class, 'index'])->name('laporan.index');

    // DETAIL LAPORAN
    Route::get('/laporan/{id}', [SettingController::class, 'show'])->name('laporan.show');

    // CETAK LAPORAN
    Route::get('/laporan/{id}/print', [SettingController::class, 'print'])->name('laporan.print');


    // ===============================
    // API DATA FOR CHARTS (TOP MENU, TOP STOK)
    // ===============================
    Route::get('/laporan/chart-data', function () {

        $topMenus = \DB::table('laporan_detail')
            ->select('nama_menu', \DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('nama_menu')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        $topStoks = \DB::table('laporan_stok')
            ->select('nama_produk', \DB::raw('SUM(jumlah_berkurang) as total_keluar'))
            ->groupBy('nama_produk')
            ->orderByDesc('total_keluar')
            ->limit(10)
            ->get();

        return response()->json([
            'top_menus' => [
                'labels' => $topMenus->pluck('nama_menu'),
                'data'   => $topMenus->pluck('total_qty'),
            ],
            'top_stoks' => [
                'labels' => $topStoks->pluck('nama_produk'),
                'data'   => $topStoks->pluck('total_keluar'),
            ],
        ]);
    })->name('laporan.chart.data');


    // ===============================
    // MULTI LANGUAGE
    // ===============================
    Route::get('lang/{lang}', function ($lang) {
        if (in_array($lang, ['en', 'id'])) {
            session(['locale' => $lang]);
            app()->setLocale($lang);
        }
        return redirect()->back();
    })->name('lang.switch');


    // ===============================
    // RESEP MENU LOGIC
    // ===============================
    Route::prefix('resep')->group(function () {
        Route::get('/{id_menu}', [ResepMenuController::class, 'index'])->name('resep.index');
        Route::post('/{id_menu}', [ResepMenuController::class, 'store'])->name('resep.store');
        Route::delete('/hapus/{id}', [ResepMenuController::class, 'destroy'])->name('resep.destroy');
    });

});
