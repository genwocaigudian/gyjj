<?php


namespace app\admin\controller;


use app\common\model\AdminUser as AdminUserModel;
use think\facade\View;
use app\admin\validate\AdminUser as AdminUserValidate;

class Login extends AdminBase
{
	public function index()
	{
		return View::fetch();
	}
	
	public function md5()
	{
		dump(session(config('admin.session_admin')));exit();
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