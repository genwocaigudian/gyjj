<?php


namespace app\common\services;

use app\common\lib\Arr;
use app\common\model\VideoCategory as VideoCategoryModel;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Exception;

class VideoCategory extends BaseServices
{
    public $model = null;

    public function __construct()
    {
        $this->model = new VideoCategoryModel();
    }

    /**
     * @param $id
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getNormalById($id)
    {
        $res = $this->model->getById($id);
        if (!$res) {
            return [];
        }
        return $res->toArray();
    }

    /**
     * @param string $field
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getList($field = '*')
    {
        $res = $this->model->getList($field);
        if (!$res) {
            return [];
        }
        return $res->toArray();
    }

    /**
     * 插入数据
     * @param $data
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws Exception
     * @throws ModelNotFoundException
     */
    public function insertData($data)
    {
        try {
            $id = $this->add($data);
        } catch (\Exception $e) {
            throw new Exception('数据库内部异常');
        }
        $result = [
            'id' => $id
        ];
        return $result;
    }

    /**
     * @param $data
     * @return \think\Collection
     * @throws \Exception
     */
    public function insertAll($data)
    {
        return $res = $this->model->saveAll($data);
    }
}
