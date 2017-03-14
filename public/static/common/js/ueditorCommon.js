function UeditorCommon()
{

}

UeditorCommon.prototype =
{
	//获取ueditor 编辑器少量配置按钮
	loadUeditorSmall:function(textId, frameWidth, frameHeight)
	{
		var ueSmall= UE.getEditor(textId,
			{
				initialFrameWidth:frameWidth,
				initialFrameHeight:frameHeight,
				autoFloatEnabled:false,
				toolbars: [
					['fullscreen', 'source', 'undo', 'redo', 'bold'],
					['bold', 'italic', 'underline', 'insertimage','fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc']
				],
			});
	},

	//获取ueditor 编辑器大量配置按钮
	loadUeditorBig:function(textId, frameWidth, frameHeight)
	{
		var ueBig= UE.getEditor(textId,
			{
				initialFrameWidth:frameWidth,
				initialFrameHeight:frameHeight,
				autoFloatEnabled:false,
			});
	},

}