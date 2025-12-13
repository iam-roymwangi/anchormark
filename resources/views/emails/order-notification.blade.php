<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Order Notification</title>
</head>
<body>
    <p>Dear Team,</p>

    <p>A new order has been placed with reference <strong>{{ $order->order_number }}</strong>.</p>

    <h3>Customer Details:</h3>
    <ul>
        <li><strong>Name:</strong> {{ $orderData['customer_name'] }}</li>
        <li><strong>Email:</strong> {{ $orderData['customer_email'] }}</li>
        <li><strong>Phone:</strong> {{ $orderData['customer_phone'] }}</li>
    </ul>

    <h3>Order Details:</h3>
    <ul>
        <li><strong>Order Number:</strong> {{ $order->order_number }}</li>
        <li><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}</li>
        <li><strong>Total Amount:</strong> Ksh. {{ number_format($order->total_amount, 2) }}</li>
        <li><strong>Payment Method:</strong> {{ ucfirst($orderData['payment_method']) }}</li>
        <li><strong>Order Status:</strong> {{ ucfirst($order->order_status) }}</li>
        <li><strong>Payment Status:</strong> {{ ucfirst($order->payment_status) }}</li>
    </ul>

    <h3>Delivery Information:</h3>
    <ul>
        <li><strong>Delivery Address:</strong> {{ $order->delivery_address }}</li>
        @if($order->delivery_city)
            <li><strong>City:</strong> {{ $order->delivery_city }}</li>
        @endif
        <li><strong>Country:</strong> {{ $order->delivery_country }}</li>
        <li><strong>Phone:</strong> {{ $order->delivery_phone }}</li>
    </ul>

    <h3>Items Ordered:</h3>
    <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
        <thead>
            <tr style="background-color: #f5f5f5;">
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Product</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">SKU</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">Quantity</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: right;">Unit Price</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px;">{{ $item->product->name }}</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">{{ $item->product->sku ?? 'N/A' }}</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">{{ $item->quantity }}</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">Ksh. {{ number_format($item->price, 2) }}</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">Ksh. {{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f5f5f5; font-weight: bold;">
                <td colspan="4" style="border: 1px solid #ddd; padding: 8px; text-align: right;">Total:</td>
                <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">Ksh. {{ number_format($order->total_amount, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <p>Please process this order and update the customer with shipping information.</p>

    <p>Best regards,</p>
    <p>Automated System</p>
</body>
</html>