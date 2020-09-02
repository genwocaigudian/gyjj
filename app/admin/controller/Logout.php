<?php


namespace app\admin\controller;


use app\common\model\AdminUser;
use think\facade\View;

class Logout extends AdminBase
{
	public function index()
	{
		session(config("admin.session_admin"), null);
		return redirect(url("login/index"));
	}
}