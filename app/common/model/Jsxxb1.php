<?php


namespace app\common\model;

use think\Model;

//教师信息表
class Jsxxb1 extends Model
{
    protected $connection = 'oracle';
    protected $table = 'zfxfzb.v_jsxxb';

    /**
     * @param $zgh
     * @return array|bool|Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getByZGH($zgh)
    {
        if (!$zgh) {
            return false;
        }
        $res = $this->where(['ZGH' => $zgh])->find();
        return $res;
    }
}