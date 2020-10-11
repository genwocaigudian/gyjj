<?php

namespace app\common\model;

use think\Model;

//我的 未还借阅记录
class BookBorrow extends Model
{
    protected $connection = 'book';
    protected $table = 'vlend_all';

    /**
     * @param $id
     * @return array
     * @throws \think\db\exception\DbException
     */
    public function getPaginateListById($id)
    {
        $where = [
            'cert_id_f' => $id
        ];
        $field = 'm_title, lend_date, norm_ret_date';
        $res = $this->where($where)->field($field)->paginate();
        $res = $res->toArray();
        return $res;
    }
}
