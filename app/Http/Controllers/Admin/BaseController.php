<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\Permissions;
use App\Http\Models\Roles;
use App\Http\Models\Admins;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

/**
 * 基础类
 * Class BaseController
 * @package App\Http\Controllers\Admin
 */
class BaseController extends Controller
{
    //
    protected $admins;
    protected $permissions;
    protected $roles;
    protected $admin;
    protected $statusCode = '200'; //请求状态

    //
    public function __construct(Admins $admins, Permissions $permissions, Roles $roles)
    {
        //依赖注入
        $this -> admins = $admins;
        $this -> permissions = $permissions;
        $this -> roles = $roles;

        //登陆者
        $this -> admin = session('admin');

        //获取skin样式
        $skin = Config::get('admin.skin');
        if(empty($skin)) $skin = 'skin-green';  //默认样式

        $shareData = [];
        $shareData['admin'] = session('admin');
        $shareData['skin'] = $skin;

        //视图共享数据
        View::share($shareData);
    }

    //获取状态
    protected function getStatusCode()
    {
        return $this -> statusCode;
    }
    //设置状态
    protected function setStatusCode($statusCode)
    {
        $this -> statusCode = $statusCode;
        return $this;
    }
    //输出失败状态
    protected function responseError($message)
    {
        return $this -> response([
            'status' => 'failed',
            'errors' => [
                'status_code' => $this -> getStatusCode(),
                'message' => $message,
            ]
        ]);
    }
    //输出成功状态
    protected function responseSuccess($message, $data = [])
    {
        return $this -> response([
            'status' => 'successful',
            'correct' => [
                'status_code' => $this -> getStatusCode(),
                'message' => $message,
                'data' => $data,
            ]
        ]);
    }
    //返回请求结果
    protected function response($data)
    {
        return Response::json($data);
    }

}
