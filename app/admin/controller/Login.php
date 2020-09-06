<?php
namespace app\admin\controller;

use app\admin\services\AdminUser as AdminUserServices;
use app\admin\validate\AdminUser as AdminUserValidate;
use app\common\lib\Show;

class Login extends AdminBase
{
    public function index()
    {
        if (!$this->request->isPost()) {
            return Show::error('非法请求');
        }
        $data = input('post.');
    
        $validate = new AdminUserValidate();
        if (!$validate->scene('login')->check($data)) {
            return Show::error($validate->getError());
        }
    
        try {
            $result = (new AdminUserServices())->login($data);
        } catch (\Exception $e) {
            return Show::error($e->getMessage(), $e->getCode());
        }
        if ($result) {
            return Show::success($result, '登陆成功');
        }
        return Show::error('登陆失败');
    }
    
    public function md5()
    {
        dump(session(config('admin.session_admin')));
        exit();
        echo md5('admin_gyjj');
    }
    
    public function check()
    {
        if (!$this->request->isPost()) {
            return show(config('status.error'), '请求方式错误');
        }
        
        $username = $this->request->param('username', '', 'trim');
        $password = $this->request->param('password', '', 'trim');
        $captcha = $this->request->param('captcha', '', 'trim');
        
        $data = [
            'username' => $username,
            'password' => $password,
            'captcha' => $captcha,
        ];
        $validate = new AdminUserValidate();
        if (!$validate->check($data)) {
            return show(config('status.error'), $validate->getError());
        }
        if (empty($username) || empty($password) || empty($captcha)) {
            return show(config('status.error'), '参数不能为空');
        }

        //		if (!captcha_check($captcha)) {
        //			return show(config('status.error'), '验证码错误'.$captcha);
        //		}
        
        try {
            $adminUserModel = new AdminUserModel();
            $adminUser = $adminUserModel->getAdminUserByUsername($username);
            if (empty($adminUser) || $adminUser->status != config('status.mysql.table_normal')) {
                return show(config('status.error'), '用户不存在');
            }
            $adminUser = $adminUser->toArray();
            if ($adminUser['password'] != md5($password.'_gyjj')) {
                return show(config('status.error'), '密码错误');
            }
            
            $updateData = [
                'last_login_time' => time(),
                'last_login_ip' => $this->request->ip(),
                'update_time' => time(),
            ];
            
            $res = $adminUserModel->updateById($adminUser['id'], $updateData);
            if (empty($res)) {
                return show(config('status.error'), '登录失败');
            }
        } catch (\Exception $e) {
            // todo 记录日志 $e->getMessage();
            return show(config('status.error'), '内部异常');
        }
        
        session(config('admin.session_admin'), $adminUser);
        
        return show(config('status.success'), '登陆成功');
    }
}
