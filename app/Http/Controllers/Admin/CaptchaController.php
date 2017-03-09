<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Support\Facades\Session;

class CaptchaController extends Controller
{
    private $captcha; //验证类

    public function __construct(CaptchaBuilder $captcha)
    {
        $this -> captcha = $captcha;
    }

    public function index()
    {
        //可以设置图片宽高及字体
        $this->captcha->build(150, 34);
        //获取验证码的内容
        $phrase = $this->captcha->getPhrase();
        //把内容存入session
        Session::flash('admin_login_captcha', strtolower($phrase));
        //生成图片
        header("Cache-Control: no-cache, must-revalidate");
        header('Content-Type: image/jpeg');
        $this->captcha->output();
    }
}
