<?php

namespace app\admin\validate;

use think\Validate;

class Rule extends Validate
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
        'save' => ['name'],
        'give' => ['id'],
        'update' => ['name'],
        'delete' => ['name'],
    ];
}
