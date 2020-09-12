<?php
namespace app\admin\controller;

use app\api\controller\ApiBase;
use \app\common\lib\Excel as ExcelLib;
use app\common\lib\Show;
use think\facade\Db;

class Excel extends ApiBase
{
    public function export(){
        // 查询要导出的数据
        $result = Db::name('repair')->select();

        if (!$result) {
            return Show::error('没有数据可导出');
        }

        $data = [];

        foreach ($result as $k => $v){
            $data[$k]['img_url']=$v['img_url'];
            $data[$k]['repare_cate_id']=$v['repare_cate_id'];
            $data[$k]['user_id']=$v['user_id'];
            $data[$k]['approver_id']=$v['approver_id'];
            $data[$k]['repare_id']=$v['repare_id'];
            $data[$k]['mobile']=$v['mobile'];
        }
//        $filename = "报修数据文档".date('YmdHis');
        $filename = "报修数据文档";
        $header = array(
            array('column' => 'id', 'name' => '课程id', 'width' => 15),
            array('column' => 'course_name', 'name' => '课程名称', 'width' => 15),
            array('column' => 'learn_style', 'name' => '学习方式', 'width' => 30),
            array('column' => 'duration', 'name' => '课程时长', 'width' => 15),
            array('column' => 'teacher', 'name' => '授课老师', 'width' => 35),
            array('column' => 'cid', 'name' => '所属分类', 'width' => 15),
            array('column' => 'knowledge', 'name' => '所属知识体系', 'width' => 15),
            array('column' => 'syllabus', 'name' => '课程大纲', 'width' => 15),
        );
        $header = [
            ['column' => 'img_url', 'name' => 'img_url', 'width' => 15],
            ['column' => 'repare_cate_id', 'name' => 'repare_cate_id', 'width' => 15],
            ['column' => 'user_id', 'name' => 'user_id', 'width' => 15],
            ['column' => 'approver_id', 'name' => 'approver_id', 'width' => 15],
            ['column' => 'repare_id', 'name' => 'repare_id', 'width' => 15],
            ['column' => 'mobile', 'name' => 'mobile', 'width' => 15],
        ];
        $excel=new ExcelLib();
        $download_url=$excel->exportSheelExcel($data,$header,$filename,'Xlsx', 1);//获取下载链接
        if($download_url){
            return Show::success();
        }

        return Show::error();
    }
}
