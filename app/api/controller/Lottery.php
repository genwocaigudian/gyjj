<?php

namespace app\api\controller;

use app\common\lib\Arr;
use app\common\lib\Show;
use app\common\services\Lottery as LotteryServices;
use think\facade\Cache;
use think\facade\Log;
use think\response\Json;

class Lottery extends AuthBase
{
    /**
     * @return Json
     */
    public function index()
    {
        $data = [];
        try {
            $list = (new LotteryServices())->getPaginateList($data, 10);
        } catch (\Exception $e) {
            $list = Arr::getPaginateDefaultData(10);
        }

        return Show::success($list);
    }

    /**
     * 详情
     * @return Json
     */
    public function read()
    {
        $id = input('param.id', 0, 'intval');
        try {
            $result = (new LotteryServices())->getNormalById($id);
        } catch (\Exception $e) {
            Log::error('api/lottery/read 错误:' . $e->getMessage());
            return Show::error($e->getMessage(), $e->getCode());
        }

        return Show::success($result);
    }

    public function take()
    {
        $id = input('param.id', 0, 'intval');
        $key = Cache::hSet(config('rediskey.lottery_by_id') . $id);
        return $key;
    }
}
