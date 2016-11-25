<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class RoleController extends AdminController
{
    /**
     * 角色列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            //重组数据
            $param = $request->all();
            return $this->response($this->roles->dataTable($param));
        }

        return view('admin.role.index');
    }

    /**
     * 添加页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.role.create');
    }

    /**
     * 添加数据
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        //获取数据
        $form_data = $request -> except('_token');
        //验证
        $rules = [
            'name' => 'required|max:255',
        ];
        $message = [
            'name.required' => '请填写角色名称!',
            'name.max' => '角色名称过长!',
        ];

        $validator = Validator::make($form_data, $rules, $message);

        $error_data = []; //错误集

        //验证表单
        if($validator -> passes()){
            //新增
            $form_data['created_at'] = date('Y-m-d H:i:s');
            $form_data['updated_at'] = $form_data['created_at'];
            $role_id = $this->roles->insertGetId($form_data);
            if(!$role_id){
                return back()->withInput()->with('prompt', ['status' => 0, 'msg' => '添加失败!']);
            }
            //编辑成功
            return redirect('admin/role')->with('prompt', ['status' => 1, 'msg' => '添加成功!']);
        }

        //整理出错信息集合
        $errors = $validator -> errors() -> messages();
        foreach($errors as $k => $error){
            $error_data[$k] = array_shift($error);
        }
        return back()->withInput()->with('errors', $error_data);
    }

    /**
     * 角色详情
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($id = 0)
    {
        $id = (int)$id;
        $role = $this->roles->getById($id);
        if(empty($role)) return redirect('admin/role')->with('prompt', ['status' => 0, 'msg' => '该角色不存在!']);

        return view('admin.role.show', compact('role'));
    }

    /**
     * 编辑数据
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit($id = 0)
    {
        $id = (int)$id;
        $role = $this->roles->getById($id);
        if(empty($role)) return redirect('admin/role')->with('prompt', ['status' => 0, 'msg' => '该角色不存在!']);

        return view('admin.role.edit', compact('role'));
    }

    /**
     * 修改数据
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id = 0)
    {
        $id = (int)$id;
        $form_data = $request -> except('_token', '_method');

        $role = $this->roles->getById($id);
        if(empty($role)) return redirect('admin/role')->with('prompt', ['status' => 0, 'msg' => '该角色不存在!']);

        //验证
        $rules = [
            'name' => 'required|max:255',
        ];
        $message = [
            'name.required' => '请填写角色名称!',
            'name.max' => '角色名称过长!',
        ];

        $validator = Validator::make($form_data, $rules, $message);

        $error_data = []; //错误集

        //验证表单
        if($validator -> passes()){

            //修改
            $role_id = $this->roles->where('id', $id)->update($form_data);
            if(!$role_id){
                return back()->withInput()->with('prompt', ['status' => 0, 'msg' => '编辑失败!']);
            }
            //编辑成功
            return redirect('admin/role')->with('prompt', ['status' => 1, 'msg' => '编辑成功!']);
        }

        //整理出错信息集合
        $errors = $validator -> errors() -> messages();
        foreach($errors as $k => $error){
            $error_data[$k] = array_shift($error);
        }
        return back()->withInput()->with('errors', $error_data);
    }

    /**
     * 授权
     * @param int $id
     */
    public function auth(Request $request, $id = 0)
    {
        $id = (int)$id;
        $role = $this->roles->getById($id);
        if(empty($role)) return redirect('admin/role')->with('prompt', ['status' => 0, 'msg' => '该角色不存在!']);

        //验证提交方式
        if(strtolower($request->method()) == 'post'){
            //获取数据
            $perm_ids = $request->only('perm_ids');
            //设置权限
            $role->setPerms($perm_ids);

            return redirect('admin/role/'.$id.'/auth')->with('prompt', ['status' => 1, 'msg' => '授权成功!']);
        }

        //获取树形全部数据
        $permission_list = $this->permissions->getTreeAllData();

        //获取当前角色已付权限
        $role_perms = $role -> perms;

        $role_perms_ids = [];
        if(count($role_perms)) $role_perms_ids = array_column($role_perms->toArray(), 'id');

        return view('admin.role.auth', compact('role', 'role_perms', 'permission_list', 'role_perms_ids'));
    }

}
