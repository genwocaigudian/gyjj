<?php


namespace app\common\services;

use think\facade\Log;

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
}
