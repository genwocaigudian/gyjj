<?php


namespace app\common\services;

use app\common\lib\Str;
use think\facade\Log;
use think\facade\Request;

class BaseServices
{
    /**
     * 新增
     * @param $data
     * @return int
     */
    public function add($data)
    {
        $data['status'] = config("status.mysql.table_normal");
        try {
            $this->model->save($data);
        } catch (\Exception $e) {
            Log::error('错误信息:' . $e->getMessage());
            return 0;
        }

        return $this->model->id;
    }

    /**
     * 批量新增
     * @param $data
     * @return bool|int
     */
    public function addAll($data)
    {
        try {
            $res = $this->model->saveAll($data);
        } catch (\Exception $e) {
            Log::error('错误信息:' . $e->getMessage());
            return 0;
        }

        return true;
    }
}
