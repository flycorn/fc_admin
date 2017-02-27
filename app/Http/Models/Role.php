<?php

namespace App\Http\Models;

use Illuminate\Support\Facades\Config;
use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    use FcAdminModel;

    //protected $table = 'roles';
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
        return $this->belongsToMany('App\Http\Models\Admin', Config::get('entrust.role_user_table'),Config::get('entrust.role_foreign_key'),Config::get('entrust.user_foreign_key'));
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

}
