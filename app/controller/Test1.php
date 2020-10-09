<?php
namespace app\controller;

use app\BaseController;
use app\common\lib\Show;
use app\common\model\Jskcb;
use app\common\model\Jsxxb;
use app\common\model\Jsxxb1;
use app\common\model\Xskcb;
use app\common\model\Xsxxb;
use app\common\model\Xsxxb1;

class Test1 extends BaseController
{
    public function index()
    {
        $res = [];
//        $xh = '0304150226';
//        $res = (new Xskcb())->getListByXH($xh);
//
//        $zgh = '01032';
//        $res = (new Jskcb())->getListByZGH($zgh);
//
//        $xh = '0304150226';
//        $res = (new Xsxxb())->getByXH($xh);
//
//        $zgh = '01032';
//        $res = (new Jsxxb())->getByZGH($zgh);
        return Show::success($res);
    }
}
