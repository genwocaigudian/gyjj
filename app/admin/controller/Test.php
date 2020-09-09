<?php
namespace app\admin\controller;

use app\common\lib\Show;
use tauthz\facade\Enforcer;

class Test extends AdminBase
{
	public function index()
	{
//		Enforcer::addPermissionForUser('eve', 'articles', 'read');
//		Enforcer::addRoleForUser('eve', 'writer');
//		Enforcer::addPolicy('writer', 'articles','edit');
//		$res = Enforcer::getAllRoles();
		$res = Enforcer::getPolicy();
		return Show::success($res);
    }
}
