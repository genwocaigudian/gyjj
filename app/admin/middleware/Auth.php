<?php
declare (strict_types = 1);
namespace app\admin\middleware;

class Auth
{
	
	public function handle($request, \Closure $next) {
		//前置中间件
//		return $next($request);
		if (session(config('admin.session_admin')) && preg_match("/login/", $request->pathinfo())) {
//			return show(config('status.error'), '失败');
			return redirect((string) url('index/index'));
		}
		
		if (empty(session(config('admin.session_admin'))) && !preg_match("/login/", $request->pathinfo())) {
//			return show(config('status.error'), '失败');
			return redirect((string) url('login/index'));
		}
		return $next($request);
	}
	
	/**
	 * 中间件结束调度
	 * @param \think\Response $response
	 */
	public function end(\think\Response $response) {
		
	}
}