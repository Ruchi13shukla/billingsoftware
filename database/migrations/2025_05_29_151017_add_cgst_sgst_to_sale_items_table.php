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
        Schema::table('sale_items', function (Blueprint $table) {
            $table->decimal('cgst_percentage', 5, 2)->default(0)->after('gst_percentage');
            $table->decimal('cgst_amount', 10, 2)->default(0)->after('cgst_percentage');
            $table->decimal('sgst_percentage', 5, 2)->default(0)->after('cgst_amount');
            $table->decimal('sgst_amount', 10, 2)->default(0)->after('sgst_percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sale_items', function (Blueprint $table) {
             $table->dropColumn(['cgst_percentage', 'cgst_amount', 'sgst_percentage', 'sgst_amount']);
        });
    }
};
