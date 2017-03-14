<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<link rel="shortcut icon" type="image/x-icon" href="http://103.42.31.90/public/static/common/images/favicon.ico">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
	<meta name="keywords" content="<?php echo ($webInfo['seo_keyword']); ?>">
	<meta name="description" content="<?php echo ($webInfo['seo_keyword']); ?>">
	<meta name="version" content="<?php echo ($version); ?>">
	<title>ForAcer后台管理-<?php echo ($webInfo['title']); ?></title>
	<link rel="stylesheet" href="http://103.42.31.90/public/static/common/css/bootstrap.css?<?php echo ($version); ?>" />
	<link rel="stylesheet" href="http://103.42.31.90/public/static/common/css/ace.css?<?php echo ($version); ?>" />
	<link rel="stylesheet" href="http://103.42.31.90/public/static/common/css/font-awesome.css?<?php echo ($version); ?>" />
	<link rel="stylesheet" href="http://103.42.31.90/public/static/common/css/global.css?<?php echo ($version); ?>" />
	<link rel="stylesheet" href="http://103.42.31.90/public/static/common/css/form.css?<?php echo ($version); ?>" />
	<link rel="stylesheet" href="http://103.42.31.90/public/static/common/css/common.css?<?php echo ($version); ?>" />

	<script type="text/javascript">
		var SITE_URL = "http://103.42.31.90";
		var COMMON_URL = "http://103.42.31.90/public/static/common";
		var APP_STATIC_URL = 'http://103.42.31.90/public/Admin';

		// Js语言变量
		var LANG = new Array();
	</script>
	<script type="text/javascript" src="http://103.42.31.90/public/static/common/js/jquery.js?<?php echo ($version); ?>"></script>
	<script type="text/javascript" src="http://103.42.31.90/public/static/common/js/bootstrap.js?<?php echo ($version); ?>"></script>
	<script type="text/javascript" src="http://103.42.31.90/public/static/common/js/ace.js?<?php echo ($version); ?>"></script>
	<script type="text/javascript" src="http://103.42.31.90/public/static/common/js/jquery-migrate-1.2.1.js?<?php echo ($version); ?>"></script>
	<script type="text/javascript" src="http://103.42.31.90/public/static/common/js/core.js?<?php echo ($version); ?>"></script>
	<script type="text/javascript" src="http://103.42.31.90/public/static/common/js/common.js?<?php echo ($version); ?>"></script>
	<script type="text/javascript" src="http://103.42.31.90/public/static/common/js/ui.core.js?<?php echo ($version); ?>"></script>
	<!--[if lte IE 8]>
	<script src="http://103.42.31.90/public/static/common/js/html5shiv.js"></script>
	<script src="http://103.42.31.90/public/static/common/js/respond.js"></script>
	<![endif]-->
	<script type="text/javascript" src="http://103.42.31.90/public/Admin/js/adminCommon.js?<?php echo ($version); ?>"></script>
	<script type="text/javascript" src="http://103.42.31.90/public/Admin/js/adminModule.js?<?php echo ($version); ?>"></script>
	<script type="text/javascript" charset="utf-8" src="http://103.42.31.90/public/static/common/js/ueditor/ueditor.config.js?<?php echo ($version); ?>"></script>
	<script type="text/javascript" charset="utf-8" src="http://103.42.31.90/public/static/common/js/ueditor/ueditor.all.min.js?<?php echo ($version); ?>"></script>
	<script type="text/javascript" charset="utf-8" src="http://103.42.31.90/public/static/common/js/ueditorCommon.js?<?php echo ($version); ?>"></script>
</head>

<body class="no-skin">
<div id="navbar" class="navbar navbar-default ">
	<div class="navbar-container container" id="navbar-container">
		<!-- #section:basics/sidebar.mobile.toggle -->
		<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
			<span class="sr-only">Toggle sidebar</span>

			<span class="icon-bar"></span>

			<span class="icon-bar"></span>

			<span class="icon-bar"></span>
		</button>

		<!-- /section:basics/sidebar.mobile.toggle -->
		<div class="navbar-header pull-left">
			<!-- #section:basics/navbar.layout.brand -->
			<a href="#" class="navbar-brand">
				<small>
					<i class="fa fa-leaf"></i>
					欢迎来到CMS后台管理系统
				</small>
			</a>
		</div>

		<!-- #section:basics/navbar.dropdown -->
		<div class="navbar-buttons navbar-header pull-right" role="navigation">
			<p style="margin-top:7px;">
				<button id="backIndex" class="btn btn-xs btn-success">
					<i class="ace-icon fa fa-check"></i>
					网站首页
				</button>
				<button id="webSetting" class="btn btn-xs btn-info">
					网站信息设置
					<i class="ace-icon fa fa-cog icon-on-right"></i>
				</button>
				<button id="cleanCache" class="btn btn-xs btn-warning">
					<i class="ace-icon fa fa-pencil bigger-110"></i>
					清除缓存
				</button>
				<button id="loginOut" class="btn btn-xs btn-danger">
					退出系统
					<i class="ace-icon fa fa-arrow-right icon-on-right"></i>
				</button>
			</p>
		</div>

		<!-- /section:basics/navbar.dropdown -->
	</div>
	<!-- /.navbar-container -->
