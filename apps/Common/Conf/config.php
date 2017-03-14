<?php
return array(
	//'配置项'=>'配置值'
	'DEFAULT_MODULE' => 'Home', //默认模块
	'MODULE_ALLOW_LIST' => array('Home','Admin'),
	'URL_MODEL' => '2', //URL模式
	'SESSION_AUTO_START' => true, //是否开启session
	'URL_CASE_INSENSITIVE'  => false,
	'LOAD_EXT_CONFIG' => 'service',
	'systemtVersion' => 'v1.0.0',
	'passCode' => '8888',

	'status' => array('0' => '正常', '1'=> '关闭'),
	'isCate' => array('0' => '分类', '1'=> '不分类'),
	'moduleType' => array('page' => '单页', 'list'=> '列表'),
	'adminType' => array( 0 => '无限制', 1 => '有限制'),
	'fieldType' => array(
		'input' => '文本框',
		'editorSmall' => '编辑器(部分)',
		'editorBig' => '编辑器(完整)',
		'image'	=> '图片选择器(单图)',
		'images' => '图片选择器(多图)',
		'resource' => '资源选择'
	),

	//'TAGLIB_BEGIN'          =>  '<!--{',  // 标签库标签开始标记
	//'TAGLIB_END'            =>  '}-->',  // 标签库标签结束标记

	/* 数据库设置 */
	'DB_TYPE'               =>  'mysql',     // 数据库类型
	'DB_HOST'               =>  'xinhui2016.gotoftp2.com', // 服务器地址
	'DB_NAME'               =>  'xinhui2016',          // 数据库名
	'DB_USER'               =>  'xinhui2016',      // 用户名
	'DB_PWD'                =>  '3h7skjpk',          // 密码
	'DB_PORT'               =>  '3306',        // 端口
	'DB_PREFIX'             =>  'cms_',    // 数据库表前缀
	'DB_PARAMS'          	=>  array(), // 数据库连接参数
	'DB_DEBUG'  			=>  TRUE, // 数据库调试模式 开启后可以记录SQL日志
	'DB_FIELDS_CACHE'       =>  true,        // 启用字段缓存
	'DB_CHARSET'            =>  'utf8',      // 数据库编码默认采用utf8
	'DB_DEPLOY_TYPE'        =>  0, // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
	'DB_RW_SEPARATE'        =>  false,       // 数据库读写是否分离 主从式有效
	'DB_MASTER_NUM'         =>  1, // 读写分离后 主服务器数量
	'DB_SLAVE_NO'           =>  '1', // 指定从服务器序号
);