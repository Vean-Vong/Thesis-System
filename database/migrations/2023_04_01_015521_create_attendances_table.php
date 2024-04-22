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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id');
            $table->foreignId('study_class_id');
            $table->date('date');
            $table->tinyInteger('absent');
            //absent === 1 =>  permission, absent === 2 => no permission
            $table->tinyInteger('meridiem');
            $table->string('reason')->nullable();
            //meridiem === 1 =>  morning, absent === 2 => afternoon
            $table->tinyInteger('month')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
