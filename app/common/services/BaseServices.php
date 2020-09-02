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
    public function add($data) {
        try {
            $this->model->save($data);
        }catch (\Exception $e) {
            // 记录日志哦，便于后续问题的排查工作
            Log::error('错误信息:' . $e->getMessage());
            return 0;
        }

        return $this->model->id;
    }
}