<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CsvController;


Route::get('/', function () {
    return redirect('/admin');
});

Auth::routes();

Route::prefix('admin')->middleware(['auth', 'locale'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'store'])->name('settings.store');

    // ✅ Daftar resource utama
    Route::resource('products', ProductController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('orders', OrderController::class); // cukup satu ini saja
    Route::resource('suppliers', SupplierController::class);

    // ✅ Cart / Menu Section
    Route::resource('cart', CartController::class);

    //lapran 
    Route::get('/admin/laporan', [App\Http\Controllers\SettingController::class, 'index'])->name('laporan.index');

    ///csv 
    Route::get('/upload-csv', [CsvController::class, 'showForm'])->name('csv.upload.form');
    Route::post('/upload-csv', [CsvController::class, 'upload'])->name('csv.upload.process');

    // ✅ Tambahan fungsi Cart
    Route::post('/cart/change-qty', [CartController::class, 'changeQty'])->name('cart.changeQty');
    Route::delete('/cart/delete', [CartController::class, 'delete'])->name('cart.delete');
    Route::delete('/cart/empty', [CartController::class, 'empty'])->name('cart.empty');

    // ✅ Pembelian
    Route::get('/purchase', [PurchaseController::class, 'index'])->name('purchase.cart.index');
    Route::post('/orders/partial-payment', [OrderController::class, 'partialPayment'])->name('orders.partial-payment');

    // ✅ Translasi bahasa
    Route::get('/locale/{type}', function ($type) {
        $translations = trans($type);
        return response()->json($translations);
    });

    // ✅ Ganti bahasa
    Route::get('/lang-switch/{lang}', function ($lang) {
        $supportedLocales = ['en', 'es'];

        if (in_array($lang, $supportedLocales)) {
            session(['locale' => $lang]);
            app()->setLocale($lang);
        }

        return redirect()->back();
    })->name('lang.switch');
});
