<?php
namespace app\admin\controller;

use app\admin\services\AdminUser;
use app\admin\validate\Lottery as LotteryValidate;
use app\common\lib\Show;
use app\common\services\Lottery as LotteryService;
use think\facade\Cache;
use think\facade\Log;
use think\response\Json;

class Lottery extends AdminAuthBase
{
    /**
     * @return Json
     * @throws \think\db\exception\DbException
     */
    public function index()
    {
        $data = [];
        $title = input('param.title', '', 'trim');
        $status = input('param.status', '', 'trim');
        if (!empty($title)) {
            $data['title'] = $title;
        }
        if (!empty($status)) {
            $data['status'] = $status;
        }

        $list = (new LotteryService())->getPaginateList($data, 10);
        
        return Show::success($list);
    }

    /**
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function save()
    {
        if (!$this->request->isPost()) {
            return Show::error('非法请求');
        }
        $data = input('post.');

        $validate = new LotteryValidate();
        if (!$validate->scene('save')->check($data)) {
            return Show::error($validate->getError());
        }

        $adminUserInfo = (new AdminUser())->getNormalUserById($data['user_id']);
        $userInfo = (new \app\common\services\User())->getNormalUserByNumber($adminUserInfo['number']);
        $data['user_id'] = $userInfo['id'];
        $data['start_time'] = $data['start_time'] . '00:00:00';
        $data['end_time'] = $data['end_time'] . '23:59:59';

        try {
            $result = (new LotteryService())->insertData($data);
            Cache::zAdd(config("rediskey.lottery_status_key"), strtotime($data['end_time']), $result['id']);
        } catch (\Exception $e) {
            Log::error('admin/lottery/save 错误:' . $e->getMessage());
            return Show::error($e->getMessage());
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
            $result = (new LotteryService())->getNormalById($id);
        } catch (\Exception $e) {
            Log::error('admin/lottery/read 错误:' . $e->getMessage());
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
            $res = (new LotteryService())->update($id, $data);
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
            $res = (new LotteryService())->del($id);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }

        return Show::success();
    }
}
