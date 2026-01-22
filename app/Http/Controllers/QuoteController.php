<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;

class QuoteController extends Controller
{
    /**
     * Display the quote request page with cart items
     */
    public function index(Request $request): Response
    {
        $cart = $this->getCurrentCart($request);

        $cart->load([
            'cartProducts.product',
            'cartProducts.product.images'
        ]);

        return Inertia::render('Quote/Index', [
            'items' => $cart->cartProducts,
            'totals' => [
                'items' => $cart->total_items,
                'amount' => $cart->total_amount,
                'formatted_amount' => $cart->formatted_total_amount
            ]
        ]);
    }

    /**
     * Get current cart for user (guest or authenticated)
     */
    private function getCurrentCart(Request $request): Cart
    {
        if (Auth::check() && Auth::user()->isShopper()) {
            // Authenticated user with shopper profile
            $cart = Auth::user()->activeCart();
            if (!$cart) {
                $cart = Cart::findOrCreateForShopper(Auth::user()->shopper->id);
            }
            return $cart;
        }

        // Guest user
        $sessionId = Session::get('cart_session_id');
        return Cart::findOrCreateForGuest($sessionId);
    }
    /**
     * Store a new quote request
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telephone' => 'required|string|max:20',
            'company' => 'nullable|string|max:255',
            'itemDescription' => 'required|string',
            'expectedDeliveryDate' => 'required|date',
            'product_id' => 'required|exists:products,id',
            'product_name' => 'required|string',
            'sku' => 'required|string',
            'size' => 'nullable|string',
            'color' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
        ]);

        try {
            // Generate unique quote reference
            $quoteReference = 'QT-' . strtoupper(uniqid());

            // Store quote in database (you'll need to create a quotes table)
            $quoteId = DB::table('quotes')->insertGetId([
                'quote_reference' => $quoteReference,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'telephone' => $validated['telephone'],
                'company' => $validated['company'] ?? null,
                'item_description' => $validated['itemDescription'],
                'expected_delivery_date' => $validated['expectedDeliveryDate'],
                'product_id' => $validated['product_id'],
                'product_name' => $validated['product_name'],
                'sku' => $validated['sku'],
                'size' => $validated['size'] ?? null,
                'color' => $validated['color'] ?? null,
                'quantity' => $validated['quantity'],
                'unit_price' => 0, // Price hidden from frontend
                'subtotal' => 0, // Price hidden from frontend
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Send email to customer
            Mail::to($validated['email'])->send(
                new \App\Mail\QuoteConfirmation($quoteId, $quoteReference, $validated)
            );

            // Send email to company
            $companyEmail = config('mail.company_email', config('mail.from.address'));
            Mail::to($companyEmail)->send(
                new \App\Mail\QuoteNotification($quoteId, $quoteReference, $validated)
            );

            return redirect()->back()->with([
                'success' => 'Quote request submitted successfully!',
                'quote_id' => $quoteId,
                'invoice_url' => route('quotes.invoice', $quoteId),
            ]);
        } catch (\Exception $e) {
            Log::error('Error storing quote: ' . $e->getMessage());
            return redirect()->back()->withErrors([
                'message' => 'Failed to submit quote request. Please try again.'
            ]);
        }
    }

    /**
     * Generate and download quote invoice PDF
     */
    public function invoice($id)
    {
        $quote = DB::table('quotes')->where('id', $id)->first();

        if (!$quote) {
            abort(404, 'Quote not found');
        }

        // Generate PDF using dompdf
        $pdf = Pdf::loadView('quotes.invoice', compact('quote'));
        return $pdf->download('quote-' . $quote->quote_reference . '.pdf');
    }

