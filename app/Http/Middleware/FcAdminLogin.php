<?php

namespace App\Http\Middleware;

use App\Libs\FcAdmin\Tool;
use Closure;
use Illuminate\Support\Facades\Auth;

/**
 * 后台登录中间件
 * Class FcAdminLogin
 * @package App\Http\Middleware
 */
class FcAdminLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {

            //后台工具
            $tool = new Tool();

            //验证是否后台
            if($guard == 'admin'){
                //判断是否ajax
                if($request->ajax() || $request->wantsJson()){
                    return $tool->setStatusCode(401)->responseError('请先登录!');
                }

                //跳转至后台登录
                return redirect('admin/login');
            }
            //跳转至前台
            return redirect('/');
        }
        //通过
        return $next($request);
    }
}
