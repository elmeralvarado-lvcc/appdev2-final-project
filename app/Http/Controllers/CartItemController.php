<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;

class CartItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cartItems = CartItem::with('variant', 'product')->get();

        return response()->json($cartItems);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CartItem $cartItem)
    {
        return response()->json($cartItem);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CartItem $cartItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CartItem $cartItem)
    {
        $cartItem->delete();

        return response()->noContent();
    }

    public function increment(CartItem $cartItem)
    {
        $cartItem?->increment('quantity');

        return response()->noContent();
    }

    public function decrement(CartItem $cartItem)
    {
        if ($cartItem->quantity > 1) {
            $cartItem?->decrement('quantity');
        }

        return response()->noContent();
    }
}
