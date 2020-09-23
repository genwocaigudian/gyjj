<?php
namespace app\admin\controller;

use app\admin\validate\Selection as SelectionValidate;
use app\common\lib\Show;
use app\common\services\Selection as SelectionService;
use think\facade\Log;
use think\response\Json;

class Sidebar extends AdminAuthBase
{
    /**
     * @return Json
     * @throws \think\db\exception\DbException
     */
    public function index()
    {
        $list = [
        	[
		        'value' => '微主页',
		        'child' => [
		        	[
				        'value' => '菜单管理',
				        'index' => '/menu',
			        ],
			        [
				        'value' => '轮播管理',
				        'index' => '/banner',
			        ],
			        [
				        'value' => '学校简介',
				        'index' => '/abstract',
			        ],
			        [
				        'value' => '学校新闻',
				        'index' => '/new',
			        ],
		        ],
	        ],
	        [
		        'value' => '微服务',
		        'child' => [
			        [
				        'value' => '掌上报修',
				        'index' => '/service',
			        ],
			        [
				        'value' => '工资管理',
				        'index' => '/wage',
			        ]
		        ],
	        ],
	        [
		        'value' => '微生活',
		        'child' => [
			        [
				        'value' => '问卷调查',
				        'index' => '/survey',
			        ],
			        [
				        'value' => '评比评选',
				        'index' => '/compare',
			        ],
			        [
				        'value' => '失物招领',
				        'index' => '/found',
			        ]
		        ],
	        ],
	        [
		        'value' => '后台管理',
		        'child' => [
			        [
				        'value' => '用户管理',
				        'index' => '/menu',
			        ],
			        [
				        'value' => '系统管理',
				        'index' => '/banner',
			        ]
		        ],
	        ]
        ];
        
        return Show::success($list);
    }

    /**
     * 新增
     * @return Json
     */
    public function save()
    {
        if (!$this->request->isPost()) {
            return Show::error('非法请求');
        }
        $data = input('post.');

        $validate = new SelectionValidate();
        if (!$validate->scene('save')->check($data)) {
            return Show::error($validate->getError());
        }

        $data['user_id'] = $this->userId;
        $data['start_time'] = $data['start_time'] . '00:00:00';
        $data['end_time'] = $data['end_time'] . '23:59:59';

        try {
            $result = (new SelectionService())->insertData($data);
        } catch (\Exception $e) {
            Log::error('admin/selection/save 错误:' . $e->getMessage());
            return Show::error($e->getMessage(), $e->getCode());
        }

        return Show::success($result);
    }

    /**
     * 详情
     * @return Json
     */
    public function read()
    {
        $id = input('param.id', 0, 'intval');
        try {
            $result = (new SelectionService())->getNormalById($id);
        } catch (\Exception $e) {
            Log::error('admin/selection/read 错误:' . $e->getMessage());
            return Show::error($e->getMessage(), $e->getCode());
        }

        return Show::success($result);
    }

    /**
     * 更新数据
     * @return Json
     */
    public function update()
    {
        if (!$this->request->isPost()) {
            return Show::error('非法请求');
        }

        $id = input('param.id', 0, 'intval');
        $data = input('post.');
//        $data = $this->request->only(['is_hot', 'is_top', 'title', 'content'], 'post');

        try {
            $res = (new SelectionService())->update($id, $data);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }

        return Show::success();
    }

    /**
     * 删除数据
     * @return Json
     */
    public function delete()
    {
        if (!$this->request->isPost()) {
            return Show::error('非法请求');
        }

        $id = input("param.id");

        try {
            $res = (new SelectionService())->del($id);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }

        return Show::success();
    }
}
