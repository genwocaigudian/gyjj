<?php

namespace app\api\controller;

use app\common\lib\Show;
use app\common\services\QuestionResult;
use app\common\services\QuestionSuggest;
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
        $data = input('param.');
        
//        $validate = new QresultValidate();
//        if (!$validate->scene('save')->check($data)) {
//            return Show::error($validate->getError());
//        }
	    foreach ($data['data'] as &$datum) {
	    	$datum['user_id'] = $this->userId;
	    }

	    $suggestData = [
	        'question_id' => 1,
	        'content' => $data['content'],
        ];

	    $qData = $data['data'];

        try {
            $res = (new QuestionResult())->insertAll($qData);
            $resu = (new QuestionSuggest())->insertData($suggestData);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }
        
        return Show::success($res);
    }
}
