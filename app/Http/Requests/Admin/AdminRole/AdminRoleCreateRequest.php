<?php

namespace App\Http\Requests\Admin\AdminRole;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Admin\FcAdminRequest;

class AdminRoleCreateRequest extends FormRequest
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
        return [
            'name' => 'required|unique:admin_roles|max:255|not_in:超级管理员',
        ];
    }

    /**
     * 提示消息
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => '请填写角色名称!',
            'name.unique' => '该角色名已存在!',
            'name.max' => '角色名称过长!',
            'name.not_in' => '角色名不能是超级管理员!',
        ];
    }

}
