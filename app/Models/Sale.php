<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
     use HasFactory;

    protected $fillable = [
        'invoice_number', 'customer_name', 'customer_phone', 'customer_address',
        'customer_gstin', 'total_amount', 'is_gst'
    ];

     // A sale has many sale items
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }
}
