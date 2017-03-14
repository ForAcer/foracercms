<?php
namespace Admin\Controller;

use Admin\Model\CopyrightModel;
use Admin\Model\NavModel;
use Admin\Model\ModuleModel;
use Admin\Model\ResourceModel;
use Admin\Model\AdminModel;
use Admin\Model\CateModel;
use Admin\Model\ModuleFieldModel;
use Admin\Model\ListModel;
use Admin\Model\ListExtModel;

defined('SITE_PATH') or die('Access Denied');

class IndexController extends BaseController
{

	public function index()
	{
		$topList = ListModel::instance()->getTopTenList();
		$this->assign("topList", $topList);
		$this->assign("indexCurrent", "open active");
		$this->display();
	}

	public function webinfo()
	{
		$this->assign("current", "webinfo");
		$webInfo = M("WebInfo")->where(array('id'=>1, 'module_id'=>1))->find();
		$this->assign("webInfo", $webInfo);
		$this->display();
	}

	public function webInfoEditFun()
	{
		$return = array('code'=>0, 'msg'=> '提交失败！', 'data'=> '');
		if(!$this->adminId)
		{
			exit(json_encode($return));
		}

		$data = array();
		$data['title'] = text($_POST['siteName']);
		$data['seo_desc'] = text($_POST['siteSeoDesc']);
		$data['seo_keyword'] = text($_POST['siteSeoKeyword']);
		$data['logo'] = text($_POST['siteLogo']);
		$data['name'] = text($_POST['siteUser']);
		$data['number'] = text($_POST['siteTel']);
		$data['phone'] = text($_POST['sitePhone']);
		$data['email'] = text($_POST['siteEmail']);
		$data['address'] = text($_POST['siteAddress']);
		$data['kfqq'] = text($_POST['siteKfqq']);
		$data['status'] = text($_POST['siteStatus']);
		$data['note'] = text($_POST['siteNote']);
		$data['ctime'] = time();

		$result = M("WebInfo")->where(array('id'=>1, 'module_id'=>1))->save($data);
		if($result)
		{
			$result = array('code'=>200, 'msg'=> '修改成功！', 'data'=> '');
			exit(json_encode($result));
		}
		else
		{
			exit(json_encode($return));
		}
	}


	/**
	 * 导航列表
	 */
	public function cateList()
	{
		$moduleId = $_GET['moduleId'];
		$moduleCode = $_GET['moduleCode'];
		$this->assign("activeLi", $moduleCode.'Cate');
		$this->assign("current", $moduleCode);

		$parentCateList  = CateModel::instance()->getParentCateList($moduleId);
		$cateList = CateModel::instance()->getCateList($moduleId);

		$data = array();
		foreach ($parentCateList as $value )
		{
			foreach ($cateList as $v)
			{
				if($value['id'] == $v['parent_id'])
				{
					$data[$value['id']][] = array(
						'id' => $v['id'],
						'module_id' => $v['module_id'],
						'parent_id' => $v['parent_id'],
						'title' => $v['title'],
						'code' => $v['code']
					);
				}
			}
		}
		$this->assign("parentCateList", $parentCateList);
		$this->assign("data", $data);
		$this->assign("moduleId", $moduleId);
		$this->display();
	}

	/**
	 * 分类编辑
	 */
	public function cateEdit()
	{
		$this->assign("activeLi", "cateEdit");
		$this->assign("current", "cate");

		$moduleId = $_GET['moduleId'];
		$parentCateList  = CateModel::instance()->getParentCateList($moduleId);
		$this->assign("parentCateList", $parentCateList);

		if(empty($_GET['cateId']))
		{
			$this->assign("dataList", $this->moduleList);
			$this->display();
			exit();
		}

		$cateId = $_GET['cateId'];
		$cateInfo = CateModel::instance()->getCateInfoById($cateId);
		$this->assign("cateInfo", $cateInfo);
		$this->display();
	}

	/**
	 * 导航列表
	 */
	public function navList()
	{
		$moduleId = $_GET['moduleId'];

		$parentNavList  = NavModel::instance()->getParentNavList();
		$navList = NavModel::instance()->getNavList();

		$data = array();
		foreach ($parentNavList as $value )
		{
			foreach ($navList as $v)
			{
				if($value['id'] == $v['parent_id'])
				{
					$data[$value['id']][] = array(
						'id' => $v['id'],
						'module_id' => $v['module_id'],
						'title' => $v['title']
					);
				}
			}
		}

		$this->assign("parentNavList", $parentNavList);
		$this->assign("data", $data);

		$this->assign("activeLi", "navList");
		$this->assign("current", "nav");
		$this->assign("moduleId", $moduleId);
		$this->display();
	}

