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

    /**
     * 获取列表数据
     * @param $where
     * @param string $field
     * @param int $num
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function getLists($where, $field = '*', $num = 10)
    {
        $order = [
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
    
    public function NewsContent()
    {
        return $this->hasOne(NewsContent::class);
    }

    /**
     * @param string $field
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNormalNews($field = "*")
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
            'title' => $title
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
     * @param $content
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function updateContentRelation($id, $content)
    {
        $data = [
            'update_time' => time(),
            'content' => $content
        ];
        $res = $this->find($id);
        return $res->NewsContent->save($data);
    }

    /**
     * 根据主键ID更新数据表中的数据
     * @param $id
     * @param $data
     * @return bool
     */
    public function deleteById($id, $data)
    {
        $id = intval($id);
        if (empty($id) || empty($data) || !is_array($data)) {
            return false;
        }

        $where = [
            "id" => $id,
        ];

        return $this->where($where)->save($data);
    }
}
