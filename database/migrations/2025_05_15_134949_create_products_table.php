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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');                    
            $table->string('category');                
            $table->decimal('price', 10, 2);  
            $table->decimal('cost_price', 10, 2);          // ✅ Cost Price added          
            $table->unsignedInteger('quantity');        
            $table->enum('gst_status', ['Included', 'Excluded'])->default('Excluded');
            $table->decimal('gst_percentage', 5, 2)->nullable();
            $table->timestamps();                       
            $table->softDeletes();                      
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
