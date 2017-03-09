<?php

namespace App\Http\Requests\Admin\Ucenter;

use App\Services\Admin\AdminUserService;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Admin\FcAdminRequest;

/**
 * 个人中心修改资料请求
 * Class UcenterEditRequest
 * @package App\Http\Requests\Admin
 */
class UcenterEditRequest extends FormRequest
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
        $admin = AdminUserService::loggedUser();
        $id = isset($admin->id)&&!empty($admin->id) ? $admin->id : 0;

        return [
            'nickname' => 'required|max:50',
            'email' => 'required|email|unique:admin_users,email,'.$id.',id|max:30',
            'introduction' => 'max:255',
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
            'email.unique' => '该邮箱已被注册!',
            'introduction.max' => '个人简介过长!',
        ];
    }
}
