<p>Dear {{ $quoteData['name'] }},</p>

<p>Thank you for your quote request. We have received your request with reference <strong>{{ $quoteReference }}</strong>.</p>

<p>Our team will review your request and get back to you with a detailed quote within 24-48 hours.</p>

<h3>Your Request Details:</h3>
<ul>
    <li><strong>Product:</strong> {{ $quoteData['product_name'] }}</li>
    <li><strong>SKU:</strong> {{ $quoteData['sku'] }}</li>
    <li><strong>Quantity:</strong> {{ $quoteData['quantity'] }}</li>
    <li><strong>Item Description:</strong> {{ $quoteData['itemDescription'] }}</li>
    <li><strong>Expected Delivery Date:</strong> {{ \Carbon\Carbon::parse($quoteData['expectedDeliveryDate'])->format('M d, Y') }}</li>
</ul>

<p>You can download your order details here: <a href="{{ $invoiceUrl }}">Download Order Details</a></p>

<p>You can view the status of your quote request by logging into your account on our website.</p>

<p>If you have any questions, please do not hesitate to contact us.</p>

<p>Sincerely,</p>
<p>The Anchormark Team</p>
