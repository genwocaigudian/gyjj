<?php

namespace app\api\validate;

use think\Validate;

class Lottery extends Validate
{
    protected $rule = [
        'id' => 'require',
    ];
    
    protected $message = [
        'id' => 'id不可为空',
    ];
    
    protected $scene = [
        'save' => ['id'],
    ];
}
