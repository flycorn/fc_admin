<?php

namespace App\Http\Controllers\Admin\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

/**
 * 权限接口
 * Class PermissionController
 * @package App\Http\Controllers\Admin\Api
 */
class PermissionController extends ApiController
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
        //重组数据
        $param = $request->all();
        return $this->response($this->permissions->dataTable($param, ['where' => ['pid' => $pid]]));
    }

    /**
     * 删除
     * @param int $id
     */
    public function destroy($id = 0)
    {
        $id = (int)$id;

        $permission = $this->permissions->getById($id);
        if(!empty($permission)){
            $res = $permission -> delete();
            if($res){
                return $this->responseSuccess('删除成功!');
            }
        }

        return $this->setStatusCode(400)->responseError('删除失败!');
    }
}
