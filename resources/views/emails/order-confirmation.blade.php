@component('mail::message')
<p>
    Hey {{ $order->user->name }},
</p>
<p>
    Thank you for your order. You find all the details below.
</p>
<table style="width:100%;">
    <thead>
        <tr>
            <th>Item</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Tax</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($order->items as $item)
            <tr>
                <td>
                    {{ $item->name }} <br>
                    {{ $item->description }}
                </td>
                <td>
                    {{ $item->price }}
                </td>
                <td>
                    {{ $item->quantity }}
                </td>
                <td>
                    {{ $item->amount_tax }}
                </td>
                <td>
                    {{ $item->amount_total }}
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        @if ($order->amount_shipping->isPositive())
        <tr>
            <td colspan="4" style="text-align: right;">
                Shipping costs
            </td>
            <td style="text-align: right;">
                {{ $order->amount_shipping }}
            </td>
        </tr>
        @endif
        @if ($order->amount_discount->isPositive())
        <tr>
            <td colspan="4" style="text-align: right;">
                Discount
            </td>
            <td style="text-align: right;">
                {{ $order->amount_discount }}
            </td>
        </tr>
        @endif
        @if ($order->amount_subtotal->isPositive())
        <tr>
            <td colspan="4" style="text-align: right;">
                Subtotal
            </td>
            <td style="text-align: right;">
                {{ $order->amount_subtotal }}
            </td>
        </tr>
        @endif
        @if ($order->amount_total->isPositive())
        <tr>
            <td colspan="4" style="text-align: right;">
                Total
            </td>
            <td style="text-align: right;">
                {{ $order->amount_total }}
            </td>
        </tr>
        @endif
    </tfoot>
</table>

@component('mail::button', ['url' => route('orders.show', $order->id), 'color' => 'success'])
    View Order
@endcomponent

@endcomponent
