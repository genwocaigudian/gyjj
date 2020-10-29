<?php

return [
    // 默认使用的数据库连接配置
    'default'         => env('database.driver', 'mysql'),

    // 自定义时间查询规则
    'time_query_rule' => [],

    // 自动写入时间戳字段
    // true为自动识别类型 false关闭
    // 字符串则明确指定时间字段类型 支持 int timestamp datetime date
    'auto_timestamp'  => true,

    // 时间字段取出后的默认时间格式
    'datetime_format' => 'Y-m-d H:i:s',

    // 数据库连接配置信息
    'connections'     => [
        'mysql' => [
            // 数据库类型
            'type'              => env('database.type', 'mysql'),
            // 服务器地址
            'hostname'          => env('database.hostname', '127.0.0.1'),
            // 数据库名
            'database'          => env('database.database', ''),
            // 用户名
            'username'          => env('database.username', 'root'),
            // 密码
            'password'          => env('database.password', ''),
            // 端口
            'hostport'          => env('database.hostport', '3306'),
            // 数据库连接参数
            'params'            => [],
            // 数据库编码默认采用utf8
            'charset'           => env('database.charset', 'utf8'),
            // 数据库表前缀
            'prefix'            => env('database.prefix', ''),

            // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
            'deploy'            => 0,
            // 数据库读写是否分离 主从式有效
            'rw_separate'       => false,
            // 读写分离后 主服务器数量
            'master_num'        => 1,
            // 指定从服务器序号
            'slave_no'          => '',
            // 是否严格检查字段是否存在
            'fields_strict'     => true,
            // 是否需要断线重连
            'break_reconnect'   => true,
            // 监听SQL
            'trigger_sql'       => env('app_debug', true),
            // 开启字段缓存
            'fields_cache'      => false,
            // 字段缓存路径
            'schema_cache_path' => app()->getRuntimePath() . 'schema' . DIRECTORY_SEPARATOR,
        ],

        // 更多的数据库配置信息
        'schedule' => [
            // 数据库类型
            'type'            => '\misuoka\think\Oracle',
            // 服务器地址
            'hostname'        => env('schedule.hostname', '127.0.0.1'), // 填写数据库 IP 地址
            // 数据库名
            'database'        => env('schedule.database', ''), // 数据库实例 SID 名称，如 ORCL
            // 用户名
            'username'        => env('schedule.username', ''), // 用户名
            // 密码
            'password'        => env('schedule.password', ''), // 密码
            // 端口
            'hostport'        => env('schedule.hostport', ''), // 端口号，如 1521
            // 数据库连接参数
            'params'          => [],
            // 数据库编码默认采用utf8
            'charset'         => 'utf8',
            // 数据库表前缀
            'prefix'          => '',
            // 自增序列名前缀（新增的，针对 Oracle 特有的），除前缀外，名称与表名一致。如果不是，请在新增数据时使用 sequence 方法设置序列
            'prefix_sequence' => '',
        ],

        // 更多的数据库配置信息
        'book' => [
            // 数据库类型
            'type'            => '\misuoka\think\Oracle',
            // 服务器地址
            'hostname'        => env('book.hostname', '127.0.0.1'), // 填写数据库 IP 地址
            // 数据库名
            'database'        => env('book.database', ''), // 数据库实例 SID 名称，如 ORCL
            // 用户名
            'username'        => env('book.username', ''), // 用户名
            // 密码
            'password'        => env('book.password', ''), // 密码
            // 端口
            'hostport'        => env('book.hostport', ''), // 端口号，如 1521
            // 数据库连接参数
            'params'          => [],
            // 数据库编码默认采用utf8
            'charset'         => 'utf8',
            // 数据库表前缀
            'prefix'          => '',
            // 自增序列名前缀（新增的，针对 Oracle 特有的），除前缀外，名称与表名一致。如果不是，请在新增数据时使用 sequence 方法设置序列
            'prefix_sequence' => '',
        ],
    ],
];
