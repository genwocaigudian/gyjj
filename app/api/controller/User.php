<?php


namespace app\api\controller;

use app\common\lib\Show;
use app\common\services\User as UserServices;

class User extends AuthBase
{
    /**
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index()
    {
        $user = (new UserServices())->getNormalUserById($this->userId);
        $res = [];
        $isPermission = 0;
        $leaders = config('repair.leaders');
        $repairs = config('repair.repairs');
        if (in_array($this->number, $repairs)) {
            $isPermission = 2;
        }
        if (in_array($this->number, $leaders)) {
            $isPermission = 1;
        }
        if ($user) {
            $res = [
                'id' => $user['id'],
                'type' => $user['type'],
                'number' => $user['number'],
                'username' => $user['username'],
	            'is_permission' => $isPermission
            ];
        }
        return Show::success($res);
    }
}
