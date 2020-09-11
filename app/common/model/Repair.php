<?php


namespace app\common\model;

use think\Collection;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Model;

class Repair extends BaseModel
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
     * @param string $field
     * @return Collection
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getNormalList($field = "*")
    {
        $where = [
            "status" => config("status.mysql.table_normal"),
        ];

        $query = $this->newQuery();
        $result = $query->where($where)
            ->field($field)
//            ->order($order)
            ->select();
        //echo $this->getLastSql();exit;
        return $result;
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
        $query = $this->newQuery();
        if ($where) {
            $query->where($where);
        }

        $result = $query->field($field)->select();
        //echo $this->getLastSql();exit;
        return $result;
    }

    /**
     * @param string $field
     * @param int $num
     * @return \think\Paginator
     * @throws DbException
     */
    public function getPaginateList($field = "*", $num = 10)
    {
        $result = $this->newQuery()->field($field)->paginate($num);
        //echo $this->getLastSql();exit;
        return $result;
    }

    /**
     * 根据主键ID更新数据表中的数据
     * @param $id
     * @param $data
     * @return bool
     */
    public function deleteById($id, $data)
    {
        if (empty($id) || empty($data) || !is_array($data)) {
            return false;
        }

        $where = [];

        if (is_array($id)) {
            $where[] = ['id', 'in', $id];
        } else {
            $where[] = ['id', '=', intval($id)];
        }

        $res = $this->where($where)->save($data);
        //echo $this->getLastSql();exit;
        return $res;
    }
}
