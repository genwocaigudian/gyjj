<?php
namespace app\admin\controller;

use app\admin\services\AdminUser;
use app\admin\validate\Role as RoleValidate;
use app\common\lib\Show;
use app\common\services\Banner as BannerService;
use tauthz\facade\Enforcer;
use think\facade\Log;
use think\response\Json;

class Role extends AdminAuthBase
{
    /**
     * @return array|Json
     */
	public function index()
	{
	    $res = [];
		$roles = Enforcer::getAllRoles();
		if (!$roles) {
		    return $res;
        }

		foreach ($roles as $role) {
		    $res[]['name'] = $role;
        }

		return Show::success($res);
	}

    /**
     * 新增角色
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function save()
    {
        if (!$this->request->isPost()) {
            return Show::error('非法请求');
        }
        $data = input('post.');

        $validate = new RoleValidate();
        if (!$validate->scene('save')->check($data)) {
            return Show::error($validate->getError());
        }
        //后台管理员必须保证id=1的账号存在, 默认超级管理员
        $admin = (new AdminUser())->getNormalUserById(1);
        $user = (new AdminUser())->getNormalUserById($data['id']);
        Enforcer::addRoleForUser($admin['username'], $data['name']);
        Enforcer::addRoleForUser($user['username'], $data['name']);

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
			Log::error('admin/role/read 错误:' . $e->getMessage());
			return Show::error($e->getMessage(), $e->getCode());
		}
		
		return Show::success($result);
	}
    
//    /**
//     * 更新数据
//     * @return Json
//     */
//    public function update()
//    {
//        if (!$this->request->isPost()) {
//            return Show::error('非法请求');
//        }
//        $data = input('post.');
//
//        $validate = new RoleValidate();
//        if (!$validate->scene('update')->check($data)) {
//            return Show::error($validate->getError());
//        }
//
//        Enforcer::deleteRole($data['name']);
//        return Show::success();
//    }
    
    /**
     * 删除数据
     * @return Json
     */
    public function delete()
    {
        if (!$this->request->isPost()) {
            return Show::error('非法请求');
        }
        $data = input('post.');

        $validate = new RoleValidate();
        if (!$validate->scene('delete')->check($data)) {
            return Show::error($validate->getError());
        }

        Enforcer::deleteRole($data['name']);
        
        return Show::success();
    }
}
