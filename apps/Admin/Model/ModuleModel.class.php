<?php
/**
 * Created by PhpStorm.
 * User: ForAcer
 * Date: 2016/11/7
 * Time: 11:56
 */

namespace Admin\Model;
use Think\Model;

defined('SITE_PATH') or die('Access Denied');

class ModuleModel extends Model
{
	public function getModuleList($data)
	{
		return $this->where($data)->select();
	}

	public function getModuleInfo($moduleId)
	{
		$where = array('id' => $moduleId, 'is_del'=> 0);
		return $this->where($where)->find();
	}
}





