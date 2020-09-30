<?php

namespace app\api\controller;

use app\api\validate\Sresult as SresultValidate;
use app\common\lib\Show;
use app\common\services\Selection;
use app\common\services\SelectionResult;
use think\response\Json;

class Sresult extends AuthBase
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
        $data = input('param.');

        $validate = new SresultValidate();
        if (!$validate->scene('save')->check($data)) {
            return Show::error($validate->getError());
        }

        $insertData = [];
	    foreach ($data['option_id'] as $key => $datum) {
	        $temp = [
	            'user_id' => $this->userId,
	            'selection_id' => $data['selection_id'],
	            'option_id' => $datum,
            ];
	        array_push($insertData, $temp);
	    }

        try {
            $result = (new SelectionResult())->insertAll($insertData);
            (new Selection())->updateAttendCount($data['selection_id']);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }
        
        return Show::success($result);
    }
}
