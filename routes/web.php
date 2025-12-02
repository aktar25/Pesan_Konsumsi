<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Halaman Utama: Menampilkan Menu Makanan
Route::get('/', [ProductController::class, 'index']);

// Halaman Tambah ke Keranjang
Route::get('add-to-cart/{id}', [ProductController::class, 'addToCart'])->name('add.to.cart');

// Halaman Lihat Keranjang
Route::get('cart', [ProductController::class, 'cart'])->name('cart');

// Aksi Hapus Item dari Keranjang
Route::delete('remove-from-cart', [ProductController::class, 'remove'])->name('remove.from.cart');

// PROSES CHECKOUT (INI YANG TADI KURANG!)
Route::post('checkout', [ProductController::class, 'checkout'])->name('checkout');

Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
Route::get('/admin/check-new', [AdminController::class, 'checkNewOrder'])->name('admin.check');
Route::post('/admin/complete/{id}', [AdminController::class, 'complete'])->name('admin.complete');
