<?php
namespace app\admin\controller;

use app\common\lib\Excel as ExcelLib;
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

    /**
     * @return Json
     */
    public function export()
    {
        $data = [];
        $cateId = input('param.cate_id', 0, 'intval');
        $desc = input('param.desc', '', 'trim');
        $time = input('param.time', '', 'trim');
        $id = input('param.id');
        if (!empty($desc)) {
            $data['repair_desc'] = $desc;
        }
        if (!empty($cateId)) {
            $data['repair_cate_id'] = $cateId;
        }
        if (!empty($id)) {
            $data['id'] = $id;
        }
        if (!empty($time)) {
            $data['create_time'] = explode(" - ", $time);
        }

        // 查询要导出的数据
        $result = (new RepairService())->getList($data);

        if (!$result) {
            return Show::error('没有数据可导出');
        }

        $data = [];

        foreach ($result as $k => $v){
            $data[$k]['id']=$v['id'];
            $data[$k]['img_url']=$v['img_url'];
            $data[$k]['repair_cate_id']=$v['repair_cate_id'];
            $data[$k]['repair_desc']=$v['repair_desc'];
            $data[$k]['user_id']=$v['user_id'];
            $data[$k]['create_time']=$v['create_time'];
            $data[$k]['approver_id']=$v['approver_id'];
            $data[$k]['repare_id']=$v['repare_id'];
            $data[$k]['progress_bar']=$v['progress_bar'];
            $data[$k]['comments']=$v['comments'];
        }
//        $filename = "报修数据文档".date('YmdHis');
        $filename = "报修数据文档";
        $header = [
            ['column' => 'id', 'name' => '工单号', 'width' => 15],
            ['column' => 'img_url', 'name' => '缩略图', 'width' => 15],
            ['column' => 'repair_cate_id', 'name' => '报修类目', 'width' => 15],
            ['column' => 'repair_desc', 'name' => '故障描述', 'width' => 15],
            ['column' => 'user_id', 'name' => '报修人', 'width' => 15],
            ['column' => 'create_time', 'name' => '报修时间', 'width' => 15],
            ['column' => 'approver_id', 'name' => '审批人', 'width' => 15],
            ['column' => 'repare_id', 'name' => '维修工', 'width' => 15],
            ['column' => 'progress_bar', 'name' => '报修状态', 'width' => 15],
            ['column' => 'comments', 'name' => '评价', 'width' => 15],
        ];
        $download_url=(new ExcelLib())->exportSheelExcel($data,$header,$filename);//获取下载链接

        if($download_url){
            return Show::success(['url' => $download_url]);
        }

        return Show::error();
    }
}
