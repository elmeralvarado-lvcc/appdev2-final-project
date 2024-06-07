<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CartController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;

use App\Http\Controllers\WebhookController;

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

// Route::get('cart/items', [CartItemController::class, 'index']);
// Route::get('cart/items/{cartItem}', [CartItemController::class, 'show']);
// Route::delete('cart/items/{cartItem}', [CartItemController::class, 'destroy']);

Route::post('login', LoginController::class);

Route::post('add-product-variant-to-cart/{variantId}/{sessionId?}', [CartController::class, 'addProductVariantToCart'])->name('add-product-variant-to-cart');

Route::apiResource('products', ProductController::class);

Route::get('/success', [CartController::class, 'success'])->name('checkout.success');
Route::get('/cancel', [CartController::class, 'cancel'])->name('checkout.cancel');

Route::post('carts/{sessionId}/migrate', [CartController::class, 'migrate'])->name('carts.migrate');
Route::post('carts/checkout', [CartController::class, 'checkout'])->name('carts.checkout');
Route::apiResource('carts', CartController::class);

Route::post('cartItems/{cartItem}/increment', [CartItemController::class, 'increment'])->name('cartItems.increment');
Route::post('cartItems/{cartItem}/decrement', [CartItemController::class, 'decrement'])->name('cartItems.decrement');
Route::apiResource('cartItems', CartItemController::class);


Route::post('/stripe/webhook', [WebhookController::class, 'handleWebhook']);

