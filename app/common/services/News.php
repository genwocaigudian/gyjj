<?php


namespace app\common\services;

use app\common\model\News as NewsModel;
use think\Exception;
use think\facade\Log;

class News extends BaseServices
{
    public $model = null;

    public function __construct()
    {
        $this->model = new NewsModel();
    }
    
    /**
     * 获取列表数据
     * @param $data
     * @param $num
     * @return array
     */
    public function getLists($data, $num)
    {
        $field = 'id, title, status';
        $list = $this->model->getLists($data, $field, $num);
        if (!$list) {
            return [];
        }
        $result = $list->toArray();
        return $result;
    }
    
    public function getNormalAllCategorys()
    {
        $field = "id, name, pid";
        try {
            $categorys = $this->model->getNormalCategorys($field);
        } catch (\Exception $e) {
            Log::error('getNormalAllCategorys 错误:' . $e->getMessage());
            throw new Exception('数据库内部异常');
        }
        
        if (!$categorys) {
            return $categorys;
        }
        $categorys = $categorys->toArray();
        return $categorys;
    }
    
    /**
     * 返回正常用户数据
     * @param $name
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNormalNewsByName($name)
    {
        $res = $this->model->getNewsByName($name);
        if (!$res || $res->status != config("status.mysql.table_normal")) {
            return [];
        }
        return $res->toArray();
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
        $res = $this->getNormalNewsByName($data['title']);
        if ($res) {
            throw new Exception("新闻标题不可重复");
        }
        
        try {
            $id = $this->add($data);
            $this->model->NewsContent()->save(['content'=>$data['content']]);
        } catch (\Exception $e) {
            throw new Exception('数据库内部异常');
        }
        $result = [
            'id' => $id
        ];
        return $result;
    }
}
