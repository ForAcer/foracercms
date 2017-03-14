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

class AdminModel extends Model
{
	public function checkAdmin($data)
	{
		return $this->where($data)->count();
	}

	public function getAdminInfo($data)
	{
		return $this->where($data)->find();
	}

	public function getAdminList()
	{
		$where = array('is_del'=> 0);
		return $this->where($where)->order(array('ctime' => 'asc', 'sort' => 'asc'))->select();
	}

	public function deleteAdmin($id)
	{
		$where = array('id'=> $id, 'is_del'=> 0);
		return $this->where($where)->save(array('is_del' => 1));
	}

}





