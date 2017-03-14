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

class ListExtModel extends Model
{
	public function getFieldContentByIds($listIds)
	{
		$where = array('id'=>array('IN', $listIds));
		return $result =  $this->where($where)->select();
	}

	public function getFieldContentByListId($listId)
	{
		return $this->where(array('id'=>$listId))->select();
	}
}





