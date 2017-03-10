<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(GateContract $gateContract, Request $request)
    {
        $this->registerPolicies();
        //后台
        $this->fcAdmin($gateContract, $request);
    }

    /**
     * 后台
     * @param $gateContract
     * @param $request
     */
    private function fcAdmin($gateContract, $request)
    {
        //区分后台访问
        $pathInfo = ltrim($request->getPathInfo(), '/');
        $prefix = is_numeric(strpos($pathInfo, '/')) ? substr($pathInfo, 0, strpos($pathInfo, '/')) : $pathInfo;
        $adminPrefixs = [
            'admin',
            'adminApi'
        ];
        if(in_array($prefix, $adminPrefixs)){

            //后台
            $gateContract->before(function ($user, $ability) {
                //是否超级管理员
                if ($user->id === 1) return true;
            });

            //获取所有权限
            $permissions = \App\Models\Admin\AdminPermission::with('roles')->get();

            foreach ($permissions as $k => $perm) {
                $gateContract->define($perm->rule, function ($user) use ($perm) {
                    return $user->hasPermission($perm);
                });
            }
        }
    }
}
