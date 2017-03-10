<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AdminLoggerEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $type = 'info'; //类型

    public $msg = ''; //信息

    public $data = null; //数据

    public $name = null; //管理员用户名

    public $nickname = null; //昵称

    public $id = 0; //管理员id

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($msg, $data = null)
    {
        //获取当前管理员数据
        $admin = auth('admin')->user();
        if(!empty($admin)){
            $this->name = $admin->name;
            $this->nickname = $admin->nickname;
            $this->id = $admin->id;
        }

        $this->setMsg($msg);
        if(!empty($data)) $this->setData($data);
    }

    /**
     * 设置类型
     * @param $type  debug , info , notice , warning , error , critical , alert , emergency
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * 设置消息
     * @param $msg
     * @return $this
     */
    public function setMsg($msg)
    {
        $this->msg = $msg;
        return $this;
    }

    /**
     * 设置数据
     * @param null $data
     * @return $this
     */
    public function setData($data = null)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
