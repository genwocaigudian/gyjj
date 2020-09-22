<?php

namespace app\admin\validate;

use think\Validate;

class Lottery extends Validate
{
    protected $rule = [
        'title' => 'require',
    ];
    
    protected $message = [
        'title' => 'title不可为空',
    ];
    
    protected $scene = [
        'save' => ['title'],
        'update' => ['title'],
        'delete' => ['title'],
    ];
}
