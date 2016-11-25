<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

/**
 * 后台
 * Class IndexController
 * @package App\Http\Controllers\Admin
 */
class IndexController extends AdminController
{
    //后台首页
    public function index()
    {
        return view('admin.index');
    }
}
