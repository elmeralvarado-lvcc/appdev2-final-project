<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function success()
    {
        return response()->json('Thank you for your order! An order confirmation email has been sent.');
    }

    public function cancel()
    {
        return response()->json('Checkout process cancelled!');
    }
}
