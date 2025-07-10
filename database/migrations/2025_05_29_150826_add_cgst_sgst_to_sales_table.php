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
        Schema::table('sales', function (Blueprint $table) {
             $table->decimal('total_cgst_amount', 10, 2)->default(0)->after('gst_percentage');
             $table->decimal('total_sgst_amount', 10, 2)->default(0)->after('total_cgst_amount');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
             $table->dropColumn(['total_cgst_amount', 'total_sgst_amount']);
        });
    }
};
