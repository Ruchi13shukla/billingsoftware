<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    use HasFactory;

     protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'price',
        'cost_price',
        'profit',
        'gst_percentage',
        'cgst_percentage', 
        'cgst_amount', 
        'sgst_percentage',
        'sgst_amount',
        'gst_amount', 
        'subtotal',
        'total',
       
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
    

    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
