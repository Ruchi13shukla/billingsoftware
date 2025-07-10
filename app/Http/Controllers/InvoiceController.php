<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Mail\InvoiceMail;
use App\Models\Sale;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Auth;
use App\Mail\SendInvoice;




class InvoiceController extends Controller
{
public function generateAndSend($saleId)
{
    $sale = Sale::with('items')->find($saleId);

    if (!$sale) {
        return response()->json(['error' => 'Sale not found'], 404);
    }

    
    $pdf = Pdf::loadView('invoice.view', compact('sale'));
    $fileName = 'invoices/invoice_' . time() . '.pdf';

    
    Storage::disk('public')->put($fileName, $pdf->output());

    
    $invoice = Invoice::create([
        'user_id' => auth()->id() ?? null,
        'sale_id' => $sale->id,
        'file_path' => $fileName,
    ]);

    
    Mail::send('emails.invoice', ['sale' => $sale], function ($message) use ($fileName) {
        $message->to('techpriya25@gmail.com')
                ->subject('New Invoice Generated')
                ->attach(storage_path('app/public/' . $fileName));
    });

    return response()->json([
        'message' => 'Invoice created, saved, and sent to CA.',
        'invoice_id' => $invoice->id,
        'pdf_path' => $fileName
    ]);
}


public function generateInvoiceFromSale(\App\Models\Sale $sale)
{
    $pdf = PDF::loadView('invoice.view', compact('sale'));

    $filename = 'invoice_' . time() . '.pdf';
    $filePath = 'invoices/' . $filename;

    Storage::disk('public')->put($filePath, $pdf->output());

    $invoice = \App\Models\Invoice::create([
        'user_id' => auth()->id(),
        'sale_id' => $sale->id,
        'file_path' => $filePath,
    ]);

    Mail::to('techpriya25@gmail.com')->send(new \App\Mail\SendInvoice($sale, $filePath));

    return $invoice;
}

}
