<?php
/**
 * 后台路由配置
 * Author: flycorn
 * Email: yuming@flycorn.com
 * Date: 16/9/12
 */

//无需登录
Route::any('/login', 'LoginController@index'); //后台登录
Route::get('/captcha', 'CaptchaController@index'); //后台验证码

//需登录
Route::group(['middleware' => ['admin.login', 'admin.permission', 'admin.auth']], function(){
    //基础
    Route::get('/', 'IndexController@index'); //主页
    Route::get('quit', 'LoginController@quit'); //退出
    Route::get('ucenter', 'UcenterController@index'); //个人中心
    Route::post('edit', 'UcenterController@edit'); //修改个人资料
    Route::post('password', 'UcenterController@password'); //修改密码
    Route::post('upload/image', 'UploadController@image'); //上传图片
    //管理员管理
    Route::get('manager/user', 'ManagerController@index')->name('admin.manager.user');
    //管理员管理
    Route::get('manager', 'ManagerController@index')->name('admin.manager.index');
    Route::get('manager/create', 'ManagerController@create')->name('admin.manager.create');
    Route::get('manager/{manager}/edit', 'ManagerController@edit')->name('admin.manager.edit');
    Route::get('manager/{manager}', 'ManagerController@show')->name('admin.manager.show');
    Route::post('manager', 'ManagerController@store')->name('admin.manager.create');
    Route::put('manager/{manager}', 'ManagerController@update')->name('admin.manager.edit');
    Route::delete('manager/{manager}', 'ManagerController@destroy')->name('admin.manager.delete');
    //权限管理
    Route::get('permission', 'PermissionController@index')->name('admin.permission.index');
    Route::get('permission/create', 'PermissionController@create')->name('admin.permission.create');
    Route::get('permission/{permission}', 'PermissionController@index')->name('admin.permission.index');
    Route::get('permission/{permission}/create', 'PermissionController@create')->name('admin.permission.create');
    Route::get('permission/{permission}/show', 'PermissionController@show')->name('admin.permission.show');
    Route::get('permission/{permission}/edit', 'PermissionController@edit')->name('admin.permission.edit');
    Route::post('permission', 'PermissionController@store')->name('admin.permission.create');
    Route::put('permission/{permission}', 'PermissionController@update')->name('admin.permission.edit');
    Route::delete('permission/{permission}', 'PermissionController@destroy')->name('admin.permission.delete');
    //角色管理
    Route::any('role/{role}/auth', 'RoleController@auth')->name('admin.role.auth'); //角色授权
    Route::get('role', 'RoleController@index')->name('admin.role.index');
    Route::get('role/create', 'RoleController@create')->name('admin.role.create');
    Route::get('role/{role}', 'RoleController@show')->name('admin.role.show');
    Route::get('role/{role}/edit', 'RoleController@edit')->name('admin.role.edit');
    Route::post('role', 'RoleController@store')->name('admin.role.create');
    Route::put('role/{role}', 'RoleController@update')->name('admin.role.edit');
    Route::delete('role/{role}', 'RoleController@destroy')->name('admin.role.delete');


    /**
     * other modules to do
     */


});


