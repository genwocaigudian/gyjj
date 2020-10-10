<?php

namespace app\api\controller;

use app\common\lib\Arr;
use app\common\lib\Show;
use app\common\model\Jsxxb;
use app\common\services\Lost as LostServices;
use app\api\validate\Lost as LostValidate;
use think\facade\Cache;
use think\facade\Log;
use think\response\Json;

class Books extends AuthBase
{
    /**
     * 首页列表
     * @return Json
     */
    public function index()
    {
        $data = input('param.');
        $data['status'] = 1;
        try {
            $info = (new Jsxxb())->getByZGH($data['number']);
            $list = (new LostServices())->getPaginateList($data, 10);
        } catch (\Exception $e) {
            $list = Arr::getPaginateDefaultData(10);
        }

        return Show::success($list);
    }

    /**
     * 我的列表
     * @return Json
     * @throws \think\db\exception\DbException
     */
    public function mindex()
    {
        $data = [];
        $data['uid'] = $this->userId;
        $list = (new LostServices())->getPaginateList($data,10);

        return Show::success($list);
    }
}
