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
        Schema::create('studies', function (Blueprint $table) {
            $table->uuid('id');
            $table->foreignUuid('academic_class_id')->index();
            $table->foreignUuid('student_id')->index();
            //first semester
            $table->string('grade_first_semester', 5)->nullable();
            $table->float('total_first_semester')->nullable();
            $table->float('avg_first_semester')->nullable();
            $table->float('number_first_semester')->nullable();
            //second semester
            $table->float('total_second_semester')->nullable();
            $table->float('avg_second_semester')->nullable();
            $table->string('grade_second_semester', 5)->nullable();
            $table->float('number_second_semester')->nullable();
            //year
            $table->float('total_year')->nullable();
            $table->float('avg_year_semester')->nullable();
            $table->string('grade_year', 5)->nullable();
            $table->float('number_year')->nullable();
            $table->string('description')->nullable();
            $table->boolean('is_finised')->default(false);
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->tinyInteger('status')->default(1)->comment('!= 1 => បានផ្ទេរ');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('studies');
    }
};
