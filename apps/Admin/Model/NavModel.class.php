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

class NavModel extends Model
{
	public function getNavList()
	{
		$data = array('is_del' => 0);
		return $this->where($data)->order(array('sort' => 'asc', 'ctime' => 'desc'))->select();
	}

	public function getParentNavList()
	{
		$data = array('parent_id'=> 0, 'is_del' => 0);
		return $this->where($data)->order(array('sort' => 'asc', 'ctime' => 'desc'))->select();
	}

	public function saveNav($navId, $data)
	{
		if(!empty($navId))
		{
			$where = array('id'=> $navId, 'is_del'=> 0);
			return $this->where($where)->save($data);
		}
		else
		{
			return $this->add($data);
		}
	}

	public function getNavInfoById($id)
	{
		$data = array('id'=> $id, 'is_del' => 0);
		return $this->where($data)->find();
	}

	public function checkIsParentNav($id)
	{
		$where = array('parent_id'=> $id, 'is_del'=> 0);
		return $this->where($where)->count();
	}

	public function deleteNav($id)
	{
		$where = array('id' => $id, 'is_del'=> 0);
		return $this->where(array('id' => $id))->save(array('is_del'=> 1));
	}
}





