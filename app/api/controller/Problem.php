<?php

namespace app\api\controller;

use app\common\lib\Show;
use app\common\services\QuestionProblem as ProblemServices;
use app\api\validate\Problem as ProblemValidate;
use think\response\Json;

class Problem extends AuthBase
{
    /**
     * @return Json
     */
    public function index()
    {
    	$data = input('param.');
	    $validate = new ProblemValidate();
	    if (!$validate->scene('index')->check($data)) {
		    return Show::error($validate->getError());
	    }
	    
	    $id = $data['question_id'];
        try {
            $list = (new ProblemServices())->getNormalListWithOption($id);
        } catch (\Exception $e) {
            $list = [];
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
        
        $validate = new QuestionValidate();
        if (!$validate->scene('save')->check($data)) {
            return Show::error($validate->getError());
        }

        try {
            $result = (new QuestionServices())->insertData($data);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }
        
        return Show::success($result);
    }
}
