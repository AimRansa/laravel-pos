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

    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'store'])->name('settings.store');

    // Resource utama
    Route::resource('products', ProductController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('cart', CartController::class);

    // Laporan
    Route::get('/laporan', [SettingController::class, 'index'])->name('laporan.index');

    // CSV Import
    Route::get('/upload-csv', [CsvController::class, 'showForm'])->name('csv.upload.form');
    Route::post('/upload-csv', [CsvController::class, 'upload'])->name('csv.upload.process');

    // Tambahan Cart
    Route::post('/cart/change-qty', [CartController::class, 'changeQty'])->name('cart.changeQty');
    Route::delete('/cart/delete', [CartController::class, 'delete'])->name('cart.delete');
    Route::delete('/cart/empty', [CartController::class, 'empty'])->name('cart.empty');

    // Pembelian
    Route::get('/purchase', [PurchaseController::class, 'index'])->name('purchase.cart.index');
    Route::post('/orders/partial-payment', [OrderController::class, 'partialPayment'])->name('orders.partial-payment');

    // Bahasa
    Route::get('lang/{lang}', function ($lang) {
        if (in_array($lang, ['en', 'id'])) {
            session(['locale' => $lang]);
            app()->setLocale($lang);
        }
        return redirect()->back();
    })->name('lang.switch');

    // ==========================
    //     RESEP ROUTES
    // ==========================
    Route::prefix('resep')->group(function () {

        Route::get('/{id_menu}', [ResepMenuController::class, 'index'])
            ->name('resep.index');

        Route::post('/{id_menu}', [ResepMenuController::class, 'store'])
            ->name('resep.store');

        Route::delete('/hapus/{id}', [ResepMenuController::class, 'destroy'])
            ->name('resep.destroy');

    });

});
