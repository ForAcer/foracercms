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

class ListExtModel extends Model
{
	public function getFieldContentByListId($listId)
	{
		return $this->where(array('id'=>$listId))->select();
	}

	public function insertFieldContent($data)
	{
		return $this->addAll($data);
	}

	public function getFieldNumById($listId)
	{
		return $this->where(array('id'=> $listId))->count();
	}

	public function deleteFieldContent($listId)
	{
		return $this->where(array('id'=> $listId))->delete();
	}

	public function getFieldByIds($listIds)
	{
		$where = array("id"=> array("IN", $listIds));
		return $this->where($where)->count();
	}

	public function deleteFieldByIds($listIds)
	{
		$where = array("id"=> array("IN", $listIds));
		return $this->where($where)->delete();
	}
}





