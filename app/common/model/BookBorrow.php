<?php

namespace app\common\model;

use think\Model;

//图书借阅信息 校方接口
class BookBorrow extends Model
{
    protected $connection = 'book';
    protected $table = 'zfxfzb.vlend_all';

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

    /**
     * 未归还记录
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function noReturned()
    {
        $res = $this->limit(10)->select();
        return $res;
    }
}
