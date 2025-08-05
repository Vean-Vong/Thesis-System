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
        Schema::create('installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->uuid('seller_id'); // ✅ Use uuid
            $table->foreign('seller_id')->references('id')->on('users')->onDelete('cascade');
            $table->decimal('deposit', 10, 2)->default(0);
            $table->date('sale_date');
            $table->decimal('total_price', 10, 2);
            $table->decimal('monthly_fee', 10, 2);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('installments');
    }
};
