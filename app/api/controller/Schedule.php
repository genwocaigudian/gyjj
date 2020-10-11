<?php

namespace app\api\controller;

use app\api\validate\Schedule as ScheduleValidate;
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
        $data = input('param.');

        $validate = new ScheduleValidate();
        if (!$validate->scene('index')->check($data)) {
            return Show::error($validate->getError());
        }

        $xq = $data['xq'];
        $xn = $data['xn'];
        switch ($this->type) {
            case 2:
                $model = new Xskcb();
                break;
            default:
                $model = new Jskcb();
                break;
        }
        try {
            $user = (new \app\common\services\User())->getNormalUserById($this->userId);
            $list = $model->getList($user['number'], $xn, $xq);
        } catch (\Exception $e) {
            $list = [];
        }

        $list = Arr::groupArr($list->toArray(), 'XQJ');
        return Show::success($list);
    }

    /**
     * @return Json
     */
    public function group()
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
            $user = (new \app\common\services\User())->getNormalUserById($this->userId);
            $list = $model->getGroup($user['number']);
        } catch (\Exception $e) {
            $list = [];
        }

        return Show::success($list);
    }
}
