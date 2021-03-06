<?php

/**
 * repair 保修相关配置
 */
return [
    'pidBindNumber' => [//对应分类绑定的维修负责人职工号 pid => number
        1 => '01130',
        2 => '02060',
        3 => '03059',
    ],

    'numberToExecute' => [//负责人对应的处理人职工号
        '01130' => ['00100'],//虚拟账号沈蒙
        '02060' => ['03206'],
        '03059' => ['01015', '03207'],
    ],

    'leaders' => ['01130', '02060', '03059'],//负责人集合
    'repairs' => ['00100', '01015', '03206', '03207'],//维修人集合
];
