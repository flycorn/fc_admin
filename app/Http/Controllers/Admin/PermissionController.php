<?php

namespace App\Http\Controllers\Admin;

use App\Events\AdminPermission;
use Event;
use App\Http\Models\BackstagePermission;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class PermissionController extends AdminController
{
    /**
     * 列表页
     * @param int $pid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($pid = 0)
    {
        $pid = (int)$pid;

        $parent_permission = null;
        if($pid) $parent_permission = $this->permission->getById($pid);

        return view('admin.permission.index', compact('pid', 'parent_permission'));
    }

    /**
     * 添加页面
     * @param $pid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($pid)
    {
        $pid = (int)$pid;
        $parent_permission = null;
        if($pid) $parent_permission = $this->permission->getById($pid);

        return view('admin.permission.create', compact('pid', 'parent_permission'));
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
            'name' => 'required|unique:permissions|max:255',
            'display_name' => 'required|max:255',
            'pid' => 'required|int',
        ];
        $message = [
            'display_name.required' => '请填写规则名称!',
            'display_name.max' => '规则名称过长!',
            'name.required' => '请填写规则!',
            'name.unique' => '规则已存在!',
            'name.max' => '规则过长!',
            'pid.required' => '请选择所属权限!',
            'pid.int' => '所属权限有误!',
        ];

        $validator = Validator::make($form_data, $rules, $message);

        //验证表单
        if($validator -> passes()){
            //新增
            $form_data['created_at'] = date('Y-m-d H:i:s');
            $form_data['updated_at'] = $form_data['created_at'];
            $permission_id = $this->permission->insertGetId($form_data);
            if(!$permission_id){
                return back()->withInput()->with('prompt', ['status' => 0, 'msg' => '添加失败!']);
            }

            //触发事件
            Event::fire(new AdminPermission($this->permission));

            //创建成功
            return redirect('admin/permission/'.$form_data['pid'])->with('prompt', ['status' => 1, 'msg' => '添加成功!']);
        }
        return $this->validator_error($validator);
    }

    /**
     * 权限详情
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function show($id = 0)
    {
        $id = (int)$id;
        $permission = $this->permission->getById($id);
        if(empty($permission)) return redirect('admin/permission')->with('prompt', ['status' => 0, 'msg' => '该权限不存在!']);

        $parent_permission = null;
        if($permission->pid) $parent_permission = $this->permission->getById($permission->pid, ['id', 'display_name', 'name']);
        return view('admin.permission.show', compact('permission', 'parent_permission'));
    }

    /**
     * 编辑数据
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit($id = 0)
    {
        $permission = $this->permission->getById($id);
        if(empty($permission)) return redirect('admin/permission')->with('prompt', ['status' => 0, 'msg' => '该权限不存在!']);

        $pid = $permission -> pid;
        $parent_permission = null;
        if($pid) $parent_permission = $this->permission->getById($pid);

        return view('admin.permission.edit', compact('permission', 'pid', 'parent_permission'));
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
        $form_data = $request -> except('_token', '_method', 'pid');

        $permission = $this->permission->getById($id);
        if(empty($permission)) return redirect('admin/permission')->with('prompt', ['status' => 0, 'msg' => '该权限不存在!']);

        //验证
        $rules = [
            'name' => 'required|unique:permissions,name,'.$id.'|max:255',
            'display_name' => 'required|max:255',
        ];
        $message = [
            'display_name.required' => '请填写规则名称!',
            'display_name.max' => '规则名称过长!',
            'name.required' => '请填写规则!',
            'name.unique' => '规则已存在!',
            'name.max' => '规则过长!',
            'pid.required' => '请选择所属权限!',
            'pid.int' => '所属权限有误!',
        ];

        $validator = Validator::make($form_data, $rules, $message);

        //验证表单
        if($validator -> passes()){

            //修改
            if(!isset($form_data['is_menu'])) $form_data['is_menu'] = 0;

            $permission_id = $this->permission->where('id', $id)->update($form_data);
            if(!$permission_id){
                return back()->withInput()->with('prompt', ['status' => 0, 'msg' => '编辑失败!']);
            }

            //触发事件
            Event::fire(new AdminPermission($this->permission));

            //编辑成功
            return redirect('admin/permission/'.$permission->pid)->with('prompt', ['status' => 1, 'msg' => '编辑成功!']);
        }
        return $this->validator_error($validator);
    }

}
