<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KantinController;
use App\Http\Controllers\KoperasiController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\PortalController;
use Illuminate\Support\Facades\Route;

// =============================================
//  HALAMAN UTAMA — Landing Page & Portal
// =============================================
Route::get('/', [PortalController::class, 'welcome'])->name('welcome');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('home');
Route::get('/portal-pemilik', [PortalController::class, 'ownerPortal'])->name('portal.owner');

// =============================================
//  KOPERASI — Halaman Produk (Publik)
// =============================================
Route::get('/koperasi', [KoperasiController::class, 'index'])->name('koperasi.index');

// =============================================
//  KANTIN — Daftar & Menu Toko (Publik)
// =============================================
Route::get('/kantin', [KantinController::class, 'index'])->name('kantin.index');
Route::get('/toko/{store}', [StudentController::class, 'show'])->name('store.show');

// =============================================
//  KERANJANG BELANJA
// =============================================
Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
Route::post('/keranjang/tambah', [CartController::class, 'add'])->name('cart.add');
Route::post('/keranjang/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/keranjang/kosongkan', [CartController::class, 'clear'])->name('cart.clear');

// =============================================
//  CHECKOUT & PEMBAYARAN
// =============================================
Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');
Route::get('/bayar/{orderCode}', [PaymentController::class, 'show'])->name('payment.show');
Route::post('/bayar/{orderCode}/simulasi', [PaymentController::class, 'simulate'])->name('payment.simulate');

// Midtrans callback (webhook - tidak butuh CSRF)
Route::post('/midtrans/callback', [PaymentController::class, 'midtransCallback'])
    ->name('midtrans.callback')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// Nota digital & status pesanan
Route::get('/pesanan/{orderCode}', [StudentController::class, 'orderStatus'])->name('order.status');

// =============================================
//  WARUNG / KOPERASI — Dashboard Penjaga (PIN)
// =============================================
Route::get('/warung/{store}/login', [StoreController::class, 'loginForm'])->name('store.login');
Route::post('/warung/{store}/login', [StoreController::class, 'login'])->name('store.login.post');

Route::middleware('store.auth')->group(function () {
    Route::get('/warung/{store}', [StoreController::class, 'dashboard'])->name('store.dashboard');
    Route::post('/warung/{store}/logout', [StoreController::class, 'logout'])->name('store.logout');
    Route::post('/warung/{store}/status', [StoreController::class, 'toggleStatus'])->name('store.toggle_status');

    // Aksi ubah status pesanan
    Route::post('/warung/pesanan/{order}/proses', [OrderController::class, 'process'])->name('order.process');
    Route::post('/warung/pesanan/{order}/siap', [OrderController::class, 'ready'])->name('order.ready');
    Route::post('/warung/pesanan/{order}/selesai', [OrderController::class, 'complete'])->name('order.complete');
    Route::post('/warung/pesanan/{order}/tolak', [OrderController::class, 'reject'])->name('order.reject');

    // CRUD Produk (penjaga warung/koperasi)
    Route::post('/warung/{store}/produk', [ProductController::class, 'store'])->name('product.store');
    Route::put('/warung/{store}/produk/{product}', [ProductController::class, 'update'])->name('product.update');
    Route::delete('/warung/{store}/produk/{product}', [ProductController::class, 'destroy'])->name('product.destroy');
    Route::post('/warung/{store}/produk/{product}/stok', [ProductController::class, 'updateStock'])->name('product.stock');
    Route::post('/warung/{store}/produk/{product}/toggle', [ProductController::class, 'toggleAvailability'])->name('product.toggle');
});

// =============================================
//  ADMIN — Panel Manajemen (Email + Password)
// =============================================
Route::get('/admin/login', [AdminAuthController::class, 'loginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.post');

Route::middleware('admin.auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/api/stats', [AjaxController::class, 'adminDashboardStats'])->name('api.stats');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Kelola toko
    Route::get('/toko', [AdminController::class, 'stores'])->name('stores');
    Route::post('/toko', [AdminController::class, 'createStore'])->name('stores.create');
    Route::put('/toko/{store}', [AdminController::class, 'updateStore'])->name('stores.update');
    Route::delete('/toko/{store}', [AdminController::class, 'deleteStore'])->name('stores.delete');
    Route::post('/toko/{store}/toggle', [AdminController::class, 'toggleStore'])->name('stores.toggle');

    // Kelola produk admin
    Route::get('/produk', [AdminController::class, 'products'])->name('products');
    Route::post('/produk', [ProductController::class, 'store'])->name('product.store');

    // Kelola akun pengelola
    Route::get('/akun', [AdminController::class, 'accounts'])->name('accounts');
    Route::post('/akun', [AdminController::class, 'createAccount'])->name('accounts.create');
    Route::put('/akun/{user}', [AdminController::class, 'updateAccount'])->name('accounts.update');
    Route::delete('/akun/{user}', [AdminController::class, 'deleteAccount'])->name('accounts.delete');

    // Laporan keuangan
    Route::get('/keuangan', [AdminController::class, 'finance'])->name('finance');
    Route::get('/keuangan/{store}', [AdminController::class, 'financeStore'])->name('finance.store');

    // Export laporan
    Route::get('/keuangan/export/pdf', [AdminController::class, 'exportPdf'])->name('finance.export.pdf');
    Route::get('/keuangan/export/excel', [AdminController::class, 'exportExcel'])->name('finance.export.excel');
    Route::get('/keuangan/{store}/export/pdf', [AdminController::class, 'exportStorePdf'])->name('finance.store.export.pdf');
    Route::get('/keuangan/{store}/export/excel', [AdminController::class, 'exportStoreExcel'])->name('finance.store.export.excel');
});

// =============================================
//  AJAX — Endpoint Polling Real-time
// =============================================
Route::get('/api/pesanan/{orderCode}/status', [AjaxController::class, 'orderStatus'])->name('api.order.status');
Route::get('/api/warung/{store}/pesanan', [AjaxController::class, 'storePendingOrders'])->name('api.store.orders');
Route::get('/api/dashboard/pesanan-aktif', [AjaxController::class, 'activeOrders'])->name('api.active.orders');
