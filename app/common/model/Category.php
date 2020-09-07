<?php


namespace app\common\model;

use think\Collection;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Model;

class Category extends BaseModel
{
    /**
     * @param string $field
     * @return Collection
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getNormalCategorys($field = "*")
    {
        $where = [
            "status" => config("status.mysql.table_normal"),
        ];
        
        $order = [
            "sequence" => "desc",
            "id" => "asc"
        ];
        $result = $this->where($where)
            ->field($field)
            ->order($order)
            ->select();
        
        return $result;
    }
    
    /**
     * 根据name查询数据
     * @param $name
     * @return array|bool|Model|null
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getCateByName($name)
    {
        if (empty($name)) {
            return false;
        }
        
        $where = [
            'name' => $name
        ];
        
        return $this->where($where)->find();
    }
    
    /**
     * @param $id
     * @return array|bool|Model|null
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getCateById($id)
    {
        $id = intval($id);
        if (!$id) {
            return false;
        }
        return $this->find($id);
    }
	
	/**
	 * 获取列表数据
	 * @param $where
	 * @param string $field
	 * @param int $num
	 * @return \think\Paginator
	 * @throws DbException
	 */
	public function getLists($where, $field = '*', $num = 10) {
		
		$order = [
			"sequence" => "desc",
			"id" => "desc"
		];
		$result = $this->where("status", "<>", config("status.mysql.table_delete"))
			->where($where)
			->field($field)
			->order($order)
			->paginate($num);
		//echo $this->getLastSql();exit;
		return $result;
	}
	
	/**
	 * 根据主键ID更新数据表中的数据
	 * @param $id
	 * @param $data
	 * @return bool
	 */
	public function updateById($id, $data) {
		$id = intval($id);
		if(empty($id) || empty($data) || !is_array($data)) {
			return false;
		}
		
		$where = [
			"id" => $id,
		];
		
		return $this->where($where)->save($data);
	}
	
	/**
	 * 根据主键ID更新数据表中的数据
	 * @param $id
	 * @param $data
	 * @return bool
	 */
	public function deleteById($id, $data) {
		$id = intval($id);
		if(empty($id) || empty($data) || !is_array($data)) {
			return false;
		}
		
		$where = [
			"id" => $id,
		];
		
		return $this->where($where)->save($data);
	}
}
