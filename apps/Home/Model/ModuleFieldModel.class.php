<?php
/**
 * Created by PhpStorm.
 * User: ForAcer
 * Date: 2016/11/7
 * Time: 11:56
 */

namespace Home\Model;
use Think\Model;

defined('SITE_PATH') or die('Access Denied');

class ModuleFieldModel extends Model
{
	public function saveModuleField($data)
	{
		return $this->add($data);
	}

	public function getFieldListByModuleId($moduleId)
	{
		$where = array('module_id'=> $moduleId, 'is_del'=>0);
		return $this->where($where)->order(array('sort'=> 'asc'))->select();
	}

	public function deleteModuleField($fieldId)
	{
		$where = array('id'=> $fieldId, 'is_del'=> 0);
		return $this->where($where)->delete();
	}
}





