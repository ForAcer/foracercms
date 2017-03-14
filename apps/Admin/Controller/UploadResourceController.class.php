<?php
namespace Admin\Controller;

use Admin\Controller\BaseController;
use Admin\Model\ResourceModel;
use Think\Image;
use Think\Page;

defined('SITE_PATH') or die('Access Denied');

class UploadResourceController extends BaseController
{

	public function uploadPage()
	{
		$this->display();
	}

	//上传文件方法
	public function uploadFile()
	{
		$return = array(
			'code' => 0,
			'msg' => '',
			'data' => ''
		);

		$upload = new \Think\Upload();
		$upload->maxSize   = 20971520 ;
		$upload->exts      = array();// 设置附件上传类型
		$upload->autoSub   = true;
		$upload->subName   =  array('date','Ymd');

		$info   =   $upload->upload();

		if(in_array($info['file']['ext'], array('png', 'jpg', 'gif', 'bmp', 'jpeg', 'bmp')))
		{
			$image = new Image();
			$image->open($upload->rootPath.$info['file']['savepath'].$info['file']['savename']);
			$num = rand(1000000,9999999);
			$image->thumb(80, 80,\Think\Image::IMAGE_THUMB_FILLED)->save('./data/thumb/thumb_'.$num.'.'.$info['file']['ext']);
			$thumb_url = '/data/thumb/thumb_'.$num.'.'.$info['file']['ext'];
		}
		else
		{
			$thumb_url = '';
		}

		if(!$info) {
			$return['msg'] = '上传失败';
			exit(json_encode($return));
		}
		else
		{
			$data = array(
				'title' 	=> $info['file']['name'],
				'file_ext'	=> $info['file']['ext'],
				'file_size'	=> $info['file']['size'],
				'file_url'	=> '/data/upload/'.$info['file']['savepath'].$info['file']['savename'],
				'thumb_url'	=> $thumb_url,
				'file_md5'	=> $info['file']['md5'],
				'author'	=> $this->adminName,
				'is_del'	=> 0,
				'ctime'		=> time()
			);

			$result = ResourceModel::instance()->addResource($data);

			$return['code'] = 200 ;
			$return['msg'] = '上传成功';
			exit(json_encode($return));
		}
	}

	//选择单张图片
	public function selectImage()
	{
		global $_G;

		$imageId = $_GET['imageId'];
		$moduleId = $_GET['moduleId'];
		$uploadType = $_GET['uploadType'];

		$inAjax = empty($_GET['inAjax']) ? 0 : 1;
		$curPage = empty($_GET['page']) ? 1 : $_GET['page'];
		$curSize = 18;

		$imagesList = ResourceModel::instance()->getImageResourceList($curPage, $curSize);
		$this->assign("imagesList", $imagesList);
		$count = ResourceModel::instance()->getImageCount();

		if($count > 0)
		{
			$_G['inAjax'] = 1;
			$pageBar = get_page_bar($count, $curPage, $curSize, 'imageResourceList', '', U('Admin/UploadResource/selectImage'));
			$this->assign("pageBar", $pageBar);
			unset($_G['inAjax']);
		}

		$this->assign("moduleId", $moduleId);
		$this->assign("imageId", $imageId);

		if($inAjax==1)
		{
			$this->display("imageList");
			exit();
		}

		$this->display();
	}


	//选择多张图片
	public function selectMoreImage()
	{
		global $_G;

		$imageId = $_GET['imageId'];
		$moduleId = $_GET['moduleId'];
		$uploadType = $_GET['uploadType'];

		$inAjax = empty($_GET['inAjax']) ? 0 : 1;
		$curPage = empty($_GET['page']) ? 1 : $_GET['page'];
		$curSize = 18;

		$imagesList = ResourceModel::instance()->getImageResourceList($curPage, $curSize);
		$this->assign("imagesList", $imagesList);
		$count = ResourceModel::instance()->getImageCount();

		if($count > 0)
		{
			$_G['inAjax'] = 1;
			$pageBar = get_page_bar($count, $curPage, $curSize, 'imageResourceList', '', U('Admin/UploadResource/selectMoreImage'));
			$this->assign("pageBar", $pageBar);
			unset($_G['inAjax']);
		}

		$this->assign("moduleId", $moduleId);
		$this->assign("imageId", $imageId);

		if($inAjax==1)
		{
			$this->display("imageList");
			exit();
		}

		$this->display();
	}

	//选择资源
	public function selectResource()
	{
		global $_G;

		$imageId = $_GET['imageId'];
		$moduleId = $_GET['moduleId'];
		$uploadType = $_GET['uploadType'];

		$inAjax = empty($_GET['inAjax']) ? 0 : 1;
		$curPage = empty($_GET['page']) ? 1 : $_GET['page'];
		$curSize = 18;

		$resourceList = ResourceModel::instance()->getResourceList($curPage);
		$this->assign("resourceList", $resourceList);
		$count = ResourceModel::instance()->getResourceCount();

		if($count > 0)
		{
			$_G['inAjax'] = 1;
			$pageBar = get_page_bar($count, $curPage, $curSize, 'imageResourceList', '', U('Admin/UploadResource/selectResource'));
			$this->assign("pageBar", $pageBar);
			unset($_G['inAjax']);
		}

		$this->assign("moduleId", $moduleId);
		$this->assign("imageId", $imageId);

		if($inAjax==1)
		{
			$this->display("resourceList");
			exit();
		}

		$this->display();
	}

}