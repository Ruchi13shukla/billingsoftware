<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
   
    public function create()
   {  
    return view('backend.product.add-product'); 
   }

    
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string',
        'category' => 'required|string',
        'price' => 'required|numeric',
        'cost_price' => 'required|numeric',
        'quantity' => 'required|integer',
        'gst_status' => 'required|in:Included,Excluded,Non-GST',
        'gst_percentage' => 'nullable|numeric|min:0|max:100',
    ]);

    Product::create([
        'name' => $request->name,
        'category' => $request->category,
        'price' => $request->price,
        'cost_price' => $request->cost_price,
        'quantity' => $request->quantity,
        'gst_status' => $request->gst_status,
        'gst_percentage' => $request->gst_percentage,
    ]);

    return redirect()->route('add-product')->with('success', 'Product added successfully.');
}


public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string',
        'category' => 'required|string',
        'price' => 'required|numeric',
        'cost_price' => 'required|numeric',
        'quantity' => 'required|integer',
        'gst_status' => 'required|in:Included,Excluded,Non-GST',
        'gst_percentage' => 'nullable|numeric|min:0|max:100',
    ]);

    $product = Product::findOrFail($id);
    $product->update([
        'name' => $request->name,
        'category' => $request->category,
        'price' => $request->price,
        'cost_price' => $request->cost_price,
        'quantity' => $request->quantity,
        'gst_status' => $request->gst_status,
        'gst_percentage' => $request->gst_percentage,
    ]);

    return redirect()->route('product.index')->with('success', 'Product updated successfully.');
}


    
    public function index()
    {
        $products = Product::all();
        return view('backend.product.index', compact('products'));
    }

     
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('backend.product.edit', compact('product'));
    }

    public function updateQuantity(Request $request, $id)
{
    $request->validate([
        'quantity' => 'required|integer|min:0',
    ]);

    $product = Product::findOrFail($id);
    $product->quantity = $request->quantity;
    $product->save();

    return redirect()->back()->with('success', 'Product quantity updated.');
}


    
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('product.index')->with('success', 'Product deleted successfully.');
    }

}
