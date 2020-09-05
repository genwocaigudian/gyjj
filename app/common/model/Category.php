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
	public function getNormalCategorys($field = "*") {
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
}