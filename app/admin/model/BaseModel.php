<?php


namespace app\admin\model;

use think\Model;

class BaseModel extends Model
{
    protected $autoWriteTimestamp = true;

    public function updateById($id, $data)
    {
        $data['update_time'] = time();
        return $this->where(["id" => $id])->save($data);
    }

    /**
     * 根据条件查询
     * @param array $condition
     * @param array $order
     * @return bool|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getByCondition($condition = [], $order = ["id" => "desc"])
    {
        if (!$condition || !is_array($condition)) {
            return false;
        }
        $result = $this->where($condition)
            ->order($order)
            ->select();

        ///echo $this->getLastSql();exit;
        return $result;
    }
	
	/**
	 * @param $id
	 * @return bool
	 */
	public function deleteById($id)
	{
		return $this->where('id', '=', $id)->delete();
	}
}
