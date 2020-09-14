<?php

/**
 * 微信公众号相关配置
 */
return [
        'app_id' => 'wxd988b1bd367a300b',         // AppID
        'secret' => 'a5d0b16afe09fe5a0842512de417a3c0',    // AppSecret
        'token' => '',           // Token
        'aes_key' => '',   // EncodingAESKey

        /*
         * OAuth 配置
         *
         * scopes：公众平台（snsapi_userinfo / snsapi_base），开放平台：snsapi_login
         * callback：OAuth授权完成后的回调页地址(如果使用中间件，则随便填写。。。)
         */
        'oauth' => [
            'scopes'   => array_map('trim', explode(',', 'snsapi_userinfo')),
            'callback' => '/api/wechat/token',
        ],
];
