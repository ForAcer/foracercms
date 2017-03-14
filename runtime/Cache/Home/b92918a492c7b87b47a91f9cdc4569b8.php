<?php if (!defined('THINK_PATH')) exit();?>
<ul>
	<?php if(is_array($catelist)): $i = 0; $__LIST__ = $catelist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$clist): $mod = ($i % 2 );++$i;?><li><a href="<?php echo msg_url($clist);?>"><h3 <?php if($clist['id'] == $listId): ?>style="color:red;"<?php endif; ?> ><?php echo ($clist['title']); ?></h3></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
</ul>

<?php echo ($rs['title']); ?>