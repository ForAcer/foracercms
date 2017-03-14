<?php
namespace Admin\Controller;

use Admin\Controller\BaseController;
use Admin\Model\CopyrightModel;

defined('SITE_PATH') or die('Access Denied');

class OtherController extends BaseController
{
	public function deleteOther()
	{
		$return = array('code'=>0, 'msg'=> '操作失败！', 'data'=> '');
		if(!$this->adminId)
		{
			exit(json_encode($return));
		}

		$where['id'] = $_POST['otherId'];
		$where['module_id'] =  $_POST['moduleId'];

		$result = CopyrightModel::instance()->deleteOther($where);
		if($result)
		{
			$return = array('code'=>200, 'msg'=> '删除成功！', 'data'=> '');
			exit(json_encode($return));
		}
		else
		{
			exit(json_encode($return));
		}
	}

	public function saveOtherInfo()
	{
		$return = array('code'=>0, 'msg'=> '操作失败！', 'data'=> '');
		$success = array('code'=>200, 'msg'=> '保存成功！', 'data'=> '');

		if(!$this->adminId)
		{
			exit(json_encode($return));
		}

		if(empty($_POST['id']))
		{
			$result = CopyrightModel::instance()->saveOther($_POST['id'], $_POST);
			if($result)
			{
				exit(json_encode($success));
			}
		}
		else
		{
			$result = CopyrightModel::instance()->saveOther($_POST['id'], $_POST);
			if($result)
			{
				exit(json_encode($success));
			}
		}
		exit(json_encode($return));
	}
}