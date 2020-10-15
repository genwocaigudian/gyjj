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
            ['column' => 'month', 'name' => '发放月份', 'width' => 15],
            ['column' => 'number', 'name' => '职工号', 'width' => 15],
            ['column' => 'username', 'name' => '人员名称', 'width' => 15],
            ['column' => 'department', 'name' => '统发工资合计', 'width' => 15],
            ['column' => 'basic_salary', 'name' => '岗位工资', 'width' => 15],
            ['column' => 'post_salary', 'name' => '薪级工资', 'width' => 15],
            ['column' => 'attendance_deduction', 'name' => '基础性绩效工资', 'width' => 15],
            ['column' => 'other_deduction', 'name' => '教护提高10%', 'width' => 15],
            ['column' => 'social_security', 'name' => '统发补发', 'width' => 15],
            ['column' => 'provident_fund', 'name' => '代发工资合计', 'width' => 15],
            ['column' => 'taxes', 'name' => '教、护龄津贴', 'width' => 15],
            ['column' => 'paid_salary', 'name' => '房贴', 'width' => 15],
            ['column' => 'paid_salary', 'name' => '独生子女费', 'width' => 15],
            ['column' => 'paid_salary', 'name' => '回民补贴', 'width' => 15],
            ['column' => 'paid_salary', 'name' => '交通补贴', 'width' => 15],
            ['column' => 'paid_salary', 'name' => '代发补发', 'width' => 15],
            ['column' => 'paid_salary', 'name' => '代扣工资合计', 'width' => 15],
            ['column' => 'paid_salary', 'name' => '公积金', 'width' => 15],
            ['column' => 'paid_salary', 'name' => '医保', 'width' => 15],
            ['column' => 'paid_salary', 'name' => '失业保险', 'width' => 15],
            ['column' => 'paid_salary', 'name' => '养老保险', 'width' => 15],
            ['column' => 'paid_salary', 'name' => '职业年金', 'width' => 15],
            ['column' => 'paid_salary', 'name' => '工会会费', 'width' => 15],
            ['column' => 'paid_salary', 'name' => '单位代扣个税', 'width' => 15],
            ['column' => 'paid_salary', 'name' => '房租', 'width' => 15],
            ['column' => 'paid_salary', 'name' => '其他代扣', 'width' => 15],
            ['column' => 'paid_salary', 'name' => '应发工资合计', 'width' => 15],
            ['column' => 'paid_salary', 'name' => '基本工资', 'width' => 15],
            ['column' => 'paid_salary', 'name' => '校龄津贴', 'width' => 15],
            ['column' => 'paid_salary', 'name' => '其他补发', 'width' => 15],
            ['column' => 'paid_salary', 'name' => '应发工资', 'width' => 15],
            ['column' => 'paid_salary', 'name' => '公积金1', 'width' => 15],
            ['column' => 'paid_salary', 'name' => '医疗保险1', 'width' => 15],
            ['column' => 'paid_salary', 'name' => '失业保险1', 'width' => 15],
            ['column' => 'paid_salary', 'name' => '养老保险1', 'width' => 15],
            ['column' => 'paid_salary', 'name' => '工会会费1', 'width' => 15],
            ['column' => 'paid_salary', 'name' => '单位代扣个税1', 'width' => 15],
            ['column' => 'paid_salary', 'name' => '其他代扣1', 'width' => 15],
            ['column' => 'paid_salary', 'name' => '代扣工资小计', 'width' => 15],
            ['column' => 'paid_salary', 'name' => '实发工资', 'width' => 15],
            ['column' => 'paid_salary', 'name' => '奖励性绩效', 'width' => 15],
            ['column' => 'paid_salary', 'name' => '课时津贴', 'width' => 15],
            ['column' => 'paid_salary', 'name' => '值班费', 'width' => 15],
            ['column' => 'paid_salary', 'name' => '考务费', 'width' => 15],
            ['column' => 'paid_salary', 'name' => '技能大赛奖补', 'width' => 15],
            ['column' => 'paid_salary', 'name' => '职教高考奖补', 'width' => 15],
            ['column' => 'paid_salary', 'name' => '其他奖补', 'width' => 15],
            ['column' => 'paid_salary', 'name' => '医疗费', 'width' => 15],
            ['column' => 'paid_salary', 'name' => '伙食补助', 'width' => 15],
            ['column' => 'paid_salary', 'name' => '慰问费', 'width' => 15],
            ['column' => 'paid_salary', 'name' => '其他1', 'width' => 15],
            ['column' => 'paid_salary', 'name' => '其他2', 'width' => 15],
        ];
        $download_url=(new ExcelLib())->exportSheelExcel($data,$header,$filename);//获取下载链接

        if($download_url){
            return Show::success(['url' => $download_url]);
        }

        return Show::error();
    }

    public function import()
    {
        $excel = new ExcelLib();
        $data = $excel->importExcel();

        if (empty($data)) {
            return Show::error('导入内容为空');
        }

        $insertData = [];
        foreach ($data as $datum) {
            $temp = [
                'username' => $datum['A'],
                'month' => $datum['B'],
                'card_number' => $datum['C'],
                'department' => $datum['D'],
                'basic_salary' => $datum['E'],
                'post_salary' => $datum['F'],
                'attendance_deduction' => $datum['G'],
                'other_deduction' => $datum['H'],
                'social_security' => $datum['I'],
                'provident_fund' => $datum['J'],
                'taxes' => $datum['K'],
                'paid_salary' => $datum['L'],
                'status' => config('status.mysql.table_normal'),
            ];
            array_push($insertData, $temp);
        }

        $res = (new SalaryService())->addAll($insertData);
        if (!$res) {
            return Show::error('插入失败');
        }
        return Show::success();
    }
}
