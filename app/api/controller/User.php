<?php


namespace app\api\controller;

use app\common\lib\Show;
use app\common\services\AdminUser as UserServices;
use app\api\validate\AdminUser as UserValidate;

class User extends AuthBase
{
	public function index()
	{
		$user = (new UserServices())->getNormalUserById($this->userId);
		$result = [
			'id' => $this->userId,
			'username' => $user['nickname'],
		];
		return Show::success($result);
	}
	
	public function update()
	{
		$username = input('param.username', '', 'trim');
		$sex = input('param.sex', 0, 'intval');
		
		$data = [
			'username' => $username,
			'sex' => $sex,
		];
		
		$validate = new UserValidate();
		if (!$validate->scene('update_user')->check($data)) {
			return Show::error($validate->getError());
		}
		
		$user = (new UserServices())->update($this->userId, $data);
		
		if (!$user) {
			return Show::error('更新失败');
		}
		return Show::success();
	}
}