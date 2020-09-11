<?php
namespace app\admin\controller;

use app\common\lib\Show;
use app\common\services\Repair as RepairService;
use think\response\Json;

class Repair extends AdminAuthBase
{
    /**
     * @return Json
     * @throws \think\db\exception\DbException
     */
    public function index()
    {
        $list = (new RepairService())->getPaginateList(10);
        
        return Show::success($list);
    }
}
