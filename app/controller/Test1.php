<?php
namespace app\controller;

use app\BaseController;
use app\common\lib\Show;
use app\common\model\Jsxxb;
use app\common\model\Xsxxb;

class Test1 extends BaseController
{
    public function index()
    {
        $xh = '0304150226';
        $res = (new Xsxxb())->getByXH($xh);

        $zgh = '01032';
        $res = (new Jsxxb())->getByZGH($zgh);
        return Show::success($res);
    }
}
