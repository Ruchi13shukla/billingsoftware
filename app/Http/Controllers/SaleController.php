<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function create()
    {
        $products = Product::all();
        return view('backend.product.sale.create', compact('products'));

    }
public function store(Request $request)
{
    
    // dd($request->all());

    $validated = $request->validate([
        'customer_name' => 'required|string|max:255',
        'customer_phone' => 'required|string|max:20',
        'customer_address' => 'nullable|string',
        'customer_gstin' => 'nullable|string|max:20',
        'gst_percentage' => 'nullable|numeric',
        'total_amount' => 'required|numeric',
        'products' => 'required|array',
        'products.*.product_id' => 'required|exists:products,id',
        'products.*.price' => 'required|numeric',
        'products.*.quantity' => 'required|integer|min:1',
    ]);

    
    foreach ($validated['products'] as $product) {
        $productModel = Product::find($product['product_id']);
        if (!$productModel || $productModel->quantity < $product['quantity']) {
            return redirect()->back()->with('error', 'Not enough stock for ' . ($productModel->name ?? 'unknown product'));
        }
    }

    DB::transaction(function () use ($validated) {
    
        $sale = Sale::create([
            'invoice_number' => 'INV' . time() . rand(10, 99),
            'customer_name' => $validated['customer_name'],
            'phone' => $validated['customer_phone'],
            'address' => $validated['customer_address'],
            'gstin' => $validated['customer_gstin'],
            'gst_percentage' => $validated['gst_percentage'] ?? 0,
            'gst_type' => ($validated['gst_percentage'] ?? 0) > 0 ? 'GST' : 'Non-GST',
            'total' => $validated['total_amount'],
        ]);

    
foreach ($validated['products'] as $product) {
    $price = $product['price'];
    $quantity = $product['quantity'];
    $baseSubtotal = $price * $quantity;

    $productModel = Product::find($product['product_id']);


    $gstPercentage = $productModel->gst_percentage ?: ($validated['gst_percentage'] ?? 0);
$gstAmount = 0;
$finalSubtotal = $baseSubtotal;

if ($productModel->gst_status === 'Excluded') {
    $gstAmount = ($baseSubtotal * $gstPercentage) / 100;
    $finalSubtotal = $baseSubtotal + $gstAmount;
} elseif ($productModel->gst_status === 'Included') {
    $gstAmount = ($baseSubtotal * $gstPercentage) / (100 + $gstPercentage);
    $finalSubtotal = $baseSubtotal;
} else {
    $gstPercentage = 0;
    $gstAmount = 0;
    $finalSubtotal = $baseSubtotal;
}

    SaleItem::create([
        'sale_id' => $sale->id,
        'product_id' => $product['product_id'],
        'price' => $price,
        'quantity' => $quantity,
        'gst_percentage' => $gstPercentage,
        'subtotal' => $finalSubtotal,
    ]);


    $productModel->quantity -= $quantity;
    $productModel->save();
}
    });

    return redirect()->back()->with('success', 'Sale recorded successfully!');
}

    public function show($id)
    {
        $sale = Sale::with('saleItems.product')->findOrFail($id);
        return view('product.sales.show', compact('sale'));
    }

}
