<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Zizaco\Entrust\Traits\EntrustUserTrait;

/**
 * 后台管理员模型
 * Class Admins
 * @package App\Http\Models
 */
class Admin extends Model
{
    use EntrustUserTrait, FcAdminModel;

    //
    protected $table = 'admins';
    protected $fillable = ['username', 'nickname', 'avatar', 'email', 'introduction', 'password', 'salt', 'remember_token'];
    protected $primaryKey = 'user_id';

    /**
     * 根据管理员id获取数据
     * @param $admin_id
     * @param array $fields
     */
    public function getByUserId($user_id, $fields = ['user_id', 'username', 'nickname', 'avatar', 'email', 'password', 'salt', 'introduction', 'created_at', 'updated_at', 'remember_token'])
    {
        return $this -> select($fields)->where('user_id', $user_id)->first();
    }

    /**
     * 根据邮箱获取数据
     * @param $email 邮箱
     * @param array $fields 查询字段
     */
    public function getByEmail($email, $fields = ['user_id', 'username', 'nickname', 'avatar', 'email', 'password', 'salt', 'introduction', 'created_at', 'updated_at', 'remember_token'])
    {
        return $this -> select($fields)->where('email', '=', $email)->first();
    }

    /**
     * 根据用户名获取数据
     * @param $username 用户名
     * @param array $fields 查询字段
     * @return mixed
     */
    public function getByUsername($username, $fields = ['user_id', 'username', 'nickname', 'avatar', 'email', 'password', 'salt', 'introduction', 'created_at', 'updated_at', 'remember_token'])
    {
        return $this -> select($fields)->where('username', '=', $username)->first();
    }

    /**
     * 根据记住密码token获取数据
     * @param $remember_token token
     * @param array $fields 查询字段
     * @return mixed
     */
    public function getByRememberToken($remember_token, $fields = ['user_id', 'username', 'nickname', 'avatar', 'email', 'password', 'salt', 'introduction', 'created_at', 'updated_at', 'remember_token'])
    {
        return $this -> select($fields)->where('remember_token', '=', $remember_token)->first();
    }


    /**
     *  验证权限
     * @param $permission
     * @param bool $requireAll
     */
    public function auth($permission, $requireAll = false)
    {
        if($this->user_id == 1) return true;
        return $this -> can($permission, $requireAll);
    }

    /**
     * 设置角色
     * @param array $role_ids
     */
    public function setRoles($role_ids = [])
    {
        $this->detachRoles();
        $this->attachRolesToId($role_ids);
    }

    /**
     * 设置角色
     * @param array $role_ids
     */
    private function attachRolesToId($role_ids = [])
    {
        if(empty($role_ids)) return;
        foreach ($role_ids as $k => $role_id){
            $this->attachRole(['id' => $role_id]);
        }
    }

}
