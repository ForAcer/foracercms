<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
	<meta name="keywords" content="红艳工作室后台登录首页，ForAcer cms内容管理系统">
	<meta name="description" content="红艳工作室后台登录首页，ForAcer cms内容管理系统">
	<title>ForAcer工作室后台登录首页</title>
	<link rel="stylesheet" href="http://103.42.31.90/public/static/common/css/bootstrap.css" />
	<link rel="stylesheet" href="http://103.42.31.90/public/static/common/css/ace.css" />
	<link rel="stylesheet" href="http://103.42.31.90/public/static/common/css/font-awesome.css" />
	<link rel="stylesheet" href="http://103.42.31.90/public/static/common/css/global.css" />
	<link rel="stylesheet" href="http://103.42.31.90/public/static/common/css/form.css" />
	<script type="text/javascript">
		var SITE_URL = "http://103.42.31.90";
		var COMMON_URL = "http://103.42.31.90/public/static/common";
		var APP_STATIC_URL = 'http://103.42.31.90/public/Admin';
		// Js语言变量
	</script>
	<script type="text/javascript" src="http://103.42.31.90/public/static/common/js/jquery.js" ></script>
	<script type="text/javascript" src="http://103.42.31.90/public/static/common/js/core.js" ></script>
	<script type="text/javascript" src="http://103.42.31.90/public/static/common/js/common.js" ></script>
	<script type="text/javascript" src="http://103.42.31.90/public/static/common/js/ui.core.js" ></script>
	<script type="text/javascript" src="http://103.42.31.90/public/static/common/js/html5shiv.js" ></script>
	<script type="text/javascript" src="http://103.42.31.90/public/static/common/js/respond.js" ></script>
	<script type="text/javascript" src="http://103.42.31.90/public/Admin/js/login.js" ></script>
</head>
<body class="login-layout light-login">
<div class="main-container">
	<div class="main-content">
		<div class="row">
			<div class="col-sm-10 col-sm-offset-1">
				<div class="login-container" style="margin-top: 5%;">
					<div class="center">
						<h1>
							<i class="ace-icon fa fa-leaf green"></i>
							<span class="red">CMS</span>
							<span class="grey" id="id-text2">QQ:74162760</span>
						</h1>
						<h4 class="blue" id="id-company-text">© ForAcer工作室</h4>
					</div>

					<div class="space-6"></div>

					<div class="position-relative">
						<div id="login-box" class="login-box visible widget-box no-border">
							<div class="widget-body">
								<div class="widget-main">
									<h4 class="header blue lighter bigger">
										<i class="ace-icon fa fa-coffee green"></i>
										请输入你的登录信息登录系统！
									</h4>

									<div class="space-6"></div>

									<form>
										<fieldset>
											<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="text" id="username" class="form-control" placeholder="用户名">
															<i class="ace-icon fa fa-user"></i>
														</span>
											</label>

											<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" id="password" class="form-control" placeholder="密码">
															<i class="ace-icon fa fa-lock"></i>
														</span>
											</label>

											<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="type" style="width:50%;float:left;margin-right:10px;" id="verifyCode" class="form-control" placeholder="验证码" />
															<img src="<?php echo U('Admin/Login/createVerify');;?>" id="changeCode" title="点击换验证码"/>
														</span>
											</label>

											<div class="space"></div>

											<div class="clearfix">
												<button type="button" id="loginSystem" class="width-35 pull-left btn btn-sm btn-primary">
													<i class="ace-icon fa fa-key"></i>
													<span class="bigger-110">登录系统</span>
												</button>
												<button type="button" id="backIndex" class="width-35 pull-right btn btn-sm btn-success">
													<i class="ace-icon fa fa-reply icon-only"></i>
													<span class="bigger-110">返回首页</span>
												</button>
											</div>

											<div class="space-4"></div>
										</fieldset>
									</form>
								</div><!-- /.widget-main -->

								<div class="toolbar clearfix">
									<div>
										<a href="#" data-target="#forgot-box" class="forgot-password-link">
											<i class="ace-icon fa fa-arrow-left"></i>
											忘记密码
										</a>
									</div>

									<div>
										<a href="#" data-target="#signup-box" class="user-signup-link">
											我想注册管理员
											<i class="ace-icon fa fa-arrow-right"></i>
										</a>
									</div>
								</div>
							</div><!-- /.widget-body -->
						</div><!-- /.login-box -->
					</div>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.main-content -->
	</div>
</div>
<script>
	$(function()
	{
		var adminLogin = new AdminLogin();
		adminLogin.init();
	});
</script>
</body>
</html>