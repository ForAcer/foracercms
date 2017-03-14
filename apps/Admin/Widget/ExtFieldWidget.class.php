<?php
namespace Admin\Widget;
use Common\Controller\BaseController;

class ExtFieldWidget extends BaseController {

	public function ExtFieldList($data, $valueList)
	{
		//对多图进行处理 把字符串分割成 一维数组
		foreach($data as $value)
		{
			if($value['field_type'] == 'images' && !empty($valueList[$value['field_code']]))
			{
				$imagesStr = $valueList[$value['field_code']];
				$imagesArr  = explode(',', $imagesStr);
				$valueList[$value['field_code']] = $imagesArr;
			}
		}

		$this->assign("valueList", $valueList);
		$this->assign("data", $data);
		$this->display("Widget:extFieldList");
	}
}