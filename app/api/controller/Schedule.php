<?php

namespace app\api\controller;

use app\common\lib\Arr;
use app\common\lib\Show;
use app\common\model\Jskcb;
use app\common\model\Xskcb;
use think\response\Json;

//课程表
class Schedule extends AuthBase
{
    /**
     * @return Json
     */
    public function index()
    {
        switch ($this->type) {
            case 2:
                $model = new Xskcb();
                break;
            default:
                $model = new Jskcb();
                break;
        }
        try {
            $list = $model->getGroup();
        } catch (\Exception $e) {
            $list = [];
        }

        return Show::success($list);
    }
}
