<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminRoleController extends BaseController
{
    /**
     * 角色列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.adminRole.index');
    }

    /**
     * 添加角色
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.adminRole.create');
    }

    /**
     * 角色详情
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function show($id)
    {
        $id = (int)$id;

        $role = $this->adminRoleService->roleData($id);
        if(empty($role)) return $this->tool->response(['status' => 0, 'msg' => '该角色不存在!'], 'admin/adminRole');

        return view('admin.adminRole.show', compact('role'));
    }

    /**
     * 编辑角色
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit($id)
    {
        $id = (int)$id;

        $role = $this->adminRoleService->roleData($id);
        if(empty($role)) return $this->tool->response(['status' => 0, 'msg' => '该角色不存在!'], 'admin/adminRole');

        return view('admin.adminRole.edit', compact('role'));
    }

    /**
     * 角色授权
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function auth($id)
    {
        $id = (int)$id;

        $role = $this->adminRoleService->roleData($id);
        if(empty($role)) return $this->tool->response(['status' => 0, 'msg' => '该角色不存在!'], 'admin/adminRole');

        $role_perms = $this->adminRoleService->rolePermissions($role)->toArray();
        $permission_list = $this->adminRoleService->treePermissions();
        $role_perms_ids = !empty($role_perms) ? array_column($role_perms, 'id') : [];

        return view('admin.adminRole.auth', compact('role', 'role_perms', 'permission_list', 'role_perms_ids'));
    }

}
