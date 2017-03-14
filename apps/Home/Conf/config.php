<?php
return array(
	//'配置项'=>'配置值'
	'VIEW_PATH'		=> './tpl/', //模板位置单独定义
	'DEFAULT_THEME' => 'cn',     //定义模板主题
	'THEME_LIST' => 'cn',
	'TMPL_PARSE_STRING' => array(
		'__TPIMG__' => SITE_URL.'/tpl/cn/images',    //模板样式路径
		'__TPJS__' => SITE_URL.'/tpl/cn/js',
		'__TPCSS__' => SITE_URL.'/tpl/cn/css',
	),

	'URL_PATHINFO_DEPR'=> '-',//URL分隔符
	'TMPL_FILE_DEPR'=>'_', //模板文件CONTROLLER_NAME与ACTION_NAME之间的分割符
	'URL_MODEL'=> 2,
	'URL_HTML_SUFFIX' => '.html',
	'URL_MAP_RULES' => array(
		'list' => 'List/cate',
		'msg' => 'List/msg'
	),
);