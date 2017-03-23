<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use App\Models\FcAdminModel;

class AdminRole extends Model
{
    use FcAdminModel;

    protected $table='admin_roles';

    /**
     * 权限
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(AdminPermission::class,'admin_permission_role','role_id','permission_id');
    }

    /**
     * 权限授权
     * @param $perm_ids
     */
    public function authorizePermissions($perm_ids)
    {
        //清空权限
        $this->permissions()->detach();

        if(!empty($perm_ids)){
            //授权权限
            $this->permissions()->sync($perm_ids);
        }
    }

}
