<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

/**
 * 后台登录模块
 * Class LoginController
 * @package App\Http\Controllers\Admin
 */
class LoginController extends BaseController
{

    //登录
    public function index(Request $request)
    {
        //验证提交方式
        if(strtolower($request -> method()) == 'post'){

            //获取提交数据
            $form_data = $request -> except('_token');
            $captcha = $request->session()->get('admin_login_captcha'); //获取验证码

            //验证
            $rules = [
                'user' => 'required',
                'password' => 'required',
                'captcha' => 'required',
            ];
            $message = [
                'user.required' => '请填写用户名或邮箱!',
                'password.required' => '请填写密码!',
                'captcha.required' => '请填写验证码!',
            ];
            $check = Validator::make($form_data, $rules, $message);
            //表单验证
            if($check -> passes()){

                //验证验证码
                if($captcha != $form_data['captcha']){
                    return back()->withInput()->with('errors', ['captcha' => '验证码不正确!']);
                }

                //数据验证
                $admin = null; //管理员
                if(isEmail($form_data['user'])){
                    //邮箱
                    $admin = $this->admin->getByEmail($form_data['user']);
                }
                if(empty($admin)) {
                    //用户名
                    $admin = $this->admin->getByUsername($form_data['user']);
                }
                //验证查询结果
                if(empty($admin)){
                    return back()->withInput()->with('errors', ['user' => '该管理员不存在!']);
                }
                //验证密码
                if(Crypt::decrypt($admin -> password) != $form_data['password'].$admin -> salt){
                    return back()->withInput()->with('errors', ['password' => '密码不正确!']);
                }
                //全部验证通过,写入session
                unset($admin -> password);
                unset($admin -> salt);

                //判断是否写入cookie
                session(['admin' => $admin]);
                if(isset($form_data['remember']) && $form_data['remember']){
                    //记住密码

                }

                //跳转至后台首页
                return redirect('admin');
            }
            //整理出错信息集合
            $error_data = [];
            $errors = $check -> errors() -> messages();

            foreach($errors as $k => $error){
                $error_data[$k] = array_shift($error);
            }
            return back()->withInput()->with('errors', $error_data);
        }

        return view('admin.login');
    }

    //退出
    public function quit()
    {
        session(['admin' => null]);
        return redirect('admin/login');
    }
}
