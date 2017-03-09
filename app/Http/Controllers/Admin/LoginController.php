<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

/**
 * 后台登录控制器
 * Class LoginController
 * @package App\Http\Controllers\Admin
 */
class LoginController extends BaseController
{
    //初始化
    protected function _init()
    {
        //设置中间件
        $this->middleware('guest:admin', ['except' => 'logout']);
    }

    /**
     * 登录页
     */
    public function index()
    {
        return view('admin.auth.login');
    }

    /**
     * 退出
     */
    public function logout()
    {
        //退出
        $result = $this->adminUserService->logout();

        //返回响应
        return $this->tool->response($result, 'admin/login');
    }
}
