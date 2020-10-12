<?php
namespace app\admin\controller;

use app\admin\validate\Proctor as ProctorValidate;
use app\common\lib\Excel as ExcelLib;
use app\common\lib\Show;
use app\common\services\Proctor as ProctorService;
use think\facade\Log;
use think\response\Json;

//监考
class Proctor extends AdminAuthBase
{
    /**
     * @return Json
     * @throws \think\db\exception\DbException
     */
    public function index()
    {
        $data = [];
        $number = input("param.number", "", "trim");

        if(!empty($number)) {
            $data['number'] = $number;
        }
        $list = (new ProctorService())->getPaginateList($data, 10);
        
        return Show::success($list);
    }

    /**
     * @return Json
     */
    public function save()
    {
        if (!$this->request->isPost()) {
            return Show::error('非法请求');
        }
        $data = input('post.');

        $validate = new ProctorValidate();
        if (!$validate->scene('save')->check($data)) {
            return Show::error($validate->getError());
        }

        try {
            $result = (new ProctorService())->insertData($data);
        } catch (\Exception $e) {
            Log::error('admin/proctor/save 错误:' . $e->getMessage());
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
            $result = (new ProctorService())->getNormalById($id);
        } catch (\Exception $e) {
            Log::error('admin/question/read 错误:' . $e->getMessage());
            return Show::error($e->getMessage());
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

        try {
            $res = (new ProctorService())->update($id, $data);
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
            $res = (new ProctorService())->del($id);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }

        return Show::success();
    }

    /**
     * @return Json
     */
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
                'number' => $datum['A'],
                'date' => $datum['B'],
                'time_period' => $datum['C'],
                'subject' => $datum['D'],
                'place' => $datum['E'],
            ];
            array_push($insertData, $temp);
        }

        $res = (new ProctorService())->addAll($insertData);
        if (!$res) {
            return Show::error('插入失败');
        }
        return Show::success();
    }

    /**
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function export()
    {
        // 查询要导出的数据
        $result = (new ProctorService())->getNormalList();

        if (!$result) {
            return Show::error('没有数据可导出');
        }

        $data = [];

        foreach ($result as $k => $v){
            $data[$k]['number']=$v['number'];
            $data[$k]['date']=$v['date'];
            $data[$k]['time_period']=$v['time_period'];
            $data[$k]['subject']=$v['subject'];
            $data[$k]['place']=$v['place'];
        }
        $filename = "监考数据模板";
        $header = [
            ['column' => 'number', 'name' => '职工号', 'width' => 15],
            ['column' => 'date', 'name' => '日期', 'width' => 15],
            ['column' => 'time_period', 'name' => '时间段', 'width' => 15],
            ['column' => 'subject', 'name' => '科目', 'width' => 15],
            ['column' => 'place', 'name' => '地点', 'width' => 30],
        ];
        $download_url=(new ExcelLib())->exportSheelExcel($data,$header,$filename);//获取下载链接

        if($download_url){
            return Show::success(['url' => $download_url]);
        }

        return Show::error();
    }
}
