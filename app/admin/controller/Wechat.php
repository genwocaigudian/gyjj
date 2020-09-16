<?php
namespace app\admin\controller;

use app\common\lib\Show;
use app\common\services\Wechat as WechatService;
use GuzzleHttp\Client;

class Wechat extends AdminAuthBase
{
    public function menu()
    {
        $accessToken = WechatService::getAccessToken();
        $client = new Client();
        $response = $client->get('https://api.weixin.qq.com/cgi-bin/get_current_selfmenu_info?access_token='.$accessToken);
        $menu = json_decode($response->getBody(), true);
        return Show::success($menu);
    }
}
