<?php
namespace app\admin\controller;

use app\admin\validate\Category as CateValidate;
use app\common\lib\Arr;
use app\common\lib\Show;
use app\common\services\Category as CateService;
use think\facade\Log;
use think\response\Json;

class Category extends AdminAuthBase
{
    public function index()
    {
    	$data = [];
    	$name = input('param.name', '', 'trim');
    	$time = input('param.time', '', 'trim');
        if (!empty($name)) {
        	$data['name'] = $name;
        }
        if (!empty($time)) {
        	$data['create_time'] = explode("-", $time);
        }
        
        $categorys = (new CateService())->getLists($data, 10);
        
        return Show::success($categorys);
    }
	
	public function index1()
	{
		$pid = input("param.pid", 0, "intval");
		$data = [
			"pid" => $pid,
		];
		try {
			$categorys = (new CateService())->getLists($data, 10);
		} catch (\Exception $e) {
			$categorys = Arr::getPaginateDefaultData(10);
		}
		
		return Show::success($categorys);
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
        
        $validate = new CateValidate();
        if (!$validate->scene('save')->check($data)) {
            return Show::error($validate->getError(), config('status.name_not_null'));
        }

        $data['is_show'] = 1;
        
        try {
            $result = (new CateService())->insertData($data);
        } catch (\Exception $e) {
            Log::error('admin/category/save 错误:' . $e->getMessage());
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
			$result = (new CateService())->getNormalCateById($id);
		} catch (\Exception $e) {
			Log::error('admin/category/read 错误:' . $e->getMessage());
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
}