<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Shopper\WhishlistController;

Route::get('/', [HomeController::class, 'home'])->name('home');

Route::get('/products', [HomeController::class, 'products'])->name('products');

Route::get('/about', [HomeController::class, 'about'])->name('about');

Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

Route::get('/product-details', [HomeController::class, 'showProductDetails'])->name('product-details');

// Quote routes
Route::get('/quote', [QuoteController::class, 'index'])->name('quote.index');
Route::post('/quotes/request', [QuoteController::class, 'store'])->name('quotes.request');
Route::post('/quotes/request-cart', [QuoteController::class, 'storeCart'])->name('quotes.request-cart');
Route::get('/quotes/{id}/invoice', [QuoteController::class, 'invoice'])->name('quotes.invoice');

// Cart routes (public - guest and authenticated users)
Route::middleware(['cart.session'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('/cart-data', [CartController::class, 'getCartData'])->name('cart.data'); // New route for fetching cart data
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/update/{cartProduct}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{cartProduct}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::put('/cart/update-prices', [CartController::class, 'updatePrices'])->name('cart.update-prices');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    
    // Checkout routes
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
});

// Authenticated cart operations
Route::middleware(['auth', 'cart.session'])->group(function () {
    Route::post('/cart/merge', [CartController::class, 'merge'])->name('cart.merge');
});

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('admin/products', \App\Http\Controllers\Admin\ProductController::class)->names([
        'index' => 'admin.products.index',
        'create' => 'admin.products.create',
        'store' => 'admin.products.store',
        'show' => 'admin.products.show',
        'edit' => 'admin.products.edit',
        'update' => 'admin.products.update',
        'destroy' => 'admin.products.destroy',
    ]);

    Route::resource('admin/categories', CategoryController::class)->names([
        'index' => 'admin.categories.index',
        'store' => 'admin.categories.store',
        'update' => 'admin.categories.update',
        'destroy' => 'admin.categories.destroy',
    ]);

    // Wishlist routes
    Route::resource('wishlists', WishlistController::class);
    Route::post('wishlists/{wishlist}/add-product', [WishlistController::class, 'addProduct'])->name('wishlists.add-product');
    Route::delete('wishlists/{wishlist}/remove-product', [WishlistController::class, 'removeProduct'])->name('wishlists.remove-product');
    Route::post('wishlists/{wishlist}/toggle-product', [WishlistController::class, 'toggleProduct'])->name('wishlists.toggle-product');
    Route::post('wishlists/move-product', [WishlistController::class, 'moveProduct'])->name('wishlists.move-product');
});



require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
