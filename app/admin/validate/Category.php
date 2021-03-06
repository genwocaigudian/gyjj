<?php

namespace app\admin\validate;

use think\Validate;

class Category extends Validate
{
    protected $rule = [
        'name' => 'require',
    ];
    
    protected $message = [
        'name' => 'name不可为空',
    ];
    
    protected $scene = [
        'save' => ['name'],
        'update' => ['name'],
    ];
}
