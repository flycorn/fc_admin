<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\Crypt;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('admins')->insert([
            'username' => 'admin',
            'nickname' => '超级管理员',
            'salt' => 'flycorn',
            'email' => 'yuming@flycorn.com',
            'password' => Crypt::encrypt('admin123'.'flycorn'),
        ]);
    }
}
