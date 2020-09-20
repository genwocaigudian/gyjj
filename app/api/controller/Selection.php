<?php

namespace app\api\controller;

use app\common\lib\Arr;
use app\common\lib\Show;
use app\common\services\Selection as SelectionServices;
use app\api\validate\Sresult as SelectionValidate;
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
     * 新增
     * @return Json
     */
    public function save()
    {
        if (!$this->request->isPost()) {
            return Show::error('非法请求');
        }
        $data = input('post.');
        
        $validate = new SelectionValidate();
        if (!$validate->scene('save')->check($data)) {
            return Show::error($validate->getError());
        }

        try {
            $result = (new SelectionServices())->insertData($data);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }
        
        return Show::success($result);
    }
}