	/**
	 * 导航编辑
	 */
	public function navEdit()
	{
		$this->assign("activeLi", "navEdit");
		$this->assign("current", "nav");


		$parentNavList  = NavModel::instance()->getParentNavList();
		$this->assign("parentNavList", $parentNavList);

		if(empty($_GET['navId']))
		{
			$this->assign("dataList", $this->moduleList);
			$this->display();
			exit();
		}

		$navId = $_GET['navId'];
		$navInfo = NavModel::instance()->getNavInfoById($navId);
		$this->assign("navInfo", $navInfo);
		$this->display();
	}

	/**
	 * 保存导航
	 */
	public function saveNavEdit()
	{
		$return = array('code'=>0, 'msg'=> '操作失败！', 'data'=> '');
		$success = array('code'=>200, 'msg'=> '保存成功！', 'data'=> '');

		if(!$this->adminId)
		{
			exit(json_encode($return));
		}

		if(empty($_POST['id']))
		{
			$result = NavModel::instance()->saveNav($_POST['id'], $_POST);
			if($result)
			{
				exit(json_encode($success));
			}
		}
		else
		{
			$result = NavModel::instance()->saveNav($_POST['id'], $_POST);
			if($result)
			{
				exit(json_encode($success));
			}
		}
		exit(json_encode($return));
	}

	/**
	 * 删除导航
	 */
	public function delNav()
	{
		$return = array('code'=>0, 'msg'=> '操作失败！', 'data'=> '');
		if(!$this->adminId)
		{
			exit(json_encode($return));
		}

		if(empty($_POST['navId']))
		{
			exit(json_encode($return));
		}

		$count = 0;
		$count = NavModel::instance()->checkIsParentNav($_POST['navId']);
		if($count > 0)
		{
			$return = array('code'=>0, 'msg'=> '还有下级导航，不能删除！', 'data'=> '');
			exit(json_encode($return));
		}

		$result = NavModel::instance()->deleteNav($_POST['navId']);
		if($result)
		{
			$result = array('code'=>200, 'msg'=> '删除成功！', 'data'=> '');
			exit(json_encode($result));
		}
		else
		{
			exit(json_encode($return));
		}
	}

	/**
	 * 模块列表
	 */
	public function module()
	{
		if(empty($this->adminId))
		{
			redirect('Admin/Login/index');
			exit();
		}

		$moduleType = C("moduleType");
		$statusList = C("status");
		$isCate = C("isCate");

		$where = array('is_del' => 0, 'status' => 0);
		$mList = ModuleModel::instance()->getModuleList($where);

		$this->assign("mList", $mList);
		$this->assign("moduleCurrent", "open active");
		$this->assign("moduleType", $moduleType);
		$this->assign("statusList", $statusList);
		$this->assign("isCate", $isCate);

		$this->display();
	}

	/**
	 * 模块编辑框
	 */
	public function editModuleBox()
	{
		$return = array('code'=>0, 'msg'=> '操作失败！', 'data'=> '');
		if(!$this->adminId)
		{
			exit(json_encode($return));
		}

		$this->display();
	}

	/**
	 * 管理员列表
	 */
	public function admin()
	{
		if(empty($this->adminId))
		{
			redirect('Admin/Login/index');
			exit();
		}

		$adminTypeList = C("adminType");
		$adminAllList = AdminModel::instance()->getAdminList();

		$this->assign("adminAllList", $adminAllList);
		$this->assign("adminTypeList", $adminTypeList);
		$this->assign("adminManageCurrent", "open active");
		$this->display();
	}

	/**
	 * 管理员信息弹框
	 */
	public function editAdminInfoBox()
	{
		$userId = $_GET['userId'];
		$where = array(
			'id' => $userId,
			'is_del' => 0
		);
		$userInfo = AdminModel::instance()->getAdminInfo($where);
		$this->assign("userInfo", $userInfo);
		$this->display();
	}

