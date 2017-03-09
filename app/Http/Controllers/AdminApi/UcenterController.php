<?php

namespace App\Http\Controllers\AdminApi;

use App\Http\Requests\Admin\Ucenter\UcenterEditRequest;
use App\Http\Requests\Admin\Ucenter\UcenterPasswordRequest;

class UcenterController extends BaseController
{
    /**
     * 修改个人资料
     * @param UcenterEditRequest $request
     */
    public function edit(UcenterEditRequest $request)
    {
        //获取表单数据
        $form_data = $request->except('_token');

        //个人中心修改
        $result = $this->adminUserService->ucenterEdit($form_data);

        //返回响应
        return $this->tool->response($result, 'admin/ucenter');
    }

    /**
     * 修改密码
     * @param UcenterPasswordRequest $request
     */
    public function password(UcenterPasswordRequest $request)
    {
        //获取表单数据
        $form_data = $request->except('_token');

        //修改密码
        $result = $this->adminUserService->ucenterPassword($form_data);

        //返回响应
        return $this->tool->response($result, 'admin/ucenter');
    }

}
