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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
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
            'name' => 'required|unique:roles|max:255',
        ];
        $message = [
            'name.required' => '请填写角色名称!',
            'name.unique' => '该角色名已存在!',
            'name.max' => '角色名称过长!',
        ];

        $validator = Validator::make($form_data, $rules, $message);

        //验证表单
        if($validator -> passes()){
            //验证角色名
            if(is_numeric(strpos($form_data['name'], '超级管理员'))){
                return back()->withInput()->with('errors', ['name' => '角色名不合法!']);
            }
            //新增
            $form_data['created_at'] = date('Y-m-d H:i:s');
            $form_data['updated_at'] = $form_data['created_at'];
            $role_id = $this->role->insertGetId($form_data);
            if(!$role_id){
                return back()->withInput()->with('prompt', ['status' => 0, 'msg' => '添加失败!']);
            }
            //编辑成功
            return redirect('admin/role')->with('prompt', ['status' => 1, 'msg' => '添加成功!']);
        }
        return $this->validator_error($validator);
    }

    /**
     * 角色详情
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($id = 0)
    {
        $id = (int)$id;
        $role = $this->role->getById($id);
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
        $role = $this->role->getById($id);
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

        $role = $this->role->getById($id);
        if(empty($role)) return redirect('admin/role')->with('prompt', ['status' => 0, 'msg' => '该角色不存在!']);

        //验证
        $rules = [
            'name' => 'required|unique:roles,name,'.$id.'|max:255',
        ];
        $message = [
            'name.required' => '请填写角色名称!',
            'name.unique' => '该角色名已存在!',
            'name.max' => '角色名称过长!',
        ];

        $validator = Validator::make($form_data, $rules, $message);

        //验证表单
        if($validator -> passes()){
            //验证角色名
            if(is_numeric(strpos($form_data['name'], '超级管理员'))){
                return back()->withInput()->with('errors', ['name' => '角色名不合法!']);
            }
            //修改
            $role_id = $this->role->where('id', $id)->update($form_data);
            if(!$role_id){
                return back()->withInput()->with('prompt', ['status' => 0, 'msg' => '编辑失败!']);
            }
            //编辑成功
            return redirect('admin/role')->with('prompt', ['status' => 1, 'msg' => '编辑成功!']);
        }
        return $this->validator_error($validator);
    }

    /**
     * 授权
     * @param int $id
     */
    public function auth(Request $request, $id = 0)
    {
        $id = (int)$id;
        $role = $this->role->getById($id);
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
        $permission_list = $this->permission->getTreeAllData();

        //获取当前角色已付权限
        $role_perms = $role -> perms;

        $role_perms_ids = [];
        if(count($role_perms)) $role_perms_ids = array_column($role_perms->toArray(), 'id');

        return view('admin.role.auth', compact('role', 'role_perms', 'permission_list', 'role_perms_ids'));
    }

}
