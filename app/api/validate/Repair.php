<?php

namespace app\api\validate;

use think\Validate;

class Repair extends Validate
{
    protected $rule = [
        'repair_cate_id' => 'require',
    ];
    
    protected $message = [
        'repair_cate_id' => 'repair_cate_id不可为空',
    ];
    
    protected $scene = [
        'save' => ['repair_cate_id'],
    ];
}
