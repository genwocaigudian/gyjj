<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/9
 * Time: 10:00
 */

namespace App\Service;


use App\Common\Dao\AdminUserDao;
use App\Exception\BusinessException;
use App\Model\AdminUser;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;

class AdminUserService extends BaseService
{
    /**
     * @Inject()
     * @var AdminUserDao
     */
    private $adminUserDao;

    /**
     * @Inject()
     * @var AdminUser
     */
    private $adminUserModel;

    /**
     * @param string $username
     * @param string $password
     * @param string $lastLoginIp
     * @param int $status
     * @param int $updateUserId
     * @return bool
     */
    public function adminUserSave(string $username, string $password, string $lastLoginIp, int $status, int $updateUserId = 0): bool
    {
        try {
            $data = [
                'username' => $username,
                'password' => password_hash($password, PASSWORD_BCRYPT, ["cost" => 8]),
                'last_login_ip' => $lastLoginIp,
                'operate_user' => 'admin',
                'last_login_time' => date('Y-m-d H:i:s'),
                'status' => $status,
            ];
            $userId = $this->adminUserModel->save($data);
//            $userId = Db::table('admin_users')->insertGetId([
//                'username' => $username,
//                'password' => password_hash($password, PASSWORD_BCRYPT, ["cost" => 8]),
//                'last_login_ip' => $lastLoginIp,
//                'operate_user' => 'admin',
//                'last_login_time' => date('Y-m-d H:i:s'),
//                'status' => $status,
//            ]);
        } catch (\Exception $e) {
            return 0;
//            throw new BusinessException($e->getMessage(), $e->getCode());
        }
        return $this->adminUserModel->id;

    }


}