    /**
     * Store a quote request from cart (multiple items)
     */
    public function storeCart(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telephone' => 'required|string|max:20',
            'company' => 'nullable|string|max:255',
            'itemDescription' => 'required|string',
            'expectedDeliveryDate' => 'required|date',
            'cart_items' => 'required|array|min:1',
            'cart_items.*.product_id' => 'required|exists:products,id',
            'cart_items.*.product_name' => 'required|string',
            'cart_items.*.sku' => 'nullable|string',
            'cart_items.*.size' => 'nullable|string',
            'cart_items.*.color' => 'nullable|string',
            'cart_items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            // Generate unique quote reference
            $quoteReference = 'QT-' . strtoupper(uniqid());

            // Get first product for required product_id field
            $firstItem = $validated['cart_items'][0];

            // Store quote in database with cart items
            // Use first product_id (required by table structure) but include all items in description
            $quoteId = DB::table('quotes')->insertGetId([
                'quote_reference' => $quoteReference,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'telephone' => $validated['telephone'],
                'company' => $validated['company'] ?? null,
                'item_description' => $validated['itemDescription'],
                'expected_delivery_date' => $validated['expectedDeliveryDate'],
                'product_id' => $firstItem['product_id'], // Use first product (required by table)
                'product_name' => 'Multiple Items (' . count($validated['cart_items']) . ' items)', // Indicates cart quote
                'sku' => 'CART-' . $quoteReference,
                'size' => $firstItem['size'] ?? null,
                'color' => $firstItem['color'] ?? null,
                'quantity' => array_sum(array_column($validated['cart_items'], 'quantity')), // Total quantity
                'unit_price' => 0, // Price hidden from frontend
                'subtotal' => 0, // Price hidden from frontend
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Try to store individual cart items in quote_items table if it exists
            // If table doesn't exist, items are already stored in item_description
            try {
                foreach ($validated['cart_items'] as $item) {
                    DB::table('quote_items')->insert([
                        'quote_id' => $quoteId,
                        'product_id' => $item['product_id'],
                        'product_name' => $item['product_name'],
                        'sku' => $item['sku'] ?? null,
                        'size' => $item['size'] ?? null,
                        'color' => $item['color'] ?? null,
                        'quantity' => $item['quantity'],
                        'unit_price' => 0, // Price hidden from frontend
                        'subtotal' => 0, // Price hidden from frontend
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } catch (\Exception $e) {
                // quote_items table doesn't exist, which is fine
                // All item details are already in item_description
                \Log::info('quote_items table not found, storing items in description only');
            }

            // Prepare data for email
            $emailData = array_merge($validated, [
                'quote_id' => $quoteId,
                'quote_reference' => $quoteReference,
            ]);

            // Send email to customer (wrap in try-catch to not fail quote submission)
            try {
                Mail::to($validated['email'])->send(
                    new \App\Mail\QuoteConfirmation($quoteId, $quoteReference, $emailData)
                );
            } catch (\Exception $mailError) {
                \Log::warning('Failed to send quote confirmation email: ' . $mailError->getMessage());
                // Don't fail the quote submission if email fails
            }

            // Send email to company (wrap in try-catch to not fail quote submission)
            try {
                $companyEmail = config('mail.company_email', config('mail.from.address'));
                Mail::to($companyEmail)->send(
                    new \App\Mail\QuoteNotification($quoteId, $quoteReference, $emailData)
                );
            } catch (\Exception $mailError) {
                \Log::warning('Failed to send quote notification email: ' . $mailError->getMessage());
                // Don't fail the quote submission if email fails
            }

            // Get cart for order creation (before clearing)
            try {
                $cart = $this->getCurrentCart($request);
                if ($cart) {
                    $cart->load('cartProducts.product');

                    // Create order from cart items
                    $order = $this->createOrderFromCart($cart, $validated, $quoteId);

                    // Clear cart after successful quote submission and order creation
                    if (!$cart->isEmpty()) {
                        $cart->clear();
                    }
                }
            } catch (\Exception $cartError) {
                \Log::warning('Error processing cart during quote submission: ' . $cartError->getMessage());
                // Don't fail the quote submission if cart processing fails
                $order = null;
            }

            return redirect()->back()->with([
                'success' => 'Quote request submitted successfully!',
                'quote_id' => $quoteId,
                'invoice_url' => route('quotes.invoice', $quoteId),
                'order_id' => $order->id ?? null,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation errors
            \Log::error('Validation error storing cart quote: ' . json_encode($e->errors()));
            return redirect()->back()->withErrors($e->errors());
        } catch (\Exception $e) {
            // Log full error details
            \Log::error('Error storing cart quote: ' . $e->getMessage());
            \Log::error('Error file: ' . $e->getFile() . ':' . $e->getLine());
            \Log::error('Error trace: ' . $e->getTraceAsString());

            // Return more detailed error for debugging (remove in production)
            $errorMessage = config('app.debug')
                ? 'Failed to submit quote request: ' . $e->getMessage() . ' (File: ' . basename($e->getFile()) . ':' . $e->getLine() . ')'
                : 'Failed to submit quote request. Please try again.';

            return redirect()->back()->withErrors([
                'message' => $errorMessage
            ]);
        }
    }

    /**
     * Create an order from cart items after quote submission
     */
    private function createOrderFromCart(Cart $cart, array $quoteData, int $quoteId): ?Order
    {
        if ($cart->isEmpty()) {
            return null;
        }

        DB::beginTransaction();

        try {
            // Get delivery information
            $shopperId = null;
            $deliveryAddress = null;
            $deliveryCity = null;
            $deliveryCountry = 'Kenya'; // Default country
            $deliveryPhone = $quoteData['telephone'];

            if (Auth::check() && Auth::user()->isShopper()) {
                $shopper = Auth::user()->shopper;
                $shopperId = $shopper->id;

                // Build address from available shopper fields
                $addressParts = array_filter([
                    $shopper->closest_landmark,
                    $shopper->town,
                    $shopper->county,
                ]);
                $deliveryAddress = !empty($addressParts)
                    ? implode(', ', $addressParts)
                    : 'Address to be confirmed';
                $deliveryCity = $shopper->town ?? $shopper->county ?? null;
                $deliveryPhone = Auth::user()->phone_number ?? $quoteData['telephone'];
            } else {
                // For guest users, use company or name for address
                $deliveryAddress = $quoteData['company']
                    ? $quoteData['company'] . ' - Address to be confirmed'
                    : $quoteData['name'] . ' - Address to be confirmed';
            }

            // Create the order
            $order = new Order();
            $order->shopper_id = $shopperId;
            $order->order_number = 'ORD-' . Str::upper(Str::random(10));
            $order->total_amount = 0; // Price hidden from frontend
            $order->order_status = OrderStatus::Pending->value;
            $order->payment_status = PaymentStatus::Unpaid->value; // Quotes are unpaid until confirmed
            $order->delivery_address = $deliveryAddress;
            $order->delivery_city = $deliveryCity;
            $order->delivery_country = $deliveryCountry;
            $order->delivery_phone = $deliveryPhone;
            $order->save();

            // Create order items from cart products
            foreach ($cart->cartProducts as $cartProduct) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $cartProduct->product_id;
                $orderItem->quantity = $cartProduct->quantity;
                $orderItem->price = 0; // Price hidden from frontend
                $orderItem->subtotal = 0; // Price hidden from frontend
                $orderItem->save();

                // Optionally, reserve stock (don't decrement yet for quotes, just reserve)
                // This depends on your business logic - you might want to reserve stock
                // or wait until quote is confirmed
                // $product = $cartProduct->product;
                // if ($product->stock_quantity >= $cartProduct->quantity) {
                //     $product->stock_quantity -= $cartProduct->quantity;
                //     $product->save();
                // }
            }

            DB::commit();

            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating order from quote: ' . $e->getMessage());
            // Don't fail the quote submission if order creation fails
            // The quote is still valid, order can be created manually later
            return null;
        }
    }
}
