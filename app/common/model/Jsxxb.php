<?php


namespace app\common\model;

use think\Model;

//教师信息表
class Jsxxb extends Model
{
    /**
     * @param $zgh
     * @return array|bool|Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getByZGH($zgh)
    {
        $field = 'xn,xq,bjmc,jsxm,skdd,,kcmc,xqj,djj';
        if (!$zgh) {
            return false;
        }
        $res = $this->where(['ZGH' => $zgh])->field($field)->group('xn');
        return $res;
    }
}
