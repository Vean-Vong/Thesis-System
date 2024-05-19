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
            $table->uuid('id')->primary();//
            $table->string('code', 20)->unique()->nullable();
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('first_name_latin', 50);
            $table->string('last_name_latin', 50);
            $table->tinyInteger('gender');
            $table->date('dob');
            $table->string('village_birth', 100);
            $table->string('commune_birth', 100);
            $table->string('district_birth', 100);
            $table->string('province_birth', 100);
            //
            $table->string('d_first_name', 50)->nullable();
            $table->string('d_last_name', 50)->nullable();
            $table->string('d_job', 50)->nullable();
            $table->string('d_phone_number', 50)->nullable();
            //
            $table->string('m_first_name', 50)->nullable();
            $table->string('m_last_name', 50)->nullable();
            $table->string('m_job', 50)->nullable();
            $table->string('m_phone_number', 50)->nullable();
            //
            $table->string('village', 100);
            $table->string('commune', 100);
            $table->string('district', 100);
            $table->string('province', 100);
            $table->string('phone', 50)->nullable();
            $table->tinyInteger('student_status')->nullable();
            
            // 1 orphan dad, 2 orphan mom, 3 orphan dad and mom, 4 disabled ពិការភាព
            $table->string('from')->nullable();
            $table->string('photo_path', 100)->nullable();
            $table->string('other')->nullable();
            $table->tinyInteger('status')->default(1);
            // guardian
            $table->string('g_first_name', 50)->nullable();
            $table->string('g_last_name', 50)->nullable();
            $table->string('g_phone_number', 50)->nullable();
            $table->string('g_job', 50)->nullable();
            $table->tinyInteger('g_gender')->nullable();
            $table->text('g_detail',)->nullable();
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
