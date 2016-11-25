<?php

namespace App\Http\Controllers\Admin;

use App\Http\Models\Admins;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

/**
 * 管理员管理
 * Class ManagerController
 * @package App\Http\Controllers\Admin
 */
class ManagerController extends AdminController
{
    /**
     * 管理员列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        //是否ajax
        if($request->ajax()) {

            //重组数据
            $param = $request->all();
            return Response::json($this->admins->dataTable($param));
        }

        return view('admin.manager.index');
    }

    /**
     * 添加页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $role_list = $this->roles->select(['id', 'name'])->get();
        return view('admin.manager.create', compact('role_list'));
    }

    /**
     * 添加数据
     * @param Request $request
     */
    public function store()
    {
        //获取数据
        $form_data = Input::except('_token');

        $role_ids = []; //角色
        if(isset($form_data['role_ids'])){
            $role_ids = $form_data['role_ids'];
            unset($form_data['role_ids']);
        }

        //验证
        $rules = [
            'username' => 'required|max:30|unique:admins',
            'nickname' => 'required|max:50',
            'email' => 'required|email|max:30|unique:admins',
            'password' => 'required|max:20'
        ];
        $message = [
            'username.required' => '请填写用户名!',
            'username.max' => '用户名过长!',
            'username.unique' => '用户名已存在!',
            'nickname.required' => '请填写昵称!',
            'nickname.max' => '昵称过长!',
            'email.required' => '请填写邮箱!',
            'email.email' => '邮箱格式不正确!',
            'email.max' => '邮箱过长!',
            'email.unique' => '邮箱已存在!',
            'password.required' => '请填写密码!',
            'password.max' => '密码过长!',
        ];
        $validator = Validator::make($form_data, $rules, $message);
        $error_data = []; //错误集
        //验证表单
        if($validator -> passes()){

            $form_data['salt'] = getSalt();
            $form_data['password'] = Crypt::encrypt($form_data['password'].$form_data['salt']);
            $form_data['created_at'] = date('Y-m-d H:i:s');
            $form_data['updated_at'] = $form_data['created_at'];

            //新增
            $avatar = $form_data['avatar'];
            unset($form_data['avatar']);
            $user_id = $this->admins->insertGetId($form_data);
            if(!$user_id){
                return back()->withInput()->with('prompt', ['status' => 0, 'msg' => '添加失败!']);
            }
            //头像
            if(!empty($avatar)){
                //移动临时头像文件
                $new_path = 'upload/admin/avatar/'.$user_id;
                $new_file = moveFile($avatar, $new_path);
                if($new_file) $avatar = $new_file;
                $this->admins->where('user_id', $user_id)->update(['avatar' => $avatar]);
            }

            //设置角色
            $this->admins->getByUserId($user_id)->setRoles($role_ids);
            //创建成功
            return redirect('admin/manager')->with('prompt', ['status' => 1, 'msg' => '添加成功!']);
        }

        //整理出错信息集合
        $errors = $validator -> errors() -> messages();
        foreach($errors as $k => $error){
            $error_data[$k] = array_shift($error);
        }
        return back()->withInput()->with('errors', $error_data);
    }

    /**
     * 编辑数据
     * @param $user_id
     */
    public function edit($user_id)
    {
        $user_id = intval(trim($user_id, ' '));
        //查询该管理员是否存在
        $user = $this->admins->getByUserId($user_id);
        if(empty($user)){
            return redirect('admin/manager')->with('prompt', ['status' => 0, 'msg' => '该管理员不存在!']);
        }
        $role_list = $this->roles->select(['id', 'name'])->get();
        $user_role_ids = count($user->roles) ? array_column($user->roles->toArray(), 'id') : [];

        return view('admin.manager.edit', compact('user', 'role_list', 'user_role_ids'));
    }

    /**
     * 修改数据
     * @param $user_id
     */
    public function update($user_id)
    {
        $user_id = intval(trim($user_id, ' '));
        //查询该管理员是否存在
        $user = $this->admins->getByUserId($user_id);
        if(empty($user)){
            return redirect('admin/manager')->with('prompt', ['status' => 0, 'msg' => '该管理员不存在!']);
        }

        //获取数据
        $form_data = Input::except('_token', '_method');
        $role_ids = []; //角色
        if(isset($form_data['role_ids'])){
            $role_ids = $form_data['role_ids'];
            unset($form_data['role_ids']);
        }

        //验证
        $rules = [
            'nickname' => 'required|max:50',
            'email' => 'required|email|max:30|unique:admins,email,'.$user_id.',user_id',
            'password' => 'max:20'
        ];
        $message = [
            'nickname.required' => '请填写昵称!',
            'nickname.max' => '昵称过长!',
            'email.required' => '请填写邮箱!',
            'email.email' => '邮箱格式不正确!',
            'email.max' => '邮箱过长!',
            'email.unique' => '邮箱已存在!',
            'password.max' => '密码过长!'
        ];
        $validator = Validator::make($form_data, $rules, $message);
        $error_data = []; //错误集
        //验证表单
        if($validator -> passes()){

            //判断是否需要修改密码
            if(!empty($form_data['password'])){
                $form_data['salt'] = getSalt();
                $form_data['password'] = Crypt::encrypt($form_data['password'].$form_data['salt']);
            } else {
                unset($form_data['password']);
            }

            //头像
            if(empty($form_data['avatar'])){
                unset($form_data['avatar']);
            } else {
                //移动临时头像文件
                $new_path = 'upload/admin/avatar/'.$user_id;
                $new_file = moveFile($form_data['avatar'], $new_path);
                if($new_file) $form_data['avatar'] = $new_file;
            }
            //修改
            $res = $this->admins->where('user_id', '=', $user_id)->update($form_data);
            if(!$res){
                return back()->with('prompt', ['status' => 0, 'msg' => '编辑失败!']);
            }

            //设置角色
            $user->setRoles($role_ids);
            //修改成功
            return back()->with('prompt', ['status' => 1, 'msg' => '编辑成功!']);
        }

        //整理出错信息集合
        $errors = $validator -> errors() -> messages();
        foreach($errors as $k => $error){
            $error_data[$k] = array_shift($error);
        }
        return back()->with('errors', $error_data);
    }

    /**
     * 管理员详情
     * @param $user_id
     */
    public function show($user_id)
    {
        $user_id = intval(trim($user_id, ' '));
        //查询该管理员是否存在
        $user = $this->admins->getByUserId($user_id);
        if(empty($user)){
            return redirect('admin/manager')->with('prompt', ['status' => 0, 'msg' => '该管理员不存在!']);
        }
        $role_list = $user->roles;

        return view('admin.manager.show', compact('user', 'role_list'));
    }

    /**
     * 删除数据
     * @param $user_id
     */
    public function destroy($user_id)
    {
        $user_id = intval(trim($user_id, ' '));

        $result = [
            'status' => 1,
            'msg' => '删除失败!'
        ];

        $user = $this->admins->getByUserId($user_id);
        if(!empty($user)){
            $avatar =$user->avatar;
            $res = $user -> delete();
            if($res){
                //删除图片
                removeFile($avatar);
                $result['status'] = 0;
                $result['msg'] = '删除成功!';
            }
        }

        echo json_encode($result);
    }

}
