<?php
/**
 * 后台角色服务
 * Author: flycorn
 * Email: ym1992it@163.com
 * Date: 2017/3/7
 * Time: 10:39
 */

namespace App\Services\Admin;


use App\Events\AdminLoggerEvent;
use App\Models\Admin\AdminPermission;
use App\Models\Admin\AdminRole;
use App\Services\DataTableService;
use App\Services\FcAdminService;
use Illuminate\Support\Facades\Event;

class AdminRoleService extends FcAdminService
{
    use DataTableService;

    private $adminRole;

    public function __construct(AdminRole $adminRole)
    {
        //依赖注入model
        $this->adminRole = $adminRole;
    }

    /**
     * 角色列表
     * @return mixed
     */
    public function roleList()
    {
        return $this->adminRole->select(['id', 'name'])->get();
    }

    /**
     * 角色数据
     * @param $id
     * @return mixed
     */
    public function roleData($id)
    {
        return $this->adminRole->find($id);
    }

    /**
     * 角色对应的权限
     * @param $role
     * @return mixed
     */
    public function rolePermissions($role)
    {
        return $role->permissions()->get();
    }
    
    /**
     * 获取树形结构权限列表
     * @return array
     */
    public function treePermissions()
    {
        $return_data = [];
        //获取所有权限
        $list = AdminPermission::select(['id', 'name', 'rule', 'description', 'pid', 'is_menu', 'icon', 'sort'])->orderBy('sort', 'ASC')->get();
        if(count($list)){
            $list = $list->toArray();

            //获取树形结构数据
            $return_data = getTreeData($list, 'id', 'pid', 'name');
        }
        return $return_data;
    }

    /**
     * 添加角色
     * @param $form_data
     */
    public function createRole($form_data)
    {
        $form_data['created_at'] = date('Y-m-d H:i:s');

        //创建角色
        foreach ($form_data as $k => $v){
            $this->adminRole->$k = $v;
        }
        $res = $this->adminRole->save();

        if(!$res) return $this->handleError('添加失败!');

        //触发事件
        Event::fire(new AdminLoggerEvent('添加了角色 [ ID:'.$this->adminRole->id.', 角色名:'.$this->adminRole->name.' ]'));

        return $this->handleSuccess('添加成功');
    }

    /**
     * 编辑角色
     * @param $id
     * @param $form_data
     * @return array
     */
    public function editRole($role_id, $form_data)
    {
        $role = $this->roleData($role_id);
        if(empty($role)) return $this->handleError('该角色不存在!');

        //修改数据
        $form_data['updated_at'] = date('Y-m-d H:i:s');
        $res = $this->adminRole->where('id', $role_id)->update($form_data);
        if(!$res) return $this->handleError('修改失败!');

        //触发事件
        Event::fire(new AdminLoggerEvent('修改了角色 [ ID:'.$role->id.', 角色名:'.$form_data['name'].' ]'));

        return $this->handleSuccess('修改成功');
    }

    /**
     * 删除角色
     * @param $role_id
     * @return array
     */
    public function deleteRole($role_id)
    {
        $role = $this->roleData($role_id);
        if(empty($role)){
            return $this->handleError('该角色不存在!');
        }
        //删除
        $res = $role->delete();
        if(!$res) return $this->handleError('删除失败!');

        //清除授权数据
        foreach ($role->permissions as $v){
            $role->permissions()->detach($v);
        }

        //触发事件
        Event::fire(new AdminLoggerEvent('删除了角色 [ ID:'.$role->id.', 角色名:'.$role->name.' ]'));

        return $this->handleSuccess('删除成功');
    }

    /**
     * 授权
     * @param $role_id
     * @param $perm_ids
     * @return array
     */
    public function auth($role_id, $perm_ids)
    {
        $role = $this->roleData($role_id);
        if(empty($role)){
            return $this->handleError('该角色不存在!');
        }

        //授权
        $role -> authorizePermissions($perm_ids);

        //触发事件
        $data = !empty($perm_ids) ? AdminPermission::select(['id', 'name', 'rule', 'description'])->whereIn('id', $perm_ids)->get()->toArray() : [];
        Event::fire(new AdminLoggerEvent('角色授权 [ ID:'.$role->id.', 角色名:'.$role->name.' ]', $data));

        return $this->handleSuccess('授权成功!');
    }

}