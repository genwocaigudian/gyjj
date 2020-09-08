<?php


namespace app\admin\model;

class AdminUser extends BaseModel
{
    /**
     * 根据id获取用户信息
     * @param $id 用户id
     * @return array|bool|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getAdminUserById($id)
    {
        if (empty($id)) {
            return false;
        }

        $where = [
            'id' => $id
        ];

        return $this->where($where)->find();
    }
    
    /**
     * 根据username获取用户信息
     * @param $username 用户名称
     * @return array|bool|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getAdminUserByUserName($username)
    {
        if (empty($username)) {
            return false;
        }
        
        $where = [
            'username' => $username
        ];
        
        return $this->where($where)->find();
    }
	
	/**
	 * 根据ids获取用户信息
	 * @param array $ids
	 * @return bool|\think\Collection
	 * @throws \think\db\exception\DataNotFoundException
	 * @throws \think\db\exception\DbException
	 * @throws \think\db\exception\ModelNotFoundException
	 */
	public function getAdminUserByIds($ids = [])
	{
		if (empty($ids)) {
			return false;
		}
		
		return $this->whereIn('id', $ids)->select();
	}
}
