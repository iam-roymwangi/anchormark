# Quote Request System Setup Instructions

## Overview
This document explains how to set up the complete quote request system with email notifications and PDF invoice generation.

## Step 1: Create Database Migration

Create a migration file to create the `quotes` table:

```bash
php artisan make:migration create_quotes_table
```

Then add the following schema to the migration file:

```php
Schema::create('quotes', function (Blueprint $table) {
    $table->id();
    $table->string('quote_reference')->unique();
    $table->string('name');
    $table->string('email');
    $table->string('telephone');
    $table->string('company')->nullable();
    $table->text('item_description');
    $table->date('expected_delivery_date');
    $table->foreignId('product_id')->constrained()->onDelete('cascade');
    $table->string('product_name');
    $table->string('sku');
    $table->string('size')->nullable();
    $table->string('color')->nullable();
    $table->integer('quantity');
    $table->decimal('unit_price', 10, 2);
    $table->decimal('subtotal', 10, 2);
    $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
    $table->timestamps();
});
```

Run the migration:
```bash
php artisan migrate
```

## Step 2: Install PDF Generation Library

Install DomPDF for PDF generation:

```bash
composer require barryvdh/laravel-dompdf
```

Publish the config file (optional):
```bash
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

## Step 3: Configure Email Settings

Add to your `.env` file:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourcompany.com
MAIL_FROM_NAME="${APP_NAME}"

# Company email for quote notifications
COMPANY_EMAIL=sales@yourcompany.com
```

Add to `config/mail.php`:

```php
'company_email' => env('COMPANY_EMAIL', env('MAIL_FROM_ADDRESS')),
```

## Step 4: Create Email Views

Create the email view files:

### `resources/views/emails/quote-confirmation.blade.php`
```blade
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Quote Request Confirmation</title>
</head>
<body>
    <h1>Thank You for Your Quote Request!</h1>
    <p>Dear {{ $quoteData['name'] }},</p>
    <p>We have received your quote request (Reference: {{ $quoteReference }}).</p>
    
    <h2>Request Details:</h2>
    <ul>
        <li><strong>Product:</strong> {{ $quoteData['product_name'] }}</li>
        <li><strong>SKU:</strong> {{ $quoteData['sku'] }}</li>
        <li><strong>Quantity:</strong> {{ $quoteData['quantity'] }}</li>
        <li><strong>Expected Delivery:</strong> {{ $quoteData['expectedDeliveryDate'] }}</li>
    </ul>
    
    <p>Our team will review your request and send you a detailed quote within 24-48 hours.</p>
    
    <p>
        <a href="{{ $invoiceUrl }}" style="background-color: #AE8625; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
            Download Quote Invoice (PDF)
        </a>
    </p>
    
    <p>Best regards,<br>{{ config('app.name') }}</p>
</body>
</html>
```

### `resources/views/emails/quote-notification.blade.php`
```blade
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Quote Request</title>
</head>
<body>
    <h1>New Quote Request Received</h1>
    <p>A new quote request has been submitted:</p>
    
    <h2>Customer Information:</h2>
    <ul>
        <li><strong>Name:</strong> {{ $quoteData['name'] }}</li>
        <li><strong>Email:</strong> {{ $quoteData['email'] }}</li>
        <li><strong>Telephone:</strong> {{ $quoteData['telephone'] }}</li>
        @if($quoteData['company'])
        <li><strong>Company:</strong> {{ $quoteData['company'] }}</li>
        @endif
    </ul>
    
    <h2>Product Details:</h2>
    <ul>
        <li><strong>Product:</strong> {{ $quoteData['product_name'] }}</li>
        <li><strong>SKU:</strong> {{ $quoteData['sku'] }}</li>
        <li><strong>Size:</strong> {{ $quoteData['size'] ?? 'N/A' }}</li>
        <li><strong>Color:</strong> {{ $quoteData['color'] ?? 'N/A' }}</li>
        <li><strong>Quantity:</strong> {{ $quoteData['quantity'] }}</li>
        <li><strong>Unit Price:</strong> ${{ number_format($quoteData['price'], 2) }}</li>
        <li><strong>Subtotal:</strong> {{ $quoteData['subtotal'] }}</li>
    </ul>
    
    <h2>Additional Information:</h2>
    <p>{{ $quoteData['itemDescription'] }}</p>
    <p><strong>Expected Delivery Date:</strong> {{ $quoteData['expectedDeliveryDate'] }}</p>
    
    <p><strong>Quote Reference:</strong> {{ $quoteReference }}</p>
    <p><strong>Quote ID:</strong> {{ $quoteId }}</p>
</body>
</html>
```

## Step 5: Create PDF Invoice View

Create `resources/views/quotes/invoice.blade.php`:

```blade
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Quote Invoice - {{ $quote->quote_reference }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .header { border-bottom: 2px solid #AE8625; padding-bottom: 20px; margin-bottom: 30px; }
        .company-info { float: right; text-align: right; }
        .quote-details { margin: 30px 0; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #AE8625; color: white; }
        .total { text-align: right; font-weight: bold; font-size: 18px; margin-top: 20px; }
        .footer { margin-top: 50px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>QUOTE INVOICE</h1>
        <div class="company-info">
            <strong>{{ config('app.name') }}</strong><br>
            Quote Reference: {{ $quote->quote_reference }}<br>
            Date: {{ date('F d, Y', strtotime($quote->created_at)) }}
        </div>
    </div>
    
    <div class="quote-details">
        <h2>Customer Information</h2>
        <p>
            <strong>{{ $quote->name }}</strong><br>
            {{ $quote->email }}<br>
            {{ $quote->telephone }}<br>
            @if($quote->company)
            {{ $quote->company }}
            @endif
        </p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Product</th>
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
                <td>${{ number_format($quote->unit_price, 2) }}</td>
                <td>${{ number_format($quote->subtotal, 2) }}</td>
            </tr>
        </tbody>
    </table>
    
    <div class="total">
        Total: ${{ number_format($quote->subtotal, 2) }}
    </div>
    
    <div class="quote-details">
        <h3>Item Description</h3>
        <p>{{ $quote->item_description }}</p>
        
        <h3>Expected Delivery Date</h3>
        <p>{{ date('F d, Y', strtotime($quote->expected_delivery_date)) }}</p>
    </div>
    
    <div class="footer">
        <p>This is a quote request. A detailed quote will be sent to you within 24-48 hours.</p>
        <p>For inquiries, please contact us at {{ config('mail.from.address') }}</p>
    </div>
</body>
</html>
```

## Step 6: Update QuoteController

Make sure the QuoteController uses the PDF facade. Add this to the top of `app/Http/Controllers/QuoteController.php`:

```php
use PDF; // Add this if using barryvdh/laravel-dompdf
```

Or use:
```php
use Barryvdh\DomPDF\Facade\Pdf;
```

And update the invoice method:
```php
$pdf = Pdf::loadView('quotes.invoice', compact('quote'));
return $pdf->download('quote-' . $quote->quote_reference . '.pdf');
```

## Step 7: Test the System

1. Fill out the quote form on the product details page
2. Submit the request
3. Check that emails are sent to both customer and company
4. Verify PDF download works

## Notes

- Make sure your mail configuration is correct in `.env`
- For production, use a proper SMTP service (SendGrid, Mailgun, etc.)
- The PDF generation requires the dompdf library
- You may need to adjust the email templates to match your brand

