<?php
/**
 * 全局工具类
 * Author: flycorn
 * Email: yuming@flycorn.com
 * Date: 16/9/13
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
 * 创建后台导航菜单
 * @param $data
 * @param $open_menu
 */
if(!function_exists('createBackstageMenu')){
    function createBackstageMenu($data, $open_menu){
        if(!empty($data)){
            echo '<ul class="treeview-menu">';
            foreach ($data as $k => $item){
                echo '<li ';
                if(in_array($item['id'], $open_menu)){
                    echo ' class="active" ';
                }
                echo '><a href="'.$item['url'].'"><i class="fa fa-circle-o"></i>'.$item['display_name'];
                if(!empty($item['data'])){
                    echo '<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>';
                }
                echo '</a>';
                if(!empty($item['data'])){
                    createBackstageMenu($item['data'], $open_menu);
                }
                echo '</li>';
            }
            echo '</ul>';
        }
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
                echo '"><i class="fa '.$item['icon'].'"></i> '.$item['display_name'].'</a></li>';
            }
            echo '</ol>';
        }
    }
}