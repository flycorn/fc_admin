<?php

namespace App\Http\Requests\Admin\AdminUser;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Admin\FcAdminRequest;

class AdminUserEditRequest extends FormRequest
{
    use FcAdminRequest;

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
        $id = (int)substr($this->getPathInfo(), strrpos($this->getPathInfo(), '/')+1);
        return [
            'nickname' => 'required|max:50',
            'email' => 'required|email|max:30|unique:admin_users,email,'.$id.',id',
            'password' => 'max:20'
        ];
    }

    /**
     * 提示消息
     * @return array
     */
    public function messages()
    {
        return [
            'nickname.required' => '请填写昵称!',
            'nickname.max' => '昵称过长!',
            'email.required' => '请填写邮箱!',
            'email.email' => '邮箱格式不正确!',
            'email.max' => '邮箱过长!',
            'email.unique' => '邮箱已存在!',
            'password.max' => '密码过长!'
        ];
    }

}
