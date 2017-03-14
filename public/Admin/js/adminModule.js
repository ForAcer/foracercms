function ModuleList()
{
	this.editModuleBoxBtn = $(".editModuleBox");
	this.saveEditModuleBtn = $(".saveEditModule");
	this.delModuleBtn = $(".delModule");
	this.closeBoxBtn = $(".closeBox");
	this.addFieldBtn = $(".addField");
	this.saveExtFieldBtn = $("#saveExtField");
	this.delExtFieldBtn = $(".delExtField");
}

ModuleList.prototype =
{
	init:function()
	{
		var _this = this;
		_this.editModuleBoxFun();
		_this.delModuleFun();
		_this.addModuleFieldFun();
		_this.saveExtFieldInfo();
		_this.delExtFieldFun();
		_this.closeBoxFun();
	},

	editModuleBoxFun:function()
	{
		var _this = this;
		_this.editModuleBoxBtn.bind("click", function()
		{
			return false;
			var moduleId = $(this).attr("moduleId");
			ui.box.load(U("Admin/Index/editModuleBox", ['moduleId='+moduleId]), "编辑模块内容", function(){
				_this.saveEditModuleBtn();
			});
		});
	},

	addModuleFieldFun:function()
	{
		var _this = this;
		_this.addFieldBtn.bind("click", function()
		{
			var moduleId = $(this).attr("moduleId");
			ui.box.load(U("Admin/ModuleField/moduleFieldBox", ['moduleId='+moduleId]), "字段管理", function()
			{
				_this.saveExtFieldInfo();
			});
		});
	},

	saveEditModuleFun:function()
	{
		var _this = this;
	},

	saveExtFieldInfo:function()
	{
		var _this = this;
		_this.saveExtFieldBtn.die("click").live("click", function()
		{
			if($("#name").val() == '')
			{
				$("#name").focus();
				ui.error("请输入字段名称！");
				return false;
			}

			if($("#fieldCode").val() == '')
			{
				$("#fieldCode").focus();
				ui.error("请输入字段标识串！");
				return false;
			}

			if($("#fieldType").val() == '')
			{
				ui.error("请选择字段标识串！");
				return false;
			}

			$.ajax({
				url:U("Admin/ModuleField/saveExtFieldInfo"),
				dataType:"json",
				type:"post",
				data:$("#extForm").serialize(),
				success:function(json){
					if(json.code == 200)
					{
						setTimeToUrl(U("Admin/Index/module"), "添加成功！", 1500, "success");
						return false;
					}
					else
					{
						ui.error(json.msg);
						return false;
					}
				},
				error:function(json){
					ui.error(json.msg);
					return false;
				}
			});

		});
	},

	delExtFieldFun:function()
	{
		var _this = this;
		_this.delExtFieldBtn.die("click").live("click", function()
		{
			var fieldId = $(this).attr('fieldId');
			ui.confirmBox("提示消息", "是否删除该字段?", function()
			{
				$.ajax({
					url:U("Admin/ModuleField/delModuleField"),
					dataType:"json",
					type:"post",
					data:{'fieldId': fieldId},
					success:function(json){
						if(json.code == 200)
						{
							setTimeToUrl(U("Admin/Index/module"), "删除成功！", 1500, "success");
							return false;
						}
						else
						{
							ui.error(json.msg);
							return false;
						}
					},
					error:function(json){
						ui.error(json.msg);
						return false;
					}
				});
			});
		});
	},

	delModuleFun:function()
	{
		var _this = this;
	},

	closeBoxFun:function()
	{
		var _this = this;
		_this.closeBoxBtn.die("click").live("click", function()
		{
			ui.box.close();
		});
	},

}

function WebInfo()
{
	this.insertImageBtn = $("#insertImage");
	this.siteStatus = $("#siteStatus");
	this.submitWebEdit = $("#submitWebEdit");
}

