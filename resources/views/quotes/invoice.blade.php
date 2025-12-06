<!DOCTYPE html>
<html>
<head>
    <title>Quote Invoice - {{ $quote->quote_reference }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            border: 1px solid #eee;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #AE8625;
        }
        .details-section {
            margin-bottom: 30px;
        }
        .details-section h2 {
            color: #AE8625;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        .details-section p {
            margin: 5px 0;
        }
        .item-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .item-table th,
        .item-table td {
            border: 1px solid #eee;
            padding: 8px;
            text-align: left;
        }
        .item-table th {
            background-color: #f8f8f8;
        }
        .total-section {
            text-align: right;
            margin-top: 20px;
        }
        .total-section p {
            margin: 5px 0;
            font-size: 1.1em;
        }
        .footer {
            text-align: center;
            margin-top: 50px;
            font-size: 0.8em;
            color: #666;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Anchormark Quote Request</h1>
            <p><strong>Quote Reference:</strong> {{ $quote->quote_reference }}</p>
            <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($quote->created_at)->format('M d, Y') }}</p>
        </div>

        <div class="details-section">
            <h2>Customer Details</h2>
            <p><strong>Name:</strong> {{ $quote->name }}</p>
            <p><strong>Email:</strong> {{ $quote->email }}</p>
            <p><strong>Telephone:</strong> {{ $quote->telephone }}</p>
            @if ($quote->company)
                <p><strong>Company:</strong> {{ $quote->company }}</p>
            @endif
            <p><strong>Expected Delivery Date:</strong> {{ \Carbon\Carbon::parse($quote->expected_delivery_date)->format('M d, Y') }}</p>
        </div>

        <div class="details-section">
            <h2>Product Details</h2>
            <table class="item-table">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>SKU</th>
                        <th>Size</th>
                        <th>Color</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $quote->product_name }}</td>
                        <td>{{ $quote->sku }}</td>
                        <td>{{ $quote->size ?? 'N/A' }}</td>
                        <td>{{ $quote->color ?? 'N/A' }}</td>
                        <td>{{ $quote->quantity }}</td>
                        <td>Ksh. {{ number_format($quote->unit_price, 2) }}</td>
                        <td>Ksh. {{ number_format($quote->subtotal, 2) }}</td>
                    </tr>
                </tbody>
            </table>
            <p><strong>Item Description:</strong> {{ $quote->item_description }}</p>
        </div>

        <div class="total-section">
            <p><strong>Total Estimated Price:</strong> <span style="color: #AE8625;">${{ number_format($quote->subtotal, 2) }}</span></p>
            <p style="font-size: 0.9em; color: #666;">(This is an estimated quote. Final price may vary upon confirmation.)</p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Anchormark. All rights reserved.</p>
            <p>Mombasa Road, Nairobi, Kenya</p>
        </div>
    </div>
</body>
</html>
