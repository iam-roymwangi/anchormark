<p>Dear {{ $quoteData['name'] }},</p>

<p>Thank you for your quote request. We have received your request with reference <strong>{{ $quoteReference }}</strong>.</p>

<p>Our team will review your request and get back to you with a detailed quote within 24-48 hours.</p>

<h3>Your Request Details:</h3>
@if(isset($quoteData['cart_items']) && is_array($quoteData['cart_items']))
    {{-- Cart Quote with Multiple Items --}}
    <ul>
        <li><strong>Item Description:</strong> {{ $quoteData['itemDescription'] }}</li>
        <li><strong>Expected Delivery Date:</strong> {{ \Carbon\Carbon::parse($quoteData['expectedDeliveryDate'])->format('M d, Y') }}</li>
        <li><strong>Total Items:</strong> {{ count($quoteData['cart_items']) }}</li>
        <li><strong>Total Amount:</strong> Ksh. {{ number_format($quoteData['total_amount'], 2) }}</li>
    </ul>

    <h3>Items Requested:</h3>
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
            @foreach($quoteData['cart_items'] as $item)
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px;">{{ $item['product_name'] }}</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">{{ $item['sku'] ?? 'N/A' }}</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">{{ $item['quantity'] }}</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">Ksh. {{ number_format($item['price'], 2) }}</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">Ksh. {{ number_format(floatval(str_replace('$', '', $item['subtotal'])), 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f5f5f5; font-weight: bold;">
                <td colspan="4" style="border: 1px solid #ddd; padding: 8px; text-align: right;">Total:</td>
                <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">Ksh. {{ number_format($quoteData['total_amount'], 2) }}</td>
            </tr>
        </tfoot>
    </table>
@else
    {{-- Single Product Quote --}}
    <ul>
        <li><strong>Product:</strong> {{ $quoteData['product_name'] ?? 'N/A' }}</li>
        <li><strong>SKU:</strong> {{ $quoteData['sku'] ?? 'N/A' }}</li>
        <li><strong>Quantity:</strong> {{ $quoteData['quantity'] ?? 'N/A' }}</li>
        <li><strong>Item Description:</strong> {{ $quoteData['itemDescription'] }}</li>
        <li><strong>Expected Delivery Date:</strong> {{ \Carbon\Carbon::parse($quoteData['expectedDeliveryDate'])->format('M d, Y') }}</li>
    </ul>
@endif

<p>You can download your order details here: <a href="{{ $invoiceUrl }}">Download Order Details</a></p>

<p>You can view the status of your quote request by logging into your account on our website.</p>

<p>If you have any questions, please do not hesitate to contact us.</p>

<p>Sincerely,</p>
<p>The Anchormark Team</p>
