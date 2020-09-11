<?php

namespace app\admin\validate;

use think\Validate;

class Role extends Validate
{
    protected $rule = [
        'id' => 'require',
        'name' => 'require',
    ];
    
    protected $message = [
        'id' => 'id不可为空',
        'name' => 'name不可为空',
    ];
    
    protected $scene = [
        'save' => ['id', 'name'],
        'update' => ['name'],
        'delete' => ['name'],
    ];
}
