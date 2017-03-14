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

class ModuleModel extends Model
{
	public function getModuleInfoById($moduleId)
	{
		$data = array('id'=> $moduleId, 'is_del' => 0);
		return $this->where($data)->find();
	}
}





