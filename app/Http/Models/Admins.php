<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Zizaco\Entrust\Traits\EntrustUserTrait;

/**
 * 后台管理员模型
 * Class Admins
 * @package App\Http\Models
 */
class Admins extends Model
{
    use EntrustUserTrait;

    //
    protected $table = 'admins';
    protected $fillable = ['username', 'nickname', 'avatar', 'email', 'introduction', 'password', 'salt'];
    protected $primaryKey = 'user_id';

    /**
     * 根据管理员id获取数据
     * @param $admin_id
     * @param array $fields
     */
    public function getByUserId($user_id, $fields = ['user_id', 'username', 'nickname', 'avatar', 'email', 'password', 'salt', 'introduction', 'created_at', 'updated_at'])
    {
        return $this -> select($fields)->where('user_id', $user_id)->first();
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

    /**
     * 根据邮箱获取数据
     * @param $email 邮箱
     * @param array $fields 查询字段
     */
    public function getByEmail($email, $fields = ['user_id', 'username', 'nickname', 'avatar', 'email', 'password', 'salt', 'introduction', 'created_at'])
    {
        return $this -> select($fields)->where('email', '=', $email)->first();
    }

    /**
     * 根据用户名获取数据
     * @param $username 用户名
     * @param array $fields 查询字段
     * @return mixed
     */
    public function getByUsername($username, $fields = ['user_id', 'username', 'nickname', 'avatar', 'email', 'password', 'salt', 'introduction', 'created_at'])
    {
        return $this -> select($fields)->where('username', '=', $username)->first();
    }

    /**
     * 对接dataTable数据表
     * @param array $param
     */
    public function dataTable($param = [])
    {
        /**
        sEcho:2
        iColumns:6
        sColumns:,,,,,
        iDisplayStart:0
        iDisplayLength:10
        mDataProp_0:admin_id
        sSearch_0:
        bRegex_0:false
        bSearchable_0:true
        bSortable_0:true
        mDataProp_1:username
        sSearch_1:
        bRegex_1:false
        bSearchable_1:true
        bSortable_1:false
        mDataProp_2:nickname
        sSearch_2:
        bRegex_2:false
        bSearchable_2:true
        bSortable_2:false
        mDataProp_3:avatar
        sSearch_3:
        bRegex_3:false
        bSearchable_3:true
        bSortable_3:false
        mDataProp_4:email
        sSearch_4:
        bRegex_4:false
        bSearchable_4:true
        bSortable_4:false
        mDataProp_5:updated_at
        sSearch_5:
        bRegex_5:false
        bSearchable_5:true
        bSortable_5:true
        sSearch:
        bRegex:false
        iSortCol_0:0
        sSortDir_0:asc
        iSortingCols:1
         */
        $db = new static;

        $result = [
            'data' => [],
            'sEcho' => 0,
            'iTotalDisplayRecords' => 0,
            'iTotalRecords' => 0
        ];
        if(isset($param['sEcho']) && is_numeric($param['sEcho'])){
            $result['sEcho'] = $param['sEcho'];
        }
        //判断是否关键字查询
        if(isset($param['sSearch']) && !empty($param['sSearch'])){
            $db = $db->where('username', 'like', '%'.$param['sSearch'].'%')->orWhere('nickname', 'like', '%'.$param['sSearch'].'%')->orWhere('email', 'like', '%'.$param['sSearch'].'%');
        }
        $result['iTotalRecords'] = $db->count();

        //查询排序
        if(isset($param['iSortCol_0']) && is_numeric($param['iSortCol_0']) && isset($param['sSortDir_0']) && !empty($param['sSortDir_0'])){
            $field = 'mDataProp_'.$param['iSortCol_0'];
            if(isset($param[$field]) && !empty($param[$field])){
                $db = $db->orderBy($param[$field], $param['sSortDir_0']);
            }
        }
        $iDisplayStart = 0;
        $iDisplayLength = 10;
        if(isset($param['iDisplayStart']) && is_numeric($param['iDisplayStart'])){
            $iDisplayStart = intval($param['iDisplayStart']);
        }
        if(isset($param['iDisplayLength']) && is_numeric($param['iDisplayLength'])){
            $iDisplayLength = intval($param['iDisplayLength']);
        }

        $result['data'] = $db->offset($iDisplayStart)->limit($iDisplayLength)->get();
        $result['iTotalDisplayRecords'] = $result['iTotalRecords'];
        return $result;
    }

}
