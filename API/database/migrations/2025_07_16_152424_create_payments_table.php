<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('installment_id')->nullable();
            $table->unsignedBigInteger('customer_id');

            // <-- here, seller_id must be char(36) to match users.id
            $table->char('seller_id', 36);

            $table->decimal('amount', 15, 2);
            $table->date('payment_date');
            $table->string('note')->nullable();

            $table->timestamps();

            // Foreign keys
            $table->foreign('installment_id')->references('id')->on('installments')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('seller_id')->references('id')->on('users')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('installments');
    }
};
