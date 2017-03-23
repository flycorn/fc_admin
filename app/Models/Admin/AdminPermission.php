<?php

namespace App\Models\Admin;

use App\Models\FcAdminModel;
use Illuminate\Database\Eloquent\Model;

class AdminPermission extends Model
{
    use FcAdminModel;

    protected $table='admin_permissions';

    public function roles()
    {
        return $this->belongsToMany(AdminRole::class,'admin_permission_role','permission_id','role_id');
    }

}
