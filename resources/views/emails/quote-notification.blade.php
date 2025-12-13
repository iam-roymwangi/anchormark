<p>Dear Team,</p>

<p>A new quote request has been submitted with reference <strong>{{ $quoteReference }}</strong>.</p>

<h3>Customer Details:</h3>
<ul>
    <li><strong>Name:</strong> {{ $quoteData["name"] }}</li>
    <li><strong>Email:</strong> {{ $quoteData["email"] }}</li>
    <li><strong>Telephone:</strong> {{ $quoteData["telephone"] }}</li>
    @if ($quoteData["company"])
        <li><strong>Company:</strong> {{ $quoteData["company"] }}</li>
    @endif
</ul>

@if(isset($quoteData['cart_items']) && is_array($quoteData['cart_items']))
    {{-- Cart Quote with Multiple Items --}}
    <h3>Quote Details:</h3>
    <ul>
        <li><strong>Item Description:</strong> {{ $quoteData["itemDescription"] }}</li>
        <li><strong>Expected Delivery Date:</strong> {{ \Carbon\Carbon::parse($quoteData["expectedDeliveryDate"])->format("M d, Y") }}</li>
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
    <h3>Product Details:</h3>
    <ul>
        <li><strong>Product:</strong> {{ $quoteData["product_name"] ?? 'N/A' }}</li>
        <li><strong>SKU:</strong> {{ $quoteData["sku"] ?? 'N/A' }}</li>
        <li><strong>Quantity:</strong> {{ $quoteData["quantity"] ?? 'N/A' }}</li>
        <li><strong>Item Description:</strong> {{ $quoteData["itemDescription"] }}</li>
        <li><strong>Expected Delivery Date:</strong> {{ \Carbon\Carbon::parse($quoteData["expectedDeliveryDate"])->format("M d, Y") }}</li>
        @if(isset($quoteData["price"]))
            <li><strong>Unit Price:</strong> Ksh. {{ number_format($quoteData["price"], 2) }}</li>
        @endif
        @if(isset($quoteData["subtotal"]))
            <li><strong>Subtotal:</strong> Ksh. {{ number_format(floatval(str_replace("$", "", $quoteData["subtotal"])), 2) }}</li>
        @endif
    </ul>
@endif

<p>Please review this request and provide a detailed quote to the customer as soon as possible.</p>

<p>Best regards,</p>
<p>Automated System</p>
