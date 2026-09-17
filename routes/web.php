<?php

use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// ------------------ Halaman Pengunjung (publik, tanpa login) ------------------
Route::get('/', [ProductController::class, 'home'])->name('home');
Route::get('/produk', [ProductController::class, 'index'])->name('products.index');
Route::get('/produk/{slug}', [ProductController::class, 'show'])->name('products.show');

// Keranjang
Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
Route::post('/keranjang/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/keranjang/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/keranjang/{product}', [CartController::class, 'remove'])->name('cart.remove');

// Checkout & Pesanan
Route::get('/checkout', [OrderController::class, 'create'])->name('orders.create');
Route::post('/checkout', [OrderController::class, 'store'])->name('orders.store');

Route::get('/cek-status', [OrderController::class, 'cekStatus'])->name('orders.cek-status');
Route::post('/cek-status', [OrderController::class, 'cekStatus']);

// ------------------ Halaman Admin (wajib login) ------------------
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/produk', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/produk/tambah', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/produk', [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/produk/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/produk/{product}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/produk/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');

    Route::get('/pesanan', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::patch('/pesanan/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');

    Route::get('/pelanggan', [AdminCustomerController::class, 'index'])->name('customers.index');
});

require __DIR__.'/auth.php';
