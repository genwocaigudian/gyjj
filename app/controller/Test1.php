<?php
namespace app\controller;

use app\BaseController;
use app\common\lib\Show;
use app\common\model\Jskcb;
use app\common\model\Jsxxb;
use app\common\model\Xskcb;
use app\common\model\Xsxxb;

class Test1 extends BaseController
{
    public function index()
    {
        $xh = '0304150226';
        $res = (new Xskcb())->getListByXH($xh);

        $zgh = '01032';
        $res = (new Jskcb())->getListByZGH($zgh);
        return Show::success($res);
    }
}
