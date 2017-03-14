<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<link rel="shortcut icon" type="image/x-icon" href="http://www.funkkim.com/public/static/common/images/favicon.ico">
		<meta name="keywords" content="<?php if( $rs[ 'seo_keyword'] != '' ): echo ($rs['seo_keyword']); else: echo ($webInfo['seo_keyword']); endif; ?>">
		<meta name="description" content="<?php if( $rs[ 'seo_desc'] != '' ): echo ($rs['seo_desc']); else: echo ($webInfo['seo_desc']); endif; ?>">
		<meta name="version" content="<?php echo ($version); ?>">
		<title>
			<?php if($rs['title'] != ''): echo ($rs['title']); ?>-<?php endif; ?>
			<?php echo ($webInfo['title']); ?>
		</title>
		<link rel="stylesheet" href="http://www.funkkim.com/public/static/common/css/bootstrap.css?<?php echo ($version); ?>" />
		<link rel="stylesheet" href="http://www.funkkim.com/public/static/common/css/ace.css?<?php echo ($version); ?>" />
		<link rel="stylesheet" href="http://www.funkkim.com/public/static/common/css/font-awesome.css?<?php echo ($version); ?>" />
		<link rel="stylesheet" href="http://www.funkkim.com/public/static/common/css/global.css?<?php echo ($version); ?>" />
		<link rel="stylesheet" href="http://www.funkkim.com/public/static/common/css/form.css?<?php echo ($version); ?>" />
		<link rel="stylesheet" href="http://www.funkkim.com/public/static/common/css/common.css?<?php echo ($version); ?>" />

		<script type="text/javascript" src="http://www.funkkim.com/public/static/common/js/jquery.js?<?php echo ($version); ?>"></script>
		<script type="text/javascript" src="http://www.funkkim.com/public/static/common/js/cms_common.js?<?php echo ($version); ?>"></script>

		<!--[if lte IE 8]>
	<script src="http://www.funkkim.com/public/static/common/js/html5shiv.js"></script>
	<script src="http://www.funkkim.com/public/static/common/js/respond.js"></script>
	<![endif]-->
		<script type="text/javascript">
			var cmsCommon = new CmsCommonFun();
			cmsCommon.init();
		</script>
	</head>	

	<body style="background:#fff;">