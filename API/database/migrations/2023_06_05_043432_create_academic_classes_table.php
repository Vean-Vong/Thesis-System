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
        Schema::create('academic_classes', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // 1 for កាត់ដេ 2 for englist 3 for computer classes
            $table->tinyInteger('type')->index();
            $table->string('name', 50);
            $table->foreignUuid('teacher_id');
            $table->foreignId('room_id')->nullable();
            $table->foreignId('time_id')->nullable();
            $table->foreignId('level_id')->nullable();
            $table->foreignId('academic_year_id');
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
        Schema::dropIfExists('academic_classes');
    }
};
