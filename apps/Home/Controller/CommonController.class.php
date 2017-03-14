<?php
namespace Home\Controller;

use Think\Controller;
use Think\ViewHistory;

defined('SITE_PATH') or die('Access Denied');

class CommonController extends Controller {

	public function __construct()
	{
		parent::__construct();
		//记录访客信息
		$data = array();
		$clientIp = get_client_ip();
		$count = 0;
		$count = M("History")->where(array('ip'=> $clientIp))->count();
		if($count <= 0)
		{
			$data['source'] = 'index';
			$data['lang'] = ViewHistory::instance()->GetLang();
			$data['browser'] = ViewHistory::instance()->GetBrowser();
			$data['os'] = ViewHistory::instance()->GetOS();
			$data['ip'] = $clientIp;
			$data['address'] = ViewHistory::instance()->getAreaByIp($data['ip']);
			$data['ctime'] = time();
			M("History")->add($data);
		}
		$version = C("systemtVersion");
		$webInfo = M("WebInfo")->where(array('id'=>1, 'module_id'=>1))->find();
		$this->webInfo = $webInfo;
		$this->assign("version", $version);
		$this->assign("webInfo", $this->webInfo);
	}
	
	public function head()
	{
		$this->display("_header");
	}
	
	public function foot()
	{
		$this->display("_footer");
	}
	
}