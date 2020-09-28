<?php


namespace app\common\model;

use think\Model;

//老师课程表
class Jskcb extends Model
{
    protected $connection = 'oracle';
    protected $table = 'zfxfzb.v_jskb';

    /**
     * @param $zgh
     * @return array|bool|Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getListByZGH($zgh)
    {
        $field = 'xn,xq,bjmc,kcmc,xqj,xm';
        if (!$zgh) {
            return false;
        }
        $res = $this->where(['jszgh' => $zgh])->field($field)->order('xn desc')->select();
        return $res;
    }
}
