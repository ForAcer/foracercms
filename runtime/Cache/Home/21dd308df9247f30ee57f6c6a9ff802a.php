<?php if (!defined('THINK_PATH')) exit();?>
<ul>
	<?php if(is_array($catelist)): $i = 0; $__LIST__ = $catelist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$clist): $mod = ($i % 2 );++$i;?><li><a href="<?php echo list_url($clist);?>"><h3><?php echo ($clist['title']); ?></h3></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
</ul>

<ul>
	<?php if(is_array($rslist)): $i = 0; $__LIST__ = $rslist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><li><a href="<?php echo msg_url($list);?>"><h3><?php echo ($list['title']); ?></h3></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
</ul>

<?php echo ($pageBar); ?>