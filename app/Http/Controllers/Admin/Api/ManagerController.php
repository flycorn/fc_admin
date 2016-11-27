<?php

namespace App\Http\Controllers\Admin\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

/**
 * 管理员接口
 * Class ManagerController
 * @package App\Http\Controllers\Admin\Api
 */
class ManagerController extends ApiController
{
    /**
     * 管理员列表数据
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        //重组数据
        $param = $request->all();
        return $this->response($this->admin->dataTable($param));
    }

    /**
     * 删除数据
     * @param $user_id
     */
    public function destroy($user_id)
    {
        $user_id = intval(trim($user_id, ' '));

        $user = $this->admin->getByUserId($user_id);
        if(!empty($user)){
            if($user->user_id == 1){
               return $this->setStatusCode(400)->responseError('超级管理员不能删除!');
            }
            $avatar =$user->avatar;
            $res = $user -> delete();
            if($res){
                //删除图片
                if($avatar != 'upload/admin/avatar/default/avatar.png') removeFile($avatar);
                return $this->responseSuccess('删除成功!');
            }
        }

        return $this->setStatusCode(400)->responseError('删除失败!');
    }
}
