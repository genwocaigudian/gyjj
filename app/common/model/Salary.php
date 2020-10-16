<?php


namespace app\common\model;

use think\Collection;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\model\concern\SoftDelete;

class Salary extends BaseModel
{
	use SoftDelete;
	protected $deleteTime = 'delete_time';
	protected $dateFormat = 'Y-m-d';
	
	protected $type = [
		'start_time'  =>  'timestamp',
		'end_time'  =>  'timestamp'
	];
	
	protected $hidden = [
		'create_time',
		'update_time',
		'delete_time',
	];
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
     * @param $likeKeys
     * @param $data
     * @param string $field
     * @return Collection
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getList($likeKeys, $data, $field = "*")
    {
        $res = $this->newQuery();
        if (!empty($likeKeys)) {
            $res = $res->withSearch($likeKeys, $data);
        }

        $result = $res->field($field)->select();
//        echo $res->getLastSql();exit;
        return $result;
    }

    /**
     * name查询条件表达式
     * 调用withSearch方法时触发
     * @param $query
     * @param $value
     */
    public function searchUserNameAttr($query, $value)
    {
        $query->where('username', 'like', '%' . $value . '%');
    }

    public function searchMonthAttr($query, $value) {
        $query->whereBetweenTime('month', $value[0], $value[1]);
    }

    public function searchNumberAttr($query, $value)
    {
        $query->where('number', '=', $value);
    }

    /**
     * @param $likeKeys
     * @param $data
     * @param string $field
     * @param int $num
     * @return \think\Paginator
     * @throws DbException
     */
    public function getPaginateList($likeKeys, $data, $field = "*", $num = 10)
    {
        $res = $this->newQuery();
        if (!empty($likeKeys)) {
            $res = $res->withSearch($likeKeys, $data);
        }
        $result = $res->field($field)->paginate($num);
        //echo $this->getLastSql();exit;
        return $result;
    }
	
	/**
	 * @param array $data
	 * @return Collection
	 * @throws DataNotFoundException
	 * @throws DbException
	 * @throws ModelNotFoundException
	 */
	public function getDateGroup($data = [])
	{
		$field = 'month';
		$order = [
			'month' => 'desc'
		];
		
		$result = $this->where('number', '=', $data['number'])
			->field($field)
			->order($order)
			->group($field)
			->select();
//        echo $this->getLastSql();exit;
		return $result;
	}
}
