<?php
namespace app\admin\controller;

use app\admin\validate\Permission as PermissionValidate;
use app\common\lib\Show;
use tauthz\facade\Enforcer;
use think\response\Json;

class Permission extends AdminAuthBase
{
    /**
     * 获取某个角色的权限列表
     * @return Json
     */
	public function index()
	{
	    //
        $res = Enforcer::hasRoleForUser('admin1', '编辑', '/index/index');
		return Show::success($res);
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

        $validate = new PermissionValidate();
        if (!$validate->scene('save')->check($data)) {
            return Show::error($validate->getError());
        }
        //为用户赋予角色
        Enforcer::addPermissionForUser($data['name'], $data['module']);
        return Show::success();
    }
	
	/**
	 * 详情
	 * @return Json
	 */
	public function read()
	{
		$id = input('param.id', 0, 'intval');
		try {
			$result = (new BannerService())->getNormalBannerById($id);
		} catch (\Exception $e) {
			Log::error('admin/banner/read 错误:' . $e->getMessage());
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
        
        $validate = new BannerValidate();
        if (!$validate->scene('update')->check($data)) {
            return Show::error($validate->getError());
        }
        try {
            $res = (new BannerService())->update($id, $data);
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
	
	    $id = input("param.id", 0, "intval");
        
        try {
            $res = (new BannerService())->delete($id);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }
        
        return Show::success();
    }
}
