<?php


namespace app\api\controller;

use app\common\lib\Arr;
use app\common\lib\Show;
use app\common\services\News as NewsService;
use app\api\validate\News as NewsValidate;
use think\facade\Log;

class News extends ApiBase
{
    public function index()
    {
        $cid = input('post.cate_id', 0, 'intval');
        $data = [
            'cate_id' => $cid,
        ];
        try {
            $list = (new NewsService())->getLists($data, 10);
        } catch (\Exception $e) {
            $list = [];
        }
        return Show::success($list);
    }
    
    /**
     * 新增
     * @return \think\response\Json
     */
    public function create()
    {
        if (!$this->request->isPost()) {
            return Show::error('非法请求');
        }
        $data = input('post.');
        
        $validate = new CateValidate();
        if (!$validate->scene('register')->check($data)) {
            return Show::error($validate->getError(), config('status.name_not_null'));
        }
        
        try {
            $result = (new CateService())->insertData($data);
        } catch (\Exception $e) {
            Log::error('api/category/create 错误:' . $e->getMessage());
            return Show::error($e->getMessage(), $e->getCode());
        }
        
        return Show::success($result);
    }
    
    public function read($id)
    {
    }
}
