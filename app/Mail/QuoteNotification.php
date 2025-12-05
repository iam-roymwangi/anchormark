<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QuoteNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $quoteId;
    public $quoteReference;
    public $quoteData;

    /**
     * Create a new message instance.
     */
    public function __construct($quoteId, $quoteReference, $quoteData)
    {
        $this->quoteId = $quoteId;
        $this->quoteReference = $quoteReference;
        $this->quoteData = $quoteData;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('New Quote Request - ' . $this->quoteReference)
                    ->view('emails.quote-notification')
                    ->with([
                        'quoteId' => $this->quoteId,
                        'quoteReference' => $this->quoteReference,
                        'quoteData' => $this->quoteData,
                    ]);
    }
}

