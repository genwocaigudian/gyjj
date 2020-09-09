<?php
namespace app\admin\controller;

use app\admin\services\AdminUser as AdminUserService;
use app\admin\validate\AdminUser as AdminUserValidate;
use app\common\lib\Arr;
use app\common\lib\Show;
use think\facade\Log;
use think\response\Json;

class AdminUser extends AdminAuthBase
{
    /**
     * @return Json
     */
    public function index()
    {
        $status = input("param.status", 1, "intval");
        $data = [
            "status" => $status,
        ];
        try {
            $list = (new AdminUserService())->getLists($data, 10);
        } catch (\Exception $e) {
            $list = Arr::getPaginateDefaultData(10);
        }
        
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
        
        $validate = new AdminUserValidate();
        if (!$validate->scene('save')->check($data)) {
            return Show::error($validate->getError());
        }

        $data['last_login_ip'] = $this->request->ip();
        
        try {
            $result = (new AdminUserService())->insertData($data);
        } catch (\Exception $e) {
            Log::error('admin/user/save 错误:' . $e->getMessage());
            return Show::error($e->getMessage(), $e->getCode());
        }
        
        return Show::success($result);
    }

    /**
     * 详情
     * @param $id
     * @return Json
     */
    public function read($id)
    {
        try {
            $result = (new AdminUserService())->getNormalUserById($this->userId);
        } catch (\Exception $e) {
            Log::error('admin/user/read 错误:' . $e->getMessage());
            return Show::error($e->getMessage(), $e->getCode());
        }

        return Show::success($result);
    }

    /**
     * 更新数据
     * @param $id
     * @return Json
     */
    public function update($id)
    {
        if (!$this->request->isPut()) {
            return Show::error('非法请求');
        }

        $data = input('post.');

        try {
            $res = (new AdminUserService())->update($id, $data);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }

        return Show::success();
    }

    /**
     * 删除数据
     * @param $id
     * @return Json
     */
    public function delete($id)
    {
        if (!$this->request->isDelete()) {
            return Show::error('非法请求');
        }

        try {
            $res = (new AdminUserService())->delete($id);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }

        return Show::success();
    }
}
