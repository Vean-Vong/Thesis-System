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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique()->nullable();
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('first_name_latin', 50);
            $table->string('last_name_latin', 50);
            $table->tinyInteger('gender');
            $table->date('dob')->nullable();
            $table->string('school_name', 50)->nullable();
            $table->string('school_code', 50)->nullable();
            $table->string('village', 50)->nullable();
            $table->string('commune', 50)->nullable();
            $table->string('district', 50)->nullable();
            $table->string('province', 50)->nullable();
            $table->string('phone', 50);
            $table->string('photo_path')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
