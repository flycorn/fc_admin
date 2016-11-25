<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Support\Facades\Config;
use Zizaco\Entrust\EntrustFacade as Entrust;
use Route,URL;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $currentRoute = Route::currentRouteName();

        //验证是否需要授权
        if(!empty($currentRoute)){
            $previousUrl = URL::previous(); //上次访问

            $admin = session('admin');

            //判断是否超级管理员
            if($admin->user_id > 1){
                //验证是否授权
                if(!$admin->can($currentRoute)){
                    //验证是否ajax
                    if($request->ajax()) {
                        return response()->json([
                            'status' => 'failed',
                            'errors' => [
                                'status_code' => 403,
                                'message' => '您没有权限执行此操作!',
                            ]
                        ]);
                    } else {
                        //获取skin样式
                        $skin = Config::get('admin.skin');
                        if(empty($skin)) $skin = 'skin-green';  //默认样式

                        return response()->view('admin.errors.403', compact('previousUrl', 'skin', 'admin'));
                    }
                }
            }
        }
        return $next($request);
    }
}
