<?php


namespace app\api\controller;

use app\common\services\User as UserServices;
use app\api\validate\User as UserValidate;

class User extends AuthBase
{
	public function index()
	{
		$user = (new UserServices())->getNormalUserById($this->userId);
		$result = [
			'id' => $this->userId,
			'username' => $user['nickname'],
		];
		return show(config('status.success'), 'ok', $result);
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
			return show(config('status.error'), $validate->getError());
		}
		
		$user = (new UserServices())->update($this->userId, $data);
		
		if (!$user) {
			return show(config('status.error'), '更新失败');
		}
		return show(config('status.success'), 'ok');
	}
}