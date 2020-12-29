<?php


namespace app\api\controller;


use app\common\lib\Show;
use app\common\model\Xsksls as XskslsModel;

class Test extends ApiBase
{
    /**
     * 获取
     */
    public function get()
    {
        $code = input('ksh', '', 'trim');
        if (!$code) {
            return Show::error('请输入考生号');
        }

        $info = (new XskslsModel())->get($code);

        if(!$info) {
            return Show::error('未查到数据, 请重新输入正确的考生号');
        }
        
        return Show::success(['res' => $info['fjh']]);
    }
}
