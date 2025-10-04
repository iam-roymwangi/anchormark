<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('/products', [HomeController::class, 'products'])->name('products');

Route::get('/about', [HomeController::class, 'about'])->name('about');

Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

Route::get('/product-details', [HomeController::class, 'showProductDetails'])->name('product-details');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/admin/products', function () {
        return Inertia::render('admin/products/Index');
    })->name('admin.products.index');

    Route::get('/admin/products/create', function () {
        return Inertia::render('admin/products/Create');
    })->name('admin.products.create');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
