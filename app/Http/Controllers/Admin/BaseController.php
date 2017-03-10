<?php
/**
 * 后台控制器基类
 * Class BaseController
 * @package App\Http\Controllers\Admin
 */

namespace App\Http\Controllers\Admin;

use App\Services\Admin\AdminPermissionService;
use App\Services\Admin\AdminRoleService;
use App\Services\Admin\AdminUserService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use App\Libs\FcAdmin\Tool;

class BaseController extends Controller
{
    protected $tool;

    protected $adminUserService;
    protected $adminPermissionService;
    protected $adminRoleService;

    public function __construct(AdminUserService $adminUserService, AdminPermissionService $adminPermissionService, AdminRoleService $adminRoleService, Tool $tool)
    {
        //依赖注入服务
        $this -> adminUserService = $adminUserService;
        $this -> adminPermissionService = $adminPermissionService;
        $this -> adminRoleService = $adminRoleService;

        //后台工具类
        $this->tool = $tool;

        //控制器初始化
        $this->_init();
    }

    //模块控制器初始化
    protected function _init(){}

}
