<?php

namespace App\Http\Controllers\AdminApi;

use App\Http\Requests\Admin\AdminPermission\AdminPermissionCreateRequest;
use App\Http\Requests\Admin\AdminPermission\AdminPermissionEditRequest;
use Illuminate\Http\Request;

class AdminPermissionController extends BaseController
{
    /**
     * 权限列表数据
     * @param Request $request
     * @param int $pid
     * @return mixed
     */
    public function index(Request $request, $pid = 0)
    {
        $pid = (int)$pid;
        //获取请求数据
        $param = $request->all();

        //返回数据
        return $this->tool->response($this->adminPermissionService->dataTable(\App\Models\Admin\AdminPermission::class, ['id', 'name', 'rule', 'description', 'sort', 'pid', 'icon', 'is_menu', 'updated_at'], $param, [
            'condition' => [
                [
                    'where',
                    ['where', 'pid = '.$pid]
                ],
                [
                    'where',
                    ['where', 'name like %?%'],
                    ['orWhere', 'rule like %?%']
                ]
            ]
        ]), true, 'json');
    }

    /**
     * 添加权限
     * @param AdminPermissionCreateRequest $request
     * @return mixed
     */
    public function store(AdminPermissionCreateRequest $request)
    {
        //获取数据
        $form_data = $request -> except(['_token', '_method']);

        //添加权限
        $result = $this->adminPermissionService->createPermission($form_data);

        $pid = isset($form_data['pid']) ? (int)$form_data['pid'] : 0;

        //返回响应
        return $this->tool->response($result, 'admin/adminPermission/'.$pid);
    }

    /**
     * 编辑权限
     * @param $id
     * @param AdminPermissionEditRequest $request
     * @return mixed
     */
    public function update($id, AdminPermissionEditRequest $request)
    {
        $id = (int)$id;
        //获取数据
        $form_data = $request -> except(['_token', '_method']);

        //编辑权限
        $result = $this->adminPermissionService->editPermission($id, $form_data);

        $pid = isset($form_data['pid']) ? (int)$form_data['pid'] : 0;

        //返回响应
        return $this->tool->response($result, 'admin/adminPermission/'.$pid);
    }

    /**
     * 删除权限
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        $id = (int)$id;

        //删除权限
        $result = $this->adminPermissionService->deletePermission($id);

        //返回响应
        return $this->tool->setType('json')->response($result);
    }

}
