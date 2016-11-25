<?php

namespace App\Http\Middleware;

use Closure;

class AdminLogin
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
        //验证是否已登录
        if(!session('admin')){
            //验证是否ajax
            if($request->ajax()){
                return response()->json([
                    'status' => 'failed',
                    'errors' => [
                        'status_code' => 403,
                        'message' => '请先登录!',
                    ]
                ]);
            }
            //跳转至登录
            return redirect('admin/login');
        }
        return $next($request);
    }
}
