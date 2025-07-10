<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Invoice;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendInvoice;
use Carbon\Carbon;
use App\Models\Customer;




class SaleController extends Controller
{
    public function create()
    {
        $products = Product::all();
        return view('backend.product.sale.create', compact('products'));

    }



public function invoice($id)
{
    
    $sale = Sale::with('items.product')->findOrFail($id);
    return view('backend.product.sale.invoice', compact('sale'));
}

public function store(Request $request)
{
    DB::beginTransaction();

    try {
        
        $validated = $request->validate([
            'customer_name' => 'required|string',
            'phone' => 'required|digits_between:10,12',
            'gst_type' => 'required|in:GST,Non-GST',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'gstin' => 'nullable|string',
            'address' => 'nullable|string',
            'email' => 'nullable|email'
        ]);

        
        $invoiceNumber = 'INV-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));

    
        $sale = Sale::create([
            'invoice_number' => $invoiceNumber,
            'customer_name' => $validated['customer_name'],
            'phone' => $validated['phone'],
            'gst_type' => $validated['gst_type'],
            'gstin' => $request->input('gstin'),
            'address' => $request->input('address'),
            'total_cgst_amount' => 0,
            'total_sgst_amount' => 0,
            'total' => 0,
        ]);

        $totalCgst = 0;
        $totalSgst = 0;
        $grandTotal = 0;

        foreach ($validated['products'] as $item) {
            $product = Product::findOrFail($item['product_id']);
            $quantity = $item['quantity'];

            if ($product->quantity == 0) {
                throw new \Exception("Product '{$product->name}' is out of stock.");
            }

            if ($product->quantity < $quantity) {
                throw new \Exception("Insufficient stock for product: {$product->name}");
            }

            $price = $product->price;
            $subtotal = $quantity * $price;

            $gstPercentage = $product->gst_percentage ?? 0;
            $cgst = $sgst = $cgstAmount = $sgstAmount = 0;

            if ($validated['gst_type'] === 'GST' && $gstPercentage > 0) {
                $cgst = $sgst = $gstPercentage / 2;
                $cgstAmount = round($subtotal * ($cgst / 100), 2);
                $sgstAmount = round($subtotal * ($sgst / 100), 2);
            }

            $total = $subtotal + $cgstAmount + $sgstAmount;

            $costPrice = $product->cost_price;
            $profit = ($price - $product->cost_price) * $quantity;
      
            
            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $price,
                'cost_price' => $costPrice, 
                'subtotal' => $subtotal,
                'gst_percentage' => $gstPercentage,
                'cgst_percentage' => $cgst,
                'cgst_amount' => $cgstAmount,
                'sgst_percentage' => $sgst,
                'sgst_amount' => $sgstAmount,
                'total' => $total,
                'profit' => $profit, 

            ]);

        
            $openingStock = $product->quantity;

         
            $product->quantity -= $quantity;
            $product->save();

            $closingStock = $product->quantity;


            \App\Models\StockReport::create([
                'product_id'     => $product->id,
                'report_date'    => now()->toDateString(),
                'opening_stock'  => $openingStock,
                'stock_out'      => $quantity,
                'closing_stock'  => $closingStock,
            ]);
        

            $totalCgst += $cgstAmount;
            $totalSgst += $sgstAmount;
            $grandTotal += $total;
        }

    
        $sale->update([
            'total_cgst_amount' => $totalCgst,
            'total_sgst_amount' => $totalSgst,
            'total' => $grandTotal,
        ]);

    
        $pdf = PDF::loadView('invoice.pdf', ['sale' => $sale]);
        $pdfPath = 'invoices/invoice_' . time() . '.pdf';
        \Storage::put($pdfPath, $pdf->output());

    
        Invoice::create([
            'sale_id' => $sale->id,
            'file_path' => $pdfPath,
            'user_id' => auth()->id(),
        ]);


        $caEmail = 'techpriya25@gmail.com';
        Mail::to($caEmail)->send(new SendInvoice($sale, $pdfPath));

        DB::commit();

        return redirect()->route('sale.invoice', $sale->id);
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Something went wrong: ' . $e->getMessage());
    }
}

public function showInvoice($id)
{
    $sale = Sale::with('saleItems.product')->findOrFail($id);
    return view('backend.product.sale.invoice', compact('sale'));
}

public function downloadPdf($id)
{
    $sale = Sale::with('saleItems.product')->findOrFail($id);

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('backend.product.sale.invoice-pdf', compact('sale'));

    return $pdf->download("invoice_{$sale->invoice_number}.pdf");
}


    public function show($id)
    {
        $sale = Sale::with('saleItems.product')->findOrFail($id);
        return view('product.sales.show', compact('sale'));
    }



public function monthlyProductSales(Request $request)
{
    $query = SaleItem::with(['product', 'sale.customer'])
        ->when($request->product_id, fn($q) => $q->where('product_id', $request->product_id))
        ->when($request->customer_id, fn($q) => $q->whereHas('sale', fn($s) => $s->where('customer_id', $request->customer_id)))
        ->when($request->from, fn($q) => $q->whereDate('created_at', '>=', Carbon::parse($request->from)))
        ->when($request->to, fn($q) => $q->whereDate('created_at', '<=', Carbon::parse($request->to)));

    $report = $query->get()->groupBy(function ($item) {
        return Carbon::parse($item->created_at)->format('Y-m');
    })->map(function ($items, $month) {
        $products = [];

        foreach ($items->groupBy('product_id') as $product_id => $productItems) {
            $product = $productItems->first()->product;
            $customers = $productItems->pluck('sale.customer')->unique('id')->filter();

            $products[] = [
                'product' => $product->name ?? 'N/A',
                'total_quantity' => $productItems->sum('quantity'),
                'total_amount' => $productItems->sum('total'),
                'customers' => $customers->map(function ($c) {
                    return [
                        'name' => $c->name ?? 'N/A',
                        'phone' => $c->phone ?? 'N/A',
                        'email' => $c->email ?? 'N/A',
                    ];
                }),
            ];
        }

        return [
            'products' => $products,
            'month_total' => $items->sum('total'),
        ];
    });

    $customers = \App\Models\Customer::all();  
    $products = \App\Models\Product::all();   

    return view('sales.monthly_report', compact('report', 'customers', 'products'));
}

}