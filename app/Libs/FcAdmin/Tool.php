<?php
namespace App\Libs\FcAdmin;

use Illuminate\Support\Facades\Response;

/**
 * 工具库
 * Author: flycorn
 * Email: ym1992it@163.com
 * Date: 2017/3/7
 * Time: 18:25
 */

class Tool
{
    private $statusCode = '200'; //请求状态
    private $urlOrIsOriginalOutput = null; //是否原样输出或跳转链接
    private $type = null; //响应类型 null json

    /**
     * 响应
     * @param array $handleResult 处理结果或数据
     *
     * @param null $urlOrIsOriginalOutput 链接或是否原样输出
     *
     * @param null $type 输出类型 null json
     *
     */
    public function response()
    {
        try{
            //获取传入参数个数
            $paramNums = func_num_args();
            //获得传入的所有参数的数组
            $args = func_get_args();

            //解析参数
            if($paramNums >= 3){
                $handleResult = $args[0];
                $urlOrIsOriginalOutput = $args[1];
                $type = $args[2];
            } else if($paramNums == 2){
                $handleResult = $args[0];
                $urlOrIsOriginalOutput = $args[1];
                $type = $this->type;
            } else if($paramNums == 1){
                $handleResult = $args[0];
                $urlOrIsOriginalOutput = $this->urlOrIsOriginalOutput;
                $type = $this->type;
            } else {
                $handleResult = [];
                $urlOrIsOriginalOutput = $this->urlOrIsOriginalOutput;
                $type = $this->type;
            }

            //判断类型
            if($type == 'json'){
                //json返回

                //是否数据原样输出
                if($urlOrIsOriginalOutput) return $this->json($handleResult);

                //判断结果
                if(!$handleResult['status']) return $this->responseError($handleResult['msg']);

                //成功
                return $this->responseSuccess($handleResult['msg'], $handleResult['data']);
            }

            //判断结果
            if($handleResult['status']){
                //成功状态

                //是否指向跳转地址
                if(empty($urlOrIsOriginalOutput)) return back()->with('prompt', ['status' => 1, 'msg' => $handleResult['msg']]);

                return redirect($urlOrIsOriginalOutput)->with('prompt', ['status' => 1, 'msg' => $handleResult['msg']]);
            }
            //失败状态

            //验证错误类型
            if(empty($handleResult['data'])) return back()->withInput()->with('prompt', ['status' => 0, 'msg' => $handleResult['msg']]);

            if(is_array($handleResult['data'])) return back()->withInput()->with('errors', $handleResult['data']);

            return back()->withInput()->with('errors', [$handleResult['data'] => $handleResult['msg']]);
        } catch (Exception $exception){
            if(env('APP_DEBUG')) exit($exception->getMessage());
        }
    }

    /**
     * 设置类型
     * @param null $type
     * @return $this
     */
    public function setType($type = null)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * 设置跳转链接或是否原样输出
     * @param $url
     */
    public function setUrlOrIsOriginalOutput($urlOrIsOriginalOutput)
    {
        $this->urlOrIsOriginalOutput = $urlOrIsOriginalOutput;
        return $this;
    }

    /**
     * 设置状态码
     * @param $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this -> statusCode = $statusCode;
        return $this;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    //输出失败状态
    public function responseError($message, $data = [])
    {
        return $this -> json([
            'status' => 'failed',
            'errors' => [
                'status_code' => $this -> getStatusCode(),
                'message' => $message,
                'data' => $data,
            ]
        ]);
    }

    //输出成功状态
    public function responseSuccess($message, $data = [])
    {
        return $this -> json([
            'status' => 'successful',
            'correct' => [
                'status_code' => $this -> getStatusCode(),
                'message' => $message,
                'data' => $data,
            ]
        ]);
    }

    //返回请求结果
    public function json($data)
    {
        return Response::json($data);
    }

}