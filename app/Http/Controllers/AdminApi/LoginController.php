<?php

namespace App\Http\Controllers\AdminApi;

use App\Http\Requests\Admin\LoginRequest;

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
     * 登录处理
     * @param LoginRequest $request
     */
    public function login(LoginRequest $request)
    {
        //登录
        $result = $this->adminUserService->login($request);

        //返回响应
        return $this->tool->response($result);
    }
}
