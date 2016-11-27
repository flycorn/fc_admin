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
