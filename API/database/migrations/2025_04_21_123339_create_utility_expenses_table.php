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
        Schema::create('utility_expenses', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['Water', 'Electricity', 'Garbage']);
            $table->date('bill_date'); // Date of bill
            $table->float('usage_amount'); // Usage (kWh or m³)
            $table->decimal('cost', 10, 2); // Total cost
            $table->decimal('unit_rate', 8, 4)->nullable(); // Cost per unit (optional)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utility_expenses');
    }
};