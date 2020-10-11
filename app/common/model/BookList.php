<?php


namespace app\common\model;

use think\Model;

//图书列表
class BookList extends Model
{
    protected $connection = 'book';
    protected $table = 'v_marc';

    /**
     * @param $data
     * @return array
     * @throws \think\db\exception\DbException
     */
    public function getPaginateList($data)
    {
        $where = [];
        $field = 'm_title, m_author, m_pub_year, m_publisher';
        if (isset($data['title']) && !empty($data['title'])) {
            $where[] = ['m_title', 'like', "%{$data['title']}%"];
        }
        $res = $this->where($where)->field($field)->paginate();
        //echo $this->getLastSql();exit();
        $res = $res->toArray();
        return $res;
    }
}
