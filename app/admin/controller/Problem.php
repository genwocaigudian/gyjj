<?php
namespace app\admin\controller;

use app\admin\validate\QuestionProblem as QuestionProblemValidate;
use app\common\lib\Excel as ExcelLib;
use app\common\lib\Show;
use app\common\services\QuestionProblem as QuestionProblemService;
use think\facade\Log;
use think\response\Json;

class Problem extends AdminAuthBase
{
    /**
     * @return Json
     * @throws \think\db\exception\DbException
     */
    public function index()
    {
        $data = input('param.');
        $validate = new QuestionProblemValidate();
        if (!$validate->scene('index')->check($data)) {
            return Show::error($validate->getError());
        }
        $id = $data['question_id'];
        $list = (new QuestionProblemService())->getPaginateListWithOption($id, 10);
        foreach ($list['data'] as &$value) {
            $value['id'] = $value['question_id'] .'_'. $value['id'];
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

        $validate = new QuestionProblemValidate();
        if (!$validate->scene('save')->check($data)) {
            return Show::error($validate->getError());
        }

        try {
            $result = (new QuestionProblemService())->insertData($data);
        } catch (\Exception $e) {
            Log::error('admin/problem/save 错误:' . $e->getMessage());
            return Show::error($e->getMessage(), $e->getCode());
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
            $result = (new QuestionProblemService())->getNormalById($id);
        } catch (\Exception $e) {
            Log::error('admin/question/read 错误:' . $e->getMessage());
            return Show::error($e->getMessage(), $e->getCode());
        }

        return Show::success($result);
    }

    /**
     * 更新数据
     * @return Json
     */
    public function update()
    {
        if (!$this->request->isPost()) {
            return Show::error('非法请求');
        }

        $id = input('param.id', 0, 'intval');
        $data = input('post.');
//        $data = $this->request->only(['is_hot', 'is_top', 'title', 'content'], 'post');

        try {
            $res = (new QuestionProblemService())->update($id, $data);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }

        return Show::success();
    }

    /**
     * 删除数据
     * @return Json
     */
    public function delete()
    {
        if (!$this->request->isPost()) {
            return Show::error('非法请求');
        }

        $id = input("param.id");

        try {
            $res = (new QuestionProblemService())->del($id);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }

        return Show::success();
    }
}
