<?php

namespace app\common\model;

use think\Model;

//书目信息 校方接口
class BookInfo extends Model
{
    protected $connection = 'book';
    protected $table = 'zfxfzb.v_marc';

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
