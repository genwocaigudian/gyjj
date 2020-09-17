<?php

namespace app\api\controller;

use app\api\validate\Qresult as QresultValidate;
use app\common\lib\Show;
use app\common\services\QuestionResult;
use think\response\Json;

class Qresult extends AuthBase
{
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
        
        $validate = new QresultValidate();
        if (!$validate->scene('save')->check($data)) {
            return Show::error($validate->getError());
        }
        
        $data['user_id'] = $this->userId;

        try {
            $result = (new QuestionResult())->insertData($data);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }
        
        return Show::success($result);
    }
}
