<?php

use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CartController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\CheckoutController;

use Illuminate\Support\Facades\Mail;

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

Route::get('checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('checkout/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel');

Route::post('carts/{sessionId}/migrate', [CartController::class, 'migrate'])->name('carts.migrate');
Route::post('carts/checkout', [CartController::class, 'checkout'])->name('carts.checkout');
Route::apiResource('carts', CartController::class);

Route::post('cartItems/{cartItem}/increment', [CartItemController::class, 'increment'])->name('cartItems.increment');
Route::post('cartItems/{cartItem}/decrement', [CartItemController::class, 'decrement'])->name('cartItems.decrement');
Route::apiResource('cartItems', CartItemController::class);

Route::apiResource('orders', OrderController::class);
Route::get('preview', function () {
    $order = \App\Models\Order::first();

    // return new \App\Mail\OrderConfirmation($order);
    Mail::to('elmeralvarado@laverdad.edu.ph')->send(new \App\Mail\OrderConfirmation($order));
});


Route::post('/stripe/webhook', [WebhookController::class, 'handleWebhook']);

