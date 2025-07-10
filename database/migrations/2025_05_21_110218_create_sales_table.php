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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
             $table->string('invoice_number')->unique();
            $table->string('customer_name');
            $table->string('phone', 10);
            $table->string('address')->nullable();
            $table->string('gstin')->nullable();
            $table->enum('gst_type', ['GST', 'Non-GST'])->default('Non-GST');
            $table->decimal('gst_percentage', 5, 2)->nullable();
            $table->decimal('total_cgst_amount', 10, 2)->default(0)->after('gst_percentage');
            $table->decimal('total_sgst_amount', 10, 2)->default(0)->after('total_cgst_amount');
            $table->decimal('total', 10, 2); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
