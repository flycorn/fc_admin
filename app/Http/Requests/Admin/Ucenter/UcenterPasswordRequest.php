<?php

namespace App\Http\Requests\Admin\Ucenter;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Admin\FcAdminRequest;

/**
 * 个人中心修改密码请求
 * Class UcenterPasswordRequest
 * @package App\Http\Requests\Admin
 */
class UcenterPasswordRequest extends FormRequest
{
    use FcAdminRequest;

    protected $dontFlash = ['old_password', 'new_password'];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password' => 'required',
            'new_password' => 'required|max:20|different:password',
        ];
    }

    /**
     * 提示消息
     * @return array
     */
    public function messages()
    {
        return [
            'password.required' => '请输入原密码!',
            'new_password.required' => '请输入新密码!',
            'new_password.max' => '新密码过长!',
            'new_password.different' => '新密码不能与原密码一致!'
        ];
    }

}
