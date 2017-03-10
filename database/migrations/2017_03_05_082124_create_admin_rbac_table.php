<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminRbacTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->unique()->comment('角色名称');
            $table->string('description')->nullable()->comment('角色描述');
            $table->timestamps();
        });

        Schema::create('admin_role_user', function (Blueprint $table) {
            $table->integer('role_id');
            $table->integer('user_id');
        });

        Schema::create('admin_permissions', function (Blueprint $table) {
            $table->increments('id')->comment('权限id');
            $table->string('name')->comment('权限名称');
            $table->string('rule', 50)->unique()->comment('权限规则');
            $table->string('description')->nullable()->comment('权限描述');
            $table->integer('pid')->default(0)->comment('所属权限');
            $table->tinyInteger('is_menu')->default(0)->comment('是否菜单');
            $table->string('icon', 50)->nullable()->comment('图标');
            $table->integer('sort')->default(0)->comment('排序');
            $table->timestamps();
        });

        Schema::create('admin_permission_role', function (Blueprint $table) {
            $table->integer('permission_id');
            $table->integer('role_id');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_permission_role');
        Schema::dropIfExists('admin_permissions');
        Schema::dropIfExists('admin_roles');
        Schema::dropIfExists('admin_role_user');
    }
}
