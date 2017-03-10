<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * 系统管理
 * Class SystemController
 * @package App\Http\Controllers\Admin
 */
class SystemController extends BaseController
{
    //初始化
    protected function _init()
    {
        //设置中间件
        $this->middleware(['fcAdmin.login:admin', 'fcAdmin.permission']);
    }

    public function index()
    {
        return redirect('admin');
    }
}
