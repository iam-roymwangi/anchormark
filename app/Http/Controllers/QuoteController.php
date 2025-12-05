<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class QuoteController extends Controller
{
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
            'price' => 'required|numeric|min:0',
            'subtotal' => 'required|string',
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
                'unit_price' => $validated['price'],
                'subtotal' => floatval(str_replace('$', '', $validated['subtotal'])),
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
            \Log::error('Error storing quote: ' . $e->getMessage());
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
}

