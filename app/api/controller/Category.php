<?php


namespace app\api\controller;

use app\common\lib\Show;
use app\common\services\Category as CateService;
use app\api\validate\Category as CateValidate;
use think\facade\Log;
use think\response\Json;

class Category extends ApiBase
{
    /**
     * @return Json
     */
    public function index()
    {
        try {
            $cateService = new CateService();
            $categorys = $cateService->getNormalAllCategorys();
        } catch (\Exception $e) {
            Log::error("api/category/index 报错:" . $e->getMessage());
            return Show::error('内部异常');
        }
        
        //		$result = Arr::getTree($categorys);
        //		$result = Arr::sliceTreeArr($result);
        return Show::success($categorys);
    }

    /**
     * @return Json
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
