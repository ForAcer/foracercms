function AdminLogin()
{
	this.changeCode = $("#changeCode");
	this.loginSystemBtn = $("#loginSystem");
	this.backIndexBtn = $("#backIndex");
}

AdminLogin.prototype =
{
	init:function()
	{
		var _this = this;
		_this.backIndexBtn.bind("click",function()
		{
			window.location.href = SITE_URL;
		});
		_this.changeCodeFun();
		_this.loginSystemFun();
	},

	changeCodeFun:function()
	{
		var _this = this;
		_this.changeCode.bind("click", function()
		{
			var srcValue = U('Admin/Login/createVerify');
			$(this).attr("src", srcValue);
		});
	},

	loginSystemFun:function()
	{
		var _this = this;
		_this.loginSystemBtn.bind("click", function()
		{
			if($("#username").val() == '')
			{
				$("#username").focus();
				ui.error("请输入你的用户名！");
				return false;
			}

			if($("#password").val() == '')
			{
				$("#password").focus();
				ui.error("请输入你的密码！");
				return false;
			}

			if($("#verifyCode").val() == '')
			{
				$("#verifyCode").focus();
				ui.error("请输入你的验证码！");
				return false;
			}

			$.ajax({
				url:U("Admin/Login/loginCheck"),
				type:"post",
				dataType:"json",
				data:{
					'user':$("#username").val(),
					'pass':$("#password").val(),
					'code':$("#verifyCode").val()
				},
				success:function(json)
				{
					if(json.code == 200)
					{
						ui.success("登录成功");

						function toUrl()
						{
							window.location.href = U("Admin/Index/webinfo");
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
					ui.error("登录失败");
					return false;
				}
			});
		});
	},
}