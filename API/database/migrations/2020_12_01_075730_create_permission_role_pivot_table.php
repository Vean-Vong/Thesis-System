<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionRolePivotTable extends Migration
{
    public function up()
    {
        Schema::create('permission_role', function (Blueprint $table) {
            $table->foreignUuid('role_id')->references('id')->on('roles')->cascadeOnDelete();
            $table->foreignUuid('permission_id')->references('id')->on('permissions')->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('permission_role');
    }
}
