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

class CateModel extends Model
{
	public function getCateInfoByCode($code)
	{
		$data = array('code'=> $code, 'is_del' => 0);
		return $this->where($data)->find();
	}

	public function getCateInfoById($cateId)
	{
		$data = array('id'=> $cateId, 'is_del' => 0);
		return $this->where($data)->find();
	}

	public function getCateList($moduleId)
	{
		if(!empty($moduleId) && $moduleId != 10)
		{
			$data['module_id'] = $moduleId;
		}

		$data['is_del'] = 0;
		return $this->where($data)->order(array('sort' => 'asc', 'ctime' => 'desc'))->select();
	}

	public function getParentCateList($moduleId)
	{
		if(!empty($moduleId) && $moduleId != 10)
		{
			$data['module_id'] = $moduleId;
		}

		$data['parent_id'] = 0;
		$data['is_del'] = 0;
		return $this->where($data)->order(array('sort' => 'asc', 'ctime' => 'desc'))->select();
	}

}





