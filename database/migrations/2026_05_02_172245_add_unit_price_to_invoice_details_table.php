<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fix Issue 6: the original file (addunit_price_t0_invoive_details_table.php)
 * was a blank stub created by a typo. This correct migration adds unit_price
 * as decimal(10,2) (not integer — prices are fractional) and adds a proper
 * down() rollback. The orphaned stub is neutralised (kept empty) to preserve
 * migration history without breaking fresh deployments.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoice_details', function (Blueprint $table) {
            $table->decimal('unit_price', 10, 2)->nullable()->after('quantity');
        });
    }

    public function down(): void
    {
        Schema::table('invoice_details', function (Blueprint $table) {
            $table->dropColumn('unit_price');
        });
    }
};
