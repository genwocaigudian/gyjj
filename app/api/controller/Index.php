<?php
namespace app\api\controller;

use app\BaseController;
use app\common\lib\Num;
use app\common\lib\Show;

class Index extends BaseController
{
    public function index()
    {
        //1.获取分类接口
        $cateService = new \app\common\services\Category();
        $cateList = $cateService->getNormalAllCategorys();
        //2.按照发布时间倒序获取最新10条新闻

        return Show::success();
    }
    
    public function redis()
    {
        $code = Num::getCode(6);
        cache(config('redis.code_pre').'18855479876', $code, config('redis.code_expire'));
        echo 'redis';
    }
}
