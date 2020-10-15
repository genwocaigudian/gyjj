<?php


namespace app\api\controller;

class AuthBase extends ApiBase
{
    public $userId = 0;
    public $type = 0;
    public $username = '';
    public $authorization = '';

    /**
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        $this->authorization = $this->request->header('Authorization');
        if (!$this->authorization || !$this->isLogin()) {
            return $this->show(config('status.not_login'), 'token已过期, 请重新授权');
        }

        if (!$this->isBind()) {
            return $this->show(config('status.not_bind'), '账号未绑定');
        }
    }
    
    public function isLogin()
    {
        $userInfo = cache(config('wx.api_token_pre').$this->authorization);
        if (!$userInfo) {
            return false;
        }
        if (!empty($userInfo['uid'])) {
            $this->userId = $userInfo['uid'];
            return true;
        }
        return false;
    }

    /**
     * 是否已经绑定
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function isBind()
    {
        $user = (new \app\common\services\User())->getNormalUserById($this->userId);
        if ($user['number']) {
            $this->type = $user['type'];
            $this->number = $user['number'];
            return true;
        }
        return false;
    }
}
