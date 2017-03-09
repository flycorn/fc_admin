<?php

namespace App\Http\Controllers\Admin;

class UcenterController extends BaseController
{
    /**
     * 个人中心首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        //获取登录的用户数据
        $user = $this->adminUserService->loggedUser();

        return view('admin.ucenter', compact('user'));
    }
}
