<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

/**
 * 后台权限验证中间件
 * Class FcAdminAuth
 * @package App\Http\Middleware
 */
class FcAdminAuth
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

        //判断是否需要验证
        if(!empty($currentRoute)){
            //认证权限
            if(!adminAuth($currentRoute)){
                $previousUrl = URL::previous(); //上次访问
                //验证是否ajax
                if($request->ajax()) {
                    return (new Tool())->setStatusCode(403)->responseError('您没有权限执行此操作!');
                } else {
                    //获取skin样式
                    $skin = Config::get('fc_admin.skin');
                    if(empty($skin)) $skin = 'skin-blue';  //默认样式

                    return response()->view('admin.errors.403', compact('previousUrl', 'skin'));
                }
            }
        }

        return $next($request);
    }
}
