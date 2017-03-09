<?php

namespace App\Console\Commands;

use App\Http\Models\Admin;
use App\Http\Models\Permission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class FcAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fc_admin:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fc_Admin init';

    private $admin;
    private $permission;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Admin $admin, Permission $permission)
    {
        parent::__construct();

        $this->admin = $admin;
        $this->permission = $permission;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //验证文件是否存在
        if(!File::exists(base_path().DIRECTORY_SEPARATOR .'fc_admin.lock')){

            $res_status = true;
            //1、生成超级管理员
            try{
                $this->admin->insert([
                    'username' => Config::get('fc_admin.username'),
                    'nickname' => '超级管理员',
                    'email' => Config::get('fc_admin.email'),
                    'salt' => 'flycorn',
                    'password' => Crypt::encrypt('admin123'.'flycorn'),
                ]);
            } catch(\Exception $e){
                $res_status = false;
            }
            //2、生成后台基础权限数据
            if($res_status){
                try{
                    $data = [
                        ['id' => 1, 'name' => 'admin.manager.user', 'display_name' => '管理员管理', 'description' => '管理员管理', 'pid' => 0, 'icon' => 'fa-users', 'sort' => 0, 'is_menu' => 1],
                        ['id' => 2, 'name' => 'admin.manager.index', 'display_name' => '管理员列表', 'description' => '管理员列表', 'pid' => 1, 'icon' => 'fa-user-secret', 'sort' => 0, 'is_menu' => 1],
                        ['id' => 3, 'name' => 'admin.permission.index', 'display_name' => '权限列表', 'description' => '权限列表', 'pid' => 1, 'icon' => 'fa-chain', 'sort' => 1, 'is_menu' => 1],
                        ['id' => 4, 'name' => 'admin.role.index', 'display_name' => '角色列表', 'description' => '角色列表', 'pid' => 1, 'icon' => 'fa-codepen', 'sort' => 2, 'is_menu' => 1],
                        ['id' => 5, 'name' => 'admin.manager.create', 'display_name' => '添加管理员', 'description' => '添加管理员', 'pid' => 2, 'icon' => '', 'sort' => 0, 'is_menu' => 0],
                        ['id' => 6, 'name' => 'admin.manager.edit', 'display_name' => '编辑管理员', 'description' => '编辑管理员', 'pid' => 2, 'icon' => '', 'sort' => 1, 'is_menu' => 0],
                        ['id' => 7, 'name' => 'admin.manager.show', 'display_name' => '管理员详情', 'description' => '管理员详情', 'pid' => 2, 'icon' => '', 'sort' => 2, 'is_menu' => 0],
                        ['id' => 8, 'name' => 'admin.manager.delete', 'display_name' => '删除管理员', 'description' => '删除管理员', 'pid' => 2, 'icon' => '', 'sort' => 3, 'is_menu' => 0],
                        ['id' => 9, 'name' => 'admin.permission.create', 'display_name' => '添加权限', 'description' => '添加权限', 'pid' => 3, 'icon' => '', 'sort' => 0, 'is_menu' => 0],
                        ['id' => 10, 'name' => 'admin.permission.edit', 'display_name' => '编辑权限', 'description' => '编辑权限', 'pid' => 3, 'icon' => '', 'sort' => 1, 'is_menu' => 0],
                        ['id' => 11, 'name' => 'admin.permission.show', 'display_name' => '权限详情', 'description' => '权限详情', 'pid' => 3, 'icon' => '', 'sort' => 2, 'is_menu' => 0],
                        ['id' => 12, 'name' => 'admin.permission.delete', 'display_name' => '删除权限', 'description' => '删除权限', 'pid' => 3, 'icon' => '', 'sort' => 3, 'is_menu' => 0],
                        ['id' => 13, 'name' => 'admin.role.create', 'display_name' => '添加角色', 'description' => '添加角色', 'pid' => 4, 'icon' => '', 'sort' => 0, 'is_menu' => 0],
                        ['id' => 14, 'name' => 'admin.role.edit', 'display_name' => '编辑角色', 'description' => '编辑角色', 'pid' => 4, 'icon' => '', 'sort' => 1, 'is_menu' => 0],
                        ['id' => 15, 'name' => 'admin.role.show', 'display_name' => '角色详情', 'description' => '角色详情', 'pid' => 4, 'icon' => '', 'sort' => 2, 'is_menu' => 0],
                        ['id' => 16, 'name' => 'admin.role.delete', 'display_name' => '删除角色', 'description' => '删除角色', 'pid' => 4, 'icon' => '', 'sort' => 3, 'is_menu' => 0],
                        ['id' => 17, 'name' => 'admin.role.auth', 'display_name' => '角色授权', 'description' => '角色授权', 'pid' => 4, 'icon' => '', 'sort' => 4, 'is_menu' => 0],
                    ];
                    $this->permission->insert($data);
                } catch(\Exception $e){
                    $res_status = false;
                }
            }
            //判断状态
            if($res_status) File::put(base_path().DIRECTORY_SEPARATOR .'fc_admin.lock', 'fc_admin');
            if(!$res_status) Log::error('initial error');
            //初始化成功
            echo $res_status ? 'initial success!' : 'initial failed!';
            return;
        }
        //已经初始化过
        echo 'already initialized!';
    }
}
