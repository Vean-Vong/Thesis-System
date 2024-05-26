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
            $table->id();
            $table->foreignUuid('academic_class_id')->index();
            $table->foreignUuid('student_id')->index();;
            $table->tinyInteger('att')->default(0);
            $table->tinyInteger('quiz')->default(0);
            $table->tinyInteger('hw')->default(0);
            $table->tinyInteger('re')->default(0);
            $table->tinyInteger('voc')->default(0);
            $table->tinyInteger('gr')->default(0);
            $table->tinyInteger('liu')->default(0);
            $table->tinyInteger('wr')->default(0);
            $table->tinyInteger('li')->default(0);
            $table->tinyInteger('sp')->default(0);

            $table->tinyInteger('mid')->default(0);
            $table->tinyInteger('attitude')->default(0);
            $table->tinyInteger('final')->default(0);
            
            $table->float('total', 5, 2)->default(0);
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
