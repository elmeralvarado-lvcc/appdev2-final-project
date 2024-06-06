<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $carts = Cart::get();

        return response()->json($carts);
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

    public function checkout(Request $request)
    {
        $token = $request->bearerToken();

        if ($token && Auth::guard('sanctum')->check()) {
            $user = Auth::guard('sanctum')->user();

            $checkout_session = $user
                ->allowPromotionCodes()
                ->checkout($this->formatCartItems($user->cart->items), [
                    'customer_update' => [
                        'shipping' => 'auto'
                    ],
                    'shipping_address_collection' => [
                        'allowed_countries' => [
                            'US',
                            'NL',
                            'PH'
                        ]
                    ],
                    'metadata' => [
                        'user_id' => $user->id,
                        'cart_id' => $user->cart->id
                    ],
                    'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => route('checkout.cancel'),
                ]);

            return response()->json($checkout_session->url);
        }

        return response()->json('Unauthorized', 401);
    }

    public function success()
    {
        return 'Successful!';
    }

    public function cancel()
    {
        return 'Cancelled!';
    }

    private function formatCartItems(Collection $items)
    {
        return $items->loadMissing('product', 'variant')->map(function (CartItem $item) {
            return [
                'price_data' => [
                    'currency' => 'USD',
                    'unit_amount' => $item->product->price->getAmount(),
                    'product_data' => [
                        'name' => $item->product->name,
                        'description' => "Size: {$item->variant->size} - Color: {$item->variant->color}",
                        'metadata' => [
                            'product_id' => $item->product->id,
                            'product_variant_id' => $item->product_variant_id
                        ]
                    ]
                ],
                'quantity' => $item->quantity,
            ];
        })->toArray();
    }
}
