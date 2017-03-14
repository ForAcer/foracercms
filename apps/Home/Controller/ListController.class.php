<?php
namespace Home\Controller;
use Home\Controller\CommonController;
use Home\Model\CateModel;
use Home\Model\ListModel;
use Home\Model\ListExtModel;
use Home\Model\ModuleModel;
use Home\Model\ModuleFieldModel;


defined('SITE_PATH') or die('Access Denied');

class ListController extends CommonController {

	public function __construct()
	{
		parent::__construct();
	}
	
	public function cate()
	{
		$cateId = $_GET['id'];

		$cateInfo = CateModel::instance()->getCateInfoById($cateId);
		$moduleId = $cateInfo['module_id'];
		$moduleInfo = ModuleModel::instance()->getModuleInfoById($moduleId);
		$cateList = CateModel::instance()->getCateList($moduleId);
		$parentCateList = CateModel::instance()->getParentCateList($moduleId);

		$data = array();
		foreach ($parentCateList as $value )
		{
			foreach ($cateList as $key=> $v)
			{
				if($value['id'] == $v['parent_id'])
				{
					$data[] = array(
						'id' => $v['id'],
						'module_id' => $v['module_id'],
						'parent_id' => $v['parent_id'],
						'title' => $v['title'],
						'code' => $v['code']
					);
				}
			}
		}

		$this->assign("catelist", $data);
		$this->assign("moduleInfo", $moduleInfo);
		$this->assign("rs", $cateInfo);

		$where = array('is_del' => 0);
		if($cateInfo['parent_id']  == 0)
		{
			$where['module_id'] = $moduleId;
		}
		else
		{
			$where['cate_id'] = $cateId;
		}

		$inAjax = empty($_GET['inAjax']) ? 0 : 1;
		$curPage = empty($_GET['page']) ? 1 : $_GET['page'];
		$curSize = 15;

		$listList = ListModel::instance()->getListDate($where, $curPage, $curSize);
		$this->assign("rslist", $listList);
		$count = ListModel::instance()->getListCountById($where);
		if($count > $curSize)
		{
			$pageArr = array('id' => $cateId);
			$pageBar = get_page_bar($count, $curPage, $curSize, 'moduleList', '', url('List/cate', $pageArr));
			$this->assign("pageBar", $pageBar);
		}
		$this->assign("heightLightId", $cateId);
		$this->head();
		$this->display("_list_".$moduleInfo['code']);
		$this->foot();
	}

	public function msg()
	{
		$cateId = $_GET['cid'];
		$listId = $_GET['id'];

		$cateInfo = CateModel::instance()->getCateInfoById($cateId);
		$moduleId = $cateInfo['module_id'];
		$moduleInfo = ModuleModel::instance()->getModuleInfoById($moduleId);

		if($moduleInfo['code'] != 'onepage')
		{
			$cateList = CateModel::instance()->getCateList($moduleId);
			$parentCateList = CateModel::instance()->getParentCateList($moduleId);
			$data = array();
			foreach ($parentCateList as $value )
			{
				foreach ($cateList as $key=> $v)
				{
					if($value['id'] == $v['parent_id'])
					{
						$data[] = array(
							'id' => $v['id'],
							'module_id' => $v['module_id'],
							'parent_id' => $v['parent_id'],
							'title' => $v['title'],
							'code' => $v['code']
						);
					}
				}
			}
			
			$parentInfo = CateModel::instance()->getCateInfoById($cateInfo['parent_id']);
			
			$this->assign("parentInfo", $parentInfo);
			$this->assign("catelist", $data);
		}
		else
		{
			$cwhere = array('cate_id'=> $cateId, 'is_del'=>0);
			$onepageList = ListModel::instance()->getOnepageListById($cwhere);
			$this->assign("catelist", $onepageList);
			$this->assign("listId", $listId);
		}

		$this->assign("moduleInfo", $moduleInfo);
		$this->assign("cateInfo", $cateInfo);
		$listInfo = ListModel::instance()->getListInfoById($listId);
		//获取拓展字段列表
		$moduleFieldList = ModuleFieldModel::instance()->getFieldListByModuleId($moduleId);

		if(!empty($moduleFieldList))
		{
			$listExtInfo = ListExtModel::instance()->getFieldContentByListId($listId);
			$valueArr = array();
			foreach($listExtInfo as $key=>$value)
			{
				$valueArr[$value['field']] = $value['content'];
			}
		}

		foreach($moduleFieldList as $value)
		{
			if($value['field_type'] == 'images' && !empty($valueArr[$value['field_code']]))
			{
				$imagesStr = $valueArr[$value['field_code']];
				$imagesArr  = explode(',', $imagesStr);
				$valueArr[$value['field_code']] = $imagesArr;
			}
		}

		$listInfoNew = array_merge($listInfo, $valueArr);
		$this->assign("rs", $listInfoNew);
		$this->assign("heightLightId", $listId);
		$this->head();
		if(!empty($listInfoNew['tpl_msg']))
		{
			$this->display("_".$listInfoNew['tpl_msg']);
		}
		else
		{
			$this->display("_msg_".$moduleInfo['code']);
		}
		$this->foot();
	}

}