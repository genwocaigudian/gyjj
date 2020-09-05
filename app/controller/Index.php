<?php
namespace app\controller;

use app\BaseController;
use app\common\lib\Show;

class Index extends BaseController
{
    public function index()
    {
        return Show::success();
    }
}
