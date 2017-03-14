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

class CateModel extends Model
{
	public function getCateList($moduleId)
	{
		if(!empty($moduleId) && $moduleId != 10)
		{
			$data['module_id'] = $moduleId;
		}

		$data['is_del'] = 0;
		return $this->where($data)->order(array('sort' => 'asc', 'ctime' => 'desc'))->select();
	}

	public function getCateAllModule()
	{
		$data['is_del'] = 0;
		return $this->field("id,title,code")->where($data)->order(array('sort' => 'asc', 'ctime' => 'desc'))->select();
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

	public function saveCate($cateId, $data)
	{
		if(!empty($cateId))
		{
			$where = array('id'=> $cateId, 'is_del'=> 0);
			return $this->where($where)->save($data);
		}
		else
		{
			return $this->add($data);
		}
	}

	public function getCateInfoById($id)
	{
		$data = array('id'=> $id, 'is_del' => 0);
		return $this->where($data)->find();
	}

	public function deleteCate($where)
	{
		$data = array('id'=> $where['id'], 'module_id'=>$where['module_id'], 'is_del' => 0);
		return $this->where($data)->save(array('is_del'=> 1));
	}

	public function checkHasSonCateFun($where)
	{
		$data = array('parent_id'=> $where['id'], 'module_id'=>$where['module_id'], 'is_del' => 0);
		return $this->where($data)->count();
	}

}





