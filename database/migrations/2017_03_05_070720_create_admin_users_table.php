<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_users', function (Blueprint $table) {
            $table->increments('id')->unique()->comment('管理员id');
            $table->string('name', 30)->unique()->default('')->comment('用户名');
            $table->string('nickname', 50)->default('')->comment('昵称');
            $table->string('avatar', 100)->default('upload/admin/avatar/default/avatar.png')->comment('头像');
            $table->string('email', 30)->unique()->default('')->comment('邮箱');
            $table->string('password', 255)->comment('密码');
            $table->string('introduction', 255)->nullable()->comment('介绍');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_users');
    }
}
