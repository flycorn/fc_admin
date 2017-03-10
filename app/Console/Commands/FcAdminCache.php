<?php

namespace App\Console\Commands;

use App\Models\Admin\AdminPermission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;

class FcAdminCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fc_admin:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fc_Admin Permissions Cache';

    private $adminPermission;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(AdminPermission $adminPermission)
    {
        parent::__construct();

        $this->adminPermission = $adminPermission;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
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
        //重新缓存
        Cache::store('file')->forever('admin_permissions', $permissions);

        echo PHP_EOL.'permissions cache success!'.PHP_EOL;
    }
}
