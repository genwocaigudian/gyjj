<?php

namespace app\api\controller;

use app\api\validate\Scores as ScoresValidate;
use app\common\lib\Arr;
use app\common\lib\Show;
use app\common\model\Jskcb;
use app\common\model\Xscjb;
use app\common\model\Xskcb;
use think\response\Json;

//学生成绩表
class Scores extends AuthBase
{
    /**
     * @return Json
     */
    public function index()
    {
        $data = input('param.');
        $list = [];

        $validate = new ScoresValidate();
        if (!$validate->scene('index')->check($data)) {
            return Show::error($validate->getError());
        }

        $xq = $data['xq'];
        $xn = $data['xn'];
        switch ($this->type) {
            case 2:
                $model = new Xscjb();
                break;
            default:
                return Show::success($list);
                break;
        }
        try {
            $user = (new \app\common\services\User())->getNormalUserById($this->userId);
            $list = $model->getList($user['number'], $xn, $xq);
        } catch (\Exception $e) {
            return Show::success($list);
        }

        return Show::success($list->toArray());
    }

    /**
     * @return Json
     */
    public function group()
    {
        $list = [];
        switch ($this->type) {
            case 2:
                $model = new Xscjb();
                break;
            default:
                return Show::success($list);
                break;
        }
        try {
            $user = (new \app\common\services\User())->getNormalUserById($this->userId);
            $list = $model->getGroup($user['number']);
        } catch (\Exception $e) {
            return Show::success($list);
        }

        return Show::success($list);
    }
}
