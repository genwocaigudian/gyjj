<?php

namespace app\api\controller;

use app\common\lib\Arr;
use app\common\lib\Show;
use app\common\services\Selection as SelectionService;
use app\common\services\Selection as SelectionServices;
use app\api\validate\Sresult as SelectionValidate;
use app\common\services\SelectionOption;
use think\facade\Log;
use think\response\Json;

class Selection extends AuthBase
{
    /**
     * @return Json
     */
    public function index()
    {
        $data = [];
        try {
            $list = (new SelectionServices())->getPaginateList($data, 10);
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
            $result = (new SelectionOption())->getPaginateList($id);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }

        return Show::success($result);
    }
}
