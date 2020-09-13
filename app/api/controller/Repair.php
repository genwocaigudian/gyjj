<?php

namespace app\api\controller;

use app\common\lib\Arr;
use app\common\lib\Show;
use app\common\services\Repair as RepairServices;
use app\api\validate\Repair as RepairValidate;
use think\facade\Cache;
use think\facade\Log;
use think\response\Json;

class Repair extends ApiBase
{
    public function index()
    {
        return Show::success();
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
        
        $validate = new RepairValidate();
        if (!$validate->scene('save')->check($data)) {
            return Show::error($validate->getError());
        }

        try {
            $result = (new RepairServices())->insertData($data);
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
			$result = (new RepairServices())->getNormalCateById($id);
		} catch (\Exception $e) {
			Log::error('api/repair/read 错误:' . $e->getMessage());
			return Show::error($e->getMessage(), $e->getCode());
		}
		
		return Show::success($result);
	}
    
    /**
     * 更新数据
     * @param $id
     * @return Json
     */
    public function update()
    {
        if (!$this->request->isPost()) {
            return Show::error('非法请求');
        }
	
	    $id = input("param.id", 0, "intval");
        $data = input('post.');
        
        $validate = new CateValidate();
        if (!$validate->scene('update')->check($data)) {
            return Show::error($validate->getError(), config('status.name_not_null'));
        }
        try {
            $res = (new CateService())->update($id, $data);
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
            $res = (new CateService())->delete($id);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }
        
        return Show::success();
    }
	
	public function test()
	{
		$orderId = 15;
		Cache::zAdd(config('redis.repair_status_key'), time()+config('redis.order_expire'), $orderId);
    }
}
