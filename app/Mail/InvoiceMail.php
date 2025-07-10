<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;


class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    // public $pdfPath;
         public $filePath;


    /**
     * Create a new message instance.
     */


         public function __construct($filePath)
    {
        $this->filePath = storage_path('app/' . $filePath);
    }

    public function build()
    {
        return $this->subject('GST Invoice')
                    ->view('emails.invoice') 
                    ->attach($this->filePath);
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
