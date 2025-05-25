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
        Schema::create('cost_sharing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('period_start');
            $table->date('period_end');
            $table->decimal('total_meals_cost', 10, 2)->default(0);
            $table->decimal('total_other_expenses', 10, 2)->default(0);
            $table->decimal('user_share_amount', 10, 2)->default(0);
            $table->integer('meals_participated')->default(0);
            $table->integer('total_meals_available')->default(0);
            $table->enum('sharing_method', ['equal', 'proportional'])->default('proportional');
            $table->enum('status', ['calculated', 'paid', 'pending'])->default('calculated');
            $table->timestamps();
            
            $table->index(['user_id', 'period_start', 'period_end']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cost_sharing');
    }
};
