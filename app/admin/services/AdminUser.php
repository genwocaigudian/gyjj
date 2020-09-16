<?php

namespace app\admin\services;

use app\common\lib\Str;
use app\common\lib\Time;
use app\admin\model\AdminUser as AdminUserModel;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\facade\Log;

class AdminUser extends AdminBaseServices
{
    public $model = null;

    public function __construct()
    {
        $this->model = new AdminUserModel();
    }

    /**
     * @param $data
     * @return array|bool
     * @throws Exception
     */
    public function login($data)
    {
        $username = $data['username'];
        $password = $data['password'];
        try {
            $user = $this->model->getAdminUserByUserName($username);
        } catch (\Exception $e) {
            Log::error('admin/service/login 错误:' . $e->getMessage());
            throw new Exception('数据库内部异常');
        }
        
        if (!$user || $user->password != md5($password.config('admin.password_suffix'))
            || $user->status != config('status.mysql.table_normal')) {
            throw new Exception('用户名或密码错误');
        }
            
        $token = Str::getLoginToken($user->id);
        $redisData = [
            'user_id' => $user->id,
            'username' => $user->username,
        ];
        $res = cache(config('admin.admin_token_pre').$token, $redisData, Time::userLoginExpiresTime(1));
        
        return $res ? ['token' => $token, 'user_id' => $user->id] : false;
    }

    /**
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNormalUserById($id)
    {
        $user = $this->model->getAdminUserById($id);
        if (!$user || $user->status != config('status.mysql.table_normal')) {
            return [];
        }
        return $user->toArray();
    }

    /**
     * @param $username
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getNormalUserByUsername($username)
    {
        $user = $this->model->getAdminUserByUserName($username);
        if (!$user || $user->status != config('status.mysql.table_normal')) {
            return [];
        }
        return $user->toArray();
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function update($id, $data)
    {
        $user = $this->getNormalUserById($id);
        if (!$user) {
            throw new Exception('不存在该用户');
        }
        $userResult = $this->getNormalUserByUsername($data['username']);
        if ($userResult && $userResult['id'] != $id) {
            throw new Exception('该用户已存在');
        }
        return $this->model->updateById($id, $data);
    }

    /**
     * @param array $ids
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
	public function getAdminUserByIds($ids = [])
	{
		$list = $this->model->getAdminUserByIds($ids);
		if (!$list) {
			return [];
		}
		$result = $list->toArray();
		$cates = array_column($result, 'username', 'id');
		return $cates;
	}

    /**
     * 获取列表数据
     * @param $data
     * @param $num
     * @return array
     * @throws \think\db\exception\DbException
     */
    public function getLists($data, $num)
    {
        $field = 'id, username, status';
        $list = $this->model->getLists($data, $field, $num);
        if (!$list) {
            return [];
        }
        $result = $list->toArray();
        return $result;
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
        $result = $this->getNormalUserByUsername($data['username']);
        if ($result) {
            throw new Exception("数据已存在");
        }

        $insertData = [
            'username' => $data['username'],
            'password' => md5($data['password'].config('admin.password_suffix')),
            'create_time' => time(),
            'update_time' => time(),
            'last_login_time' => time(),
            'last_login_ip' => $data['last_login_ip'],
            'operate_user' => 'admin',
        ];

        try {
            $id = $this->add($insertData);
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
        return ['id' => $id];
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

        return $this->model->deleteById($id, $data);
    }
}