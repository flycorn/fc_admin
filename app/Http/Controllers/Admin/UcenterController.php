<?php

namespace App\Http\Controllers\Admin;

use App\Http\Models\Admins;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class UcenterController extends AdminController
{
    //个人首页
    public function index()
    {
        //获取当前用户信息
        $user = $this->admin->getByUserId(session('admin.user_id'));
        if(empty($user)){
            //清空session
            session(['admin' => null]);
            return redirect('admin/login');
        }
        return view('admin.ucenter', compact('user', 'errors'));
    }

    /**
     * 修改个人资料
     * @param Request $request
     */
    public function edit(Request $request)
    {
        $form_data = $request -> except('_token');

        //验证
        $rules = [
            'nickname' => 'required|max:50',
            'email' => 'required|email|unique:admins,email,'.session('admin.user_id').',user_id|max:30',
            'introduction' => 'max:255',
        ];
        $message = [
            'nickname.required' => '请填写昵称!',
            'nickname.max' => '昵称过长!',
            'email.required' => '请填写邮箱!',
            'email.email' => '邮箱格式不正确!',
            'email.unique' => '该邮箱已被注册!',
            'introduction.max' => '个人简介过长!',
        ];
        $validator = Validator::make($form_data, $rules, $message);
        //验证表单
        if($validator -> passes()){
            //头像
            if(empty($form_data['avatar'])){
                unset($form_data['avatar']);
            } else {
                //移动临时头像文件
                $new_path = 'upload/admin/avatar/'.session('admin.user_id');
                $new_file = moveFile($form_data['avatar'], $new_path);
                if($new_file) $form_data['avatar'] = $new_file;
            }
            $res = $this->admin->where('user_id', '=', session('admin.user_id'))->update($form_data);

            if(!$res){
                return back()->with('prompt', ['status' => 0, 'msg' => '修改资料失败!']);
            }

            //修改成功,更新session
            $this -> updateSession($form_data);

            return back()->with('prompt', ['status' => 1, 'msg' => '修改资料成功!']);
        }
        return $this->validator_error($validator);
    }

    /**
     * 修改个人密码
     * @param Request $request
     */
    public function password(Request $request)
    {
        $form_data = $request -> except('_token');

        //验证
        $rules = [
            'old_password' => 'required',
            'new_password' => 'required|max:20|different:old_password',
        ];
        $message = [
            'old_password.required' => '请输入原密码!',
            'new_password.required' => '请输入新密码!',
            'new_password.max' => '新密码过长!',
            'new_password.different' => '新密码不能与原密码一致!',
        ];
        $validator = Validator::make($form_data, $rules, $message);
        //表单验证
        if($validator -> passes()){

            //验证旧密码密码
            $user = $this->admin->select(['user_id', 'password', 'salt'])->where('user_id', '=', session('admin.user_id'))->first();
            if(Crypt::decrypt($user -> password) != $form_data['old_password'].$user -> salt){
                return back()->with('errors', [
                    'new_password' => '原密码有误!'
                ]);
            }
            //修改数据
            $user -> salt = getSalt();
            $user -> password = Crypt::encrypt($form_data['new_password'].$user -> salt);
            $res = $user -> save();

            if(!$res){
                return back()->with('prompt', ['status' => 0, 'msg' => '修改密码失败!']);
            }
            //修改成功
            return back()->with('prompt', ['status' => 1, 'msg' => '修改密码成功!']);
        }
        return $this->validator_error($validator);
    }
}
