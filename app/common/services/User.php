<?php


namespace app\common\services;

use app\common\model\User as UserModel;
use think\facade\Log;

class User extends BaseServices
{
    public $model = null;

    public function __construct()
    {
        $this->model = new UserModel();
    }

    /**
     * 新增逻辑
     */
    public function add($data) {
//        $data['status'] = config("status.mysql.table_normal");
        try {
            $this->model->save($data);
        }catch (\Exception $e) {
            // 记录日志哦，便于后续问题的排查工作
            Log::error('错误信息:' . $e->getMessage());
            return 0;
        }

        // // 返回主键ID
        return $this->model->id;
    }
}