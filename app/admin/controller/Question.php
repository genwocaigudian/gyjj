<?php
namespace app\admin\controller;

use app\admin\validate\Question as QuestionValidate;
use app\common\lib\Excel as ExcelLib;
use app\common\lib\Show;
use app\common\services\Question as QuestionService;
use app\common\services\QuestionResult as QresultService;
use think\facade\Log;
use think\response\Json;

//问卷调查
class Question extends AdminAuthBase
{
    /**
     * @return Json
     * @throws \think\db\exception\DbException
     */
    public function index()
    {
        $data = [];
        $title = input("param.title", "", "trim");
        $status = input("param.status", "", "trim");

        if(!empty($title)) {
            $data['title'] = $title;
        }
        if(!empty($status)) {
            $data['status'] = $status;
        }
        $list = (new QuestionService())->getPaginateList($data, 10);
        
        return Show::success($list);
    }

    /**
     * 新增
     * @return Json
     */
    public function save()
    {
        if (!$this->request->isPost()) {
            return Show::error('非法请求');
        }
        $data = input('post.');

        $validate = new QuestionValidate();
        if (!$validate->scene('save')->check($data)) {
            return Show::error($validate->getError());
        }

        $data['user_id'] = $this->userId;
        $data['start_time'] = $data['start_time'] . '00:00:00';
        $data['end_time'] = $data['end_time'] . '23:59:59';

        try {
            $result = (new QuestionService())->insertData($data);
        } catch (\Exception $e) {
            Log::error('admin/question/save 错误:' . $e->getMessage());
            return Show::error($e->getMessage(), $e->getCode());
        }

        return Show::success($result);
    }

    /**
     * 详情
     * @return Json
     */
    public function read()
    {
        $id = input('param.id', 0, 'intval');
        try {
            $result = (new QuestionService())->getNormalById($id);
        } catch (\Exception $e) {
            Log::error('admin/question/read 错误:' . $e->getMessage());
            return Show::error($e->getMessage(), $e->getCode());
        }

        return Show::success($result);
    }

    /**
     * 更新数据
     * @return Json
     */
    public function update()
    {
        if (!$this->request->isPost()) {
            return Show::error('非法请求');
        }

        $id = input('param.id', 0, 'intval');
        $data = input('post.');
//        $data = $this->request->only(['is_hot', 'is_top', 'title', 'content'], 'post');

        try {
            $res = (new QuestionService())->update($id, $data);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }

        return Show::success();
    }

    /**
     * 删除数据
     * @return Json
     */
    public function delete()
    {
        if (!$this->request->isPost()) {
            return Show::error('非法请求');
        }

        $id = input("param.id");

        try {
            $res = (new QuestionService())->del($id);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }

        return Show::success();
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
	
	/**
	 * 图表导出
	 * @return Json
	 */
	public function estats()
	{
		$data = input('post.');
		
		$validate = new QuestionValidate();
		if (!$validate->scene('export')->check($data)) {
			return Show::error($validate->getError());
		}
		
		$qid = $data['question_id'];
		
		// 查询要导出的数据
		$result = (new QresultService())->getGroupOptionCount($qid);
		
		if (!$result) {
			return Show::error('没有数据可导出');
		}
		
		$excel = new ExcelLib();
		$download_url = $excel->barSheet($result);
		
		if($download_url){
			return Show::success(['url' => $download_url]);
		}
		
		return Show::error();
	}
}
