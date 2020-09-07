<?php
namespace app\admin\controller;

use app\admin\validate\News as NewsValidate;
use app\common\lib\Show;
use app\common\services\News as NewsService;
use think\facade\Log;
use think\response\Json;

class News extends AdminAuthBase
{
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
}
