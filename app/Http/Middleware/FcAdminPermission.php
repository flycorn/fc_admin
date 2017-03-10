<?php

namespace App\Http\Middleware;

use App\Models\Admin\AdminPermission;
use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

/**
 * 后台权限中间件
 * Class FcAdminPermission
 * @package App\Http\Middleware
 */
class FcAdminPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //获取数据
        $data = $this -> authPermissionMenu();

        $shareData = [];
        $shareData['authMenus'] = $data['menu_list'];
        $shareData['openMenus'] = $data['open_menu'];
        $shareData['breadcrumbs'] = $data['breadcrumbs'];

        //数据绑定模板
        View::share($shareData);
        return $next($request);
    }

    /**
     * 获取所有权限节点
     * @return mixed
     */
    private function getPermissions()
    {
        return Cache::store('file')->rememberForever('admin_permissions',function(){
            $permissions = AdminPermission::select(['id', 'rule', 'name', 'pid', 'icon', 'is_menu'])->orderBy('sort', 'Asc')->get();
            $permissions = count($permissions) ? $permissions->toArray() : [];
            if(count($permissions)){
                foreach ($permissions as $k => $v){
                    //获取链接
                    try{
                        $url = URL::route($v['rule'], null);
                        if(substr($url, -1, 1) == '?') $url = substr($url, 0, -1);
                    } catch(\Exception $e){
                        $url = '/admin';
                    }
                    $permissions[$k]['url'] = $url;
                }
            }
            return $permissions;
        });
    }

    /**
     * 获取授权菜单
     */
    private function authPermissionMenu()
    {
        $data = [];

        //当前路由别名
        $currentRoute = Route::currentRouteName();

        //获取所有权限及菜单
        $permissions = $this -> getPermissions();

        //菜单
        $menu_list = [];
        //重组数据
        $perms = [];
        $now_perms = [];

        foreach ($permissions as $k => $v){
            if($v['rule'] == $currentRoute){
                $now_perms = $v;
            } else {
                $perms[$v['id']] = $v;
            }
            if($v['id'] == 3){

//                dd(adminAuth($v['rule']));
            }
            if($v['is_menu'] && adminAuth($v['rule'])){
                $menu_list[] = $v;
            }
        }

//        dd($menu_list);

        //获取当前面包屑
        $breadcrumbs = [];
        $breadcrumb_ids = [];
        if(!empty($now_perms)){
            $breadcrumbs = getParentData($perms, 'pid', $now_perms['pid']);
            array_unshift($breadcrumbs, $now_perms);
            usort($breadcrumbs, function($a, $b){
                if($a['pid'] == $b['pid']){
                    return 0;
                }
                return ($a['pid'] < $b['pid']) ? -1 : 1;
            });
            $breadcrumb_ids = array_column($breadcrumbs, 'id');
        }

        //转换菜单结构
        $menu_list = getSubTreeData($menu_list, 'pid', 0);

        $data['menu_list'] = $menu_list;
        $data['open_menu'] = array_unique($breadcrumb_ids);
        $data['breadcrumbs'] = $breadcrumbs;
        return $data;
    }

}
