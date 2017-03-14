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

class ResourceModel extends Model
{
	/**
	 * 增加资源
	 * @param $data
	 * @return mixed
	 */
	public function addResource($data)
	{
		return $this->add($data);
	}

	/*
	 * 获取图片类型的资源
	 */
	public function getImageResourceList($page, $size)
	{
		$where['file_ext'] = array('IN', array('png', 'jpg', 'gif', 'bmp'));
		$where['is_del'] = 0;
		return $this->where($where)->order("ctime desc")->page($page, $size)->select();
	}

	/**
	 * 获取图片总记录数
	 */
	public function getImageCount()
	{
		$where['file_ext'] = array('IN', array('png', 'jpg', 'gif', 'bmp'));
		$where['is_del'] = 0;
		return $this->where($where)->order("ctime desc")->count();
	}

	/*
	 * 获取资源类型的资源
	 */
	public function getResourceList($page)
	{
		//$where['file_ext'] = array('IN', array('png', 'jpg', 'gif', 'bmp'));
		$where['is_del'] = 0;
		return $this->where($where)->order("ctime desc")->page($page, 18)->select();
	}

	/*
	 * 获取资源类型的资源
	 */
	public function ManageResourceList($page)
	{
		//$where['file_ext'] = array('IN', array('png', 'jpg', 'gif', 'bmp'));
		$where['is_del'] = 0;
		return $this->where($where)->order("ctime desc")->page($page, 50)->select();
	}


	/**
	 * 获取资源总记录数
	 */
	public function getResourceCount()
	{
		//$where['file_ext'] = array('IN', array('png', 'jpg', 'gif', 'bmp'));
		$where['is_del'] = 0;
		return $this->where($where)->order("ctime desc")->count();
	}

	/**
	 * 删除资源
	 */
	public function deleteRes($id)
	{
		$where['id'] = $id;
		$where['is_del'] = 0;
		return $this->where($where)->save(array('is_del'=> 1));
	}

}





