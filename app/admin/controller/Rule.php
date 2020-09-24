<?php
namespace app\admin\controller;

use app\admin\services\AdminUser;
use app\admin\validate\Rule as RuleValidate;
use app\common\lib\Show;
use app\common\services\Rules as RuleService;
use tauthz\facade\Enforcer;
use think\response\Json;

class Rule extends AdminAuthBase
{
    /**
     * 获取所有角色
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
	public function index()
	{
//	    $res = [];
//		$roles = Enforcer::getAllRoles();
//		if (!$roles) {
//            return Show::success($res);
//        }
//
//		foreach ($roles as $role) {
//		    $res[]['name'] = $role;
//        }
//
//		return Show::success($res);

        $data = [
            'type' => 'g',
            'uid' => input('param.uid', 0, 'intval'),
            'name' => '',
        ];

        $field = 'id, v1';

        $list = (new RuleService())->getList($data, $field);

        return Show::success($list);
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

        $validate = new RuleValidate();
        if (!$validate->scene('save')->check($data)) {
            return Show::error($validate->getError());
        }
        //后台管理员必须保证id=1的账号存在, 默认超级管理员
//        $admin = (new AdminUser())->getNormalUserById(1);
//        $user = (new AdminUser())->getNormalUserById($data['id']);
        Enforcer::addRoleForUser(1, $data['name']);
//        Enforcer::addRoleForUser($user['username'], $data['name'], 'index');

        return Show::success();
    }

    public function give()
    {
        if (!$this->request->isPost()) {
            return Show::error('非法请求');
        }
        $data = input('post.');

        $validate = new RuleValidate();
        if (!$validate->scene('give')->check($data)) {

            return Show::error($validate->getError());
        }
        //后台管理员必须保证id=1的账号存在, 默认超级管理员
//        $admin = (new AdminUser())->getNormalUserById(1);
//        $user = (new AdminUser())->getNormalUserById($data['id']);
        Enforcer::addRoleForUser(1, $data['name']);
//        Enforcer::addRoleForUser($user['username'], $data['name'], 'index');

        return Show::success();
    }
}
