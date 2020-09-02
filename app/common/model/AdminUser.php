<?php


namespace app\common\model;

class AdminUser extends BaseModel
{
	/**
	 * 根据用户名获取数据
	 * @param $username
	 * @return array|bool|\think\Model|null
	 * @throws \think\db\exception\DataNotFoundException
	 * @throws \think\db\exception\DbException
	 * @throws \think\db\exception\ModelNotFoundException
	 */
	public function getAdminUserByUsername($username)
	{
		if (empty($username)) {
			return false;
		}
		
		$where = [
			'username' => trim($username),
		];
		
		$result = $this->where($where)->find();
		return $result;
    }
	
	public function updateById($id, $data)
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