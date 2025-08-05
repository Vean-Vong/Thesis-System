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
            $table->id(); // BIGINT UNSIGNED primary key
            $table->unsignedBigInteger('customer_id')->nullable(); // if you link to customers
            $table->string('model');
            $table->decimal('price', 10, 2);
            $table->decimal('discount', 10, 2)->nullable();
            $table->date('date');
            $table->string('duration')->nullable();
            $table->string('warranty')->nullable();
            $table->string('seller')->nullable();
            $table->string('contract_type')->nullable();
            $table->timestamps();

            // Add foreign key if you have customers table
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
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
