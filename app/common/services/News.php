<?php


namespace app\common\services;

use app\admin\services\AdminUser as AdminUserService;
use app\common\lib\Arr;
use app\common\model\News as NewsModel;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
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
     * @param $data
     * @param $num
     * @return array
     * @throws DbException
     */
    public function getPaginateList($data, $num)
    {
        $field = 'id, small_title, cate_id, title, is_top, is_hot, status, img_urls';
        $likeKeys = [];
        if (!empty($data)) {
            $likeKeys = array_keys($data);
        }
        try {
            $list = $this->model->getPaginateList($likeKeys, $data, $field = '*', $num);
            $result = $list->toArray();
            if ($result['data']) {
                $cateIds = array_column($result['data'], 'cate_id');
                $cateNames = (new Category())->getCateByIds($cateIds);
                foreach ($result['data'] as &$data) {
                    $data['cate_name'] = $cateNames[$data['cate_id']];
                }
            }
        } catch (\Exception $e) {
            $result = Arr::getPaginateDefaultData($num);
        }
        return $result;
    }
    
    public function getNormalAllNews()
    {
        $field = "id, small_title, title, is_top, is_hot, status, img_urls, create_time";
        try {
            $res = $this->model->getNormalNews($field, 10);
        } catch (\Exception $e) {
            Log::error('getNormalAllNews 错误:' . $e->getMessage());
            throw new Exception('数据库内部异常');
        }
        
        if (!$res) {
            return $res;
        }
        $res = $res->toArray();
        return $res;
    }

    /**
     * 返回正常数据
     * @param $title
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getNormalNewsByName($title)
    {
        $res = $this->model->getNewsByTitle($title);
        if (!$res || $res->status != config("status.mysql.table_normal")) {
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
        $news = $this->getNormalNewsById($id);
        if (!$news) {
            throw new Exception("数据不存在");
        }

        //检查名称是否存在
        $result = [];
        if (isset($data['title'])) {
            $result = $this->getNormalNewsByTitle($data['title']);
        }
        if ($result && $result['id'] != $id) {
            throw new Exception("标题不可重复");
        }

        try {
            if (isset($data['content'])) {
                $res = $this->model->updateContentRelation($id, $data);
            } else {
                $res = $this->model->updateById($id, $data);
            }
        } catch (\Exception $e) {
            Log::error('service/news/update 错误:' . $e->getMessage());
            throw new Exception('数据库内部异常');
        }
        return $res;
    }

    /**
     * @param $id
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getNormalNewsById($id)
    {
        $news = $this->model->getNewsById($id);
        if (!$news || $news->status != config('status.mysql.table_normal')) {
            return [];
        }
        return $news->toArray();
    }

    /**
     * 格式化news结果集
     * @param $id
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function formatNews($id)
    {
        $result = $this->getNormalNewsById($id);
        if (!$result) {
            return [];
        }

        $cates = (new Category())->getCateByIds(array($result['cate_id']));
        $users = (new AdminUserService())->getAdminUserByIds(array($result['user_id']));
        $result['cate_name'] = $cates[$result['cate_id']]??'';
        $result['user_name'] = $users[$result['user_id']]??'';
        $result['content'] = $result['newsContent']['content']??'';
        unset($result['newsContent']);
        
        return $result;
    }

    /**
     * @param $title
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getNormalNewsByTitle($title)
    {
        $res = $this->model->getNewsByTitle($title);
        if (!$res || $res->status != config('status.mysql.table_normal')) {
            return [];
        }
        return $res->toArray();
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
        $data = [
            'status' => config('status.mysql.table_delete')
        ];

        return $this->model->deleteById($id);
    }
}
