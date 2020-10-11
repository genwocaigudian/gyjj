<?php

namespace app\api\controller;

use app\common\lib\Arr;
use app\common\lib\Show;
use app\common\model\BookBorrow;
use app\common\model\BookList;
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
        try {
            $list = (new BookList())->getPaginateList($data);
        } catch (\Exception $e) {
            $list = Arr::getPaginateDefaultData(10);
        }

        return Show::success($list);
    }

    /**
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function mindex()
    {
        $list = [];
        $user = (new \app\common\services\User())->getNormalUserById($this->userId);
        if (!$user) {
            return Show::success($list);
        }

        $list = (new BookBorrow())->getPaginateListById($user['identity_card']);

        return Show::success($list);
    }
}
