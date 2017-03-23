<?php

namespace App\Models\Admin;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\FcAdminModel;

class AdminUser extends Authenticatable
{
    use FcAdminModel;

    protected $table='admin_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'nickname', 'avatar', 'email', 'password', 'introduction',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    //用户角色
    public function roles()
    {
        return $this->belongsToMany(AdminRole::class,'admin_role_user','user_id','role_id');
    }

    /**
     * 授权角色
     * @param array $role_ids
     */
    public function authorizeRoles(array $role_ids)
    {
        //清空角色
        $this->roles()->detach();

        if(!empty($role_ids)){
            //授权角色
            $this->roles()->sync($role_ids);
        }
    }

    /**
     * 判断用户是否具有某个角色
     * @param $role
     * @return bool
     */
    public function hasRole($role)
    {
        if (is_numeric($role)) {
            return $this->roles->contains('id', $role);
        }
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }

        return !!$role->intersect($this->roles)->count();
    }

    /**
     * 验证用户是否具有某权限
     * @param $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        if (is_string($permission)) {
            $permission = AdminPermission::where('rule', $permission)->first();
            if (!$permission) return false;
        }
        return $this->hasRole($permission->roles);
    }
    
}
