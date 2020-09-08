<?php


namespace app\common\services;

use app\common\model\Category as CategoryModel;
use think\Collection;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Exception;

class Category extends BaseServices
{
    public $model = null;

    public function __construct()
    {
        $this->model = new CategoryModel();
    }

    /**
     * @return array|Collection
     * @throws Exception
     */
    public function getNormalAllCategorys()
    {
        $field = "id, name";
        try {
            $categorys = $this->model->getNormalCategorys($field);
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }

        return $categorys->toArray();
    }
    
    /**
     * 返回正常用户数据
     * @param $name
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getNormalCateByName($name)
    {
        $cate = $this->model->getCateByName($name);
        if (!$cate || $cate->status != config("status.mysql.table_normal")) {
            return [];
        }
        return $cate->toArray();
    }
    
    /** 插入数据
     * @param $data
     * @return array
     * @throws Exception
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function insertData($data)
    {
        $cateResult = $this->getNormalCateByName($data['name']);
        if ($cateResult) {
            throw new Exception("数据已存在");
        }
        
        try {
            $id = $this->add($data);
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
        return ['id' => $id];
    }
    
    /**
     * 获取列表数据
     * @param $data
     * @param $num
     * @return array
     */
    public function getLists($data, $num)
    {
        $field = 'id , name, pid';
        $list = $this->model->getLists($data, $field, $num);
        if (!$list) {
            return [];
        }
        $result = $list->toArray();
        return $result;
    }
	
	/**
	 * 获取列表数据
	 * @param $data
	 * @param $num
	 * @return array
	 */
	public function getCateByIds($ids = [])
	{
		$list = $this->model->getCateByIds($ids);
		if (!$list) {
			return [];
		}
		$result = $list->toArray();
		$users = array_column($result, 'name', 'id');
		return $users;
	}
    
    /**
     * @param $id
     * @param $data
     * @return bool
     * @throws DataNotFoundException
     * @throws DbException
     * @throws Exception
     * @throws ModelNotFoundException
     */
    public function update($id, $data)
    {
        $user = $this->getNormalCateById($id);
        if (!$user) {
            throw new Exception("数据不存在");
        }
        
        //检查名称是否存在
        $cateResult = [];
        if ($data['name']) {
            $cateResult = $this->getNormalCateByCatename($data['name']);
        }
        if ($cateResult && $cateResult['id'] != $id) {
            throw new Exception("分类名称不可重复");
        }
        return $this->model->updateById($id, $data);
    }

    /**
     * @param $id
     * @return bool
     * @throws DataNotFoundException
     * @throws DbException
     * @throws Exception
     * @throws ModelNotFoundException
     */
    public function delete($id)
    {
        $cate = $this->getNormalCateById($id);
        if (!$cate) {
            throw new Exception("数据不存在");
        }
        
        $data = [
            'status' => config('status.mysql.table_delete')
        ];
        
        return $this->model->deleteById($id, $data);
    }

    /**
     * @param $id
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getNormalCateById($id)
    {
        $cate = $this->model->getCateById($id);
        if (!$cate || $cate->status != config('status.mysql.table_normal')) {
            return [];
        }
        return $cate->toArray();
    }
}
