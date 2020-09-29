<?php

namespace app\common\model;

use think\Model;

//图书未归还记录 校方接口
class BookReturn extends Model
{
    protected $connection = 'book';
    protected $table = 'zfxfzb.v_lend_lst';

    /**
     * 获取书目信息
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function get()
    {
        $res = $this->limit(10)->select();
        return $res;
    }
}
