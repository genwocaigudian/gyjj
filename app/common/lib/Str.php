<?php


namespace app\common\lib;

use think\facade\Request;

class Str
{
    /**
     * 生成登录所需的token
     * @param $string
     * @return string
     */
    public static function getLoginToken($string)
    {
        $str = md5(uniqid(md5(microtime(true)), true));
        $token = sha1($str.$string);
        return $token;
    }
	
	/**
	 * @return string
	 */
	public static function generateToken()
	{
		//3组字符串md5加密
		//生成32位随机字符串
		$randChars = self::getRandChar(32);
		$timestamp = Request::server('REQUEST_TIME_FLOAT');
		$salt = config('wx.token_salt');
		
		return md5($randChars.$timestamp.$salt);
	}
	
	/**
	 * 生成随机字符串
	 * @param $length
	 * @return string|null
	 */
	public static function getRandChar($length)
	{
		$str = null;
		$strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
		$max = strlen($strPol) - 1;
		
		for ($i = 0; $i < $length; $i++) {
			$str .= $strPol[rand(0, $max)];
		}
		
		return $str;
    }
}
