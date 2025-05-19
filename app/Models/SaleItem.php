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
        'subtotal',
    ];

    // Each sale item belongs to a sale
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    // Each sale item belongs to a product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
