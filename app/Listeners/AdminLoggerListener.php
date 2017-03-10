<?php

namespace App\Listeners;

use App\Events\AdminLoggerEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class AdminLoggerListener
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
     * @param  AdminLoggerEvent  $event
     * @return void
     */
    public function handle(AdminLoggerEvent $event)
    {
        try{
            $type = $event->type ? $event->type : 'info';
            $msg = !empty($event->name) ? '管理员 [ID:'.$event->id.', 用户名:'.$event->name.', 昵称:'.$event->nickname.'] '.$event->msg : $event->msg;
            //写入日志
            !empty($event->data) ? Log::$type($msg, $event->data) : Log::$type($msg);
        }catch (Exception $exception){
            if(env('APP_DEBUG')) exit($exception->getMessage());
        }
    }
}
