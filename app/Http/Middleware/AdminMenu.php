<?php

namespace App\Http\Middleware;

use App\Http\Models\Permissions;
use Closure;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

/**
 * 后台菜单
 * Class AdminMenu
 * @package App\Http\Middleware
 */
class AdminMenu
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
        $menus = $this -> authMenu();
        $shareData = [];
        $shareData['authMenu'] = $menus['menu_list'];
        $shareData['open_menu'] = $menus['open_menu'];
        $shareData['breadcrumbs'] = $menus['breadcrumbs'];

        //数据绑定模板
        View::share($shareData);
        return $next($request);
    }

    /**
     * 获取授权菜单
     */
    private function authMenu()
    {
        $data = [];
        $open_menu = [];

        //获取当前登陆者
        $admin = session('admin');

        //当前路由别名
        $currentRoute = Route::currentRouteName();

        //查询出所有菜单
        $menu_list = Cache::store('file')->rememberForever('admin_menu_list',function(){
            $menu_list = Permissions::select('id', 'name', 'display_name', 'pid', 'icon')->where('is_menu', 1)->orderBy('sort', 'ASC')->get();
            return count($menu_list) ? $menu_list->toArray() : [];
        });

        //获取所有权限
        $permission_list = Cache::store('file')->rememberForever('admin_permission_list',function(){
            $permission_list = Permissions::select(['id', 'name', 'display_name', 'pid', 'icon'])->get();
            return count($permission_list) ? $permission_list->toArray() : [];
        });

        //转换
        $perms = [];
        $now_perms = [];
        foreach ($permission_list as $k => $v){
            if($v['name'] == $currentRoute){
                $now_perms = $v;
            } else {
                $v['url'] = URL::route($v['name'], '');
                $perms[$v['id']] = $v;
            }
        }

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

        //判断该菜单是否已授权
        foreach ($menu_list as $k => $item){
            if(!empty($item['name']) && !$admin -> auth($item['name'])){
                unset($menu_list[$k]);
                continue;
            }

            //转成链接
            $menu_list[$k]['url'] = URL::route($item['name'], null);
        }
        $menu_list = (new Permissions())->getSubTree($menu_list, 0);

        $data['menu_list'] = $menu_list;
        $data['open_menu'] = array_unique($breadcrumb_ids);
        $data['breadcrumbs'] = $breadcrumbs;
        return $data;
    }

}
