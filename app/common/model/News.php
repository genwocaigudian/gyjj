<?php


namespace app\common\model;

class News extends BaseModel
{
    /**
     * 获取列表数据
     * @param $where
     * @param string $field
     * @param int $num
     * @return \think\Paginator
     * @throws DbException
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
    public function getNormalCategorys($field = "*")
    {
        $where = [
            "status" => config("status.mysql.table_normal"),
        ];
        
        $order = [
            "sequence" => "desc",
            "id" => "desc"
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
     * @return array|bool|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNewsByName($name)
    {
        if (empty($name)) {
            return false;
        }
        
        $where = [
            'title' => $name
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
    public function getCateById($id)
    {
        $id = intval($id);
        if (!$id) {
            return false;
        }
        return $this->find($id);
    }
}
