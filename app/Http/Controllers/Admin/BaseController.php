<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\Permission;
use App\Http\Models\Role;
use App\Http\Models\Admin;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

/**
 * 基础类
 * Class BaseController
 * @package App\Http\Controllers\Admin
 */
class BaseController extends Controller
{
    //
    protected $admin;
    protected $permission;
    protected $role;
    protected $login_admin; //登录的管理员

    //
    public function __construct(Admin $admin, Permission $permission, Role $role)
    {
        //依赖注入
        $this -> admin = $admin;
        $this -> permission = $permission;
        $this -> role = $role;

        //登陆者
        $this -> login_admin = session('admin');

        //获取skin样式
        $skin = Config::get('fc_admin.skin');
        if(empty($skin)) $skin = 'skin-green';  //默认样式

        $shareData = [];
        $shareData['admin'] = session('admin');
        $shareData['skin'] = $skin;
        //视图共享数据
        View::share($shareData);
    }


    /**
     * 更新session
     * @param array $data
     */
    protected function updateSession($data = [])
    {
        $admin = session('admin');
        if(!empty($data)){
            foreach ($data as $k => $item) {
                $admin -> $k = $item;
            }
        }
        //刷新session
        session(['admin' => $admin]);
    }
}
