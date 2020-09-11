<?php


namespace app\common\services;

use app\common\lib\Arr;
use app\common\model\RepairCate as RepairCateModel;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Exception;

class RepairCate extends BaseServices
{
    public $model = null;

    public function __construct()
    {
        $this->model = new RepairCateModel();
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
        try {
            $id = $this->add($data);
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
        return ['id' => $id];
    }

    /**
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getList()
    {
        $field = 'id, name';
        $list = $this->model->getList($field);
        if (!$list) {
            return [];
        }
        $result = $list->toArray();
        return $result;
    }

    /**
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getNormalList()
    {
        $field = 'id, name';
        $list = $this->model->getNormalList($field);
        if (!$list) {
            return [];
        }
        $result = $list->toArray();
        return $result;
    }

    /**
     * @param int $num
     * @return array
     * @throws DbException
     */
    public function getPaginateList($num = 10)
    {
        $field = 'id, name';
        try {
            $list = $this->model->getPaginateList($field, $num);
            $result = $list->toArray();
        } catch (\Exception $e) {
            $result = Arr::getPaginateDefaultData($num);
        }
        return $result;
    }
	
	/**
	 * @param $id
	 * @return array
	 * @throws DataNotFoundException
	 * @throws DbException
	 * @throws ModelNotFoundException
	 */
	public function getNormalBannerById($id)
	{
		$res = $this->model->getById($id);
		if (!$res || $res->status != config('status.mysql.table_normal')) {
			return [];
		}
		return $res->toArray();
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
		$res = $this->model->getById($id);
		if (!$res) {
			throw new Exception("数据不存在");
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
        $cate = $this->model->getById($id);
        if (!$cate) {
            throw new Exception("数据不存在");
        }
        
        $data = [
            'status' => config('status.mysql.table_delete')
        ];
        
        return $this->model->updateById($id, $data);
    }
}
