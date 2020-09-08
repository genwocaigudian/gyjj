<?php

namespace app\admin\validate;

use think\Validate;

class News extends Validate
{
    protected $rule = [
        'title' => 'require',
        'cate_id' => 'require',
        'content' => 'require',
    ];
    
    protected $message = [
        'name' => 'name不可为空',
        'cate_id' => 'cate_id不可为空',
        'content' => 'content不可为空',
    ];
    
    protected $scene = [
        'save' => ['title', 'cate_id', 'content'],
    ];
}
