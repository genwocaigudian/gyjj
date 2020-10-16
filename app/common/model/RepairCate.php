<?php


namespace app\common\model;

use think\Collection;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

class RepairCate extends BaseModel
{

    /**
     * @param $id
     * @return array|bool|\think\Model|null
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getById($id)
    {
        $id = intval($id);
        if (!$id) {
            return false;
        }
        $res = $this->find($id);
        return $res;
    }

    /**
     * @param $where
     * @param string $field
     * @return Collection
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getList($where, $field = "*")
    {
        $order = [
            'id' => 'desc'
        ];
        $result = $this->where($where)->field($field)->order($order)->select();
//        echo $res->getLastSql();exit;
        return $result;
    }

    /**
     * getChildListInPids
     * @param $condition
     * @return mixed
     */
    public function getChildListInPids($condition) {
        $where[] = ["pid", "in", $condition['pid']];
        $res = $this->where($where)
            ->field(["id", "pid", "name"])
//            ->group("pid")
            ->select();
//        echo $this->getLastSql();exit;
        return $res;
    }
}
