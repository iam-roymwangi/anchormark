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

<h3>Product Details:</h3>
<ul>
    <li><strong>Product:</strong> {{ $quoteData["product_name"] }}</li>
    <li><strong>SKU:</strong> {{ $quoteData["sku"] }}</li>
    <li><strong>Quantity:</strong> {{ $quoteData["quantity"] }}</li>
    <li><strong>Item Description:</strong> {{ $quoteData["itemDescription"] }}</li>
    <li><strong>Expected Delivery Date:</strong> {{ \Carbon\Carbon::parse($quoteData["expectedDeliveryDate"])->format("M d, Y") }}</li>
    <li><strong>Unit Price:</strong> ${{ number_format($quoteData["price"], 2) }}</li>
    <li><strong>Subtotal:</strong> ${{ number_format(floatval(str_replace("$", "", $quoteData["subtotal"])), 2) }}</li>
</ul>

<p>Please review this request and provide a detailed quote to the customer as soon as possible.</p>

<p>Best regards,</p>
<p>Automated System</p>
