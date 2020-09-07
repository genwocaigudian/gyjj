<?php


namespace app\common\model;

class User extends BaseModel
{
    public function news()
    {
        return $this->belongsTo(News::class);
    }
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
    
    /**
     * 根据phoneNumber获取用户信息
     * @param $openid 微信openid
     * @return array|bool|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getUserByPhoneNumber($phoneNumber)
    {
        if (empty($phoneNumber)) {
            return false;
        }
        
        $where = [
            'phone_number' => $phoneNumber
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
    public function getUserById($id)
    {
        $id = intval($id);
        if (!$id) {
            return false;
        }
        return $this->find($id);
    }
    
    public function getUserByUesrname($username)
    {
        if (empty($username)) {
            return false;
        }
        
        $where = [
            'nickname' => $username
        ];
        
        return $this->where($where)->find();
    }
    
    public function updateById1($id, $data)
    {
        $id = intval($id);
        if (empty($id) || empty($data) || !is_array($data)) {
            return false;
        }
        $where = [
            'id' => $id
        ];
        return $this->where($where)->save($data);
    }
}
