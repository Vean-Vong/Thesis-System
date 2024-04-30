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
        Schema::create('students', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('school_id')->index();
            $table->string('code', 20)->nullable()->index();
            $table->string('name', 100);
            $table->string('name_latin', 100);
            $table->tinyInteger('sex')->nullable();
            $table->date('dob')->nullable();
            $table->string('photo_path')->nullable();
            $table->string('pob')->nullable();
            $table->string('address')->nullable();
            $table->string('dad_name', 50)->nullable();
            $table->string('dad_phone', 50)->nullable();
            $table->string('mom_name', 50)->nullable();
            $table->string('mom_phone', 50)->nullable();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
