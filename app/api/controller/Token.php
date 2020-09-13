<?php


namespace app\api\controller;


use app\BaseController;
use app\common\lib\Show;
use app\common\services\UserCode;
use app\common\services\UserToken;

class Token extends BaseController
{
	//获取code
	public function code()
	{
		$code = input('code', '', 'trim');
		if (!$code) {
			$url = UserCode::getCode();
			return redirect($url);
		}
		
		return Show::success(['code' => $code]);
	}
	
	//获取token
	public function get()
	{
		$code = input('code', '', 'trim');
		if (!$code) {
			return Show::error('code不可为空');
		}
		$ut = new UserToken($code);
		$token = $ut->getToken();
		return Show::success(['token' => $token]);
	}
}