</div>
<div class="main-container container" id="main-container">
	<!-- #section:basics/sidebar -->
<div id="sidebar" class="sidebar responsive" data-sidebar="true" data-sidebar-scroll="true" data-sidebar-hover="true">
	<ul class="nav nav-list" style="top: 0px;">
		<li class="<?php echo ($indexCurrent); ?>">
			<a href="<?php echo U('Admin/Index/index');;?>">
				<i class="menu-icon fa fa-desktop"></i>
							<span class="menu-text">
								网站统计信息
							</span>
			</a>
		</li>
		<?php if($moduleList != ''): if(is_array($moduleList)): $i = 0; $__LIST__ = $moduleList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i; if($list['type'] == 'list'): ?><li <?php if($list['code'] == $current): ?>class="open active"<?php endif; ?> >
					<a href="#" class="dropdown-toggle">
						<i class="menu-icon fa <?php echo ($list['ico']); ?>"></i>
						<span class="menu-text"><?php echo ($list['title']); ?></span>
						<b class="arrow fa fa-angle-down"></b>
					</a>

					<b class="arrow"></b>
					<ul class="submenu">
						<li <?php if($activeLi == $list['code'].'List'): ?>class="active"<?php endif; ?>>
						<?php if($list['code'] == 'cate'): ?><a href="<?php echo ($site_url_admin); ?>cateList?moduleId=<?php echo ($list['id']); ?>&moduleCode=<?php echo ($list['code']); ?>">
								<i class="menu-icon fa fa-caret-right"></i>分类列表
							</a>
							<?php elseif($list['code'] == 'nav'): ?>
							<a href="<?php echo ($site_url_admin); ?>navList?moduleId=<?php echo ($list['id']); ?>&moduleCode=<?php echo ($list['code']); ?>">
								<i class="menu-icon fa fa-caret-right"></i>导航列表
							</a>
							<?php elseif($list['code'] == 'other'): ?>
							<a href="<?php echo ($site_url_admin); ?>otherList?moduleId=<?php echo ($list['id']); ?>&moduleCode=<?php echo ($list['code']); ?>">
								<i class="menu-icon fa fa-caret-right"></i>单项列表
							</a>
							<?php else: ?>
							<a href="<?php echo ($site_url_admin); ?>listPage?moduleId=<?php echo ($list['id']); ?>&moduleCode=<?php echo ($list['code']); ?>">
								<i class="menu-icon fa fa-caret-right"></i>内容列表
							</a><?php endif; ?>
						<b class="arrow"></b>
						</li>
						<li <?php if($activeLi == $list['code'].'Edit'): ?>class="active"<?php endif; ?> >
						<?php if($list['code'] == 'cate'): ?><a href="<?php echo ($site_url_admin); ?>cateEdit?moduleId=<?php echo ($list['id']); ?>&moduleCode=<?php echo ($list['code']); ?>">
								<i class="menu-icon fa fa-caret-right"></i>添加分类
							</a>
							<?php elseif($list['code'] == 'nav'): ?>
							<a href="<?php echo ($site_url_admin); ?>navEdit?moduleId=<?php echo ($list['id']); ?>&moduleCode=<?php echo ($list['code']); ?>">
								<i class="menu-icon fa fa-caret-right"></i>添加导航
							</a>
							<?php elseif($list['code'] == 'other'): ?>
							<a href="<?php echo ($site_url_admin); ?>otherEdit?moduleId=<?php echo ($list['id']); ?>&moduleCode=<?php echo ($list['code']); ?>">
								<i class="menu-icon fa fa-caret-right"></i>添加单项
							</a>
							<?php else: ?>
							<a href="<?php echo ($site_url_admin); ?>listEdit?moduleId=<?php echo ($list['id']); ?>&moduleCode=<?php echo ($list['code']); ?>">
								<i class="menu-icon fa fa-caret-right"></i>添加内容</a><?php endif; ?>
						<b class="arrow"></b>
						</li>
						<?php if($list['is_cate'] == 0): ?><li <?php if($activeLi == $list['code'].'Cate'): ?>class="active"<?php endif; ?> >
							<a href="<?php echo ($site_url_admin); ?>cateList?moduleId=<?php echo ($list['id']); ?>&moduleCode=<?php echo ($list['code']); ?>">
								<i class="menu-icon fa fa-caret-right"></i>分类管理
							</a>

							<b class="arrow"></b>
							</li><?php endif; ?>
					</ul>
					</li>
					<?php else: ?>
					<li <?php if($list['code'] == $current): ?>class="open active"<?php endif; ?> >
					<a href="<?php echo ($site_url_admin); echo ($list['code']); ?>?moduleId=<?php echo ($list['id']); ?>">
						<i class="menu-icon fa <?php echo ($list['ico']); ?>"></i>
						<span class="menu-text"><?php echo ($list['title']); ?></span>
					</a>
					<b class="arrow"></b>
					</li><?php endif; endforeach; endif; else: echo "" ;endif; endif; ?>
		<?php if($adminLimit == 0): ?><li class="<?php echo ($moduleCurrent); ?>">
				<a href="<?php echo U('Admin/Index/module');;?>">
					<i class="menu-icon fa fa-adjust"></i>
					<span class="menu-text"> 模块管理 </span>
				</a>
				<b class="arrow"></b>
			</li>
			<li class="<?php echo ($adminManageCurrent); ?>">
				<a href="<?php echo U('Admin/Index/admin');;?>">
					<i class="menu-icon fa fa-users"></i>
					<span class="menu-text"> 管理员管理 </span>
				</a>
				<b class="arrow"></b>
			</li><?php endif; ?>
	</ul>
	<!-- /.nav-list -->
