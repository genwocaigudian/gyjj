<?php

namespace app\api\controller;

use app\api\validate\Proctor as ProctorValidate;
use app\common\services\Proctor as ProctorServices;
use app\common\services\User as UserServices;
use app\common\lib\Show;
use think\response\Json;

//监考
class Proctor extends AuthBase
{

    /**
     * @return Json
     */
    public function index()
    {
        $data = input('param.');
        $list = [];

        $validate = new ProctorValidate();
        if (!$validate->scene('index')->check($data)) {
            return Show::error($validate->getError());
        }

        try {
            $user = (new UserServices())->getNormalUserById($this->userId);
            $data['number'] = $user['number'];
            $list = (new ProctorServices())->getListByData($data);
        } catch (\Exception $e) {
            return Show::success($list);
        }

        return Show::success($list);
    }

    /**
     * @return Json
     */
    public function group()
    {
        $data['time'] = time();
        try {
            $user = (new UserServices())->getNormalUserById($this->userId);
            $data['number'] = $user['number']??'';
            $list = (new ProctorServices())->getDateGroup($data);
        } catch (\Exception $e) {
            halt($e->getMessage());
            $list = [];
        }

        return Show::success($list);
    }
}
