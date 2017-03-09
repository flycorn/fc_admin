<?php
/**
 * FcAdmin后台全局工具
 * Author: flycorn
 * Email: ym1992it@163.com
 * Date: 2017/3/5
 * Time: 17:32
 */

/**
 * 获取盐
 * @param $length 长度
 */
if(!function_exists('getSalt')){
    function getSalt($length = -5)
    {
        return substr(uniqid(rand()), $length);
    }
}

/**
 * 后台认证授权
 */
if(!function_exists('adminAuth')){
    function adminAuth($rule)
    {
        $admin = auth('admin')->user();
        if(empty($admin)) return false;
        //验证是否超级管理员
        if($admin->id === 1) return true;
        return \Illuminate\Support\Facades\Gate::forUser($admin)->check($rule);
    }
}

/**
 * 验证是否为邮箱格式
 * @param $email 邮箱
 */
if(!function_exists('isEmail')){
    function isEmail($email = '')
    {
        if(!empty($email)) {
            $email_rule = "/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/";
            if (preg_match($email_rule, $email)) {
                return true;
            }
        }
        return false;
    }
}

/**
 * 检测目录
 * @param $dir_name 文件夹名称
 */
if(!function_exists('checkDir')){
    function checkDir($dir_name){
        if(!empty($dir_name) && $dir_name != '.'){
            $dir_arr = array();
            $dir_arr = explode('/',$dir_name);
            //遍历
            $tmp_dir = '';
            foreach($dir_arr as $k => $v){
                $tmp_dir .= $v.'/';

                //判断文件夹是否存在
                if(!file_exists($tmp_dir)){
                    @mkdir($tmp_dir); //创建文件夹
                }
            }
        }
    }
}

/**
 * 移动文件
 * @param $file 原文件
 * @param $path 新目录
 * @return bool
 */
if(!function_exists('moveFile')){
    function moveFile($file, $path)
    {
        if(!empty($file) && !empty($path)){
            //验证文件是否存在
            if(\Illuminate\Support\Facades\File::exists($file)){
                $path = rtrim($path, PATHINFO_BASENAME);
                //检测文件夹是否存在
                checkDir($path);
                $file_name = pathinfo($file, PATHINFO_BASENAME);
                $bool = \Illuminate\Support\Facades\File::move($file, $path.DIRECTORY_SEPARATOR.$file_name);
                return $bool ? $path.DIRECTORY_SEPARATOR.$file_name : $bool;
            }
        }
        return false;
    }
}

/**
 * 删除文件
 * @param $file 文件
 * @return bool
 */
if(!function_exists('removeFile')){
    function removeFile($file)
    {
        if(!empty($file)) {
            //验证文件是否存在
            if (\Illuminate\Support\Facades\File::exists($file)) {
                return \Illuminate\Support\Facades\File::delete($file);
            }
        }
        return false;
    }
}

/**
 * 获取树形结构数据
 * @param array $data
 * @param $field
 * @param $parent_field
 * @param $depth_field
 * @param int $value
 * @param int $depth
 * @return array
 */
if(!function_exists('getTreeData')){
    function getTreeData(&$data = [], $field, $parent_field, $depth_field, $value = 0, $depth = 0)
    {
        if($value != 0){
            ++$depth;
        }

        //查询该数据的子集
        $sub_list = searchParentSubData($data, $parent_field, $value);

        //判断子集是否为空
        if(empty($sub_list) && count($sub_list) <= 0){
            return $data;
        }

        //遍历子集
        foreach($sub_list as $k => $item){
            $item['depth'] = $depth;

            if($depth > 0){
                $_name = str_repeat('&nbsp;', 8 * $depth).'|---'.$item[$depth_field];
            } else {
                $_name = $item[$depth_field];
            }
            $item['_'.$depth_field] = $_name;
            $data[] = $item;
            getTreeData($data, $field, $parent_field, $depth_field, $item[$field], $depth);
        }

        return $data;
    }
}

/**
 * 获取子集树形结构数据
 * @param $data
 * @param $field
 * @param int $value
 * @param string $sub_field
 * @return array
 */
if(!function_exists('getSubTreeData')){
    function getSubTreeData(&$data, $field, $value = 0, $sub_field = 'data')
    {
        //查询该子集数据
        $list = searchParentSubData($data, $field, $value);

        foreach ($list as $k => $item){
            $list[$k][$sub_field] = getSubTreeData($data, $field, $item['id']);
        }
        return $list;
    }
}

/**
 * 查询父集中的子集数据
 * @param $field
 * @param $value
 * @param $data
 * @return array
 */
if(!function_exists('searchParentSubData')){
    function searchParentSubData(&$data, $field, $value)
    {
        $sub_data = [];
        if(empty($data)) return $sub_data;

        foreach ($data as $k => $v)
        {
            if($v[$field] == $value){
                $sub_data[] = $v;
                unset($data[$k]);
            }
        }
        return $sub_data;
    }
}

/**
 * 获取所有上级数据
 * @param $data
 * @param string $field
 * @param $value
 * @return array
 */
if(!function_exists('getParentData')){
    function getParentData(&$data, $field = 'pid', $value)
    {
        $return_data = [];
        if(!empty($data)){
            foreach ($data as $key => $val){
                if($key == $value){
                    $return_data[] = $val;
                    unset($data[$key]);
                    $tmp = getParentData($data, $field, $val[$field]);
                    $return_data = array_merge($return_data, $tmp);
                }
            }
        }

        return $return_data;
    }
}



/**
 * 创建后台导航菜单
 * @param $data
 * @param $openMenus
 */
if(!function_exists('createAdminMenu')){
    function createAdminMenu($data, $openMenus, $type = 0){
        if(!empty($data)){
            if($type > 0) echo '<ul class="treeview-menu">';
            foreach ($data as $k => $item){
                echo '<li ';
                echo ' class="';
                if($item['pid']==0) echo 'treeview';
                if(in_array($item['id'], $openMenus)){
                    echo ' active ';
                }
                echo '" ';
                echo '><a href="'.$item['url'].'"><i class="fa '.$item['icon'].'"></i>';
                echo $item['pid'] == 0 ? '<span>'.$item['name'].'</span>' : $item['name'];
                if(!empty($item['data'])){
                    echo '<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>';
                }
                echo '</a>';
                if(!empty($item['data'])){
                    $type++;
                    createAdminMenu($item['data'], $openMenus, $type);
                }
                echo '</li>';
            }
            if($type > 0) echo '</ul>';
        }
    }
}

/**
 * 根据对应的字段值查询数据
 * @param array $data
 * @param $field
 * @param $value
 * @return array
 */
if(!function_exists('searchDataByFieldValue')){
    function searchDataByFieldValue($data, $field, $value)
    {
        if(empty($data)) return [];
        foreach ($data as $k => $item){
            if($item[$field] != $value){
                unset($data[$k]);
            }
        }
        return $data;
    }
}

/**
 * 创建面包屑导航
 * @param array $data
 */
if(!function_exists('createBreadCrumb')){
    function createBreadCrumb($data)
    {
        if(!empty($data)){
            echo '<ol class="breadcrumb">';
            foreach ($data as $k => $item)
            {
                echo '<li><a href="';
                if(count($data) != ($k+1)){
                    echo $item['url'];
                } else {
                    echo 'javascript:;';
                }
                echo '"><i class="fa '.$item['icon'].'"></i> '.$item['name'].'</a></li>';
            }
            echo '</ol>';
        }
    }
}