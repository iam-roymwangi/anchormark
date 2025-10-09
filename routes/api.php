<?php

use App\Http\Controllers\Api\CartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Cart API Routes
Route::prefix('cart')->group(function () {
    // Public cart operations (guest and authenticated users)
    Route::middleware(['cart.session'])->group(function () {
        Route::get('/', [CartController::class, 'index']);                    // Get cart
        Route::get('/count', [CartController::class, 'count']);               // Get cart count
        Route::post('/add', [CartController::class, 'add']);                  // Add product to cart
        Route::put('/update', [CartController::class, 'update']);             // Update cart item
        Route::delete('/remove', [CartController::class, 'remove']);          // Remove cart item
        Route::delete('/clear', [CartController::class, 'clear']);            // Clear cart
        Route::put('/update-prices', [CartController::class, 'updatePrices']); // Update prices
    });

    // Authenticated user operations
    Route::middleware(['auth:sanctum', 'cart.session'])->group(function () {
        Route::post('/merge', [CartController::class, 'merge']);              // Merge guest cart on login
    });
});
