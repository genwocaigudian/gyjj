<?php


namespace app\common\model;

use think\Model;

//图书列表
class BookList extends Model
{

    /**
     * @param $data
     * @return array
     * @throws \think\db\exception\DbException
     */
    public function getPaginateList($data)
    {
        $where = [];
        if (isset($data['title']) && !empty($data['title'])) {
            $where[] = ['m_title', 'like', "%{$data['title']}%"];
        }
        $res = $this->where($where)->paginate();
        //echo $this->getLastSql();exit();
        $res = $res->toArray();
        return $res;
    }
}
