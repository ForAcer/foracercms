<?php
namespace Admin\Controller;

use Admin\Controller\BaseController;
use Admin\Model\CateModel;
use Admin\Model\ListModel;

defined('SITE_PATH') or die('Access Denied');

class CateController extends BaseController
{
	public function deleteCate()
	{
		$return = array('code'=>0, 'msg'=> '操作失败！', 'data'=> '');
		if(!$this->adminId)
		{
			exit(json_encode($return));
		}

		$where['id'] = $_POST['cateId'];
		$where['module_id'] =  $_POST['moduleId'];
		$countData = 0;
		$countData = ListModel::instance()->checkListValue($where);
		if($count > 0)
		{
			$return = array('code'=>0, 'msg'=> '该分类还有数据存在，删除失败！', 'data'=> '');
			exit(json_encode($return));
		}

		$countSon = 0;
		$countSon = CateModel::instance()->checkHasSonCateFun($where);
		if($countSon > 0)
		{
			$return = array('code'=>0, 'msg'=> '该分类还有子分类存在，删除失败！', 'data'=> '');
			exit(json_encode($return));
		}

		$result = CateModel::instance()->deleteCate($where);
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

	public function saveCateInfo()
	{
		$return = array('code'=>0, 'msg'=> '操作失败！', 'data'=> '');
		$success = array('code'=>200, 'msg'=> '保存成功！', 'data'=> '');

		if(!$this->adminId)
		{
			exit(json_encode($return));
		}

		if(empty($_POST['id']))
		{
			$result = CateModel::instance()->saveCate($_POST['id'], $_POST);
			if($result)
			{
				exit(json_encode($success));
			}
		}
		else
		{
			$result = CateModel::instance()->saveCate($_POST['id'], $_POST);
			if($result)
			{
				exit(json_encode($success));
			}
		}
		exit(json_encode($return));
	}
}