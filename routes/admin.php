<?php
/**
 * 后台路由
 * Author: flycorn
 * Email: ym1992it@163.com
 * Date: 2017/3/5
 * Time: 16:30
 */

//登录
Route::get('captcha', 'CaptchaController@index'); //登录验证码
Route::get('login', 'LoginController@index'); //登录页
Route::get('logout', 'LoginController@logout'); //退出

Route::group(['middleware' => ['fcAdmin.login:admin', 'fcAdmin.permission', 'fcAdmin.auth']], function () {

    //基础路由
    Route::get('/', 'IndexController@index');
    Route::get('ucenter', 'UcenterController@index'); //个人中心

    //系统管理
    Route::name('admin.system.index')->get('system', 'SystemController@index');

    //管理员管理
    Route::name('admin.adminUser.index')->get('adminUser', 'AdminUserController@index');
    Route::name('admin.adminUser.create')->get('adminUser/create', 'AdminUserController@create');
    Route::name('admin.adminUser.edit')->get('adminUser/{id}/edit', 'AdminUserController@edit');
    Route::name('admin.adminUser.show')->get('adminUser/{id}', 'AdminUserController@show');

    //权限管理
    Route::name('admin.adminPermission.index')->get('adminPermission', 'AdminPermissionController@index');
    Route::name('admin.adminPermission.create')->get('adminPermission/create', 'AdminPermissionController@create');
    Route::name('admin.adminPermission.index')->get('adminPermission/{id}', 'AdminPermissionController@index');
    Route::name('admin.adminPermission.create')->get('adminPermission/{id}/create', 'AdminPermissionController@create');
    Route::name('admin.adminPermission.show')->get('adminPermission/{id}/show', 'AdminPermissionController@show');
    Route::name('admin.adminPermission.edit')->get('adminPermission/{id}/edit', 'AdminPermissionController@edit');

    //角色管理
    Route::name('admin.adminRole.auth')->get('adminRole/{id}/auth', 'AdminRoleController@auth'); //角色授权
    Route::name('admin.adminRole.index')->get('adminRole', 'AdminRoleController@index');
    Route::name('admin.adminRole.create')->get('adminRole/create', 'AdminRoleController@create');
    Route::name('admin.adminRole.show')->get('adminRole/{id}', 'AdminRoleController@show');
    Route::name('admin.adminRole.edit')->get('adminRole/{id}/edit', 'AdminRoleController@edit');

    /**
     * other modules
     */
    
});

