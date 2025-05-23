<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
     use HasFactory;

    protected $fillable = [
    'invoice_number',
    'customer_name',
    'phone',         
    'address',
    'gstin',
    'gst_type', 
    'gst_percentage',    
    'total',         
];

     
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }
}
