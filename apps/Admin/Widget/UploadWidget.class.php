<?php
namespace Admin\Widget;
use Common\Controller\BaseController;

class UploadWidget extends BaseController {

	public function uploadImage()
	{
		$this->display("Widget:uploadImage");
	}

	public function uploadResource()
	{
		$this->display("Widget:uploadResource");
	}

	public function resourceManage()
	{
		$this->display("Widget:resourceManage");
	}

}