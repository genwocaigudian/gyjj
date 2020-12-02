<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/9
 * Time: 10:00
 */

namespace App\Service;


use App\Common\Dao\AdminUserDao;
use Hyperf\Di\Annotation\Inject;

class AdminUserService extends BaseService
{
    /**
     * @Inject()
     * @var AdminUserDao
     */
    private $adminUserDao;


}