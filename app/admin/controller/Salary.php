<?php
namespace app\admin\controller;

use app\common\lib\Excel as ExcelLib;
use app\common\lib\Show;
use app\common\services\Salary as SalaryService;
use think\response\Json;

class Salary extends AdminAuthBase
{
    /**
     * @return Json
     * @throws \think\db\exception\DbException
     */
    public function index()
    {
        $data = [];
        $list = (new SalaryService())->getPaginateList($data, 10);
        
        return Show::success($list);
    }

    /**
     * @return Json
     */
    public function export()
    {
        $data = [];
        $name = input('param.name', '', 'trim');
        $number = input('param.number', '', 'trim');
        $month = input('param.month', '', 'trim');
        $id = input('param.id');
        if (!empty($name)) {
            $data['username'] = $name;
        }
        if (!empty($number)) {
            $data['card_number'] = $number;
        }
        if (!empty($id)) {
            $data['id'] = $id;
        }
        if (!empty($month)) {
            $data['month'] = $month;
        }

        // 查询要导出的数据
        $result = (new SalaryService())->getList($data);

        if (!$result) {
            return Show::error('没有数据可导出');
        }

        $data = [];

        foreach ($result as $k => $v){
            $data[$k]['id']=$v['id'];
            $data[$k]['username']=$v['username'];
            $data[$k]['card_number']=$v['card_number'];
            $data[$k]['department']=$v['department'];
            $data[$k]['basic_salary']=$v['basic_salary'];
            $data[$k]['post_salary']=$v['post_salary'];
            $data[$k]['attendance_deduction']=$v['attendance_deduction'];
            $data[$k]['other_deduction']=$v['other_deduction'];
            $data[$k]['social_security']=$v['social_security'];
            $data[$k]['provident_fund']=$v['provident_fund'];
            $data[$k]['taxes']=$v['taxes'];
            $data[$k]['paid_salary']=$v['paid_salary'];
            $data[$k]['month']=$v['month'];
        }
//        $filename = "报修数据文档".date('YmdHis');
        $filename = "薪酬数据文档";
        $header = [
            ['column' => 'id', 'name' => '编号', 'width' => 15],
            ['column' => 'username', 'name' => '姓名', 'width' => 15],
            ['column' => 'month', 'name' => '月份', 'width' => 15],
            ['column' => 'card_number', 'name' => '老师编号', 'width' => 15],
            ['column' => 'department', 'name' => '部门', 'width' => 15],
            ['column' => 'basic_salary', 'name' => '基本工资', 'width' => 15],
            ['column' => 'post_salary', 'name' => '岗位工资', 'width' => 15],
            ['column' => 'attendance_deduction', 'name' => '考勤扣款', 'width' => 15],
            ['column' => 'other_deduction', 'name' => '其他扣款', 'width' => 15],
            ['column' => 'social_security', 'name' => '社保', 'width' => 15],
            ['column' => 'provident_fund', 'name' => '公积金', 'width' => 15],
            ['column' => 'taxes', 'name' => '个税', 'width' => 15],
            ['column' => 'paid_salary', 'name' => '应发工资', 'width' => 15],
        ];
        $download_url=(new ExcelLib())->exportSheelExcel($data,$header,$filename);//获取下载链接

        if($download_url){
            return Show::success(['url' => $download_url]);
        }

        return Show::error();
    }
}
