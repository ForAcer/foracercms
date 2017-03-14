<?php
namespace Admin\Controller;

use Think\Controller;
use Admin\Model\ModuleModel;
use Think\ViewHistory;

defined('SITE_PATH') or die('Access Denied');

class BaseController extends Controller {

	private $adminInfo;
	private $adminInfoList;

	public function __construct()
	{
		parent::__construct();
		if(empty($_SESSION['adminInfo']))
		{
			redirect(U("Admin/Login/index"));
			exit();
		}

		//记录访客信息
		$data = array();
		$clientIp = get_client_ip();
		$count = 0;
		$count = M("History")->where(array('ip'=> $clientIp))->count();
		if($count <= 0)
		{
			$data['source'] = 'admin';
			$data['lang'] = ViewHistory::instance()->GetLang();
			$data['browser'] = ViewHistory::instance()->GetBrowser();
			$data['os'] = ViewHistory::instance()->GetOS();
			$data['ip'] = $clientIp;
			$data['address'] = ViewHistory::instance()->getAreaByIp($data['ip']);
			$data['ctime'] = time();
			M("History")->add($data);
		}

		$version = C("systemtVersion");
		$adminInfo = $_SESSION['adminInfo'];

		//权限过滤 取得管理员所能操作的模块
		if(!empty($adminInfo['module_id_list'])&&($adminInfo['limit']==1))
		{
			$adminModuleStr = $adminInfo['module_id_list'];
			$adminModuleArr = explode(',', $adminModuleStr);

			$where = array('is_del'=>0, 'status'=>0, 'id'=> array('IN', $adminModuleArr));
			$moduleList = ModuleModel::instance()->getModuleList($where);
		}
		else
		{
			$where = array('is_del'=>0, 'status'=>0);
			$moduleList = ModuleModel::instance()->getModuleList($where);
		}

		$webInfo = M("WebInfo")->where(array('id'=>1, 'module_id'=>1))->find();
		$this->webInfo = $webInfo;
		$this->adminId = $adminInfo['id'];
		$this->adminLimit = $adminInfo['limit'];
		$this->adminName = $adminInfo['username'];
		$this->adminInfoList = $adminInfo;
		$this->assign("version", $version);
		$this->assign("site_url_admin", SITE_URL.'/Admin/Index/');
		$this->assign("webInfo", $this->webInfo);
		$this->assign("adminLimit", $this->adminLimit);
		$this->assign("moduleList", $moduleList);
	}
}