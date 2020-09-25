<?php
namespace app\controller;

use app\BaseController;
use app\common\lib\Show;
use app\common\model\Xsxxb;

class Test1 extends BaseController
{
    public function index()
    {
        $xh = '0304150226';
        $res = (new Xsxxb())->getByXh($xh);
        return Show::success($res);
    }
}
