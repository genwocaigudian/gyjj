<?php


namespace app\common\model;

use think\Model;

//图书列表
class BookList extends Model
{
    protected $connection = 'book';
    protected $table = 'zfxfzb.v_marc';

    /**
     * @param $data
     * @return array
     * @throws \think\db\exception\DbException
     */
    public function getPaginateList($data)
    {
        $where = [];
        $field = 'M_TITLE, M_AUTHOR, M_PUB_YEAR, M_PUBLISHER';
        if ($data) {
            $where = ['M_TITLE', 'like', "%{$data['title']}%"];
        }
        $res = $this->where($where)->field($field)->paginate();
        $res = $res->toArray();
        return $res;
    }
}
