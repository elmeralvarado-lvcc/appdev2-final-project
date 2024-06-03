<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        //
    }

    public function addProductVariantToCart(Request $request, $variantId, $sessionId = null)
    {
        $sessionId = $sessionId ?: session()->getId();

        $token = $request->bearerToken();

        if ($token && Auth::guard('sanctum')->check()) {
            $user = Auth::guard('sanctum')->user();
            $cart = $user->cart ?: $user->cart()->create();
        } else {
            $cart = Cart::firstOrCreate(['session_id' => $sessionId]);
        }

        $cartItem = $cart->items()->firstOrCreate(
            ['product_variant_id' => $variantId],
            ['quantity' => 0]
        );

        $cartItem->increment('quantity');

        return response()->json($cart->load('items'));
    }

    public function migrate(Request $request, $sessionId)
    {
        $cart = Cart::where('session_id', $sessionId)->first();

        $token = $request->bearerToken();

        if ($token && Auth::guard('sanctum')->check()) {
            $user = Auth::guard('sanctum')->user();

            if ($cart) {
                $userCart = $user->cart()->firstOrCreate();

                foreach ($cart->items as $item) {
                    $existingItem = $userCart->items()->where('product_variant_id', $item->product_variant_id)->first();

                    if ($existingItem) {
                        $existingItem->increment('quantity', $item->quantity);
                    } else {
                        $userCart->items()->create([
                            'product_variant_id' => $item->product_variant_id,
                            'quantity' => $item->quantity
                        ]);
                    }
                }

                $cart->delete();
                $cart->items()->delete();
            }
        }

        return response()->json($userCart->load('items'));
    }
}
