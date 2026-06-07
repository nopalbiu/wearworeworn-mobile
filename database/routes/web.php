<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AdminOrderController; 
use App\Http\Controllers\UserProfileController; // <-- Ini yang tadi kelupaan

// Halaman Home 
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/catalog', [HomeController::class, 'index'])->name('catalog.index');

// Autentikasi User
Route::get('/register', [AuthenticationController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthenticationController::class, 'processRegister']);

Route::get('/login', [AuthenticationController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthenticationController::class, 'processLogin']);

// Autentikasi Admin
Route::get('/admin/login', [AuthenticationController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/admin/login', [AuthenticationController::class, 'processAdminLogin']);

// Route yang butuh login (Customer)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Fitur Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{productId}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/buy-now/{productId}', [CartController::class, 'buyNow'])->name('cart.buyNow');
    Route::delete('/cart/remove/{cartItemId}', [CartController::class, 'remove'])->name('cart.remove');
    
    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'processOrder'])->name('checkout.process');
    Route::get('/checkout/success/{orderId}', [CheckoutController::class, 'success'])->name('checkout.success');

    // Fitur Profil User
    Route::get('/profile', [UserProfileController::class, 'index'])->name('user.profile');
    Route::put('/profile/update', [UserProfileController::class, 'updateProfile'])->name('user.profile.update');
    Route::put('/profile/password', [UserProfileController::class, 'updatePassword'])->name('user.profile.password');
});

// Admin Area
Route::prefix('admin')->name('admin.')->group(function () {
    
    // --- FITUR PESANAN (ORDERS) ---
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::put('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');

    // --- FITUR KATALOG PRODUK ---
    // 1. Rute Utama (Dipakai oleh Navbar)
    Route::get('/products', [AdminProductController::class, 'index'])->name('product.index');
    
    // 2. RUTE JEMBATAN (Ini yang dicari oleh controller Naufal setelah sukses login)
    Route::get('/katalog-redirect', function () {
        return redirect()->route('admin.product.index');
    })->name('katalog');
    
    // 3. Rute CRUD Lainnya
    Route::post('/products', [AdminProductController::class, 'store'])->name('product.store');
    Route::put('/products/{id}', [AdminProductController::class, 'update'])->name('product.update');
    Route::delete('/products/{id}', [AdminProductController::class, 'destroy'])->name('product.destroy');
        
});
 
// Halaman Detail Produk (publik, bisa diakses tanpa login)
Route::get('/product/{nama}', [ProductController::class, 'show'])->name('product.show');