<?php

namespace app\api\controller;

use app\common\lib\Arr;
use app\common\lib\Show;
use app\common\services\Selection as SelectionServices;
use app\common\services\SelectionOption;
use app\common\services\SelectionResult;
use think\response\Json;

class Selection extends AuthBase
{
    /**
     * @return Json
     */
    public function index()
    {
        $data = [];
        $data['target'] = $this->type;
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
        $isSubmit = 0;
        try {
            $result = (new SelectionOption())->getPaginateList($id);
            $isSubmit = (new SelectionResult())->isSubmit($id);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }

        $result['is_submit'] = $isSubmit;

        return Show::success($result);
    }
}
