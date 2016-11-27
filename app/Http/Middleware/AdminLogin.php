<?php

namespace App\Http\Middleware;

use App\Http\Models\Admin;
use Closure;
use Cookie;

class AdminLogin
{
    protected $admin;
    public function __construct(Admin $admin)
    {
        $this->admin = $admin;
    }
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
            //获取Cookie
            $remember_token = Cookie::get('fc_admin_remember_token');
            $admin = null;
            if(!empty($remember_token)){
                //查询该用户
                $admin = $this->admin->getByRememberToken($remember_token);
                if($admin){
                    unset($admin -> password);
                    unset($admin -> salt);
                    //写入session
                    session(['admin' => $admin]);
                } else {
                    //销毁无效Cookie
                    Cookie::forget('fc_admin_remember_token');
                }
            }
            //验证登录结果
            if(!$admin){
                //验证是否ajax
                if($request->ajax()){
                    return response()->json([
                        'status' => 'failed',
                        'errors' => [
                            'status_code' => 401,
                            'message' => '请先登录!',
                        ]
                    ]);
                }
                //跳转至登录
                return redirect('admin/login');
            }
        }
        return $next($request);
    }
}
