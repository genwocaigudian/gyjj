<?php

namespace app\api\validate;

use think\Validate;

class Repair extends Validate
{
    protected $rule = [
        'title' => 'require',
        'repair_cate_id' => 'require',
    ];
    
    protected $message = [
        'title' => 'title不可为空',
        'repair_cate_id' => 'repair_cate_id不可为空',
    ];
    
    protected $scene = [
        'save' => ['title', 'repair_cate_id'],
    ];
}
