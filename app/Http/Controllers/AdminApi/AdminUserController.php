<?php

namespace App\Http\Controllers\AdminApi;

use App\Http\Controllers\AdminApi\BaseController;
use App\Http\Requests\Admin\AdminUser\AdminUserCreateRequest;
use App\Http\Requests\Admin\AdminUser\AdminUserEditRequest;
use Illuminate\Http\Request;

class AdminUserController extends BaseController
{
    /**
     * 管理员列表数据
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        //获取请求数据
        $param = $request->all();

        //返回响应
        return $this->tool->response($this->adminUserService->dataTable(\App\Models\Admin\AdminUser::class, ['id', 'name', 'nickname', 'avatar', 'email', 'updated_at'], $param, [
            'condition' => [
                [
                    'where',
                    ['where', 'name like %?%'],
                    ['orWhere', 'nickname like %?%'],
                    ['orWhere', 'email like %?%']
                ]
            ]
        ]), true, 'json');
    }

    /**
     * 创建管理员
     * @param AdminUserCreateRequest $request
     * @return mixed
     */
    public function store(AdminUserCreateRequest $request)
    {
        //获取数据
        $form_data = $request->except(['_token', '_method']);

        //创建管理员
        $result = $this->adminUserService->createAdmin($form_data);

        //返回响应
        return $this->tool->response($result, 'admin/adminUser');
    }

    /**
     * 编辑管理员
     * @param $id
     * @param AdminUserEditRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, AdminUserEditRequest $request)
    {
        $id = (int)$id;
        //获取数据
        $form_data = $request->except(['_token', '_method']);

        //编辑管理员
        $result = $this->adminUserService->editAdmin($id, $form_data);

        //返回响应
        return $this->tool->response($result, 'admin/adminUser');
    }

    /**
     * 删除管理员
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        $id = (int)$id;

        //删除管理员
        $result = $this->adminUserService->deleteAdmin($id);

        //返回响应
        return $this->tool->setType('json')->response($result);
    }

}
