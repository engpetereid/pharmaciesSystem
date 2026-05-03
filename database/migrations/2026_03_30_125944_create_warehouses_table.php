<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fix Issue 5: change warehouses.quantity from decimal()->nullable() to
 * unsignedInteger()->default(0) to match the model's integer cast and
 * eliminate the null/decimal type mismatch. Added a unique composite
 * index on (pharmacy_id, drug_id) to prevent duplicate warehouse rows
 * under concurrent firstOrCreate() calls (new Feature #3 from review).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pharmacy_id')->constrained('pharmacies')->cascadeOnDelete();
            $table->foreignId('drug_id')->constrained('drugs')->cascadeOnDelete();
            $table->unsignedInteger('quantity')->default(0);
            $table->timestamps();

            // Prevent duplicate rows for the same pharmacy + drug combination.
            $table->unique(['pharmacy_id', 'drug_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};
