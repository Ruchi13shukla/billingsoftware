<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockReport extends Model
{
        use HasFactory;

        protected $fillable = [
        'product_id',
        'report_date',
        'opening_stock',
        'stock_out',
        'closing_stock',
    ];

        public function product()
    {
        return $this->belongsTo(Product::class);
    }


}
