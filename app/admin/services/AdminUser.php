<?php

namespace app\admin\services;

use app\common\lib\Str;
use app\common\lib\Time;
use app\admin\model\AdminUser as AdminUserModel;
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
     * 新增逻辑
     * @param $data
     * @return int|mixed
     */
    public function add($data)
    {
//        $data['status'] = config("status.mysql.table_normal");
        try {
            $this->model->save($data);
        } catch (\Exception $e) {
            Log::error('错误信息:' . $e->getMessage());
            return 0;
        }

        // // 返回主键ID
        return $this->model->id;
    }
    
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
     */
    public function getNormalUserById($id)
    {
        $user = $this->model->getUserById($id);
        if (!$user || $user->status != config('status.mysql.table_normal')) {
            return [];
        }
        return $user->toArray();
    }
    
    public function getNormalUserByUsername($username)
    {
        $user = $this->model->getUserByUesrname($username);
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
        //redis需要同步
        return $this->model->updateById1($id, $data);
    }
}
