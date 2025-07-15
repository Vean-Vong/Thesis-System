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
        Schema::create('setups', function (Blueprint $table) {
            $table->id();
            $table->string("customer_name");
            $table->string("model")->nullable();
            $table->string('invoice_number')->unique();
            $table->decimal("unit", 8, 2)->default(0);
            $table->decimal("cash_on_hand", 10, 2)->default(0);
            $table->decimal("cash_on_bank", 10, 2)->default(0);
            $table->date("date")->nullable();
            $table->string("remark")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setups');
    }
};