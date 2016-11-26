<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\Permissions;
use App\Http\Models\Roles;
use App\Http\Models\Admins;
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
    protected $admins;
    protected $permissions;
    protected $roles;
    protected $admin;
    protected $statusCode = '200'; //请求状态

    //
    public function __construct(Admins $admins, Permissions $permissions, Roles $roles)
    {
        //依赖注入
        $this -> admins = $admins;
        $this -> permissions = $permissions;
        $this -> roles = $roles;

        //登陆者
        $this -> admin = session('admin');

        //获取skin样式
        $skin = Config::get('fc_admin.skin');
        if(empty($skin)) $skin = 'skin-green';  //默认样式

        $shareData = [];
        $shareData['admin'] = session('admin');
        $shareData['skin'] = $skin;
        //视图共享数据
        View::share($shareData);
    }
}
