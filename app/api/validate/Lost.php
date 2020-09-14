<?php

namespace app\api\validate;

use think\Validate;

class Lost extends Validate {
	protected $rule = [
		'title' => 'require',
	];
	
	protected $message = [
		'title' => 'title不可为空',
	];
	
	protected $scene = [
		'save' => ['title'],
	];
}