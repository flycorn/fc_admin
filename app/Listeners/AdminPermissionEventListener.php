<?php

namespace App\Listeners;

use App\Events\AdminPermission;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Cache;

class AdminPermissionEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AdminPermission  $event
     * @return void
     */
    public function handle(AdminPermission $event)
    {
        //获取最新权限数据
        $permissions = $event->permission->select(['id', 'name', 'display_name', 'pid', 'icon', 'is_menu'])->get();
        $permissions = count($permissions) ? $permissions->toArray() : [];
        if(count($permissions)){
            foreach ($permissions as $k => $v){
                //获取链接
                try{
                    $url = URL::route($v['name'], null);
                    if(substr($url, -1, 1) == '?') $url = substr($url, 0, -1);
                } catch(\Exception $e){
                    $url = '/admin';
                }
                $permissions[$k]['url'] = $url;
            }
        }

        //重新缓存
        Cache::store('file')->forever('admin_permissions', $permissions);
    }
}
