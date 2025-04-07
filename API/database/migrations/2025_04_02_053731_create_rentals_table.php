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
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->String('model');
            $table->string('price');
            $table->string('discount')->nullable();
            $table->date('date');
            $table->string('duration')->nullable();
            $table->string('warranty')->nullable();
            $table->String('seller');
            $table->enum('contract_type', ['rental']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};