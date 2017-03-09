<?php

namespace App\Http\Requests\Admin\AdminPermission;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Admin\FcAdminRequest;

class AdminPermissionEditRequest extends FormRequest
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
            'name' => 'required|max:255',
            'rule' => 'required|unique:admin_permissions,rule,'.$id.'|max:255',
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
            'sort.integer' => '排序需为整数!',
        ];
    }
}
