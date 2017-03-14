<?php
namespace Admin\Controller;

use Admin\Controller\BaseController;
use Admin\Model\ModuleFieldModel;

defined('SITE_PATH') or die('Access Denied');

class ModuleFieldController extends BaseController
{
	public function moduleFieldBox()
	{
		$fieldTypeList = C("fieldType");
		$moduleId = $_GET['moduleId'];
		$extFieldList = ModuleFieldModel::instance()->getFieldListByModuleId($moduleId);

		$this->assign("fieldTypeList", $fieldTypeList);
		$this->assign("extFieldList", $extFieldList);
		$this->assign("moduleId", $moduleId);
		$this->display();
	}

	public function saveExtFieldInfo()
	{
		$return = array('code'=>0, 'msg'=> '操作失败！', 'data'=> '');
		if(!$this->adminId)
		{
			exit(json_encode($return));
		}

		if(!$_POST)
		{
			exit(json_encode($return));
		}

		$result = ModuleFieldModel::instance()->saveModuleField($_POST);
		if($result)
		{
			$result = array('code'=>200, 'msg'=> '添加成功！', 'data'=> '');
			exit(json_encode($result));
		}
		else
		{
			exit(json_encode($return));
		}
	}

	public function delModuleField()
	{
		$return = array('code'=>0, 'msg'=> '操作失败！', 'data'=> '');
		if(!$this->adminId)
		{
			exit(json_encode($return));
		}

		$fieldId = $_POST['fieldId'];
		$result = ModuleFieldModel::instance()->deleteModuleField($fieldId);
		if($result)
		{
			$result = array('code'=>200, 'msg'=> '删除成功！', 'data'=> '');
			exit(json_encode($result));
		}
		else
		{
			exit(json_encode($return));
		}
	}

}