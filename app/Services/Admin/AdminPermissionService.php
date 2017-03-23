<?php
/**
 * 后台权限服务
 * Author: flycorn
 * Email: ym1992it@163.com
 * Date: 2017/3/7
 * Time: 10:39
 */

namespace App\Services\Admin;


use App\Events\AdminLoggerEvent;
use App\Events\AdminPermissionChangeEvent;
use App\Models\Admin\AdminPermission;
use App\Services\DataTableService;
use App\Services\FcAdminService;
use Illuminate\Support\Facades\Event;

class AdminPermissionService extends FcAdminService
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
        //创建权限
        foreach ($form_data as $k => $v){
            $this->adminPermission->$k = $v;
        }
        $res = $this->adminPermission->save();

        if(!$res) return $this->handleError('添加失败!');

        //触发事件
        Event::fire(new AdminPermissionChangeEvent($this->adminPermission));
        Event::fire(new AdminLoggerEvent('创建了权限 [ ID:'.$this->adminPermission->id.', 权限名:'.$this->adminPermission->name.', 规则:'.$this->adminPermission->rule.' ]'));

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

        //触发事件
        Event::fire(new AdminPermissionChangeEvent($this->adminPermission));
        Event::fire(new AdminLoggerEvent('修改了权限 [ ID:'.$permission->id.', 权限名:'.$form_data['name'].', 规则:'.$form_data['rule'].' ]'));

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

        //验证是否有自权限
        $sub = $this->adminPermission->where('pid', $id)->first();
        if(!empty($sub)){
            return $this->handleError('请先删除该权限下所有子权限!');
        }
        //删除
        $res = $permission->delete();
        if(!$res) return $this->handleError('删除失败!');

        //清除角色对应权限数据
        foreach ($permission->roles as $role) {
            $permission->roles()->detach($role -> id);
        }

        //触发事件
        Event::fire(new AdminPermissionChangeEvent($this->adminPermission));
        Event::fire(new AdminLoggerEvent('删除了权限 [ ID:'.$permission->id.', 权限名:'.$permission->name.', 规则:'.$permission->rule.' ]'));

        return $this->handleSuccess('删除成功');
    }

}