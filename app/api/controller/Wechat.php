<?php
namespace app\api\controller;

use app\BaseController;
use EasyWeChat\Factory;

class Wechat extends BaseController
{

    public function profile()
    {
        $config = [
            'app_id' => config('wechat.app_id'),
            'secret' => config('wechat.secret'),
            'oauth' => [
                'scopes'   => config('wechat.oauth.scopes'),
                'callback' => config('wechat.oauth.callback'),
            ],
        ];

        $app = Factory::officialAccount($config);
        $oauth = $app->oauth;
        return $oauth->redirect();

    }

    public function callback()
    {
        $config = [
            'app_id' => config('wechat.app_id'),
            'secret' => config('wechat.secret'),
        ];

        $app = Factory::officialAccount($config);
        $oauth = $app->oauth;

        // 获取 OAuth 授权结果用户信息
//        return show(config("status.success"), "ok", $oauth->user());

        header('location:'. 'index');
    }
}
