<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
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
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string|max:255',
            'customer_gstin' => 'nullable|string|max:15',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            // Generate unique invoice number
            $invoiceNumber = 'INV-' . str_pad((Sale::max('id') ?? 0) + 1, 6, '0', STR_PAD_LEFT);

            // Create sale
            $sale = Sale::create([
                'invoice_number' => $invoiceNumber,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'customer_gstin' => $request->customer_gstin,
            ]);

            $totalAmount = 0;

            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['product_id']);
                $quantity = $item['quantity'];
                $subtotal = $product->price * $quantity;

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                ]);

                $totalAmount += $subtotal;

                // Optional: reduce stock
                $product->decrement('quantity', $quantity);
            }

            $sale->update(['total_amount' => $totalAmount]);

            DB::commit();

            return redirect()->route('sales.show', $sale->id)->with('success', 'Sale created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $sale = Sale::with('saleItems.product')->findOrFail($id);
        return view('product.sales.show', compact('sale'));
    }
}