	/**
	 * 删除管理员
	 */
	public function deleteAdminUser()
	{
		$return = array('code'=>0, 'msg'=> '操作失败！', 'data'=> '');
		if(!$this->adminId)
		{
			exit(json_encode($return));
		}

		$adminId = $_POST['userId'];
		$result = AdminModel::instance()->deleteAdmin($adminId);
		if($result)
		{
			$result = array('code'=>200, 'msg'=> '删除成功！', 'data'=> '');
			exit(json_encode($result));
		}
		else
		{
			exit(json_encode($return));
		}
	}


	/**
	 * 资源管理模块
	 */
	public function resource()
	{
		global $_G;

		$moduleId = $_GET['moduleId'];

		$inAjax = empty($_GET['inAjax']) ? 0 : 1;
		$curPage = empty($_GET['page']) ? 1 : $_GET['page'];
		$curSize = 50;

		$resourceList = ResourceModel::instance()->ManageResourceList($curPage);
		$this->assign("resourceList", $resourceList);
		$count = ResourceModel::instance()->getResourceCount();

		if($count > 50)
		{
			$_G['inAjax'] = 1;
			$pageBar = get_page_bar($count, $curPage, $curSize, 'resourceListArea', '', U('Admin/Index/resource'));
			$this->assign("pageBar", $pageBar);
			unset($_G['inAjax']);
		}

		$this->assign("moduleId", $moduleId);
		$this->assign("current", "resource");

		if($inAjax==1)
		{
			$this->display("resourceManageList");
			exit();
		}

		$this->display();
	}

	/**
	 * 删除资源
	 */
	public function deleteResource()
	{
		$return = array('code'=>0, 'msg'=> '操作失败！', 'data'=> '');
		if(!$this->adminId)
		{
			exit(json_encode($return));
		}

		$resId = $_POST['resourceId'];
		$result = ResourceModel::instance()->deleteRes($resId);
		if($result)
		{
			$result = array('code'=>200, 'msg'=> '删除成功！', 'data'=> '');
			exit(json_encode($result));
		}
		else
		{
			exit(json_encode($return));
		}
	}

	public function otherList()
	{
		$this->assign("activeLi", "otherList");
		$this->assign("current", "other");
		$otherList = CopyrightModel::instance()->getOtherList();

		$this->assign("otherList", $otherList);
		$this->display();
	}

	public function otherEdit()
	{
		$this->assign("activeLi", "otherEdit");
		$this->assign("current", "other");

		$otherId = $_GET['otherId'];
		$where = array(
			'id' => $otherId,
			'is_del' => 0
		);
		$otherInfo = CopyrightModel::instance()->getOtherInfo($where);
		$this->assign("otherInfo", $otherInfo);
		$this->display();
	}

	public function listEdit()
	{
		$moduleId = $_GET['moduleId'];
		$moduleCode = $_GET['moduleCode'];
		$this->assign("activeLi", $moduleCode.'Edit');
		$this->assign("current", $moduleCode);

		$listId = $_GET['listId'];
		$moduleId = $_GET['moduleId'];

		$moduleInfo = ModuleModel::instance()->getModuleInfo($moduleId);
		$this->assign("moduleInfo", $moduleInfo);

		$parentCateList  = CateModel::instance()->getParentCateList($moduleId);
		$cateList = CateModel::instance()->getCateList($moduleId);

		$data = array();
		foreach ($parentCateList as $value )
		{
			foreach ($cateList as $v)
			{
				if($value['id'] == $v['parent_id'])
				{
					$data[$value['id']][] = array(
						'id' => $v['id'],
						'module_id' => $v['module_id'],
						'parent_id' => $v['parent_id'],
						'title' => $v['title'],
						'code' => $v['code']
					);
				}
			}
		}

		$this->assign("parentCateList", $parentCateList);
		$this->assign("data", $data);

		//获取拓展字段列表
		$moduleFieldList = ModuleFieldModel::instance()->getFieldListByModuleId($moduleId);
		$this->assign("moduleFieldList", $moduleFieldList);

		if(!empty($listId))
		{
			//如果不为空为修改
			$listInfo = ListModel::instance()->getListInfoById($listId);
			$listExtInfo = ListExtModel::instance()->getFieldContentByListId($listId);
			$valueArr = array();
			foreach($listExtInfo as $key=>$value)
			{
				$valueArr[$value['field']] = $value['content'];
			}

			$this->assign("rs", $listInfo);

			//输出拓展字段的值
			$this->assign("valueArr", $valueArr);
		}

		$this->assign("moduleId", $moduleId);
		$this->display();
	}

