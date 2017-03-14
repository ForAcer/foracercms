<?php if (!defined('THINK_PATH')) exit();?>
<div class="page-content">
	<!-- /section:settings.box -->
	<div class="row">
		<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->

			<!-- #section:pages/error -->
			<div class="error-container">
				<div class="well">
					<h1 class="grey lighter smaller">
											<span class="blue bigger-125">
												<i class="ace-icon fa fa-random"></i>
												您好！
											</span>
						网络工作室，正在努力的上线中....<i class="ace-icon fa fa-wrench icon-animated-wrench bigger-125"></i>
					</h1>
					<div class="space"></div>
				</div>
			</div>

			<!-- /section:pages/error -->

			<!-- PAGE CONTENT ENDS -->
		</div><!-- /.col -->
	</div><!-- /.row -->
</div>
<?php $getParentNavList = M("Nav")->where(array("parent_id"=>0,"is_del"=>0))->select();$getAllNavList = M("Nav")->where(array("is_del"=>0))->select();$data = array();foreach ($getAllNavList as $v ){foreach ($getParentNavList as $key=>$value){if($value["id"] == $v["parent_id"]){$getParentNavList[$key]["sonlist"][] = array("id" => $v["id"],"module_id" => $v["module_id"],"title" => $v["title"],"link_url"=> $v["link_url"],);}}}foreach ($getParentNavList as $key=>$nav){ ?><h3><a href="<?php echo ($nav['link_url']); ?>"><p><?php echo ($nav['title']); ?></p></a></h3>
	<ul>
		<?php if(is_array($nav['sonlist'])): $i = 0; $__LIST__ = $nav['sonlist'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sonlist): $mod = ($i % 2 );++$i;?><li><a href="<?php echo ($sonlist['link_url']); ?>"><?php echo ($sonlist['title']); ?></a> </li><?php endforeach; endif; else: echo "" ;endif; ?>
	</ul><?php }?>