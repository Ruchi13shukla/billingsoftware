<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Sale;

class SendInvoice extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */

    public $sale;
    public $filePath;

    public function __construct(Sale $sale, $filePath)
    {
        $this->sale = $sale;
        $this->filePath = $filePath;
    }

    public function build()
    {
        return $this->subject('Your Invoice - #' . $this->sale->invoice_number)
                    ->view('emails.invoice')
                    ->with(['sale' => $this->sale])
                    ->attach(storage_path('app/' . $this->filePath)); 
    }

   
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Send Invoice',
        );
    }


    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
