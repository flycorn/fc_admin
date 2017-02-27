<?php

namespace App\Http\Models;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    use FcAdminModel;
    
    //protected $table = 'permissions';
    protected $fillable = ['name', 'display_name', 'description', 'pid', 'icon', 'sort', 'is_menu'];
    protected $primaryKey = 'id';

    public function getById($id, $field = ['id', 'name', 'display_name', 'description', 'pid', 'icon', 'sort', 'is_menu', 'created_at', 'updated_at'])
    {
        return $this->select($field)->where('id', $id)->first();
    }

    /**
     * 获取树形全部数据
     * @return array
     */
    public function getTreeAllData()
    {
        $return_data = [];
        $data = $this->select(['id', 'name', 'display_name', 'description', 'pid', 'icon', 'sort', 'is_menu'])->orderBy('sort', 'ASC')->get();
        if(count($data)){
            //转成数组
            $data = $data -> toArray();
            //获取树形结构数据
            $return_data = getTreeData($data, 'id', 'pid', 'display_name');
        }
        return $return_data;
    }

    /**
     * 获取树形结构
     * @param int $pid
     * @param array $data
     * @param int $depth
     * @return array
     */
//    private function getTree($pid = 0, &$data = [], $depth = 0)
//    {
//        if($pid != 0){
//            ++$depth;
//        }
//
//        //查询该菜单节点子集
//        $sub_list = $this -> searchPidSubData($pid, $data);
//
//        //判断子集是否为空
//        if(empty($sub_list) && count($sub_list) <= 0){
//            return $data;
//        }
//
//        //遍历子集
//        foreach($sub_list as $k => $item){
//            $item['depth'] = $depth;
//
//            if($depth > 0){
//                $_name = str_repeat('&nbsp;', 8 * $depth).'|---'.$item['display_name'];
//            } else {
//                $_name = $item['display_name'];
//            }
//            $item['_name'] = $_name;
//            $data[] = $item;
//            $this -> getTree($item['id'], $data, $depth);
//        }
//
//        return $data;
//    }

    /**
     * 获取树形结构
     * @param $data
     * @param int $pid
     * @return array
     */
//    public function getSubTree(&$data, $pid = 0)
//    {
//        //查询该子集数据
//        $list = $this -> searchPidSubData($pid, $data);
//
//        foreach ($list as $k => $item){
//            $list[$k]['data'] = $this -> getSubTree($data, $item['id']);
//        }
//        return $list;
//    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function($permission) {
            dd(122);
            if (!method_exists(Config::get('entrust.permission'), 'bootSoftDeletes')) {
                $permission->roles()->sync([]);
            }

            return true;
        });
    }

    /**
     * 查询子集数据
     * @param $pid
     * @param $data
     * @return array
     */
//    private function searchPidSubData($pid, &$data)
//    {
//        $sub_data = [];
//        if(empty($data)) return $sub_data;
//
//        foreach ($data as $k => $v)
//        {
//            if($v['pid'] == $pid){
//                $sub_data[] = $v;
//                unset($data[$k]);
//            }
//        }
//        return $sub_data;
//    }

}
