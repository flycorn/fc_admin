<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class IndexController extends BaseController
{
    //首页
    public function index()
    {
        return view('admin.index');
    }
}
