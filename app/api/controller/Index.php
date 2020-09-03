<?php
namespace app\api\controller;

use app\BaseController;
use app\common\lib\Num;

class Index extends BaseController
{
    public function index()
    {
        echo 'hehe';
    }
	
	public function redis()
	{
		$code = Num::getCode(6);
		cache(config('redis.code_pre').'18855479876', $code, config('redis.code_expire'));
		echo 'redis';
    }
}
