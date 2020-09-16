<?php

namespace app\api\controller;

use app\common\lib\Arr;
use app\common\lib\Show;
use app\common\services\Lost as LostServices;
use app\api\validate\Lost as LostValidate;
use think\facade\Cache;
use think\facade\Log;
use think\response\Json;

class Lost extends ApiBase
{
    /**
     * @return Json
     */
    public function index()
    {
        $data = [];
        try {
            $list = (new LostServices())->getPaginateList($data, 10);
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
        
        $validate = new LostValidate();
        if (!$validate->scene('save')->check($data)) {
            return Show::error($validate->getError());
        }

        try {
            $result = (new LostServices())->insertData($data);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
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
			$result = (new LostServices())->getNormalById($id);
		} catch (\Exception $e) {
			Log::error('api/lost/read 错误:' . $e->getMessage());
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
	
	    $id = input("param.id", 0, "intval");
        $data = input('post.');
        
        $validate = new LostValidate();
        if (!$validate->check($data)) {
            return Show::error($validate->getError());
        }
        try {
            $res = (new LostServices())->update($id, $data);
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
            $res = (new LostServices())->del($id);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }
        
        return Show::success();
    }
}