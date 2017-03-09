<?php
namespace App\Http\Controllers\AdminApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

/**
 * 后台上传图片
 * Class UploadController
 * @package App\Http\Controllers\Admin
 */
class UploadController extends BaseController
{

    //上传图片
    public function image()
    {
        $result = [
            'status' => 0,
            'msg' => '上传失败!',
            'data' => []
        ];
        $file = Input::file('Filedata');
        //验证文件
        if($file -> isValid()){
            $entension = $file -> getClientOriginalExtension(); //上传文件的后缀.

            //验证后缀
            $allow_ext = ['jpg', 'png', 'jpeg'];
            if(!in_array(strtolower($entension), $allow_ext)){
                $result['msg'] = '文件格式不正确!';
                return Response::json($result);
            }

            $tmp_name = date('YmdHis').mt_rand(100,999).'.'.$entension;//新文件名
            $tmp_path = 'upload/tmp/image/'.date('Ymd').'/'; //临时文件路径

            //上传
            checkDir($tmp_path);
            $file -> move(base_path().'/public/'.$tmp_path, $tmp_name);
            $filepath = $tmp_path.$tmp_name;

            $result['status'] = 1;
            $result['msg'] = '上传成功!';
            $result['data'] = [
                'img' => $filepath,
                'img_url' => asset($filepath)
            ];
        }

        //返回响应
        return $this->tool->response($result, true, 'json');
    }

}
