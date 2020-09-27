<?php


namespace app\api\controller;

use app\common\lib\Show;
use app\common\services\News as NewsService;

class News extends ApiBase
{
    public function index()
    {
        $data = [];
        try {
            $list = (new NewsService())->getNormalAllNews($data, 10);
        } catch (\Exception $e) {
            $list = [];
        }
        return Show::success($list);
    }
    
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
}
