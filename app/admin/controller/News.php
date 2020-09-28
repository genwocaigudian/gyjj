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
        $data = [];
        $title = input("param.title", "", "trim");
        $cateId = input("param.cate_id", "0", "intval");

        if(!empty($title)) {
            $data['title'] = $title;
        }
        if(!empty($cateId)) {
            $data['cate_id'] = $cateId;
        }
        try {
            $list = (new NewsService())->getPaginateList($data, 10);
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
        $data['img_urls'] = json_encode($data['img_urls']);
        
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
    public function read()
    {
        $id = input('param.id', 0, 'intval');
        try {
            $result = (new NewsService())->formatNews($id);
        } catch (\Exception $e) {
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
//        $data = input('post.');
        $data = $this->request->only(['is_hot', 'is_top', 'title', 'small_title', 'img_urls', 'content'], 'post');

        try {
            $res = (new NewsService())->update($id, $data);
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

        $id = input('post.id');

        try {
            $res = (new NewsService())->delete($id);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }

        return Show::success();
    }
}