WebInfo.prototype=
{
	init:function()
	{
		var _this = this;
		_this.insertImageBtn.bind('click', function()
		{
			var moduleId = $(this).attr("moduleId");
			var imageId = $(this).attr("imageId");
			var uploadType = $(this).attr("uploadType");
			ui.box.load(U("Admin/UploadResource/selectImage", ['moduleId='+moduleId, 'imageId='+imageId, 'uploadType='+uploadType]),"选择单张图片");
		});

		_this.siteStatus.bind('click', function()
		{
			if($(this).is(':checked'))
			{
				$(this).attr('value', 1);
			}
			else
			{
				$(this).attr('value', 0);
			}
		});

		_this.submitWebEdit.bind("click", function()
		{
			if($("#siteName").val() == '')
			{
				$("#siteName").focus();
				ui.success("请输入网站名称！");
			}

			if($("#siteStatus").val() == 1 && $("#siteNote").val() == '')
			{
				$("#siteNote").focus();
				ui.error("请输入关闭网站理由！");
				return false;
			}

			$.ajax({
				url:U("Admin/Index/webInfoEditFun"),
				dataType:"json",
				type:"post",
				data:$("#webInfoForm").serialize(),
				success:function(json){
					if(json.code == 200)
					{
						ui.success("修改成功！");
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
				error:function(json){
					ui.error(json.msg);
					return false;
				}
			});
		});
	},
}

function NavList()
{
	this.deleteNavBtn = $(".deleteNav");
}

NavList.prototype =
{
	init: function()
	{
		var _this = this;
		_this.deleteNavFun();
	},

	deleteNavFun:function()
	{
		var _this = this;
		_this.deleteNavBtn.bind("click", function()
		{
			var navId = $(this).attr("navId");
			ui.confirmBox("提示", "是否删除该导航？", function()
			{
				$.ajax({
					url:U("Admin/Index/delNav"),
					dataType:"json",
					type:"post",
					data:{'navId' : navId},
					success:function(json){
						if(json.code == 200)
						{
							ui.success(json.msg);
							$("#nav"+navId).fadeOut(300);
							return false;
						}
						else
						{
							ui.error(json.msg);
							return false;
						}
					},
					error:function(json){
						ui.error(json.msg);
						return false;
					},
				});
			});
		});
	}
}

function NavEdit()
{
	this.insertImageBtn = $("#insertImage");
	this.navTypeListLi = $(".navTypeList li");
	this.targetCheck = $("#targetCheck");
	this.submitNavEditBtn = $("#submitNavEdit");
}

NavEdit.prototype =
{
	init:function()
	{
		var _this = this;
		_this.insertImageBtn.bind('click', function()
		{
			var imageId = $(this).attr("imageId");
			var uploadType = $(this).attr("uploadType");
			ui.box.load(U("Admin/UploadResource/selectImage", ['moduleId='+moduleId, 'imageId='+imageId, 'uploadType='+uploadType]),"选择单张图片");
		});

		_this.targetCheck.bind("click", function()
		{
			if($(this).is(':checked'))
			{
				$(this).attr("value", "1");
			}
			else
			{
				$(this).attr("value", "0");
			}
		});

		_this.submitNavEditBtn.bind("click", function()
		{
			if($("#title").val() == '')
			{
				$("#title").focus();
				ui.error("请输入导航标题！");
				return false;
			}

			if($("#link_url").val() == '')
			{
				$("#link_url").focus();
				ui.error("请输入导航链接！");
				return false;
			}

			$.ajax({
				url:U("Admin/Index/saveNavEdit"),
				dataType:"json",
				type:"post",
				data:$("#navInfo").serialize(),
				success:function(json)
				{
					if(json.code == 200)
					{
						ui.success(json.msg);
						function toUrl()
						{
							window.location.href = U("Admin/Index/navList");
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
					ui.error(json.msg);
					return false;
				}
			});

		});

	},
}

function ResourceList()
{
	this.deleteResBtn = $(".deleteRes");
}

ResourceList.prototype =
{
	init:function()
	{
		var _this = this;
		_this.deleteResBtn.live("click", function()
		{
			var resId = $(this).attr('resId');
			ui.confirmBox("提示消息", "是否删除该资源?", function()
			{
				$.ajax({
					url:U("Admin/Index/deleteResource"),
					dataType:"json",
					type:"post",
					data:{'resourceId' : resId},
					success:function(json)
					{
						if(json.code == 200)
						{
							ui.success(json.msg);
							$("#res"+resId).fadeOut(350);
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
						ui.error(json.msg);
						return false;
					}
				});
			});
		});
	},
}

function AdminList()
{
	this.editAdminInfoBoxBtn = $(".editAdminInfoBox");
	this.delAdminUserBtn = $(".delAdminUser");
}

AdminList.prototype =
{
	init:function()
	{
		var _this = this;
		_this.editAdminInfoFun();
		_this.deleteAdminUserFun();
	},

	editAdminInfoFun:function()
	{
		var _this = this;
		_this.editAdminInfoBoxBtn.bind("click", function()
		{
			return false;
			var userId = $(this).attr('adminId');
			ui.box.load(U("Admin/Index/editAdminInfoBox", ['userId='+userId]),"修改管理员信息");
		});
	},

	deleteAdminUserFun:function()
	{
		var _this = this;
		_this.delAdminUserBtn.bind("click", function()
		{
			var userId = $(this).attr('adminId');
			ui.confirmBox("提示消息", "是否要删除该管理员?", function()
			{
				$.ajax({
					url:U("Admin/Index/deleteAdminUser"),
					dataType:"json",
					type:"post",
					data:{'userId' : userId},
					success:function(json)
					{
						if(json.code == 200)
						{
							ui.success(json.msg);
							$("#admin_"+userId).fadeOut(350);
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
						ui.error(json.msg);
						return false;
					}
				});
			});
		});
	},
}

function CateList()
{
	this.moduleId = $("#moduleId");
	this.deleteCateClick = $(".deleteCate");
}

CateList.prototype =
{
	init:function()
	{
		var _this = this;
		_this.deleteCateFun();
	},

	deleteCateFun:function()
	{
		var _this = this;
		_this.deleteCateClick.bind('click', function()
		{
			var cateId = $(this).attr('cateId');
			var moduleId = $(this).attr('moduleId');
			ui.confirmBox("提示消息", "是否要删除此分类?", function()
			{
				$.ajax({
					url:U("Admin/Cate/deleteCate"),
					dataType:"json",
					type:"post",
					data:{'cateId': cateId, 'moduleId': moduleId },
					success:function(json)
					{
						if(json.code == 200)
						{
							ui.success(json.msg);
							$("#cate_"+ cateId).fadeOut(350);
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
						ui.error(json.msg);
						return false;
					}
				});
			});
		});
	},
}

function CateEdit()
{
	this.insertIco = $("#insertIco");
	this.insertSmall = $("#insertSmall");
	this.insertBig = $("#insertBig");
	this.submitCateEdit = $("#submitCateEdit");
}

CateEdit.prototype =
{
	init:function()
	{
		var _this = this;
		_this.insertIco.bind('click', function()
		{
			var imageId = $(this).attr("imageId");
			var uploadType = $(this).attr("uploadType");
			ui.box.load(U("Admin/UploadResource/selectImage", ['moduleId='+moduleId, 'imageId='+imageId, 'uploadType='+uploadType]),"选择单张图片");
		});
		_this.insertSmall.bind('click', function()
		{
			var imageId = $(this).attr("imageId");
			var uploadType = $(this).attr("uploadType");
			ui.box.load(U("Admin/UploadResource/selectImage", ['moduleId='+moduleId, 'imageId='+imageId, 'uploadType='+uploadType]),"选择单张图片");
		});
		_this.insertBig.bind('click', function()
		{
			var imageId = $(this).attr("imageId");
			var uploadType = $(this).attr("uploadType");
			ui.box.load(U("Admin/UploadResource/selectImage", ['moduleId='+moduleId, 'imageId='+imageId, 'uploadType='+uploadType]),"选择单张图片");
		});
		_this.submitCateEditFun();
	},

	submitCateEditFun:function()
	{
		var _this = this;
		_this.submitCateEdit.bind("click", function()
		{
			var moduleId = $("#moduleId").val();
			if($("#title").val() == '')
			{
				ui.error("请输入分类名称");
				return false;
			}

			if($("#code").val() == '')
			{
				ui.error("请输入分类标识串");
				return false;
			}

			$.ajax({
				url:U("Admin/Cate/saveCateInfo"),
				dataType:"json",
				type:"post",
				data: $("#cateInfo").serialize(),
				success:function(json)
				{
					if(json.code == 200)
					{
						setTimeToUrl(U("Admin/Index/cateList", ['moduleId=10']), json.msg, 1500, 'success')
					}
					else
					{
						ui.error(json.msg);
						return false;
					}
				},
				error:function(json)
				{
					ui.error(json.msg);
					return false;
				}
			});
		});
	},
}

function OtherEdit()
{
	this.submitOtherEditBtn = $("#submitOtherEdit");
	this.insertPicUrlBtn = $("#insertPicUrl");
	this.moduleId = $("#moduleId");
}

OtherEdit.prototype =
{
	init:function()
	{
		var _this = this;
		_this.insertPicUrlBtn.bind("click", function()
		{
			var imageId = $(this).attr("imageId");
			var uploadType = $(this).attr("uploadType");
			ui.box.load(U("Admin/UploadResource/selectImage", ['moduleId='+_this.moduleId, 'imageId='+imageId, 'uploadType='+uploadType]),"选择单张图片");
		});

		_this.submitOtherFun();
	},

	submitOtherFun:function()
	{
		var _this = this;
		_this.submitOtherEditBtn.bind("click", function()
		{
			var moduleId = $("#moduleId").val();
			if($("#title").val() == '')
			{
				$("#title").focus();
				ui.error("请输入名称");
				return false;
			}

			if($("#code").val() == '')
			{
				$("#code").focus();
				ui.error("请输入标识串");
				return false;
			}

			$.ajax({
				url:U("Admin/Other/saveOtherInfo"),
				dataType:"json",
				type:"post",
				data: $("#otherInfo").serialize(),
				success:function(json)
				{
					if(json.code == 200)
					{
						setTimeToUrl(U("Admin/Index/OtherList", ['moduleId=9']), json.msg, 1500, 'success')
					}
					else
					{
						ui.error(json.msg);
						return false;
					}
				},
				error:function(json)
				{
					ui.error(json.msg);
					return false;
				}
			});
		});
	}
}

function ListEdit()
{
	this.listThreeAttrLi = $("#listThreeAttr li label input");
	this.insertImageBtn = $("#insertImage");
	this.submitListEditBtn = $("#submitListEdit");
	this.moduleId = $("#moduleId").val();
	this.moduleCode = $("#moduleCode").val();
}

ListEdit.prototype =
{
	init:function()
	{
		var _this = this;

		_this.listThreeAttrLi.bind("click", function()
		{
			if($(this).is(":checked"))
			{
				$(this).attr('value', 1);
			}
			else
			{
				$(this).attr('value', 0);
			}
		});

		_this.insertImageBtn.bind("click", function()
		{
			var moduleId = $(this).attr("moduleId");
			var imageId = $(this).attr("imageId");
			var uploadType = $(this).attr("uploadType");
			ui.box.load(U("Admin/UploadResource/selectImage", ['moduleId='+moduleId, 'imageId='+imageId, 'uploadType='+uploadType]),"选择单张图片");
		});

		_this.submitListEditBtn.bind("click", function()
		{
			if($("#title").val() == '')
			{
				$("#title").focus();
				ui.error("请输入标题");
				return false;
			}

			$.ajax({
				url:U("Admin/List/saveListInfo"),
				dataType:"json",
				type:"post",
				data: $("#listInfo").serialize(),
				success:function(json)
				{
					if(json.code == 200)
					{
						setTimeToUrl(U("Admin/Index/listPage", ['moduleId='+_this.moduleId, 'moduleCode='+_this.moduleCode]), json.msg, 1500, 'success');
					}
					else
					{
						ui.error(json.msg);
						return false;
					}
				},
				error:function(json)
				{
					ui.error(json.msg);
					return false;
				}
			});

		});
	},
}

function ListPage()
{
	this.moduleId = $("#moduleId").val();
	this.moduleCode = $("#moduleCode").val();
	this.deleteListBtn = $(".deleteList");
	this.checkAllBtn = $("#checkAll");
	this.submitlistSet = $("#submitlistSet");
}

ListPage.prototype =
{
	init:function()
	{
		var _this = this;

		$("input[name='is_top']").die("change").live("change", function()
		{
			if($(this).is(":checked"))
			{
				$(this).attr('value', 1);
			}
			else
			{
				$(this).attr('value', 0);
			}

			var listId = $(this).attr("listId");
			var isValue = $(this).attr("value");
			$.ajax({
				url:U("Admin/List/changeListAttrFun"),
				dataType:"json",
				type:"post",
				data: {
					'listId': listId,
					'type': 'is_top',
					'isValue': isValue
				},
				success:function(json)
				{
					if(json.code == 200)
					{
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
					ui.error(json.msg);
					return false;
				}
			});
		});

		$("input[name='is_vouch']").die("change").live("change", function()
		{
			if($(this).is(":checked"))
			{
				$(this).attr('value', 1);
			}
			else
			{
				$(this).attr('value', 0);
			}

			var listId = $(this).attr("listId");
			var isValue = $(this).attr("value");
			$.ajax({
				url:U("Admin/List/changeListAttrFun"),
				dataType:"json",
				type:"post",
				data: {
					'listId': listId,
					'type': 'is_vouch',
					'isValue': isValue
				},
				success:function(json)
				{
					if(json.code == 200)
					{
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
					ui.error(json.msg);
					return false;
				}
			});
		});

		$("input[name='is_best']").die("change").live("change", function()
		{
			if($(this).is(":checked"))
			{
				$(this).attr('value', 1);
			}
			else
			{
				$(this).attr('value', 0);
			}

			var listId = $(this).attr("listId");
			var isValue = $(this).attr("value");
			$.ajax({
				url:U("Admin/List/changeListAttrFun"),
				dataType:"json",
				type:"post",
				data: {
					'listId': listId,
					'type': 'is_best',
					'isValue': isValue
				},
				success:function(json)
				{
					if(json.code == 200)
					{
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
					ui.error(json.msg);
					return false;
				}
			});
		});

		_this.deleteListBtn.die("click").live("click",function()
		{
			var listId = $(this).attr("listId");
			ui.confirmBox("提示消息", "是否删除该主题?", function(){
				$.ajax({
					url:U("Admin/List/deleteList"),
					dataType:"json",
					type:"post",
					data: {
						'listIds': listId,
					},
					success:function(json)
					{
						if(json.code == 200)
						{
							ui.success(json.msg);
							$("#List_"+listId).remove();
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
						ui.error(json.msg);
						return false;
					}
				});
			});
		});

		_this.checkAllBtn.die("change").live("change", function()
		{
			if($(this).is(":checked"))
			{
				$("input[name='listId']").attr("checked", true);
			}
			else
			{
				$("input[name='listId']").attr("checked", false);
			}
		});

		_this.submitlistSet.die("click").live("click", function()
		{
			var checkLength = $("input[name='listId']:checked").length;
			if(checkLength <= 0)
			{
				return false;
			}

			var setType = $("#selectListSetType").val();
			if(setType == '' || setType == undefined)
			{
				$("#selectListSetType").focus();
				return false;
			}

			if(setType == 'deleteMore')
			{
				var listStr = $("input[name='listId']:checked").map(function(index, elem)
				{
					return $(elem).val();
				}).get().join(',');

				$.ajax({
					url:U("Admin/List/deleteList"),
					dataType:"json",
					type:"post",
					data: {
						'listIds': listStr
					},
					success:function(json)
					{
						if(json.code == 200)
						{
							setTimeToUrl(U("Admin/Index/listPage", ['moduleId='+_this.moduleId, 'moduleCode='+_this.moduleCode]), json.msg, 1500, 'success')
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
						ui.error(json.msg);
						return false;
					}
				});
				return false;
			}

			if(setType == 'copyFive')
			{
				if(checkLength > 1)
				{
					ui.error("只能选择一个主题来复制！");
					return false;
				}

				var listId = $("input[name='listId']:checked").val();
				$.ajax({
					url:U("Admin/List/copyContentFun"),
					dataType:"json",
					type:"post",
					data: {
						'listId': listId,
						'number': 5
					},
					success:function(json)
					{
						if(json.code == 200)
						{
							setTimeToUrl(U("Admin/Index/listPage", ['moduleId='+_this.moduleId, 'moduleCode='+_this.moduleCode]), json.msg, 1500, 'success')
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
						ui.error(json.msg);
						return false;
					}
				});
				return false;
			}

			if(setType == 'copyTen')
			{
				if(checkLength > 1)
				{
					ui.error("只能选择一个主题来复制！");
					return false;
				}

				var listId = $("input[name='listId']:checked").val();
				$.ajax({
					url:U("Admin/List/copyContentFun"),
					dataType:"json",
					type:"post",
					data: {
						'listId': listId,
						'number': 10
					},
					success:function(json)
					{
						if(json.code == 200)
						{
							setTimeToUrl(U("Admin/Index/listPage", ['moduleId='+_this.moduleId, 'moduleCode='+_this.moduleCode]), json.msg, 1500, 'success')
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
						ui.error(json.msg);
						return false;
					}
				});
				return false;
			}

		});
	},
}