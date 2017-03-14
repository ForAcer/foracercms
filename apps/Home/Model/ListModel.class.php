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

class ListModel extends Model
{
	public function getOnepageListById($data)
	{
		return $this->where($data)->order(array('sort'=>'desc','ctime'=> 'asc'))->select();
	}

	public function getListByNeed($cateId, $sort, $number, $type)
	{
		//显示全部
		if ($type == 'all')
		{
			$where = array('cate_id' => $cateId, 'is_del' => 0);
			return $this->where($where)->order(array('is_top' => 'desc', 'sort' => $sort, 'ctime' => $sort))->limit($number)->select();
		}
		//过滤掉is_top is_best is_vouch
		elseif ($type == 'common')
		{
			$where = array('cate_id' => $cateId, 'is_top' => 0, 'is_best' => 0, 'is_vouch' => 0, 'is_del' => 0);

			return $this->where($where)->order(array('is_top' => 'desc', 'sort' => $sort, 'ctime' => $sort))->limit($number)->select();
		}
		//显示对应类型的内容
		else
		{
			$where = array('cate_id' => $cateId, $type => 1, 'is_del' => 0);

			return $this->where($where)->order(array('sort' => $sort, 'ctime' => $sort))->limit($number)->select();
		}
	}

	public function getListDate($where, $page, $size)
	{
		$listArrInfo = $this->where($where)->order(array('ctime' => 'desc', 'sort' => 'desc'))->page($page, $size)->select();
		$listIds = getSubByKey($listArrInfo, "id");
		if (!empty($listIds))
		{
			$listExtArrInfo = M("ListExt")->where(array("id" => array("IN", $listIds)))->select();
		}
		$moduleFieldList = M("ModuleField")->field("module_id,field_code,field_type")->where(array("module_id" => $getModuleInfo["id"], "field_type" => "images", "is_del" => 0))->order(array("sort" => "desc"))->find();
		if (!empty($listExtArrInfo))
		{
			foreach ($listExtArrInfo as $value)
			{
				foreach ($listArrInfo as $key => $v)
				{
					if ($value["id"] == $v["id"])
					{
						if ($value["field"] == $moduleFieldList["field_code"])
						{
							$value["content"] = explode(",", $value["content"]);
						}
						$listArrInfo[$key][$value["field"]] = $value["content"];
					}
				}
			}
		}
		return $listArrInfo;
	}

	public function getListCountById($data)
	{
		return $this->where($data)->count();
	}

	public function getListInfoById($listId)
	{
		$where = array('id'=> $listId, 'is_del'=>0);
		return $this->where($where)->find();
	}
}