</div>

<!-- /section:basics/sidebar -->
	<div class="main-content">
		<div class="main-content-inner">
			<div class="breadcrumbs" id="breadcrumbs">
				<ul class="breadcrumb">
					<li>
						<i class="ace-icon fa fa-home home-icon"></i>
						<a href="#">后台首页</a>
					</li>

					<li>
						管理员列表
					</li>
				</ul>
				<div class="nav-search" id="nav-search">
				</div>
			</div>

			<div class="page-content" style="min-height:600px;padding:10px 0px;">
				<div class="col-xs-12">
					<div class="row" style="margin:0px;">
						<table id="simple-table" class="table table-striped table-bordered table-hover">
							<thead>
							<tr>
								<th>用户名</th>
								<th>邮箱</th>
								<th>注释</th>
								<th>类型</th>
								<th>限制模块</th>
								<th>创建时间</th>
								<th>操作</th>
							</tr>
							</thead>
							<tbody>
							<?php if($adminAllList != ''): if(is_array($adminAllList)): $i = 0; $__LIST__ = $adminAllList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><tr id="admin_<?php echo ($list['id']); ?>">
										<td><?php echo ($list['username']); ?></td>
										<td><?php echo ($list['email']); ?></td>
										<td><?php echo ($list['note']); ?></td>
										<td><?php echo ($adminTypeList[$list['limit']]); ?></td>
										<td><?php echo ($list['module_id_list']); ?></td>
										<td><?php echo date('Y-m-d h:i:s', $list['ctime']);;?></td>
										<td>
											<div class="hidden-sm hidden-xs">
												<button class="btn btn-xs btn-info editAdminInfoBox" adminId="<?php echo ($list['id']); ?>" title="修改">
													<i class="ace-icon fa fa-pencil bigger-120"></i>
												</button>
												<?php if($list['limit'] != 0): ?><button class="btn btn-xs btn-danger delAdminUser" adminId="<?php echo ($list['id']); ?>" title="删除">
														<i class="ace-icon fa fa-trash-o bigger-120"></i>
													</button><?php endif; ?>
											</div>
										</td>
									</tr><?php endforeach; endif; else: echo "" ;endif; ?>
								<?php else: ?>
								<tr>
									<td colspan="7" style="width:100%;text-align: center;font-size:100%;color:#333;">暂无数据</td>
								</tr><?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(function()
	{
		var adminList = new AdminList();
		adminList.init();
	});
</script>
<div class="footer">
	<div class="footer-inner">
		<!-- #section:basics/footer -->
		<div class="footer-content">
						<span class="bigger-120">
							<span class="blue bolder">ForAcer</span> © 2015-2016 合作QQ:741623760
						</span>

			&nbsp; &nbsp;
			<!--<span class="action-buttons">
				<a href="#">
					<i class="ace-icon fa fa-twitter-square light-blue bigger-150"></i>
				</a>

				<a href="#">
					<i class="ace-icon fa fa-facebook-square text-primary bigger-150"></i>
				</a>

				<a href="#">
					<i class="ace-icon fa fa-rss-square orange bigger-150"></i>
				</a>
			</span>-->
		</div>

		<!-- /section:basics/footer -->
	</div>
</div>
<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse display">
	<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
</a>
</div>
<script>
	$(function()
	{
		var adminIndex = new AdminIndex();
		adminIndex.init();
	});
</script>
</body>
</html>