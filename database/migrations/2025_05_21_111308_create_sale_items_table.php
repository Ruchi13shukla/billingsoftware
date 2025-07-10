<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
             $table->foreignId('sale_id')->constrained('sales')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->unsignedInteger('quantity');
            $table->decimal('price', 10, 2);  
            $table->decimal('cost_price', 10, 2)->default(0);  
            $table->decimal('gst_percentage', 5, 2)->nullable(); 
            $table->decimal('cgst_percentage', 5, 2)->default(0)->after('gst_percentage');
            $table->decimal('cgst_amount', 10, 2)->default(0)->after('cgst_percentage');
            $table->decimal('sgst_percentage', 5, 2)->default(0)->after('cgst_amount');
            $table->decimal('sgst_amount', 10, 2)->default(0)->after('sgst_percentage');
            $table->decimal('subtotal', 10, 2); 
            $table->decimal('total', 10, 2)->nullable(); 
            $table->decimal('profit', 10, 2)->default(0);    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};
