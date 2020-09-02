<?php
namespace app\api\controller;

use app\BaseController;

class Index extends BaseController
{
    public function index()
    {
        echo 'hehe';
    }
	
	public function redis()
	{
		$code = rand(100000, 999999);
		cache(config('redis.code_pre').'18855479876', $code, config('redis.code_expire'));
		echo 'redis';
    }
}
