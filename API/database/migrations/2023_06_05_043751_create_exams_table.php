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
        Schema::create('exams', function (Blueprint $table) {
            $table->uuid('id');
            $table->foreignUuid('academic_class_id')->index();
            $table->foreignUuid('student_id')->index();
            $table->tinyInteger('semester')->comment('for semester 1 or 2');
            $table->tinyInteger('type')->comment('0 => is semester exam, 1-12 is monthly exam');
            $table->float('m')->comment('Mathemathic')->default(0)->nullable();
            $table->float('k')->comment('Khmer')->default(0)->nullable();
            $table->float('sc')->comment('Science')->default(0)->nullable();
            $table->float('s')->comment('Sociality')->default(0)->nullable();
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
        Schema::dropIfExists('exams');
    }
};
