<?php


namespace app\common\model;

class User extends BaseModel
{
    /**
     * 根据openid获取用户信息
     * @param $openid 微信openid
     * @return array|bool|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getUserByOpenid($openid)
    {
        if (empty($openid)) {
            return false;
        }

        $where = [
            'openid' => $openid
        ];

        return $this->where($where)->find();
    }
}