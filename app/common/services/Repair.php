<?php


namespace app\common\services;

use app\common\lib\Arr;
use app\common\model\Repair as RepairModel;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\facade\Cache;

class Repair extends BaseServices
{
    public $model = null;

    public function __construct()
    {
        $this->model = new RepairModel();
    }

    /**
     * 插入数据
     * @param $data
     * @return array
     * @throws Exception
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
     * @param $data
     * @return array
     */
    public function getList($data)
    {
        $likeKeys = [];
        if (!empty($data)) {
            $likeKeys = array_keys($data);
        }
        try {
            $list = $this->model->getList($likeKeys, $data);
            $result = $list->toArray();
        } catch (\Exception $e) {
            $result = [];
        }
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
     * @param $data
     * @param int $num
     * @return array
     * @throws DbException
     */
    public function getPaginateList($data, $num = 10)
    {
        $likeKeys = [];
        if (!empty($data)) {
            $likeKeys = array_keys($data);
        }
        try {
            $list = $this->model->getPaginateList($likeKeys, $data, $field = '*', $num);
            $result = $list->toArray();
            if ($result['data']) {
                $uids = array_unique(array_column($result['data'], 'user_id'));
                if ($uids) {
                    $users = (new User())->getUserByIds($uids);
                    $userNames = array_column($users, 'username', 'id');
                }
                foreach ($result['data'] as &$datum) {
                    $datum['user_name'] = $userNames[$datum['user_id']]??'';
                    $datum['img_url'] = json_decode($datum['img_url'], true);
                }
            }
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
        $res = $this->model->getBannerById($id);
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
        $res = $this->getNormalById($id);
        if (!$res) {
            throw new Exception("数据不存在");
        }
        return $this->model->updateById($id, $data);
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
        $info = $res->toArray();
        $info['img_url'] = json_decode($res['img_url']);
        return $info;
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
        $cate = $this->getNormalBannerById($id);
        if (!$cate) {
            throw new Exception("数据不存在");
        }
        
        $data = [
            'status' => config('status.mysql.table_delete')
        ];
        
        return $this->model->updateById($id, $data);
    }
    
    /**
     * @param $orderId
     * @param $time
     * @return bool
     */
    public function testCommond()
    {
        $result = Cache::zRangeByScore('order_status', 0, time(), ['limit' => [0, 1]]);
        //		$result = Cache::store('redis')->zRangeByScore("order_status", 0, time(), ['limit' => [0, 1]]);
        
        if (empty($result) || empty($result[0])) {
            return false;
        }
        
        try {
            $delRedis = Cache::zRem('order_status', $result[0]);
            //			$delRedis = Cache::store('redis')->zRem("order_status", $result[0]);
        } catch (\Exception $e) {
            // 记录日志
            $delRedis = "";
        }
        if ($delRedis) {
            echo "订单id:{$result[0]}在规定时间内没有完成支付 我们判定为无效订单删除".PHP_EOL;
        } else {
            return false;
        }
        
        return true;
    }
}