	public function listPage()
	{
		global $_G;

		$moduleId = $_GET['moduleId'];
		$moduleCode = $_GET['moduleCode'];
		$this->assign("activeLi", $moduleCode.'List');
		$this->assign("current", $moduleCode);

		$moduleInfo = ModuleModel::instance()->getModuleInfo($moduleId);
		$this->assign("moduleInfo", $moduleInfo);

		$moduleCateList = CateModel::instance()->getCateList($moduleId);
		$cateList = array();
		foreach($moduleCateList as $value)
		{
			$cateList[$value['id']] = array(
				'id' => $value['id'],
				'title' => $value['title']
			);
		}

		$inAjax = empty($_GET['inAjax']) ? 0 : 1;
		$curPage = empty($_GET['page']) ? 1 : $_GET['page'];
		$curSize = 15;

		$listList = ListModel::instance()->getListAll($moduleId, $curPage, $curSize);
		$this->assign("listData", $listList);
		$count = ListModel::instance()->getListCountById($moduleId);
		if($count > $curSize)
		{
			$_G['inAjax'] = 1;
			$pageArr = array('moduleId' => $moduleId);
			$pageBar = get_page_bar($count, $curPage, $curSize, 'moduleList', '', U('Admin/Index/listPage', $pageArr));
			$this->assign("pageBar", $pageBar);
			unset($_G['inAjax']);
		}

		$this->assign("cateList", $cateList);
		$this->assign("moduleId", $moduleId);
		if($inAjax==1)
		{
			$this->display("moduleList");
			exit();
		}

		$this->display();
	}

	public function message()
	{
		$this->assign("current", "message");

		$moduleId = $_GET['moduleId'];
		$moduleInfo = ModuleModel::instance()->getModuleInfo($moduleId);

		$this->assign("moduleInfo", $moduleInfo);

		$this->display();
	}

	public function listCateNavBox()
	{
		$linkUrlId = text($_GET['linkUrlId']);

		$parentCateList  = CateModel::instance()->getParentCateList($moduleId);
		$cateList = CateModel::instance()->getCateList($moduleId);

		$data = array();
		foreach ($parentCateList as $value )
		{
			foreach ($cateList as $v)
			{
				if($value['id'] == $v['parent_id'])
				{
					$data[$value['id']][] = array(
						'id' => $v['id'],
						'module_id' => $v['module_id'],
						'parent_id' => $v['parent_id'],
						'title' => $v['title'],
						'code' => $v['code']
					);
				}
			}
		}

		$this->assign("parentCateList", $parentCateList);
		$this->assign("data", $data);
		$this->assign("linkUrlId", $linkUrlId);

		$this->display();
	}

	public function listMsgNavBox()
	{
		global $_G;

		$linkUrlId = text($_GET['linkUrlId']);

		$inAjax = empty($_GET['inAjax']) ? 0 : 1;
		$curPage = empty($_GET['page']) ? 1 : $_GET['page'];
		$curSize = 15;

		$allCateList = CateModel::instance()->getCateAllModule();
		$cateList = array();
		foreach($allCateList as $value)
		{
			$cateList[$value['id']] = array(
				'id' => $value['id'],
				'title' => $value['title']
			);
		}

		$inAjax = empty($_GET['inAjax']) ? 0 : 1;
		$curPage = empty($_GET['page']) ? 1 : $_GET['page'];
		$curSize = 15;

		$listList = ListModel::instance()->getListAllModule($curPage, $curSize);
		$this->assign("listData", $listList);
		$count = ListModel::instance()->getAllListCount();
		if($count > $curSize)
		{
			$_G['inAjax'] = 1;
			$pageArr = array();
			$pageBar = get_page_bar($count, $curPage, $curSize, 'boxListArea', '', U('Admin/Index/listMsgNavBox', $pageArr));
			$this->assign("pageBar", $pageBar);
			unset($_G['inAjax']);
		}

		$this->assign("linkUrlId", $linkUrlId);
		$this->assign("cateList", $cateList);
		if($inAjax==1)
		{
			$this->display("boxListArea");
			exit();
		}

		$this->display();
	}

	public function moban()
	{
		$this->display();
	}
}