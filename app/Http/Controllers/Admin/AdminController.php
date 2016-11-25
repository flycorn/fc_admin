<?php

namespace App\Http\Controllers\Admin;

use App\Http\Models\Permissions;
use App\Http\Models\Roles;
use App\Http\Models\Admins;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

/**
 * 后台基类
 * Class AdminController
 * @package App\Http\Controllers\Admin
 */
class AdminController extends BaseController
{
    //
    public function __construct(Admins $admins, Permissions $permissions, Roles $roles)
    {
        parent::__construct($admins, $permissions, $roles);

        //获取skin样式
        $skin = Config::get('fc_admin.skin');
        if(empty($skin)) $skin = 'skin-green';  //默认样式

        $shareData = [];
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
