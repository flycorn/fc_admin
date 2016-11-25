<?php

namespace App\Http\Models;

use Illuminate\Support\Facades\Config;
use Zizaco\Entrust\EntrustRole;

class Roles extends EntrustRole
{
    
    //
    protected $table = 'roles';
    protected $fillable = ['id', 'name', 'display_name', 'description'];
    protected $primaryKey = 'id';

    public function getById($id = 0, $field = ['id', 'name', 'display_name', 'description', 'created_at', 'created_at'])
    {
        return $this->select($field)->where('id', $id)->first();
    }

    /**
     * 重写关联用户
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('App\Http\Models\Admins', Config::get('entrust.role_user_table'),Config::get('entrust.role_foreign_key'),Config::get('entrust.user_foreign_key'));
    }
    
    /**
     * 获取角色权限
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function perms()
    {
        return $this->belongsToMany(Config::get('entrust.permission'), Config::get('entrust.permission_role_table'), 'role_id', 'permission_id');
    }

    /**
     * 角色设置权限
     * @param array $perm_ids
     */
    public function setPerms($perm_ids = [])
    {
        $this->detachPermissions($this->perms);
        $this->attachPermissionToId($perm_ids);
    }

    /**
     * 设置权限
     * @param $perm_ids
     */
    private function attachPermissionToId($perm_ids)
    {
        if(empty($perm_ids)) return;
        foreach ($perm_ids as $id) {
            $this->attachPermission(['id' => $id]);
        }
    }
    
    /**
     * 对接dataTable数据表
     * @param array $param
     * @param array $other
     */
    public function dataTable($param = [], $other = [])
    {
        $db = new static;

        $result = [
            'data' => [],
            'sEcho' => 0,
            'iTotalDisplayRecords' => 0,
            'iTotalRecords' => 0
        ];
        if(isset($param['sEcho']) && is_numeric($param['sEcho'])){
            $result['sEcho'] = $param['sEcho'];
        }

        if(!empty($other['where'])){
            foreach ($other['where'] as $k => $v){
                $db = $db->where($k, $v);
            }
        }

        //判断是否关键字查询
        if(isset($param['sSearch']) && !empty($param['sSearch'])){
            $db = $db->where('display_name', 'like', '%'.$param['sSearch'].'%')->orWhere('name', 'like', '%'.$param['sSearch'].'%');
        }
        $result['iTotalRecords'] = $db->count();

        //查询排序
        if(isset($param['iSortCol_0']) && is_numeric($param['iSortCol_0']) && isset($param['sSortDir_0']) && !empty($param['sSortDir_0'])){
            $field = 'mDataProp_'.$param['iSortCol_0'];
            if(isset($param[$field]) && !empty($param[$field])){
                $db = $db->orderBy($param[$field], $param['sSortDir_0']);
            }
        }
        $iDisplayStart = 0;
        $iDisplayLength = 10;
        if(isset($param['iDisplayStart']) && is_numeric($param['iDisplayStart'])){
            $iDisplayStart = intval($param['iDisplayStart']);
        }
        if(isset($param['iDisplayLength']) && is_numeric($param['iDisplayLength'])){
            $iDisplayLength = intval($param['iDisplayLength']);
        }

        $result['data'] = $db->offset($iDisplayStart)->limit($iDisplayLength)->get();
        $result['iTotalDisplayRecords'] = $result['iTotalRecords'];
        return $result;
    }

}
