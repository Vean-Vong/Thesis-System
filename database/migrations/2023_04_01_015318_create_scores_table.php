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
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('study_class_id');
            $table->foreignId('student_id');
            $table->tinyInteger('semester');
            //semester === 1 => semester 1, semester === 2 => semester 2
            $table->tinyInteger('type')->default(null)->nullable();
            // type === 0 => semester exam, type !== 0 => monthly exam
            $table->float('total')->nullable();
            $table->float('avg')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scores');
    }
};
