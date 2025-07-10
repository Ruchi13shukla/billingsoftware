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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('sale_id')->nullable(); // ✅ Added sale_id

            $table->string('file_path');
            $table->timestamps();

            // Optional: foreign key constraint if you want referential integrity
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade'); // ✅ FK for sale


            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
