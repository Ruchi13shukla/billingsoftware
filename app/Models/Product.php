<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'price',
        'cost_price',
        'quantity',
        'gst_status',
        'gst_percentage', 
    ];

    
    protected $casts = [
        'gst_percentage' => 'float',
];


    public function saleItems()
{
    return $this->hasMany(SaleItem::class);
}
}
