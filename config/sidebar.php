<?php

/**
 * 微信公众号相关配置
 */
return [
        'list' => [
	        [
		        'value' => '微主页',
		        'child' => [
			        ['value' => '菜单管理', 'index' => '/menu'],
			        ['value' => '轮播管理', 'index' => '/banner'],
			        ['value' => '学校简介', 'index' => '/abstract'],
			        ['value' => '学校新闻', 'index' => '/new']
		        ],
	        ],
	        [
		        'value' => '微服务',
		        'child' => [
			        ['value' => '掌上报修', 'index' => '/service'],
			        ['value' => '工资管理', 'index' => '/wage']
		        ],
	        ],
	        [
		        'value' => '微生活',
		        'child' => [
			        ['value' => '问卷调查', 'index' => '/survey'],
			        ['value' => '评比评选', 'index' => '/compare'],
			        ['value' => '失物招领', 'index' => '/found']
		        ],
	        ],
	        [
		        'value' => '后台管理',
		        'child' => [
			        ['value' => '用户管理', 'index' => '/menu'],
			        ['value' => '系统管理', 'index' => '/banner']
		        ],
	        ]
        ]
];
