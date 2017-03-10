<?php

namespace App\Console\Commands;

use App\Models\Admin\AdminPermission;
use App\Models\Admin\AdminUser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
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

    private $adminUser;
    private $adminPermission;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(AdminUser $adminUser, AdminPermission $adminPermission)
    {
        parent::__construct();

        $this->adminUser = $adminUser;
        $this->adminPermission = $adminPermission;
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

            //重置密钥
            if(!File::exists(base_path().DIRECTORY_SEPARATOR .'fc_admin_key.lock')) $this->execShellWithPrettyPrint('php artisan key:generate', function(){
                File::put(base_path().DIRECTORY_SEPARATOR .'fc_admin_key.lock', 'fc_admin_key');
            });
            //数据迁移
            if(!File::exists(base_path().DIRECTORY_SEPARATOR .'fc_admin_migrate.lock')) $this->execShellWithPrettyPrint('php artisan migrate', function(){
                File::put(base_path().DIRECTORY_SEPARATOR .'fc_admin_migrate.lock', 'fc_admin_migrate');
            });

            $res_status = true;
            $date_time = date('Y-m-d H:i:s');
            //1、生成超级管理员
            try{
                $res = $this->adminUser->insert([
                    'name' => Config::get('fc_admin.username'),
                    'nickname' => '超级管理员',
                    'email' => Config::get('fc_admin.email'),
                    'password' => bcrypt(Config::get('fc_admin.password')),
                    'created_at' => $date_time
                ]);
                if(!$res) $res_status = false;
            } catch(\Exception $e){
                $res_status = false;
            }
            //2、生成后台基础权限数据
            if($res_status){
                try{
                    $data = [
                        ['id' => 1, 'rule' => 'admin.system.index', 'name' => '系统管理', 'description' => '系统管理', 'pid' => 0, 'icon' => 'fa-cog', 'sort' => 0, 'is_menu' => 1, 'created_at' => $date_time],
                        ['id' => 2, 'rule' => 'admin.adminUser.index', 'name' => '管理员管理', 'description' => '管理员管理', 'pid' => 1, 'icon' => 'fa-user-secret', 'sort' => 0, 'is_menu' => 1, 'created_at' => $date_time],
                        ['id' => 3, 'rule' => 'admin.adminPermission.index', 'name' => '权限管理', 'description' => '权限管理', 'pid' => 1, 'icon' => 'fa-chain', 'sort' => 1, 'is_menu' => 1, 'created_at' => $date_time],
                        ['id' => 4, 'rule' => 'admin.adminRole.index', 'name' => '角色管理', 'description' => '角色管理', 'pid' => 1, 'icon' => 'fa-codepen', 'sort' => 2, 'is_menu' => 1, 'created_at' => $date_time],
                        ['id' => 5, 'rule' => 'log-viewer::dashboard', 'name' => '系统日志', 'description' => '系统日志', 'pid' => 1, 'icon' => 'fa-bell', 'sort' => 3, 'is_menu' => 1, 'created_at' => $date_time],
                        ['id' => 6, 'rule' => 'admin.adminUser.create', 'name' => '添加管理员', 'description' => '添加管理员', 'pid' => 2, 'icon' => '', 'sort' => 0, 'is_menu' => 0, 'created_at' => $date_time],
                        ['id' => 7, 'rule' => 'admin.adminUser.edit', 'name' => '编辑管理员', 'description' => '编辑管理员', 'pid' => 2, 'icon' => '', 'sort' => 1, 'is_menu' => 0, 'created_at' => $date_time],
                        ['id' => 8, 'rule' => 'admin.adminUser.show', 'name' => '管理员详情', 'description' => '管理员详情', 'pid' => 2, 'icon' => '', 'sort' => 2, 'is_menu' => 0, 'created_at' => $date_time],
                        ['id' => 9, 'rule' => 'admin.adminUser.delete', 'name' => '删除管理员', 'description' => '删除管理员', 'pid' => 2, 'icon' => '', 'sort' => 3, 'is_menu' => 0, 'created_at' => $date_time],
                        ['id' => 10, 'rule' => 'admin.adminPermission.create', 'name' => '添加权限', 'description' => '添加权限', 'pid' => 3, 'icon' => '', 'sort' => 0, 'is_menu' => 0, 'created_at' => $date_time],
                        ['id' => 11, 'rule' => 'admin.adminPermission.edit', 'name' => '编辑权限', 'description' => '编辑权限', 'pid' => 3, 'icon' => '', 'sort' => 1, 'is_menu' => 0, 'created_at' => $date_time],
                        ['id' => 12, 'rule' => 'admin.adminPermission.show', 'name' => '权限详情', 'description' => '权限详情', 'pid' => 3, 'icon' => '', 'sort' => 2, 'is_menu' => 0, 'created_at' => $date_time],
                        ['id' => 13, 'rule' => 'admin.adminPermission.delete', 'name' => '删除权限', 'description' => '删除权限', 'pid' => 3, 'icon' => '', 'sort' => 3, 'is_menu' => 0, 'created_at' => $date_time],
                        ['id' => 14, 'rule' => 'admin.adminRole.create', 'name' => '添加角色', 'description' => '添加角色', 'pid' => 4, 'icon' => '', 'sort' => 0, 'is_menu' => 0, 'created_at' => $date_time],
                        ['id' => 15, 'rule' => 'admin.adminRole.edit', 'name' => '编辑角色', 'description' => '编辑角色', 'pid' => 4, 'icon' => '', 'sort' => 1, 'is_menu' => 0, 'created_at' => $date_time],
                        ['id' => 16, 'rule' => 'admin.adminRole.show', 'name' => '角色详情', 'description' => '角色详情', 'pid' => 4, 'icon' => '', 'sort' => 2, 'is_menu' => 0, 'created_at' => $date_time],
                        ['id' => 17, 'rule' => 'admin.adminRole.delete', 'name' => '删除角色', 'description' => '删除角色', 'pid' => 4, 'icon' => '', 'sort' => 3, 'is_menu' => 0, 'created_at' => $date_time],
                        ['id' => 18, 'rule' => 'admin.adminRole.auth', 'name' => '角色授权', 'description' => '角色授权', 'pid' => 4, 'icon' => '', 'sort' => 4, 'is_menu' => 0, 'created_at' => $date_time],
                        ['id' => 19, 'rule' => 'log-viewer::logs.list', 'name' => '日志列表', 'description' => '日志列表', 'pid' => 5, 'icon' => '', 'sort' => 0, 'is_menu' => 0, 'created_at' => $date_time],
                        ['id' => 20, 'rule' => 'log-viewer::logs.filter', 'name' => '日志筛选', 'description' => '日志筛选', 'pid' => 5, 'icon' => '', 'sort' => 1, 'is_menu' => 0, 'created_at' => $date_time],
                        ['id' => 21, 'rule' => 'log-viewer::logs.show', 'name' => '日志详情', 'description' => '日志详情', 'pid' => 5, 'icon' => '', 'sort' => 2, 'is_menu' => 0, 'created_at' => $date_time],
                        ['id' => 22, 'rule' => 'log-viewer::logs.delete', 'name' => '删除日志', 'description' => '删除日志', 'pid' => 5, 'icon' => '', 'sort' => 3, 'is_menu' => 0, 'created_at' => $date_time],
                        ['id' => 23, 'rule' => 'log-viewer::logs.download', 'name' => '下载日志', 'description' => '下载日志', 'pid' => 5, 'icon' => '', 'sort' => 4, 'is_menu' => 0, 'created_at' => $date_time]
                    ];
                    $this->adminPermission->insert($data);
                } catch(\Exception $e){
                    $res_status = false;
                }
            }
            //判断状态
            if($res_status) File::put(base_path().DIRECTORY_SEPARATOR .'fc_admin.lock', 'fc_admin');
            if(!$res_status) Log::error('initial error');
            //初始化成功
            echo $res_status ? PHP_EOL.'initial success!'.PHP_EOL : PHP_EOL.'initial failed!'.PHP_EOL;
            return;
        }
        //已经初始化过
        echo PHP_EOL.'already initialized!'.PHP_EOL;
    }

    /**
     * Exec sheel with pretty print.
     *
     * @param  string $command
     * @return mixed
     */
    public function execShellWithPrettyPrint($command, callable $func = null)
    {
        $this->info('---');
        $this->info($command);
        $output = shell_exec($command);
        $this->info($output);
        $this->info('---');

        if($func != null) $func();
    }

}
