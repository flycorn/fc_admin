<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminPermissionController extends BaseController
{
    /**
     * 权限列表
     * @param int $pid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($pid = 0)
    {
        $pid = (int)$pid;

        $parent_permission = null;
        if($pid) $parent_permission = $this->adminPermissionService->permissionData($pid);

        return view('admin.adminPermission.index', compact('pid', 'parent_permission'));
    }

    /**
     * 添加权限
     * @param $pid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($pid)
    {
        $pid = (int)$pid;
        $parent_permission = null;
        if($pid) $parent_permission = $this->adminPermissionService->permissionData($pid);

        return view('admin.adminPermission.create', compact('pid', 'parent_permission'));
    }

    /**
     * 权限详情
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function show($id)
    {
        $id = (int)$id;

        $permission = $this->adminPermissionService->permissionData($id);
        if(empty($permission)) return $this->tool->response(['status' => 0, 'msg' => '该权限不存在!'], 'admin/adminPermission');

        $parent_permission = null;
        if($permission->pid) $parent_permission = $this->adminPermissionService->permissionData($permission->pid);

        return view('admin.adminPermission.show', compact('permission', 'parent_permission'));
    }

    /**
     * 编辑权限
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit($id)
    {
        $id = (int)$id;

        $permission = $this->adminPermissionService->permissionData($id);
        if(empty($permission)) return $this->tool->response(['status' => 0, 'msg' => '该权限不存在!'], 'admin/adminPermission');

        $pid = $permission -> pid;
        $parent_permission = null;
        if($permission->pid) $parent_permission = $this->adminPermissionService->permissionData($permission->pid);

        return view('admin.adminPermission.edit', compact('permission', 'pid', 'parent_permission'));
    }

}
