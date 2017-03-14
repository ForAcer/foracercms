function AdminIndex()
{
	this.webSettingBtn = $("#webSetting");
	this.cleanCacheBtn = $("#cleanCache");
	this.backIndexBtn = $("#backIndex");
	this.loginOutBtn = $("#loginOut");
}

AdminIndex.prototype =
{
	init:function()
	{
		var _this = this;
		_this.backIndexBtn.bind("click",function()
		{
			window.location.href = SITE_URL;
		});
		_this.webSettingBtn.bind("click",function()
		{
			return false;
			//ui.box.load(U("Admin/Index/webSetting"),"网站设置");
		});
		_this.cleanCacheFun();
		_this.loginOutFun();
	},

	loginOutFun:function()
	{
		var _this = this;
		_this.loginOutBtn.bind("click", function()
		{
			ui.confirmBox("提示消息","是否退出系统?", function(){
				$.ajax({
					url:U("Admin/Login/loginOutFun"),
					type:"post",
					dataType:"json",
					data:{},
					success:function(json)
					{
						if(json.code == 200)
						{
							ui.success(json.msg);

							function toUrl()
							{
								window.location.href = U("Admin/Login/index");
							}

							setTimeout( toUrl, 1500);
						}
						else
						{
							ui.error(json.msg);
							return false;
						}
					},
					error:function(json)
					{
						ui.error("退出失败");
						return false;
					}
				});
			});
		});
	},

	cleanCacheFun:function()
	{
		var _this = this;
		_this.cleanCacheBtn.bind("click", function()
		{
			$.ajax({
				url:U("Admin/Login/cleanCacheFun"),
				type:"post",
				dataType:"json",
				data:{},
				success:function(json)
				{
					if(json.code == 200)
					{
						ui.success(json.msg);
						return false;
					}
					else
					{
						ui.error(json.msg);
						return false;
					}
				},
				error:function(json)
				{
					ui.error("清除失败");
					return false;
				}
			});
		});
	},
}