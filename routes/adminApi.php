<?php
/**
 * 后台Api路由
 * Author: flycorn
 * Email: ym1992it@163.com
 * Date: 2017/3/5
 * Time: 16:30
 */

Route::post('login', 'LoginController@login'); //登录处理

Route::group(['middleware' => ['fcAdmin.login:admin', 'fcAdmin.auth']], function () {

    //个人中心
    Route::post('ucenter/edit', 'UcenterController@edit'); //修改个人资料
    Route::post('ucenter/password', 'UcenterController@password'); //修改密码
    Route::post('upload/image', 'UploadController@image'); //上传图片

    //管理员
    Route::name('admin.adminUser.index')->get('adminUser', 'AdminUserController@index');
    Route::name('admin.adminUser.create')->post('adminUser', 'AdminUserController@store');
    Route::name('admin.adminUser.edit')->put('adminUser/{id}', 'AdminUserController@update');
    Route::name('admin.adminUser.delete')->delete('adminUser/{id}', 'AdminUserController@destroy');

    //权限
    Route::name('admin.adminPermission.index')->get('adminPermission', 'AdminPermissionController@index');
    Route::name('admin.adminPermission.index')->get('adminPermission/{id}', 'AdminPermissionController@index');
    Route::name('admin.adminPermission.create')->post('adminPermission', 'AdminPermissionController@store');
    Route::name('admin.adminPermission.edit')->put('adminPermission/{id}', 'AdminPermissionController@update');
    Route::name('admin.adminPermission.delete')->delete('adminPermission/{id}', 'AdminPermissionController@destroy');

    //角色
    Route::name('admin.adminRole.auth')->put('adminRole/{id}/auth', 'AdminRoleController@auth'); //角色授权
    Route::name('admin.adminRole.index')->get('adminRole', 'AdminRoleController@index');
    Route::name('admin.adminRole.create')->post('adminRole', 'AdminRoleController@store');
    Route::name('admin.adminRole.edit')->put('adminRole/{id}', 'AdminRoleController@update');
    Route::name('admin.adminRole.delete')->delete('adminRole/{role}', 'AdminRoleController@destroy');

    /**
     * other modules
     */
    
});
