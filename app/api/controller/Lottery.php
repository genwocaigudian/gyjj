<?php

namespace app\api\controller;

use app\api\validate\Lottery as LotteryValidate;
use app\common\lib\Arr;
use app\common\lib\Key;
use app\common\lib\Num;
use app\common\lib\Show;
use app\common\services\Lottery as LotteryServices;
use app\common\services\LotteryWinning as WinnerServices;
use think\facade\Cache;
use think\facade\Log;
use think\response\Json;

//抽奖
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
     * 新增
     * @return Json
     */
    public function save()
    {
        if (!$this->request->isPost()) {
            return Show::error('非法请求');
        }
        $data = input('post.');

        $validate = new LotteryValidate();
        if (!$validate->scene('save')->check($data)) {
            return Show::error($validate->getError());
        }

        $id = $data['id'];

        try {
            $info = (new LotteryServices())->getNormalById($id);
            $userId = $info['user_id']??0;
            if ($userId != $this->userId) {
                return Show::error('您没有开奖权限!');
            }
            $count = (new WinnerServices())->getCountById($id);
            if ($count > $info['count']) {
                return Show::error('奖项已抽完!');
            }

            $result = (new WinnerServices())->insertData($data);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }

        return Show::success($result);
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

        $result['is_allow'] = $this->userId == $result['user_id'] ? 1 : 0;

        return Show::success($result);
    }

    /**
     * 取号
     * @return mixed
     */
    public function take()
    {
        $id = input('param.id', 0, 'intval');
        $incr = Cache::incr(Key::LotteryNumIncrKey($id));
        $num = Cache::hSet(Key::LotteryKey($id), $this->userId, $incr);
        $incrNum = Num::fixFourNum($incr);
        $res['number'] = $incrNum;
        return Show::success($res);
    }

    /**
     * 读号
     * @return mixed
     */
    public function get()
    {
        $id = input('param.id', 0, 'intval');
        $num = Cache::hGet(Key::LotteryKey($id), $this->userId);
        if (!$num) {
            return Show::error('您还没有取号');
        }
        $res['number'] = Num::fixFourNum($num);
        return Show::success($res);
    }
}
