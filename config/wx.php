<?php

/**
 * 微信公众号相关配置
 */
return [
    'app_id' => 'wxd988b1bd367a300b',         // AppID
    'app_secret' => 'a5d0b16afe09fe5a0842512de417a3c0',    // AppSecret
	
	//获取code
    'get_code_url' => "https://open.weixin.qq.com/connect/oauth2/authorize?" .
            "appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect",
    'redirect_uri' => '/api/token/code',
	//获取token
    'get_token_url' => "https://api.weixin.qq.com/sns/oauth2/access_token?" .
            "appid=%s&secret=%s&code=%s&grant_type=authorization_code",

    'api_token_pre' => 'api_token_',
	//随机秘钥
	'token_salt' => 'WYMYyEx4FvWw5ioc',
	//过期时间
	'token_expire_in' => 7200,
];
