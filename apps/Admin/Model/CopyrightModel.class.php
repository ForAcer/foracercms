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

class CopyrightModel extends Model
{
	public function getOtherList()
	{
		return $this->where(array('is_del'=> 0))->select();
	}

	public function getOtherInfo($data)
	{
		return $this->where($data)->find();
	}

	public function saveOther($otherId, $data)
	{
		if(!empty($otherId))
		{
			$where = array('id'=> $otherId, 'is_del'=> 0);
			return $this->where($where)->save($data);
		}
		else
		{
			return $this->add($data);
		}
	}

	public function deleteOther($where)
	{
		$data = array('id'=> $where['id'], 'module_id'=>$where['module_id'], 'is_del' => 0);
		return $this->where($data)->save(array('is_del'=> 1));
	}

}





