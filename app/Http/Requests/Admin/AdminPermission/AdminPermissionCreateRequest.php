<?php

namespace App\Http\Requests\Admin\AdminPermission;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Admin\FcAdminRequest;

class AdminPermissionCreateRequest extends FormRequest
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
            'name' => 'required|max:255',
            'rule' => 'required|unique:admin_permissions|max:255',
            'pid' => 'required|int',
            'sort' => 'integer',
        ];
    }

    /**
     * 提示消息
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => '请填写规则名称!',
            'name.max' => '规则名称过长!',
            'rule.required' => '请填写规则!',
            'rule.unique' => '规则已存在!',
            'rule.max' => '规则过长!',
            'pid.required' => '请选择所属权限!',
            'pid.int' => '所属权限有误!',
            'sort.integer' => '排序需为整数!',
        ];
    }
}
