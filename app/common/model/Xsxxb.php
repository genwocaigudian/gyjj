<?php


namespace app\common\model;

use think\Model;

//学生信息表
class Xsxxb extends Model
{
    protected $connection = 'oracle';
    protected $table = 'zfxfzb.v_xsxxb';

    /**
     * @param $xh
     * @return array|bool|Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getByXh($xh)
    {
        if (!$xh) {
            return false;
        }
        $res = $this->where(['XH' => $xh])->find();
        return $res;
    }
}
