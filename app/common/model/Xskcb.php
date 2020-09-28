<?php


namespace app\common\model;

use think\Model;

//学生课程表
class Xskcb extends Model
{
    protected $connection = 'oracle';
    protected $table = 'zfxfzb.v_xskcb';

    /**
     * 根据学号获取学生课表
     * @param $xh
     * @return array|bool|Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getListByXH($xh)
    {
        $field = 'xn,xq,bjmc,jsxm,skdd,,kcmc,xqj,djj';
        if (!$xh) {
            return false;
        }
        $res = $this->where(['xh' => $xh])->field($field)->group('xn');
        return $res;
    }
}
