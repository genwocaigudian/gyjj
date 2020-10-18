<?php

namespace app\admin\validate;

use think\Validate;

class Repair extends Validate
{
    protected $rule = [
        'name' => 'require',
        'repair_cate_id' => 'require',
    ];
    
    protected $message = [
        'name' => 'name不可为空',
        'repair_cate_id' => 'repair_cate_id不可为空',
    ];
    
    protected $scene = [
        'save' => ['name'],
        'update' => ['name'],
        'delete' => ['name'],
        'export' => ['repair_cate_id'],
    ];
}
