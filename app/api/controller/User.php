<?php


namespace app\api\controller;

use app\common\lib\Show;
use app\common\services\User as UserServices;
use app\api\validate\User as UserValidate;

class User extends AuthBase
{
    public function index()
    {
        $user = (new UserServices())->getNormalUserById($this->userId);
        $result = [
            'id' => $this->userId,
            'username' => $user['nickname'],
        ];
        return Show::success($result);
    }

    /**
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function update()
    {
        if (!$this->request->isPost()) {
            return Show::error('非法请求');
        }

        $data = input('param.');
        
        $validate = new UserValidate();
        if (!$validate->scene('update_user')->check($data)) {
            return Show::error($validate->getError());
        }
        
        $user = (new UserServices())->update($this->userId, $data);
        
        if (!$user) {
            return Show::error('操作失败');
        }
        return Show::success();
    }


    /**
     * 账号绑定
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function bind()
    {
        if (!$this->request->isPost()) {
            return Show::error('非法请求');
        }

        $data = input('param.');

        $validate = new UserValidate();
        if (!$validate->scene('bind')->check($data)) {
            return Show::error($validate->getError());
        }

        $user = (new UserServices())->update($this->userId, $data);

        if (!$user) {
            return Show::error('绑定失败');
        }
        return Show::success();
    }
}
