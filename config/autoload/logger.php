<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
return [
    'default' => [
//        'handler' => [
//            'class' => Monolog\Handler\StreamHandler::class,
//            'constructor' => [
//                'stream' => BASE_PATH . '/runtime/logs/hyperf.log',
//                'level' => Monolog\Logger::DEBUG,
//            ],
//        ],
    //日期轮转
        'handler' => [
            'class' => Monolog\Handler\RotatingFileHandler::class,
            'constructor' => [
//                'stream' => BASE_PATH . '/runtime/logs/hyperf.log',
                'filename' => BASE_PATH . '/runtime/logs/hyperf.log', // hyperf2.0后调整此参数
                'level' => Monolog\Logger::INFO,
            ],
        ],
        'formatter' => [
            'class' => Monolog\Formatter\LineFormatter::class,
            'constructor' => [
                'format' => null,
//                'dateFormat' => 'Y-m-d H:i:s',
                'dateFormat' => null,
                'allowInlineLineBreaks' => true,
            ],
        ],
    ],
];
