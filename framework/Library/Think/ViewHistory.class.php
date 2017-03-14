<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

namespace Think;

/**
 * ThinkPHP 数据库中间层实现类
 */
class ViewHistory extends Base {

	public function GetLang()
	{
		$Lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 4);
		//使用substr()截取字符串，从 0 位开始，截取4个字符
		if (preg_match('/zh-c/i',$Lang)) {
			//preg_match()正则表达式匹配函数
			$Lang = '简体中文';
		}
		elseif (preg_match('/zh/i',$Lang)) {
			$Lang = '繁體中文';
		}
		else {
			$Lang = 'English';
		}
		return $Lang;
	}

	public function GetBrowser()
	{
		$Browser = $_SERVER['HTTP_USER_AGENT'];
		if (preg_match('/MSIE/i',$Browser)) {
			$Browser = 'MSIE';
		}
		elseif (preg_match('/Firefox/i',$Browser)) {
			$Browser = 'Firefox';
		}
		elseif (preg_match('/Chrome/i',$Browser)) {
			$Browser = 'Chrome';
		}
		elseif (preg_match('/Safari/i',$Browser)) {
			$Browser = 'Safari';
		}
		elseif (preg_match('/Opera/i',$Browser)) {
			$Browser = 'Opera';
		}
		else {
			$Browser = 'Other';
		}
		return $Browser;
	}

	public function GetOS()
	{
		$OS = $_SERVER['HTTP_USER_AGENT'];
		if (preg_match('/win/i',$OS)) {
			$OS = 'Windows';
		}
		elseif (preg_match('/mac/i',$OS)) {
			$OS = 'MAC';
		}
		elseif (preg_match('/linux/i',$OS)) {
			$OS = 'Linux';
		}
		elseif (preg_match('/unix/i',$OS)) {
			$OS = 'Unix';
		}
		elseif (preg_match('/bsd/i',$OS)) {
			$OS = 'BSD';
		}
		else {
			$OS = 'Other';
		}
		return $OS;
	}

	public function GetIP()
	{
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			//如果变量是非空或非零的值，则 empty()返回 FALSE。
			$IP = explode(',',$_SERVER['HTTP_CLIENT_IP']);
		}
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$IP = explode(',',$_SERVER['HTTP_X_FORWARDED_FOR']);
		}
		elseif (!empty($_SERVER['REMOTE_ADDR'])) {
			$IP = explode(',',$_SERVER['REMOTE_ADDR']);
		}
		else {
			$IP[0] = 'None';
		}
		return $IP[0];
	}

	public function getAreaByIp($ip)
	{
		$url = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip='.$ip;
		$ch = curl_init($url);
		curl_setopt($ch,CURLOPT_ENCODING ,'utf8');
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回
		$location = curl_exec($ch);
		$location = json_decode($location);
		curl_close($ch);
		$loc = "";
		if($location===FALSE) return "";
		if (empty($location->desc))
		{
			$loc = $location->province.$location->city.$location->district.$location->isp;
		}
		else
		{
			$loc = $location->desc;
		}
		return $loc;
	}

}
