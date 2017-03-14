<?php
namespace Home\Controller;
use Home\Controller\CommonController;

defined('SITE_PATH') or die('Access Denied');

class IndexController extends CommonController {
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index(){
		$version = C("systemtVersion");
		$this->assign("code", 'product');
		$this->assign("version", $version);
		$this->head();
		$this->display("_index");
		$this->foot();
	}
}