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
    'total_cgst_amount', 
    'total_sgst_amount',
       
];

     
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function items()
{
    return $this->hasMany(SaleItem::class);
}
    
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

}
