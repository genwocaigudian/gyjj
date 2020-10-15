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
            ['column' => 'tfgzhj', 'name' => '统发工资合计', 'width' => 15],
            ['column' => 'gwgz', 'name' => '岗位工资', 'width' => 15],
            ['column' => 'xjgz', 'name' => '薪级工资', 'width' => 15],
            ['column' => 'jcxjxgz', 'name' => '基础性绩效工资', 'width' => 15],
            ['column' => 'jhtg', 'name' => '教护提高10%', 'width' => 15],
            ['column' => 'tfbf', 'name' => '统发补发', 'width' => 15],
            ['column' => 'dfgzhj', 'name' => '代发工资合计', 'width' => 15],
            ['column' => 'jhljt', 'name' => '教、护龄津贴', 'width' => 15],
            ['column' => 'ft', 'name' => '房贴', 'width' => 15],
            ['column' => 'dszvf', 'name' => '独生子女费', 'width' => 15],
            ['column' => 'hmbt', 'name' => '回民补贴', 'width' => 15],
            ['column' => 'jtbt', 'name' => '交通补贴', 'width' => 15],
            ['column' => 'dfbf', 'name' => '代发补发', 'width' => 15],
            ['column' => 'dkgzhj', 'name' => '代扣工资合计', 'width' => 15],
            ['column' => 'gjj', 'name' => '公积金', 'width' => 15],
            ['column' => 'yb', 'name' => '医保', 'width' => 15],
            ['column' => 'sybx', 'name' => '失业保险', 'width' => 15],
            ['column' => 'ylbx', 'name' => '养老保险', 'width' => 15],
            ['column' => 'zynj', 'name' => '职业年金', 'width' => 15],
            ['column' => 'ghhf', 'name' => '工会会费', 'width' => 15],
            ['column' => 'dwdkgs', 'name' => '单位代扣个税', 'width' => 15],
            ['column' => 'fz', 'name' => '房租', 'width' => 15],
            ['column' => 'qtdk', 'name' => '其他代扣', 'width' => 15],
            ['column' => 'yfgzhj', 'name' => '应发工资合计', 'width' => 15],
            ['column' => 'jbgz', 'name' => '基本工资', 'width' => 15],
            ['column' => 'xljt', 'name' => '校龄津贴', 'width' => 15],
            ['column' => 'qtbf', 'name' => '其他补发', 'width' => 15],
            ['column' => 'yfgz', 'name' => '应发工资', 'width' => 15],
            ['column' => 'gjj1', 'name' => '公积金1', 'width' => 15],
            ['column' => 'ylbx1', 'name' => '医疗保险1', 'width' => 15],
            ['column' => 'sybx1', 'name' => '失业保险1', 'width' => 15],
            ['column' => 'ylbx1', 'name' => '养老保险1', 'width' => 15],
            ['column' => 'ghhf1', 'name' => '工会会费1', 'width' => 15],
            ['column' => 'dwdkgs1', 'name' => '单位代扣个税1', 'width' => 15],
            ['column' => 'qtdk1', 'name' => '其他代扣1', 'width' => 15],
            ['column' => 'dkgzxj', 'name' => '代扣工资小计', 'width' => 15],
            ['column' => 'sfgz', 'name' => '实发工资', 'width' => 15],
            ['column' => 'jlxjx', 'name' => '奖励性绩效', 'width' => 15],
            ['column' => 'ksjt', 'name' => '课时津贴', 'width' => 15],
            ['column' => 'zbf', 'name' => '值班费', 'width' => 15],
            ['column' => 'kwf', 'name' => '考务费', 'width' => 15],
            ['column' => 'jndsjb', 'name' => '技能大赛奖补', 'width' => 15],
            ['column' => 'zjgkjb', 'name' => '职教高考奖补', 'width' => 15],
            ['column' => 'qtjb', 'name' => '其他奖补', 'width' => 15],
            ['column' => 'ylf', 'name' => '医疗费', 'width' => 15],
            ['column' => 'hsbz', 'name' => '伙食补助', 'width' => 15],
            ['column' => 'wwf', 'name' => '慰问费', 'width' => 15],
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
        	$date = explode('.', $datum['A']);
        	$month = $date[0].'-'.$date[1];
        	$month = date('Y-m', strtotime($month));
            $temp = [
                'month' => $month,
                'number' => $datum['B'],
                'username' => $datum['C'],
                'tfgzhj' => $datum['D'],
                'gwgz' => $datum['E'],
                'xjgz' => $datum['F'],
                'jcxjxgz' => $datum['G'],
                'jhtg' => $datum['H'],
                'tfbf' => $datum['I'],
                'dfgzhj' => $datum['J'],
                'jhljt' => $datum['K'],
                'ft' => $datum['L'],
                'dszvf' => $datum['M'],
                'hmbt' => $datum['N'],
                'jtbt' => $datum['O'],
                'dfbf' => $datum['P'],
                'dkgzhj' => $datum['Q'],
                'gjj' => $datum['R'],
                'yb' => $datum['S'],
                'sybx' => $datum['T'],
                'ylbx' => $datum['U'],
                'zynj' => $datum['V'],
                'ghhf' => $datum['W'],
                'dwdkgs' => $datum['X'],
                'fz' => $datum['Y'],
                'qtdk' => $datum['Z'],
                'yfgzhj' => $datum['AA'],
                'sfgzhj' => $datum['AB'],
                'jbgz' => $datum['AC'],
                'xljt' => $datum['AD'],
                'qtbf' => $datum['AE'],
                'yfgz' => $datum['AF'],
                'gjj1' => $datum['AG'],
                'ylbx1' => $datum['AH'],
                'sybx1' => $datum['AI'],
                'ylbx11' => $datum['AJ'],
                'ghhf1' => $datum['AK'],
                'dwdkgs1' => $datum['AL'],
                'qtdk1' => $datum['AM'],
                'dkgzxj' => $datum['AN'],
                'sfgz' => $datum['AO'],
                'jlxjx' => $datum['AP'],
                'ksjt' => $datum['AQ'],
                'zbf' => $datum['AR'],
                'kwf' => $datum['AS'],
                'jndsjb' => $datum['AT'],
                'zjgkjb' => $datum['AU'],
                'qtjb' => $datum['AV'],
                'ylf' => $datum['AW'],
                'hsbz' => $datum['AX'],
                'wwf' => $datum['AY'],
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
