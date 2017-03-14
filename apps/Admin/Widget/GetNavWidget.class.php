<?php
namespace Admin\Widget;
use Common\Controller\BaseController;

class GetNavWidget extends BaseController {
	public function listNav($link_url)
	{
		$this->assign("link_url", $link_url);
		$this->display("Widget:listNav");
	}
}