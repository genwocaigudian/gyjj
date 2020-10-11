<?php


namespace app\common\model;

use think\Model;

//学生课程表
class Xskcb extends Model
{
    protected $connection = 'schedule';
    protected $table = 'zfxfzb.v_xskcb';

    /**
     * 根据学号获取学生课表
     * @param $number
     * @param $xn
     * @param $xq
     * @return array|bool|Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getList($number, $xn = '' , $xq = 1)
    {
        $field = 'xn,xq,bjmc,kcmc,xqj,xm';
        if (!$number) {
            return false;
        }
        $res = $this->where(['xh' => $number])->field($field)->order('xn desc')->select();
        return $res;
    }

    /**
     * @param $xh
     * @return array|bool|Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getGroup($xh)
    {
        $field = 'xn,xq,bjmc,kcmc,xqj,xm';
        if (!$xh) {
            return false;
        }
        $res = $this->where(['jszgh' => $xh])->field('xn')->group('xn')->order('xn desc')->select();
//        $res = $this->where(['jszgh' => $zgh])->field('xn,xq')->group('xn,xq')->order('xn desc')->select();
        return $res;
    }
}
