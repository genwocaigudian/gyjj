<?php
namespace app\controller;

use app\BaseController;
use app\common\lib\Show;
use app\common\model\BookBorrow;
use app\common\model\BookInfo;
use app\common\model\BookReturn;

class Book extends BaseController
{
    public function index()
    {
        $res = (new BookInfo())->get();

//        $res = (new BookBorrow())->get();

//        $res = (new BookReturn())->get();
        return Show::success($res);
    }
}
