<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Constants\ErrorCode;
use App\Controller\AbstractController;
use App\Exception\BusinessException;
use App\Service\AdminUserService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;

class UserController extends AbstractController
{
    /**
     * @Inject()
     * @var AdminUserService
     */
    private $adminUserService;

    /**
     * @Inject()
     * @var ValidatorFactoryInterface
     */
    private $validationFactory;

    public function userSave()
    {
        $username = (string)$this->request->input('username');
        $password = (string)$this->request->input('password');
        $mobile = $this->request->input('mobile');
        $email = (string)$this->request->input('email');
        $roleIdList = $this->request->input('roleIdList'); //组数
        $salt = (string)$this->request->input('salt');
        $status = (int)$this->request->input('status');


        $input = $this->request->all();

        $validator = $this->validationFactory->make(
            $input,
            [
                'username' => 'required',
                'password' => 'required',
            ],
            [
                'username.required' => '用户名不可为空',
                'password.required' => '密码不可为空',
            ]
        );

        if ($validator->fails()) {
            $errorMessage = $validator->errors()->first();
            throw new BusinessException(ErrorCode::PARAMS_INVALID, $errorMessage);
        }
        if (!$this->request->has('status')) {
            $input['status'] = 1;
        }
        $lastLoginIp = get_client_ip();
        $result = $this->adminUserService->adminUserSave($input['username'], $input['password'], $lastLoginIp, $input['status']);
        if (!$result) {
            return $this->response->error(ErrorCode::getMessage(ErrorCode::OPERATION_FAILED));
        }
        return $this->response->success();
    }
}
