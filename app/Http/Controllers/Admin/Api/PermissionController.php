<?php

namespace App\Http\Controllers\Admin\Api;

use Illuminate\Http\Request;
use App\Events\AdminPermission;
use Event;
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
        
        return $this->response($this->permission->dataTable(['id', 'name', 'display_name', 'description', 'sort', 'pid', 'icon', 'is_menu', 'updated_at'], $param, [
            'condition' => [
                [
                    'where',
                    ['where', 'pid = '.$pid]
                ],
                [
                    'where',
                    ['where', 'display_name like %?%'],
                    ['orWhere', 'name like %?%']
                ]
            ]
        ]));
    }

    /**
     * 删除
     * @param int $id
     */
    public function destroy($id = 0)
    {
        $id = (int)$id;

        $permission = $this->permission->getById($id);
        if(!empty($permission)){
            //验证是否有自权限
            $sub = $this->permission->where('pid', $id)->first();
            if(!empty($sub)){
                return $this->setStatusCode(400)->responseError('请先删除子权限!');
            }
            $res = $permission -> delete();
            if($res){

                //触发事件
                Event::fire(new AdminPermission($this->permission));

                return $this->responseSuccess('删除成功!');
            }
        }

        return $this->setStatusCode(400)->responseError('删除失败!');
    }
}
