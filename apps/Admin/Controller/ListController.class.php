<?php
namespace Admin\Controller;

use Admin\Controller\BaseController;
use Admin\Model\ModuleFieldModel;
use Admin\Model\ListModel;
use Admin\Model\ListExtModel;

defined('SITE_PATH') or die('Access Denied');

class ListController extends BaseController
{
	public function saveListInfo()
	{
		$return = array('code'=>0, 'msg'=> '操作失败！', 'data'=> '');
		$success = array('code'=>200, 'msg'=> '保存成功！', 'data'=> '');

		$data = $_POST;
		$data['ctime'] = strtotime($data['ptime']);
		$moduleFieldList = ModuleFieldModel::instance()->getFieldListByModuleId($data['module_id']);
		if(empty($data['id']))
		{
			$result = ListModel::instance()->saveListInfo($data['id'], $data);
			if(!empty($moduleFieldList))
			{
				$fieldValueList = array();
				foreach($data as $key=>$v)
				{
					foreach($moduleFieldList as $value)
					{
						if($key == $value['field_code'])
						{
							if(is_array($v)&& !empty($v))
							{
								$v = implode(',', $v);
							}
							$fieldValueList[] = array('id'=> $result, 'field'=> $key, 'content'=> $v);
						}
					}
				}

				$addResult = ListExtModel::instance()->insertFieldContent($fieldValueList);
			}

			if($result)
			{
				exit(json_encode($success));
			}
			else
			{
				exit(json_encode($return));
			}
		}
		else
		{
			$listId = $data['id'];
			$result = ListModel::instance()->saveListInfo($listId, $data);
			$count = ListExtModel::instance()->getFieldNumById($listId);
			if($count > 0)
			{
				ListExtModel::instance()->deleteFieldContent($listId);
			}

			if(!empty($moduleFieldList))
			{
				$fieldValueList = array();
				foreach ($data as $key => $v)
				{
					foreach ($moduleFieldList as $value)
					{
						if ($key == $value['field_code'])
						{
							if (is_array($v) && !empty($v))
							{
								$v = implode(',', $v);
							}
							$fieldValueList[] = array('id' => $listId, 'field' => $key, 'content' => $v);
						}
					}
				}

				$addResult = ListExtModel::instance()->insertFieldContent($fieldValueList);
			}

			exit(json_encode($success));
		}

		$return = array('code'=>0, 'msg'=> '操作失败！', 'data'=> $data);
		exit(json_encode($return));
	}

	public function changeListAttrFun()
	{
		$return = array('code'=>0, 'msg'=> '操作失败！', 'data'=> '');
		$success = array('code'=>200, 'msg'=> '', 'data'=> '');

		if(!$this->adminId)
		{
			exit(json_encode($return));
		}

		$listId = $_POST['listId'];
		$attrType = text($_POST['type']);
		$attrValue = $_POST['isValue'];

		$result = ListModel::instance()->changeListAttr($listId, $attrType, $attrValue);
		if($result)
		{
			exit(json_encode($success));
		}
		else
		{
			exit(json_encode($return));
		}
	}

	public function deleteList()
	{
		$return = array('code'=>0, 'msg'=> '操作失败！', 'data'=> '');
		$success = array('code'=>200, 'msg'=> '删除成功！', 'data'=> '');

		if(!$this->adminId)
		{
			exit(json_encode($return));
		}

		$listIds = text($_POST['listIds']);
		$listIdArr = explode(',', $listIds);
		$result = ListModel::instance()->deleteListFun($listIdArr);

		$countNum = ListExtModel::instance()->getFieldByIds($listIdArr);
		if($countNum > 0)
		{
			$delResult = ListExtModel::instance()->deleteFieldByIds($listIdArr);
		}

		if($result)
		{
			exit(json_encode($success));
		}
		else
		{
			exit(json_encode($return));
		}
	}

	public function copyContentFun()
	{
		$return = array('code'=>0, 'msg'=> '操作失败！', 'data'=> '');
		$success = array('code'=>200, 'msg'=> '复制成功！', 'data'=> '');

		if(!$this->adminId)
		{
			exit(json_encode($return));
		}

		$listId = text($_POST['listId']);
		$number = text($_POST['number']);

		$listInfo = ListModel::instance()->getListInfoById($listId);
		$listInfo['ctime'] = time();
		unset($listInfo['id']);
		$listArr = array();
		for($i=0;$i<$number;$i++)
		{
			$listArr[$i] = $listInfo;
		}

		$result = ListModel::instance()->copyListFun($listArr);
		if($result)
		{
			exit(json_encode($success));
		}
		else
		{
			exit(json_encode($return));
		}
	}
}