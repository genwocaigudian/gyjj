<?php
namespace app\controller;

use app\BaseController;
use app\common\lib\Show;
use app\common\services\News;

class Index extends BaseController
{
    public function index()
    {
        return Show::success();
    }

    public function test()
    {
        $res = (new News())->rssSync(2);
        return Show::success([$res]);
    }
}
