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
        Schema::create('bazaar_records', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->decimal('quantity', 10, 2);
            $table->string('unit');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_cost', 10, 2);
            $table->date('purchase_date');
            $table->string('vendor_name')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('meal_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['purchase_date', 'item_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bazaar_records');
    }
};
