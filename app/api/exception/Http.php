<?php

namespace app\api\exception;

use think\exception\Handle;
use think\Response;
use Throwable;

class Http extends Handle
{
    protected $httpStatus = 500;

    /**
     * Render an exception into an HTTP response.
     *
     * @access public
     * @param \think\Request   $request
     * @param Throwable $e
     * @return Response
     */
    public function render($request, Throwable $e): Response
    {
        if (method_exists($e, 'getStatusCode')) {
            $httpStatus = $e->getStatusCode();
        } else {
            $httpStatus = $this->httpStatus;
        }
        return show(config("status.error"), $e->getMessage(), [], $httpStatus);
    }
}