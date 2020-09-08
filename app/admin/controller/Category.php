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
    public function update($id)
    {
        if (!$this->request->isPut()) {
            return Show::error('非法请求');
        }
        
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
     * @param $id
     * @return Json
     */
    public function delete($id)
    {
        if (!$this->request->isDelete()) {
            return Show::error('非法请求');
        }
        
        try {
            $res = (new CateService())->delete($id);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }
        
        return Show::success();
    }
}
