<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('admins', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('user_id')->comment('用户id');
            $table->string('username', 30)->default('')->comment('用户名');
            $table->string('nickname', 50)->default('')->comment('昵称');
            $table->string('avatar', 100)->default('upload/admin/avatar/default/avatar.png')->comment('头像');
            $table->string('email', 30)->default('')->comment('邮箱');
            $table->string('password', 255)->default('')->comment('密码');
            $table->char('salt', 10)->default('')->comment('密码盐');
            $table->string('introduction', 255)->default('')->comment('介绍');
            $table->timestamps();
            $table->unique(['user_id', 'email', 'username']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop('admins');
    }
}
