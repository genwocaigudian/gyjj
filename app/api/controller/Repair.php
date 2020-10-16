<?php

namespace app\api\controller;

use app\common\lib\Arr;
use app\common\lib\Show;
use app\common\services\Repair as RepairServices;
use app\api\validate\Repair as RepairValidate;
use think\console\output\question\Choice;
use think\facade\Cache;
use think\facade\Log;
use think\response\Json;

//报修
class Repair extends AuthBase
{
    /**
     * @return Json
     * @throws \think\db\exception\DbException
     */
    public function index()
    {
        $data = [];
        $status = input('param.status', '', 'trim');
        switch ($status) {
            case 'commit' ://已提交
                $data['progress_bar'] = [1];
                break;
            case 'Processing' : //处理中
                $data['progress_bar'] = [2,3];
                break;
            case 'processed' : //已处理
                $data['progress_bar'] = [4];
                break;
            case 'approve' : //审批中
                $data['progress_bar'] = [2];
                break;
            default:
                $data['progress_bar'] = [0,1,2,3,4];
                break;
        }
        try {
            $list = (new RepairServices())->getPaginateList($data, 10);
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
        $data = input('param.');

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
        Cache::zAdd(config('rediskey.repair_status_key'), time()+config('rediskey.order_expire'), $orderId);
    }
}
