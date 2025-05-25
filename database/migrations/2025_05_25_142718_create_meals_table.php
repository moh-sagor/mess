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
        Schema::create('meals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['breakfast', 'lunch', 'dinner']);
            $table->date('meal_date');
            $table->time('meal_time');
            $table->foreignId('meal_category_id')->constrained()->onDelete('cascade');
            $table->text('description')->nullable();
            $table->decimal('estimated_cost', 10, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('preference_deadline')->nullable();
            $table->timestamps();
            
            $table->index(['meal_date', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meals');
    }
};
