<?php

namespace App\Http\Controllers\Admin;

use App\Http\Models\Permission;
use App\Http\Models\Role;
use App\Http\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;

/**
 * 后台基类
 * Class AdminController
 * @package App\Http\Controllers\Admin
 */
class AdminController extends BaseController
{
    public function __construct(Admin $admin, Permission $permission, Role $role)
    {
        parent::__construct($admin, $permission, $role);

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
     * 验证错误处理
     * @param Validator $check
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function validator_error(Validator $validator)
    {
        //整理出错信息集合
        $error_data = [];
        $errors = $validator -> errors() -> messages();

        foreach($errors as $k => $error){
            $error_data[$k] = array_shift($error);
        }
        return back()->withInput()->with('errors', $error_data);
    }
}
