<?php
namespace app\admin\controller;

use app\admin\validate\Repair as RepairValidate;
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
        $data = [];
        $list = (new RepairService())->getPaginateList($data,10);
        
        return Show::success($list);
    }

    /**
     * @return Json
     */
    public function export()
    {
        $data = [];
        $input = input('param.');
	
	    $validate = new RepairValidate();
	    if (!$validate->scene('export')->check($input)) {
		    return Show::error($validate->getError());
	    }
	
	    $cateId = $input['repair_cate_id'];
	    $time = $input['time']??'';
	    
        if (!empty($cateId)) {
            $data['repair_cate_id'] = $cateId;
        }
        if (!empty($time)) {
            $data['create_time'] = explode(" - ", $time);
        }

        // 查询要导出的数据
        $result = (new RepairService())->getExportList($data);

        if (!$result) {
            return Show::error('没有数据可导出');
        }
        
        $cate = (new \app\common\services\RepairCate())->getNormalById($cateId);

        $data = [];

        foreach ($result as $k => $v){
            $data[$k]['cate_name']=$cate['name']??'';
            $data[$k]['count']=$v['count']??0;
        }
//        $filename = "报修数据文档".date('YmdHis');
        $filename = "报修数据文档";
        $header = [
            ['column' => 'cate_name', 'name' => '分类名称', 'width' => 15],
            ['column' => 'count', 'name' => '报修总数', 'width' => 15],
        ];
        $download_url=(new ExcelLib())->exportSheelExcel($data,$header,$filename);//获取下载链接

        if($download_url){
            return Show::success(['url' => $download_url]);
        }

        return Show::error();
    }
}
