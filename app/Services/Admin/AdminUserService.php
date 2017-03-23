<?php

/**
 * 后台用户服务
 * Author: flycorn
 * Email: ym1992it@163.com
 * Date: 2017/3/5
 * Time: 17:32
 */

namespace App\Services\Admin;

use App\Events\AdminLoggerEvent;
use App\Models\Admin\AdminUser;
use Illuminate\Support\Facades\Auth;
use App\Services\DataTableService;
use App\Services\FcAdminService;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;

class AdminUserService extends FcAdminService
{
    use DataTableService;

    private $adminUser;

    public function __construct(AdminUser $adminUser)
    {
        //依赖注入model
        $this->adminUser = $adminUser;
    }

    /**
     * 登录服务
     * @param $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function login($request)
    {
        //表单数据
        $form_data = $request->except('_token', 'captcha');

        $user = $form_data['user'];
        unset($form_data['user']);
        unset($form_data['remember']);

        if(isEmail($user)){
            //邮箱
            $form_data['email'] = $user;

            $res = Auth::guard('admin')->attempt($form_data, $request->has('remember'));
        } else{
            //用户名
            $form_data['name'] = $user;

            $res = Auth::guard('admin')->attempt($form_data, $request->has('remember'));
        }

        //验证结果
        if(!$res) return $this->handleError('密码不正确!', 'password');

        //session
        $request->session()->regenerate();

        //跳转至后台首页
        return $this->handleSuccess('登录成功 , 欢迎 '.$this->loggedUser()->nickname.' !');
    }

    /**
     * 退出
     * @param $request
     */
    public function logout()
    {
        Auth::guard('admin')->logout();

        request()->session()->flush();

        request()->session()->regenerate();

        return $this->handleSuccess('退出成功!');
    }

    /**
     * 获取登录的用户
     */
    public static function loggedUser()
    {
        return Auth::guard('admin')->user();
    }

    /**
     * 个人中心修改资料
     * @param array $form_data
     * @return \Illuminate\Http\RedirectResponse
     */
    public function ucenterEdit(array $form_data)
    {
        //头像
        if(empty($form_data['avatar'])){
            unset($form_data['avatar']);
        } else {
            //移动临时头像文件
            $new_path = 'upload/admin/avatar/'.$this->loggedUser()->id;
            $new_file = moveFile($form_data['avatar'], $new_path);
            if($new_file) $form_data['avatar'] = $new_file;
        }

        //修改用户数据
        $admin = $this->loggedUser();
        foreach($form_data as $k => $v){
            $admin -> $k = $v;
        }
        $res = $admin -> save();

        if(!$res) return $this->handleError('修改资料失败!');

        return $this->handleSuccess('修改资料成功!');
    }

    /**
     * 个人中心重置密码
     * @param array $form_data
     * @return \Illuminate\Http\RedirectResponse
     */
    public function ucenterPassword(array $form_data)
    {
        $admin = $this->loggedUser();

        //验证密码
        $res = Auth::guard('admin')->validate($form_data);
        if(!$res) return $this->handleError('原密码有误!');

        //重置密码
        $res = $admin->forceFill([
            'password' => bcrypt($form_data['new_password']),
            'remember_token' => Str::random(60),
        ])->save();

        if(!$res) return $this->handleError('密码修改失败!');

        Auth::guard('admin')->login($admin);

        return $this->handleSuccess('密码修改成功!');
    }

    /**
     * 获取管理员信息
     * @param $id
     * @return mixed
     */
    public function adminData($id)
    {
        return $this->adminUser->find($id);
    }

    /**
     * 管理员角色
     * @param $admin
     * @return mixed
     */
    public function adminRoles($admin)
    {
        return $admin->roles()->get();
    }

    /**
     * 创建管理员
     * @param array $form_data
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createAdmin(array $form_data)
    {
        //绑定角色
        $role_ids = [];
        if(isset($form_data['role_ids'])){
            $role_ids = $form_data['role_ids'];
            unset($form_data['role_ids']);
        }

        //头像
        $avatar = !empty($form_data['avatar']) ? $form_data['avatar'] : null;
        unset($form_data['avatar']);

        //加密密码
        $form_data['password'] = bcrypt($form_data['password']);

        //创建账号
        foreach ($form_data as $k => $v){
            $this->adminUser->$k = $v;
        }
        $res = $this->adminUser->save();

        if(!$res) return $this->handleError('添加失败');

        //头像
        if(!empty($avatar)){
            //移动临时头像文件
            $new_path = 'upload/admin/avatar/'.$this->adminUser->id;
            $new_file = moveFile($avatar, $new_path);
            if($new_file) $avatar = $new_file;
            $this->adminUser->avatar = $avatar;
        }

        //授权角色
        $this->adminUser->authorizeRoles($role_ids);

        //触发事件
        Event::fire(new AdminLoggerEvent('创建了管理员 [ ID:'.$this->adminUser->id.', 用户名:'.$this->adminUser->name.', 昵称:'.$this->adminUser->nickname.' ]'));

        //创建结果
        return $this->handleSuccess('添加成功!');
    }

    /**
     * 编辑管理员
     * @param $id
     * @param array $form_data
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editAdmin($id, array $form_data)
    {
        $admin = $this->adminData($id);
        //验证
        if(empty($admin)) return $this->handleError('该管理员不存在!');

        //绑定角色
        $role_ids = isset($form_data['role_ids']) ? $form_data['role_ids'] : [];
        unset($form_data['role_ids']);

        //判断是否需要修改密码
        if(empty($form_data['password'])){
            unset($form_data['password']);
        } else {
            $form_data['password'] = bcrypt($form_data['password']);
        }
        //头像
        if(empty($form_data['avatar'])){
            unset($form_data['avatar']);
        } else {
            //移动临时头像文件
            $new_path = 'upload/admin/avatar/'.$id;
            $new_file = moveFile($form_data['avatar'], $new_path);
            if($new_file) $form_data['avatar'] = $new_file;
        }

        //更新数据
        $form_data['updated_at'] = date('Y-m-d H:i:s');
        $res = $this->adminUser->where('id', $id)->update($form_data);
        if(!$res) return $this->handleError('编辑失败!');

        //授权角色
        $admin->authorizeRoles($role_ids);

        //触发事件
        Event::fire(new AdminLoggerEvent('修改了管理员 [ ID:'.$admin->id.', 用户名:'.$admin->name.', 昵称:'.$form_data['nickname'].' ]'));

        //修改成功
        return $this->handleSuccess('编辑成功!');
    }

    /**
     * 删除管理员
     * @param $user
     * @return mixed
     */
    public function deleteAdmin($id)
    {
        $admin = $this->adminData($id);
        //验证
        if(empty($admin)){
            return $this->handleError('该管理员不存在!');
        } else if($admin->id == 1){
            return $this->handleError('超级管理员不能删除!');
        }

        //头像
        $avatar = $admin->avatar;
        $res = $admin -> delete();
        if(!$res) $this->handleError('删除失败!');

        //授权角色
        $admin->authorizeRoles([]);

        //删除图片
        if($avatar != 'upload/admin/avatar/default/avatar.png') removeFile($avatar);

        //触发事件
        Event::fire(new AdminLoggerEvent('删除了管理员 [ ID:'.$admin->id.', 用户名:'.$admin->name.', 昵称:'.$admin->nickname.' ]'));

        return $this->handleSuccess('删除成功!');
    }

}