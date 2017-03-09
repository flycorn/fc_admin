<?php
/**
 * Author: flycorn
 * Email: ym1992it@163.com
 * Date: 2017/3/7
 * Time: 10:39
 */

namespace App\Services\Admin;


use App\Models\Admin\AdminPermission;

class AdminPermissionService extends AdminService
{
    use DataTableService;

    private $adminPermission;

    public function __construct(AdminPermission $adminPermission)
    {
        //依赖注入model
        $this->adminPermission = $adminPermission;
    }

    /**
     * 权限数据
     * @param $id
     * @return mixed
     */
    public function permissionData($id)
    {
        return $this->adminPermission->find($id);
    }

    /**
     * 添加权限
     * @param $form_data
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createPermission($form_data)
    {
        $form_data['created_at'] = date('Y-m-d H:i:s');
        //写入数据
        $id = $this->adminPermission->insertGetId($form_data);

        if(!$id) return $this->handleError('添加失败!');

        return $this->handleSuccess('添加成功!');
    }

    /**
     * 编辑权限
     * @param $id
     * @param $form_data
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editPermission($id, $form_data)
    {
        $permission = $this->permissionData($id);
        if(empty($permission)) return $this->handleError('该权限不存在!');

        if(!isset($form_data['is_menu'])) $form_data['is_menu'] = 0;

        $form_data['is_menu'] = (int)$form_data['is_menu'];
        $form_data['pid'] = (int)$form_data['pid'];

        //修改数据
        $form_data['updated_at'] = date('Y-m-d H:i:s');
        $res = $this->adminPermission->where('id', $id)->update($form_data);
        if(!$res) return $this->handleError('编辑失败!');

        return $this->handleSuccess('编辑成功!');
    }

    /**
     * 删除权限
     * @param $id
     * @return mixed
     */
    public function deletePermission($id)
    {
        $permission = $this->permissionData($id);
        if(empty($permission)) return $this->handleError('该权限不存在!');

        //删除
        $res = $permission->delete();
        if(!$res) return $this->handleError('删除失败!');

        return $this->handleSuccess('删除成功');
    }

}