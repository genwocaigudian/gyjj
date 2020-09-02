<?php


namespace app\admin\controller;


use app\BaseController;
use think\exception\HttpResponseException;

class AdminBase extends BaseController
{
	public $adminUser = null;
	
	public function isLogin()
	{
		$this->adminUser = session(config('admin.session_admin'));
		if (empty($this->adminUser)) {
			return false;
		}
		return true;
	}
}