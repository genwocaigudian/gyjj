<?php
namespace app\admin\controller;

use app\admin\validate\News as NewsValidate;
use app\common\lib\Arr;
use app\common\lib\Show;
use app\common\services\News as NewsService;
use think\facade\Log;
use think\response\Json;

class News extends AdminAuthBase
{
    /**
     * @return Json
     */
    public function index()
    {
        $status = input("param.status", 1, "intval");
        $data = [
            "status" => $status,
        ];
        try {
            $list = (new NewsService())->getLists($data, 10);
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
        
        $validate = new NewsValidate();
        if (!$validate->scene('save')->check($data)) {
            return Show::error($validate->getError());
        }
        
        $data['user_id'] = $this->userId;
        
        try {
            $result = (new NewsService())->insertData($data);
        } catch (\Exception $e) {
            Log::error('admin/news/save 错误:' . $e->getMessage());
            return Show::error($e->getMessage(), $e->getCode());
        }
        
        return Show::success($result);
    }

    /**
     * 详情
     * @param $id
     * @return Json
     */
    public function read($id)
    {
        try {
            $result = (new NewsService())->getNormalNewsById($id);
            halt($result);
        } catch (\Exception $e) {
            Log::error('admin/news/save 错误:' . $e->getMessage());
            return Show::error($e->getMessage(), $e->getCode());
        }

        return Show::success($result);
    }

    /**
     * 更新数据
     * @param $id
     * @return Json
     */
    public function update($id)
    {
        if (!$this->request->isPut()) {
            return Show::error('非法请求');
        }

        $data = input('post.');

        try {
            $res = (new NewsService())->update($id, $data);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }

        return Show::success();
    }

    /**
     * 删除数据
     * @param $id
     * @return Json
     */
    public function delete($id)
    {
        if (!$this->request->isDelete()) {
            return Show::error('非法请求');
        }

        try {
            $res = (new NewsService())->delete($id);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }

        return Show::success();
    }
}
