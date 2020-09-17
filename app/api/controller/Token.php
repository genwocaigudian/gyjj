<?php


namespace app\api\controller;

use app\BaseController;
use app\common\lib\Show;
use app\common\services\UserCode;
use app\common\services\UserToken;
use think\facade\Request;

class Token extends BaseController
{
    /**
     * 获取code
     * @return \think\response\Json|\think\response\Redirect
     */
    public function code()
    {
        $code = input('code', '', 'trim');
        if (!$code) {
            $url = UserCode::getCode();
            return redirect($url);
        }
        
        return Show::success(['code' => $code]);
    }

    /**
     * 获取token
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function get()
    {
        $code = input('code', '', 'trim');
        if (!$code) {
            return Show::error('code不可为空');
        }
        $ut = new UserToken();
        $token = $ut->getToken($code);
        return Show::success(['token' => $token]);
    }

    /**
     * 获取token
     * @return \think\response\Json
     */
    public function check()
    {
        $token = Request::header('Authorization');
        if (!$token) {
            return Show::error('token不可为空');
        }
        $ut = new UserToken();
        $res = $ut->checkToken($token);
        return Show::success(['res' => $res]);
    }
}
