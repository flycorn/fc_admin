<?php
/**
 * 后台服务基类
 * Author: flycorn
 * Email: ym1992it@163.com
 * Date: 2017/3/7
 * Time: 15:18
 */

namespace App\Services;

abstract class FcAdminService
{

    /**
     * 处理失败
     * @param string $msg
     * @param null $data
     */
    protected function handleError($msgOrData = '', $data = null, $statusCode = 302)
    {
        if(is_array($msgOrData)){
            $data = $msgOrData;
            $msgOrData = '';
        }
        return $this -> handleResult(false, $msgOrData, $data, $statusCode);
    }

    /**
     * 处理成功
     * @param string $msg
     * @param null $data
     */
    protected function handleSuccess($msg = '', $data = null, $statusCode = 200)
    {
        return $this -> handleResult(true, $msg, $data, $statusCode);
    }

    /**
     * 处理结果
     * @param $status
     * @param string $msg
     * @return array
     */
    protected function handleResult($status, $msg = null, $data = null, $statusCode = 200){
        return [
            'status' => $status,
            'msg' => $msg,
            'data' => $data,
            'statusCode' => $statusCode
        ];
    }

}