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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('model');
            $table->string('colors');
            $table->string('filtration_stage');
            $table->string('cold_water_tank_capacity');
            $table->string('hot_water_tank_capacity');
            $table->string('heating_capacity');
            $table->string('cooling_capacity');
            $table->string('cold_power_consumption');
            $table->string('hot_power_consumption');
            $table->integer('quantity')->default(0);
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};