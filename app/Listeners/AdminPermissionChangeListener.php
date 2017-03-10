<?php

namespace App\Listeners;

use App\Events\AdminPermissionChangeEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;

class AdminPermissionChangeListener
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
     * @param  AdminPermissionChangeEvent  $event
     * @return void
     */
    public function handle(AdminPermissionChangeEvent $event)
    {
        //清理缓存
        Cache::store('file')->forget('admin_permissions');
    }
}
