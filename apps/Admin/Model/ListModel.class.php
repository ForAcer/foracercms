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

class ListModel extends Model
{
	//获取点击量前十主题
	public function getTopTenList()
	{
		$data = array(
			'is_del'=> 0
		);
		return $this->where($data)->order(array('view_count' => 'desc'))->limit(10)->select();
	}

	public function checkListValue($where)
	{
		$data = array(
			'cate_id' => $where['id'],
			'module_id' => $where['module_id'],
			'is_del' => 0,
			'status' => 0
		);
		return $this->where($data)->count();
	}

	/**
	 * 获取该模块内容记录数
	 */
	public function getListCountById($moduleId)
	{
		$where['module_id'] = $moduleId;
		$where['is_del'] = 0;
		return $this->where($where)->count();
	}

	public function getListAll($moduleId, $page, $size)
	{
		$where = array('module_id'=>$moduleId, 'is_del'=>0);
		return $this->where($where)->order(array('ctime'=>'desc','sort'=>'desc'))->page($page, $size)->select();
	}

	public function getListAllModule($page, $size)
	{
		$where = array('is_del'=>0);
		return $this->field("id,cate_id,title")->where($where)->order(array('ctime'=>'desc','sort'=>'desc'))->page($page, $size)->select();
	}

	public function getAllListCount()
	{
		$where = array('is_del'=>0);
		return $this->where($where)->count();
	}

	public function getListInfoById($listId)
	{
		$where = array('id'=> $listId, 'is_del'=>0);
		return $this->where($where)->find();
	}

	public function saveListInfo($listId, $data)
	{
		if(empty($listId))
		{
			return $this->add($data);
		}
		else
		{
			$where = array('id'=> $listId, 'is_del'=> 0);
			return $this->where($where)->save($data);
		}
	}

	/**
	 * 修改内容属性 is_top is_best is_vouch
	 */
	public function changeListAttr($listId, $attrType, $attrValue)
	{
		$where = array('id'=>$listId, 'is_del'=>0);
		return $this->where($where)->save(array($attrType => $attrValue));
	}

	/**
	 * 删除内容
	 */
	public function deleteListFun($listIds)
	{
		$where = array('id'=> array('IN', $listIds), 'is_del'=>0);
		return $this->where($where)->delete();
	}

	/**
	 * 批量复制
	 */
	public function copyListFun($data)
	{
		return $this->addAll($data);
	}

}





