<?php


namespace app\common\model;

use think\model\Relation;

class News extends BaseModel
{
    public $allowField = [
        'title',
        'small_title',
        'user_id',
        'cate_id',
        'status',
        'img_urls',
        'is_hot',
    ];

    protected $hidden = [
        'update_time',
        'delete_time'
    ];

    /**
     * @param $likeKeys
     * @param $data
     * @param string $field
     * @param int $num
     * @return \think\Paginator
     * @throws \think\db\exception\DbException\
     */
    public function getPaginateList($likeKeys, $data, $field = "*", $num = 10)
    {
        $order = [
            "id" => "desc"
        ];
        if (!empty($likeKeys)) {
            $res = $this->withSearch($likeKeys, $data);
        } else {
            $res = $this;
        }
        $result = $res->field($field)->order($order)->paginate($num);
//        echo $this->getLastSql();exit;
        return $result;
    }

    /**
     * cate_id查询条件表达式
     * 调用withSearch方法时触发
     * @param $query
     * @param $value
     */
    public function searchCateIdAttr($query, $value)
    {
        $query->where('cate_id', '=', $value);
    }

    /**
     * title查询条件表达式
     * 调用withSearch方法时触发
     * @param $query
     * @param $value
     */
    public function searchTitleAttr($query, $value)
    {
        $query->where('title', 'like', '%' . $value . '%');
    }
    
    public function NewsContent()
    {
        return $this->hasOne(NewsContent::class);
    }

    /**
     * @param string $field
     * @param string $num
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNormalNews($field = "*", $num)
    {
        $where = [
            "status" => config("status.mysql.table_normal"),
        ];
        
        $order = [
            "id" => "desc"
        ];
        $result = $this->where($where)
            ->field($field)
            ->order($order)
            ->limit($num)
            ->select();
        
        return $result;
    }
    
    /**
     * 根据title查询数据
     * @param $title
     * @return array|bool|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNewsByTitle($title)
    {
        if (empty($title)) {
            return false;
        }
        
        $where = [
            'title' => $title,
            'status' => config('status.mysql.table_normal'),
        ];
        
        return $this->where($where)->find();
    }

    /**
     * @param $id
     * @return array|bool|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNewsById($id)
    {
        $id = intval($id);
        if (!$id) {
            return false;
        }
        $res = $this->withJoin(['newsContent' => function (Relation $query) {
            $query->withField(['content']);
        }])->find($id);
        return $res;
    }

    /**
     * 根据id更新关联模型newsContent数据
     * @param $id
     * @param $data
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function updateContentRelation($id, $data)
    {
        $res = $this->find($id);
        $res->NewsContent->content = $data['content'];
        $res->NewsContent->update_time = time();
        return $res->together(['NewsContent'])->save($data);
    }
}
