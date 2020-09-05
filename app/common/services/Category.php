<?php


namespace app\common\services;

use app\common\model\Category as CategoryModel;
use think\Exception;

class Category extends BaseServices
{
    public $model = null;

    public function __construct()
    {
        $this->model = new CategoryModel();
    }

    /**
     * @return array|\think\Collection
     * @throws Exception
     */
	public function getNormalAllCategorys()
	{
		$field = "id, name, pid";
		try {
			$categorys = $this->model->getNormalCategorys($field);
		} catch (\Exception $e) {
			throw new Exception($e->getMessage());
		}
		
		if(!$categorys) {
			return $categorys;
		}
		return $categorys->toArray();;
    }
	
	/**
	 * 返回正常用户数据
	 * @param $name
	 * @return array
	 * @throws \think\db\exception\DataNotFoundException
	 * @throws \think\db\exception\DbException
	 * @throws \think\db\exception\ModelNotFoundException
	 */
	public function getNormalCateByName($name) {
		$cate = $this->model->getCateByName($name);
		if(!$cate || $cate->status != config("status.mysql.table_normal")) {
			return [];
		}
		return $cate->toArray();
	}
	
	/** 插入数据
	 * @param $data
	 * @return array
	 * @throws Exception
	 * @throws \think\db\exception\DataNotFoundException
	 * @throws \think\db\exception\DbException
	 * @throws \think\db\exception\ModelNotFoundException
	 */
	public function insertData($data)
	{
		$cateResult = $this->getNormalCateByName($data['name']);
		if($cateResult) {
			throw new Exception("数据已存在");
		}
		
		try {
			$id = $this->add($data);
		} catch (\Exception $e) {
			throw new Exception($e->getMessage());
		}
		$result = [
			'id' => $id
		];
		return $result;
    }
}