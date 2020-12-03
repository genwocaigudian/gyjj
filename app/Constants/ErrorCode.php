<?php

declare(strict_types=1);

namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * @Constants
 */
class ErrorCode extends AbstractConstants
{
    //基本错误码 1000-2000
    /**
     * @Message("Server Error！")
     */
    const SERVER_ERROR = 500;

    /**
     * @Message("系统参数错误！")
     */
    const SYSTEM_INVALID = 1000;
    /**
     * @Message("操作失败")
     */
    const OPERATION_FAILED = 1001;

    /**
     * @Message("参数无效！")
     */
    const PARAMS_INVALID = 1002;

    /**
     * @Message("token参数无效！")
     */
    const TOKEN_INVALID = 1003;


    //用户错误码 3000～3999
}
