/**
 * 核心Js函数库文件，目前已经在core中自动加载
 */


/**
 * 字符串长度 - 中文和全角符号为1；英文、数字和半角为0.5
 */
var getLength = function(str, shortUrl) {
	if (true == shortUrl) {
		// 一个URL当作十个字长度计算
		return Math.ceil(str.replace(/((news|telnet|nttp|file|http|ftp|https):\/\/){1}(([-A-Za-z0-9]+(\.[-A-Za-z0-9]+)*(\.[-A-Za-z]{2,5}))|([0-9]{1,3}(\.[0-9]{1,3}){3}))(:[0-9]*)?(\/[-A-Za-z0-9_\$\.\+\!\*\(\),;:@&=\?\/~\#\%]*)*/ig, 'xxxxxxxxxxxxxxxxxxxx')
				.replace(/^\s+|\s+$/ig,'').replace(/[^\x00-\xff]/ig,'xx').length/2);
	} else {
		return Math.ceil(str.replace(/^\s+|\s+$/ig,'').replace(/[^\x00-\xff]/ig,'xx').length/2);
	}
};

/**
 * 从url中提取参数名和参数值
 */
var getValue = function(url, name) {
	var reg = new RegExp('(\\?|&)' + name + '=([^&?]*)', 'i');
	var arr = url.match(reg);
	if (arr) {
		return arr[2];
	}
	return null;
}

/**
 * 截取字符串
 */
var subStr = function(str, len) {
	if(!str) {
		return '';
	}
	len = len > 0 ? len * 2 : 280;
	var count = 0;			// 计数：中文2字节，英文1字节
	var temp = '';  		// 临时字符串
	for(var i = 0; i < str.length; i ++) {
		if(str.charCodeAt(i) > 255) {
			count += 2;
		} else {
			count ++;
		}

		// 如果增加计数后长度大于限定长度，就直接返回临时字符串
		if(count > len) {
			return temp;
		}

		// 将当前内容加到临时字符串
		temp += str.charAt(i);
	}

	return str;
};

/**
 * 异步请求页面
 */
var async_page = function(url, target, callback) {
	if(!url) {
		return false;
	} else if(target) {
		var $target = $(target);
		//$target.html('<img src="'+_THEME_+'/image/icon_waiting.gif" width="20" style="margin:10px 50%;" />');
	}
	$.post(url, {}, function(txt) {
		txt = eval("(" + txt + ")");
		if(txt.status) {
			if(target) {
				$target.html(txt.data);
			}
			if(callback) {
				if(callback.match(/[(][^()]*[)]/)) {
					eval(callback);
				} else {
					eval(callback)(txt);
				}
			}
			if(txt.info) {
				ui.success(txt.info);
			}
		} else if(txt.info) {
			ui.error(txt.info);
			return false;
		}
	});

	return true;
};

/**
 * 异步加载翻页
 */
var async_turn_page = function(page_number, target) {
	$(page_number).click(function(o) {
		var $a = $(o.target);
		var url = $a.attr("href");
		if(url) {
			async_page(url, target);
		}
		return false;
	});
};

// 表单异步处理
/* 生效条件：包含 jquery.form.js */
//TODO 优化jquery.form.js的加载机制
var async_form = function(form) {
	var $form = form ? $(form) : $("form[ajax='ajax']");

	// 监听 form 表单提交
	$form.bind('submit', function() {
		var callback = $(this).attr('callback');
		var options = {
			success: function(txt) {
				txt = eval("("+txt+")");
				if(callback){
					if (callback.match(/[(][^()]*[)]/)) {
						eval(callback);
					} else {
						eval(callback)(txt);
					}
				}else{
					if(txt.status && txt.info){
						ui.success( txt.info );
					}else if (txt.info) {
						ui.error( txt.info );
					}
				}
			}
		};
		$(this).ajaxSubmit(options);
		return false;
	});
};

/**
 * 复制剪贴板
 */
var copy_clip = function (copy){
	if (window.clipboardData)
	{
		window.clipboardData.setData("Text", copy);
	}
	else if (window.netscape)
	{
		try
		{
			netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
		}
		catch (e)
		{
			alert(L('PUBLIC_EXPLORER_ISCTRL'));
			return false;
		}
		var clip = Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);
		if (!clip)
		{
			return false;
		}
		var trans = Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);
		if (!trans)
		{
			return false;
		}
		trans.addDataFlavor('text/unicode');
		var str = new Object();
		var len = new Object();
		var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);
		var copytext = copy;
		str.data = copytext;
		trans.setTransferData("text/unicode", str, copytext.length * 2);
		var clipid = Components.interfaces.nsIClipboard;
		if (!clip)
		{
			return false;
		}
		clip.setData(trans, null, clipid.kGlobalClipboard);
	}
	ui.success(L('PUBLIC_EXPLORER_CTRL'));
	return true;
};

/**
 * 是否含有某个样式
 */
function hasClass(ele,cls) {
	return $(ele).hasClass(cls);
}

/**
 * 添加某个样式
 */
function addClass(ele,cls) {
	$(ele).addClass(cls);
}

/**
 * 移除某个样式
 */
function removeClass(ele,cls) {
	$(ele).removeClass(cls);
}

var toElement = function(){
	var div = document.createElement('div');
	return function(html){
		div.innerHTML = html;
		var el = div.childNodes[0];
		div.removeChild(el);
		return el;
	}
}();

/**
 *	与php的implode方法用法一样
 *	@from php.js
 */
var implode  = function (glue, pieces) {
	var i = '', retVal = '', tGlue = '';
	if (arguments.length === 1) {
		pieces = glue;
		glue = '';
	}
	if (typeof(pieces) === 'object') {
		if (Object.prototype.toString.call(pieces) === '[object Array]') {
			return pieces.join(glue);
		}
		for (i in pieces) {
			retVal += tGlue + pieces[i];
			tGlue = glue;
		}
		return retVal;
	}
	return pieces;
};

/**
 * 与php的explode用法一致
 * @from php.js
 */
var explode = function(delimiter, string, limit){
	var emptyArray = {0:''};

	if (arguments.length < 2 || typeof arguments[0] == 'undefined' || typeof arguments[1] == 'undefined') {
		return null;
	}

	if (delimiter === '' || delimiter === false || delimiter === null) {
		return false;
	}

	if (typeof delimiter == 'function' || typeof delimiter == 'object' || typeof string == 'function' || typeof string == 'object') {
		return emptyArray;
	}
	if (delimiter === true) {
		delimiter = '1';
	}
	if (!limit) {
		return string.toString().split(delimiter.toString());
	}

	// support for limit argument
	var splitted = string.toString().split(delimiter.toString());
	var partA = splitted.splice(0, limit - 1);
	var partB = splitted.join(delimiter.toString());
	partA.push(partB);
	return partA;
};

/**
 *	与php的strlen方法用法一样
 *	@from php.js
 */
var strlen = function (string) {
	var str = string + '';
	var i = 0,
		chr = '',
		lgth = 0;

	if (!this.php_js || !this.php_js.ini || !this.php_js.ini['unicode.semantics'] || this.php_js.ini['unicode.semantics'].local_value.toLowerCase() !== 'on') {
		return string.length;
	}

	var getWholeChar = function (str, i) {
		var code = str.charCodeAt(i);
		var next = '',			prev = '';
		if (0xD800 <= code && code <= 0xDBFF) { // High surrogate (could change last hex to 0xDB7F to treat high private surrogates as single characters)
			if (str.length <= (i + 1)) {
				throw 'High surrogate without following low surrogate';
			}			next = str.charCodeAt(i + 1);
			if (0xDC00 > next || next > 0xDFFF) {
				throw 'High surrogate without following low surrogate';
			}
			return str.charAt(i) + str.charAt(i + 1);		} else if (0xDC00 <= code && code <= 0xDFFF) { // Low surrogate
			if (i === 0) {
				throw 'Low surrogate without preceding high surrogate';
			}
			prev = str.charCodeAt(i - 1);			if (0xD800 > prev || prev > 0xDBFF) { //(could change last hex to 0xDB7F to treat high private surrogates as single characters)
				throw 'Low surrogate without preceding high surrogate';
			}
			return false; // We can pass over low surrogates now as the second component in a pair which we have already processed
		}		return str.charAt(i);
	};

	for (i = 0, lgth = 0; i < str.length; i++) {
		if ((chr = getWholeChar(str, i)) === false) {			continue;
		} // Adapt this line at the top of any loop, passing in the whole string and the current iteration and returning a variable to represent the individual character; purpose is to treat the first part of a surrogate pair as the whole character and then ignore the second part
		lgth++;
	}
	return lgth;
};

/**
 * 与PHP的substr一样的用法、
 * @from php.js
 */
var substr = function(str, start, len) {
	var i = 0,
		allBMP = true,
		es = 0,		el = 0,
		se = 0,
		ret = '';
	str += '';
	var end = str.length;
	// BEGIN REDUNDANT
	this.php_js = this.php_js || {};
	this.php_js.ini = this.php_js.ini || {};
	// END REDUNDANT
	switch ((this.php_js.ini['unicode.semantics'] && this.php_js.ini['unicode.semantics'].local_value.toLowerCase())) {
		case 'on':
			// Full-blown Unicode including non-Basic-Multilingual-Plane characters
			// strlen()
			for (i = 0; i < str.length; i++) {			if (/[\uD800-\uDBFF]/.test(str.charAt(i)) && /[\uDC00-\uDFFF]/.test(str.charAt(i + 1))) {
				allBMP = false;
				break;
			}
			}
			if (!allBMP) {
				if (start < 0) {
					for (i = end - 1, es = (start += end); i >= es; i--) {
						if (/[\uDC00-\uDFFF]/.test(str.charAt(i)) && /[\uD800-\uDBFF]/.test(str.charAt(i - 1))) {						start--;
							es--;
						}
					}
				} else {				var surrogatePairs = /[\uD800-\uDBFF][\uDC00-\uDFFF]/g;
					while ((surrogatePairs.exec(str)) != null) {
						var li = surrogatePairs.lastIndex;
						if (li - 2 < start) {
							start++;					} else {
							break;
						}
					}
				}
				if (start >= end || start < 0) {
					return false;
				}
				if (len < 0) {				for (i = end - 1, el = (end += len); i >= el; i--) {
					if (/[\uDC00-\uDFFF]/.test(str.charAt(i)) && /[\uD800-\uDBFF]/.test(str.charAt(i - 1))) {
						end--;
						el--;
					}				}
					if (start > end) {
						return false;
					}
					return str.slice(start, end);			} else {
					se = start + len;
					for (i = start; i < se; i++) {
						ret += str.charAt(i);
						if (/[\uD800-\uDBFF]/.test(str.charAt(i)) && /[\uDC00-\uDFFF]/.test(str.charAt(i + 1))) {						se++; // Go one further, since one of the "characters" is part of a surrogate pair
						}
					}
					return ret;
				}			break;
			}
		// Fall-through
		case 'off':
		// assumes there are no non-BMP characters;		//	if there may be such characters, then it is best to turn it on (critical in true XHTML/XML)
		default:
			if (start < 0) {
				start += end;
			}		end = typeof len === 'undefined' ? end : (len < 0 ? len + end : len + start);
	}
	return undefined; // Please Netbeans
};

var trim = function(str,charlist){
	return str;
};

/**
 * 与php的rtrim函数用法一致
 * @from php.js
 */
var rtrim = function(str, charlist) {
	return str;
};

/**
 * 与PHP的ltrim用法一致
 * @from php.js
 */
var ltrim = function(str, charlist) {
	return str;
};

/**
 * 闪动对象背景
 * @param obj
 * @author yangjs
 */
var flashTextarea = function(obj){
	var nums = 0;
	var flash = function(){
		if(nums > 3 ){
			return false;
		}
		if(hasClass(obj,'fb')){
			removeClass(obj,'fb');
		}else{
			addClass(obj,'fb')
		}
		setTimeout(flash, 300);
		nums ++;
	}
	flash();
	return false;
};

/**
 * 更新页面上监听的用户统计数目
 * @example
 * updateUserData('favorite_count', 1); 表示当前用户的收藏数+1
 * 页面结构例子:<strong event-node ="favorite_count" event-args ="uid={$uid}">{$_userData.favorite_count|default=0}</strong>
 * @param string key 监听的Key值
 * @param integer flag 改变的幅度值
 * @param integer uid 改变的用户ID
 * @return boolean false
 */
var updateUserData = function(key, flag, uid)
{
	// 获取所有Key监听的对象
	var countObj = M.nodes.events[key];
	// 判断数据类型
	if("undefined" === typeof countObj) {
		return false;
	}
	if("undefined" === typeof uid) {
		uid = UID;
	}
	// 修改数值
	for(var i in countObj) {
		var _wC = countObj[i];
		var args = M.getEventArgs(_wC);
		if(args.uid == uid) {
			_wC.innerHTML = parseInt(_wC.innerHTML, 10) + parseInt(flag, 10);
		}
	}

	return false;
};

/**
 * 滚动到顶端
 */
var scrolltotop={
	//startline: 鼠标向下滚动了100px后出现#topcontrol
	//scrollto: 它的值可以是整数，也可以是一个id标记。若为整数（假设为n），则滑动到距离top的n像素处；若为id标记，则滑动到该id标记所在的同等高处
	//scrollduration:滑动的速度
	//fadeduration:#topcontrol这个div的淡入淡出速度，第一个参数为淡入速度，第二个参数为淡出速度
	//controlHTML:控制向上滑动的html源码，默认为<img src="up.png" style="width:48px; height:48px" />，可以自行更改。该处的html代码会被包含在一个id标记为#topcontrol的div中。
	//controlattrs:控制#topcontrol这个div距离右下角的像素距离
	//anchorkeyword:滑动到的id标签
	/*state: isvisible:是否#topcontrol这个div为可见
	 shouldvisible:是否#topcontrol这个div该出现
	 */

	setting: {startline:100, scrollto: 0, scrollduration:500, fadeduration:[500, 100]},
	controlHTML: '<a href="#top" class="top_stick">&nbsp;</a>',
	controlattrs: {offsetx:20, offsety:30},
	anchorkeyword: '#top',

	state: {isvisible:false, shouldvisible:false},

	scrollup:function() {
		// 模拟点击“回到顶部”
		window.scrollTo('0','0');
		return false;

		// 后面的就不要了，scrolltotop.init()也被注释掉不执行了，简单点来。。。by blog.snsgou.com
		if (!this.cssfixedsupport)
		{
			this.$control.css({opacity:0}) //点击后隐藏#topcontrol这个div
		};

		var dest=isNaN(this.setting.scrollto)? this.setting.scrollto : parseInt(this.setting.scrollto);
		if (typeof dest=="string" && jQuery('#'+dest).length==1) { // 检查若scrollto的值是一个id标记的话
			dest=jQuery('#'+dest).offset().top;
		} else { // 检查若scrollto的值是一个整数
			dest=this.setting.scrollto;
		};
		this.$body.animate({scrollTop: dest}, this.setting.scrollduration);
	},

	keepfixed:function(){
		// 获得浏览器的窗口对象
		var $window=jQuery(window);

		// 获得#topcontrol这个div的x轴坐标
		var controlx=$window.scrollLeft() + $window.width() - this.$control.width() - this.controlattrs.offsetx;

		// 获得#topcontrol这个div的y轴坐标
		var controly=$window.scrollTop() + $window.height() - this.$control.height() - this.controlattrs.offsety;

		// 随着滑动块的滑动#topcontrol这个div跟随着滑动
		this.$control.css({left:controlx+'px', top:controly+'px'});
	},

	togglecontrol:function(){
		// 当前窗口的滑动块的高度
		var scrolltop=jQuery(window).scrollTop();
		if (!this.cssfixedsupport) {
			this.keepfixed();
		};

		// 若设置了startline这个参数，则shouldvisible为true
		this.state.shouldvisible=(scrolltop>=this.setting.startline)? true : false;

		// 若shouldvisible为true，且!isvisible为true
		if (this.state.shouldvisible && !this.state.isvisible){
			this.$control.stop().animate({opacity:1}, this.setting.fadeduration[0]);
			this.state.isvisible=true;
		} // 若shouldvisible为false，且isvisible为false
		else if (this.state.shouldvisible==false && this.state.isvisible){
			this.$control.stop().animate({opacity:0}, this.setting.fadeduration[1]);
			this.state.isvisible=false;
		}
	},

	init:function(){
		jQuery(document).ready(function($){
			var mainobj=scrolltotop;
			var iebrws=document.all;
			mainobj.cssfixedsupport=!iebrws || iebrws && document.compatMode=="CSS1Compat" && window.XMLHttpRequest; //not IE or IE7+ browsers in standards mode
			mainobj.$body=(window.opera)? (document.compatMode=="CSS1Compat"? $('html') : $('body')) : $('html,body');

			// 包含#topcontrol这个div
			mainobj.$control=$('<div id="topcontrol">'+mainobj.controlHTML+'</div>')
				.css({position:mainobj.cssfixedsupport? 'fixed' : 'absolute', bottom:mainobj.controlattrs.offsety, right:mainobj.controlattrs.offsetx, opacity:0, cursor:'pointer'})
				.attr({title:L('PUBLIC_MOVE_TOP')})
				.click(function(){mainobj.scrollup(); return false;})
				.appendTo('body');

			if (document.all && !window.XMLHttpRequest && mainobj.$control.text()!='') {//loose check for IE6 and below, plus whether control contains any text
				mainobj.$control.css({width:mainobj.$control.width()}); //IE6- seems to require an explicit width on a DIV containing text
			};

			mainobj.togglecontrol();

			// 点击控制
			$('a[href="' + mainobj.anchorkeyword +'"]').click(function(){
				mainobj.scrollup();
				return false;
			});

			$(window).bind('scroll resize', function(e){
				mainobj.togglecontrol();
			});
		});
	}
};
//scrolltotop.init();

// JavaScript双语方法
function L(key, obj) {
	if('undefined' == typeof(LANG[key])) {
		return key;
	}
	if('object' != typeof(obj)) {
		return LANG[key];
	} else {
		var r = LANG[key];
		for(var i in obj) {
			r = r.replace("{"+i+"}", obj[i]);
		}
		return r;
	}
};

/**
 * 数组去重
 * @param array arr 去重数组
 * @return array 已去重的数组
 */
var unique = function(arr) {
	var obj = {};
	for(var i = 0, j = arr.length; i < j; i++) {
		obj[arr[i]] = true;
	}
	var data = [];
	for(var i in obj) {
		data.push[i];
	}

	return data;
};

var shortcut = function (shortcut,callback,opt) {
	// Provide a set of default options
	var default_options = {
		'type':'keydown',
		'propagate':false,
		'target':document
	}
	if(!opt) opt = default_options;
	else {
		for(var dfo in default_options) {
			if(typeof opt[dfo] == 'undefined') opt[dfo] = default_options[dfo];
		}
	}

	var ele = opt.target
	if(typeof opt.target == 'string') ele = document.getElementById(opt.target);
	var ths = this;

	// The function to be called at keypress
	var func = function(e) {
		e = e || window.event;

		// Find Which key is pressed
		if (e.keyCode) code = e.keyCode;
		else if (e.which) code = e.which;
		var character = String.fromCharCode(code).toLowerCase();

		var keys = shortcut.toLowerCase().split("+");
		// Key Pressed - counts the number of valid keypresses - if it is same as the number of keys, the shortcut function is invoked
		var kp = 0;

		// Work around for stupid Shift key bug created by using lowercase - as a result the shift+num combination was broken
		var shift_nums = {
			"`":"~",
			"1":"!",
			"2":"@",
			"3":"#",
			"4":"$",
			"5":"%",
			"6":"^",
			"7":"&",
			"8":"*",
			"9":"(",
			"0":")",
			"-":"_",
			"=":"+",
			";":":",
			"'":"\"",
			",":"<",
			".":">",
			"/":"?",
			"\\":"|"
		}

		// Special Keys - and their codes
		var special_keys = {
			'esc':27,
			'escape':27,
			'tab':9,
			'space':32,
			'return':13,
			'enter':13,
			'backspace':8,
			'scrolllock':145,
			'scroll_lock':145,
			'scroll':145,
			'capslock':20,
			'caps_lock':20,
			'caps':20,
			'numlock':144,
			'num_lock':144,
			'num':144,

			'pause':19,
			'break':19,

			'insert':45,
			'home':36,
			'delete':46,
			'end':35,

			'pageup':33,
			'page_up':33,
			'pu':33,

			'pagedown':34,
			'page_down':34,
			'pd':34,

			'left':37,
			'up':38,
			'right':39,
			'down':40,

			'f1':112,
			'f2':113,
			'f3':114,
			'f4':115,
			'f5':116,
			'f6':117,
			'f7':118,
			'f8':119,
			'f9':120,
			'f10':121,
			'f11':122,
			'f12':123
		}

		for(var i=0; k=keys[i],i<keys.length; i++) {
			//Modifiers
			if(k == 'ctrl' || k == 'control') {
				if(e.ctrlKey) kp++;

			} else if(k ==  'shift') {
				if(e.shiftKey) kp++;

			} else if(k == 'alt') {
				if(e.altKey) kp++;

			} else if(k.length > 1) { //If it is a special key
				if(special_keys[k] == code) kp++;

			} else { //The special keys did not match
				if(character == k) kp++;
				else {
					if(shift_nums[character] && e.shiftKey) { //Stupid Shift key bug created by using lowercase
						character = shift_nums[character];
						if(character == k) kp++;
					}
				}
			}
		}

		if(kp == keys.length) {
			if (lock == 0) {
				lock = 1;
				setTimeout(function(){
					lock = 0;
				}, 1500);
			} else {
				return false;
			}
			callback(e);

			if(!opt['propagate']) { //Stop the event
				//e.cancelBubble is supported by IE - this will kill the bubbling process.
				e.cancelBubble = true;
				e.returnValue = false;

				//e.stopPropagation works only in Firefox.
				if (e.stopPropagation) {
					e.stopPropagation();
					e.preventDefault();
				}
				return false;
			}
		}
	}

	//Attach the function with the event
	var lock = 0;
	if(ele.addEventListener) ele.addEventListener(opt['type'], func, false);
	else if(ele.attachEvent) ele.attachEvent('on'+opt['type'], func);
	else ele['on'+opt['type']] = func;
};


(function($){
	$.fn.extend({
		inputToEnd: function(myValue){
			var $t=$(this)[0];
			if (document.selection) {
				this.focus();
				sel = document.selection.createRange();
				sel.text = myValue;
				this.focus();
			} else if ($t.selectionStart || $t.selectionStart == '0') {
				var startPos = $t.selectionStart;
				var endPos = $t.selectionEnd;
				var scrollTop = $t.scrollTop;
				$t.value = $t.value.substring(0, startPos) + myValue + $t.value.substring(endPos, $t.value.length);
				this.focus();
				$t.selectionStart = startPos + myValue.length;
				$t.selectionEnd = startPos + myValue.length;
				$t.scrollTop = scrollTop;
			} else {
				this.value += myValue;
				this.focus();
			}
		},
		inputToDIV: function(myValue){
			var obj=$(this)[0];
			obj.focus();
			var selection = window.getSelection ? window.getSelection() : document.selection;
			var range = selection.createRange ? selection.createRange() : selection.getRangeAt(0);

			if (!window.getSelection){
				var selection = window.getSelection ? window.getSelection() : document.selection;
				var range = selection.createRange ? selection.createRange() : selection.getRangeAt(0);
				range.pasteHTML(myValue);
				range.collapse(false);
				range.select();
			}else{
				range.collapse(false);
				var hasR = range.createContextualFragment(myValue);
				var hasR_lastChild = hasR.lastChild;
				while (hasR_lastChild && hasR_lastChild.nodeName.toLowerCase() == "br" && hasR_lastChild.previousSibling && hasR_lastChild.previousSibling.nodeName.toLowerCase() == "br") {
					var e = hasR_lastChild;
					hasR_lastChild = hasR_lastChild.previousSibling;
					hasR.removeChild(e)
				}
				range.insertNode(hasR);
				if (hasR_lastChild) {
					range.setEndAfter(hasR_lastChild);
					range.setStartAfter(hasR_lastChild);
				}
				selection.removeAllRanges();
				selection.addRange(range);
			}
		}
	});
})(jQuery);

/**
 * 去掉字符串中的HTML标签
 *
 * @param string str 需要处理的字符串
 * @return string 已去掉HTML的字符串
 */
var removeHTMLTag = function(str) {
	str = str.replace(/<\/?[^>]*>/g,'');
	return str;
};

var quickLogin = function (){
	window.location.href = U('public/Passport/login');
	return false;
	//
	ui.box.load(U('public/Passport/quickLogin'),'快速登录');
};

/* 图片切换 */
(function(){
	var fSwitchPic = function( oPicSection, nInterval ) {
		try {
			this.dPicSection = "string" === typeof oPicSection ? document.getElementById( oPicSection ) : oPicSection;
			this.nInterval = nInterval > 0 ? nInterval : 2000;
			this.dPicList  = this.dPicSection.getElementsByTagName( "div" );
			this.nPicNum   = this.dPicList.length;
		} catch( e ) {
			return e;
		}
		this.nCurrentPic = this.nPicNum - 1;
		this.nNextPic = 0;
		this.fInitPicList();

		this.dPicNav = this.dPicSection.getElementsByTagName( "ul" )[0];
		this.fInitPicNav();

		clearTimeout( this.oTimer );
		this.fSwitch();
		this.fStart();
	};

	fSwitchPic.prototype = {
		constructor: fSwitchPic,
		fInitPicList: function() {
			var oSwitchPic = this;
			this.dPicSection.onmouseover = function() {
				oSwitchPic.fPause();
			};
			this.dPicSection.onmouseout  = function() {
				oSwitchPic.fGoon();
			};
		},
		fInitPicNav: function() {
			var oSwitchPic = this,
				sPicNav = '',
				nPicNum = this.nPicNum;

			for ( var i = 0; i < nPicNum; i ++ ) {
				sPicNav += '<li style="list-style-type:none;"><a href="javascript:;" target="_self">' + ( i + 1 ) + '</a></li>';
			}
			this.dPicNav.innerHTML = sPicNav;

			// 追加属性和Event
			var dPicNavMenu = this.dPicNav.getElementsByTagName( "a" ),
				nL = dPicNavMenu.length;

			while ( nL -- > 0 ) {
				dPicNavMenu[nL].nIndex = nL;
				dPicNavMenu[nL].onclick	 = function() {
					oSwitchPic.fGoto( this.nIndex );
					return false;
				};
			}
			this.dPicNavMenu = dPicNavMenu;
		},
		fSwitch: function() {
			if ( this.nPicNum <= 1 ){
				return;
			}
			var nCurrentPic = this.nCurrentPic,
				nNextPic	= this.nNextPic;
			this.dPicList[nNextPic].style.display = "";
			this.dPicList[nCurrentPic].style.display = "none";

			this.dPicNavMenu[nNextPic].className = "sel";
			this.dPicNavMenu[nCurrentPic].className = "";

			this.nCurrentPic = nNextPic;
			this.nNextPic = ( nNextPic < this.nPicNum - 1 ) ? ( nNextPic + 1 ) : 0;
		},
		fStart: function() {
			var oSwitchPic = this;
			this.oTimer = setTimeout( function() {
				oSwitchPic.fSwitch();
				oSwitchPic.fStart();
			}, this.nInterval );
		},
		fPause: function() {
			clearTimeout( this.oTimer );
		},
		fGoon: function() {
			clearTimeout( this.oTimer );
			this.fStart();
		},
		fGoto: function( nIndex ) {
			var nIndex = parseInt( nIndex );
			if ( nIndex == this.nCurrentPic ) {
				return false;
			}

			clearTimeout( this.oTimer );
			this.nNextPic = nIndex;
			this.fSwitch();
		}
	};

	window.fSwitchPic = fSwitchPic;

})();

var switchVideo = function(id,type,host,flashvar){
	if( type == 'close' ){
		$("#video_mini_show_"+id).show();
		$("#video_content_"+id).html( '' );
		$("#video_show_"+id).hide();
	}else{
		$("#video_mini_show_"+id).hide();
		$("#video_content_"+id).html( showFlash(host,flashvar) );
		$("#video_show_"+id).show();
	}
}
/**
 * 显示视频
 */
var showFlash = function (host, flashvar) {
	if(host=='youtube.com'){
		var flashHtml = '<iframe width="560" height="315"  src="http://www.youtube.com/embed/'+flashvar+'" frameborder="0" allowfullscreen></iframe>';
	}else if(host=='video.eledu.com.cn'){
		var flashHtml='<object type="application/x-shockwave-flash" data="'+COMMON_STATIC_URL+'/player/player.swf" width="470" height="305" id="container1">' +
			'<param name="allowfullscreen" value="true">' +
			'<param name="allowscriptaccess" value="always">' +
			'<param name="seamlesstabbing" value="true">' +
			'<param name="wmode" value="opaque">' +
			'<param name="flashvars" value="file='+flashvar+'&autostart=true&controlbar.position=over"></object>';
	}else{
		var flashHtml = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="430" height="400">'
			+ '<param value="transparent" name="wmode"/>'
			+ '<param value="'+flashvar+'" name="movie" />'
			+ '<embed src="'+flashvar+'" wmode="transparent" allowfullscreen="true" type="application/x-shockwave-flash" width="525" height="420"></embed>'
			+ '</object>';
	}
	return flashHtml;
}

/**
 * 过滤html标签
 */
function strip_tags (input, allowed) {
	allowed = (((allowed || "") + "").toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []).join(''); // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
	var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
		commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
	return input.replace(commentsAndPhpTags, '').replace(tags, function ($0, $1) {
		return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
	});
}

function quickLoginAfter() {
	window.parent.ui.box.close();
	setTimeout("window.parent.location.reload();", 500);
}

function is_email(strEmail) {
	if (strEmail.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) != -1) {
		return true;
	} else {
		return false;
	}
}

/**
 * 检查手机号码的合法性
 * 国家号码段分配如下*
 * 移动  134.135.136.137.138.139.150.151.152.157.158.159.187.188 ,147(数据卡)
 * 联通  130.131.132.155.156.185.186
 * 电信  133.153.180.189
 * CDMA 133,153
 */
function is_mobile(mobile) {
	var length = mobile.length;
	var P = /^(((13[0-9]{1})|(14[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
	return length == 11 && P.test(mobile);
}

/*********************************************************************
 * 新公共JS
 * @author blog.snsgou.com
 *********************************************************************/
var BROWSER = {};
var USERAGENT = navigator.userAgent.toLowerCase();
browserVersion({'ie': 'msie', 'firefox': '', 'chrome': '', 'opera': '', 'safari': '', 'mozilla': '', 'webkit': '', 'maxthon': '', 'qq': 'qqbrowser', 'rv': 'rv'});
if (BROWSER.safari) {
	BROWSER.firefox = true;
}
BROWSER.opera = BROWSER.opera ? opera.version() : 0;

var CSSLOADED = [];
var JSLOADED = [];
var safescripts = {};
var evalscripts = [];

/**
 * 根据ID获取对象
 */
function $$(id) {
	return !id ? null : document.getElementById(id);
}

/**
 * 根据类名获取对象
 */
function $C(classname, ele, tag) {
	var returns = [];
	ele = ele || document;
	tag = tag || '*';
	if (ele.getElementsByClassName) {
		var eles = ele.getElementsByClassName(classname);
		if (tag != '*') {
			for (var i = 0, L = eles.length; i < L; i++) {
				if (eles[i].tagName.toLowerCase() == tag.toLowerCase()) {
					returns.push(eles[i]);
				}
			}
		} else {
			returns = eles;
		}
	} else {
		eles = ele.getElementsByTagName(tag);
		var pattern = new RegExp("(^|\\s)" + classname + "(\\s|$)");
		for (i = 0, L = eles.length; i < L; i++) {
			if (pattern.test(eles[i].className)) {
				returns.push(eles[i]);
			}
		}
	}
	return returns;
}

/**
 * 添加事件
 */
function _attachEvent(obj, evt, func, eventobj) {
	eventobj = !eventobj ? obj : eventobj;
	if (obj.addEventListener) {
		obj.addEventListener(evt, func, false);
	} else if (eventobj.attachEvent) {
		obj.attachEvent('on' + evt, func);
	}
}

/**
 * 移除事件
 */
function _detachEvent(obj, evt, func, eventobj) {
	eventobj = !eventobj ? obj : eventobj;
	if (obj.removeEventListener) {
		obj.removeEventListener(evt, func, false);
	} else if (eventobj.detachEvent) {
		obj.detachEvent('on' + evt, func);
	}
}

/**
 * 获取事件对象
 */
function getEvent() {
	if (document.all) return window.event;
	func = getEvent.caller;
	while (func != null) {
		var arg0 = func.arguments[0];
		if (arg0) {
			if ((arg0.constructor == Event || arg0.constructor == MouseEvent) || (typeof(arg0) == "object" && arg0.preventDefault && arg0.stopPropagation)) {
				return arg0;
			}
		}
		func = func.caller;
	}
	return null;
}

/**
 * 阻止事件对象的默认行为及冒泡传播
 */
function doane(event, preventDefault, stopPropagation) {
	var preventDefault = isUndefined(preventDefault) ? 1 : preventDefault;
	var stopPropagation = isUndefined(stopPropagation) ? 1 : stopPropagation;
	e = event ? event : window.event;
	if (!e) {
		e = getEvent();
	}
	if (!e) {
		return null;
	}
	if (preventDefault) {
		if (e.preventDefault) {
			e.preventDefault();
		} else {
			e.returnValue = false;
		}
	}
	if (stopPropagation) {
		if (e.stopPropagation) {
			e.stopPropagation();
		} else {
			e.cancelBubble = true;
		}
	}
	return e;
}

/**
 * 浏览器版本计算
 */
function browserVersion(types) {
	var other = 1;
	for (i in types) {
		var v = types[i] ? types[i] : i;
		if (USERAGENT.indexOf(v) != -1) {
			var re = new RegExp(v + '(?:\\/|\\s|:)([\\d\\.]+)', 'ig');
			var matches = re.exec(USERAGENT);
			var ver = matches != null ? matches[1] : 0;
			other = ver !== 0 && v != 'mozilla' ? 0 : other;
		} else {
			var ver = 0;
		}
		ver = parseFloat(ver);
		eval('BROWSER.' + i + '= ver');
	}
	BROWSER.other = other;
}

/**
 * 是否为 undefined
 */
function isUndefined(variable) {
	return typeof variable == 'undefined' ? true : false;
}

/**
 * 是否在数组中
 */
function in_array(needle, haystack) {
	if (typeof needle == 'string' || typeof needle == 'number') {
		for (var i in haystack) {
			if (haystack[i] == needle) {
				return true;
			}
		}
	}
	return false;
}

/**
 * 附加JS脚本
 */
function appendScript(src, text, reload, charset) {
	var id = hash(src + text);
	if (!reload && in_array(id, evalscripts)) return;
	if (reload && $$(id)) {
		$$(id).parentNode.removeChild($$(id));
	}

	evalscripts.push(id);
	var scriptNode = document.createElement("script");
	scriptNode.type = "text/javascript";
	scriptNode.id = id;
	scriptNode.charset = charset ? charset : (BROWSER.firefox ? document.characterSet : document.charset);
	try {
		if (src) {
			scriptNode.src = src;
			scriptNode.onloadDone = false;
			scriptNode.onload = function() {
				scriptNode.onloadDone = true;
				JSLOADED[src] = 1;
			};
			scriptNode.onreadystatechange = function() {
				if ((scriptNode.readyState == 'loaded' || scriptNode.readyState == 'complete') && !scriptNode.onloadDone) {
					scriptNode.onloadDone = true;
					JSLOADED[src] = 1;
				}
			};
		} else if (text) {
			scriptNode.text = text;
		}
		document.getElementsByTagName('head')[0].appendChild(scriptNode);
	} catch (e) {}
}

/**
 * 执行文本中的JS脚本
 */
function evalScript(str) {
	if (typeof str === 'string' && str.indexOf('<script') == -1) return str;
	var p = /<script[^\>]*?>([^\x00]*?)<\/script>/ig;
	var arr = [];
	while (arr = p.exec(str)) {
		var p1 = /<script[^\>]*?src=\"([^\>]*?)\"[^\>]*?(reload=\"1\")?(?:charset=\"([\w\-]+?)\")?><\/script>/i;
		var arr1 = [];
		arr1 = p1.exec(arr[0]);
		if (arr1) {
			appendScript(arr1[1], '', arr1[2], arr1[3]);
		} else {
			p1 = /<script(.*?)>([^\x00]+?)<\/script>/i;
			arr1 = p1.exec(arr[0]);
			appendScript('', arr1[2], arr1[1].indexOf('reload=') != -1);
		}
	}
	return str;
}

/**
 * 计算字符串的hash值
 */
function hash(string, length) {
	var length = length ? length : 32;
	var start = 0;
	var i = 0;
	var result = '';
	filllen = length - string.length % length;
	for (i = 0; i < filllen; i++) {
		string += "0";
	}
	while (start < string.length) {
		result = stringxor(result, string.substr(start, length));
		start += length;
	}
	return result;
}

/**
 * 字符串移位运算
 */
function stringxor(s1, s2) {
	var s = '';
	var hash = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	var max = Math.max(s1.length, s2.length);
	for (var i = 0; i < max; i++) {
		var k = s1.charCodeAt(i) ^ s2.charCodeAt(i);
		s += hash.charAt(k % 52);
	}
	return s;
}

/**
 * 获取URL域名
 */
function getHost(url) {
	var host = "null";
	if (typeof url == "undefined" || null == url) {
		url = window.location.href;
	}
	var regex = /^\w+\:\/\/([^\/]*).*/;
	var match = url.match(regex);
	if (typeof match != "undefined" && null != match) {
		host = match[1];
	}
	return host;
}

/**
 * 添加url参数
 *
 * @param url 原始url
 * @param params 链接参数数组，如 ['name=jianbao','age=18']
 */
function addUrlParams(url, params)
{
	params = params.join('&');
	url += (url.indexOf('?') == -1 ? '?' : '&') + params;
	return url;
}

/**
 * 新建函数对象
 */
function newFunction(func) {
	var args = [];
	for (var i = 1; i < arguments.length; i++) args.push(arguments[i]);
	return function(event) {
		doane(event);
		window[func].apply(window, args);
		return false;
	}
}

/**
 * Ajax类
 *
 * @param string recvType 返回类型
 * @param object waitId loading对象ID
 */
function Ajax(recvType, waitId) {
	var aj = new Object();

	aj.loading = '正在加载中...';
	aj.recvType = recvType ? recvType : 'XML';
	aj.waitId = waitId ? $$(waitId) : null;

	aj.resultHandle = null;
	aj.sendString = '';
	aj.targetUrl = '';

	aj.setLoading = function(loading) {
		if (typeof loading !== 'undefined' && loading !== null) aj.loading = loading;
	};

	aj.setRecvType = function(recvtype) {
		aj.recvType = recvtype;
	};

	aj.setWaitId = function(waitId) { // waitId 即可是对象ID，也可是对象
		aj.waitId = typeof waitId == 'object' ? waitId : $$(waitId);
	};

	aj.createXMLHttpRequest = function() {
		var request = false;
		if (window.XMLHttpRequest) {
			request = new XMLHttpRequest();
			if (request.overrideMimeType) {
				request.overrideMimeType('text/xml');
			}
		} else if (window.ActiveXObject) {
			var versions = ['Microsoft.XMLHTTP', 'MSXML.XMLHTTP', 'Microsoft.XMLHTTP', 'Msxml2.XMLHTTP.7.0', 'Msxml2.XMLHTTP.6.0', 'Msxml2.XMLHTTP.5.0', 'Msxml2.XMLHTTP.4.0', 'MSXML2.XMLHTTP.3.0', 'MSXML2.XMLHTTP'];
			for (var i = 0; i < versions.length; i++) {
				try {
					request = new ActiveXObject(versions[i]);
					if (request) {
						return request;
					}
				} catch (e) {}
			}
		}
		return request;
	};

	aj.XMLHttpRequest = aj.createXMLHttpRequest();
	aj.showLoading = function() {
		if (aj.waitId && (aj.XMLHttpRequest.readyState != 4 || aj.XMLHttpRequest.status != 200)) {
			aj.waitId.style.display = '';
			var IMGDIR = COMMON_STATIC_URL + '/image';
			aj.waitId.innerHTML = '<span><img src="' + IMGDIR + '/loading.gif" class="vm"> ' + aj.loading + '</span>';
		}

	};

	aj.processHandle = function() {
		if (aj.XMLHttpRequest.readyState == 4 && aj.XMLHttpRequest.status == 200) {
			if (aj.waitId) {
				aj.waitId.style.display = 'none';
			}
			if (aj.recvType == 'HTML') {
				aj.resultHandle(aj.XMLHttpRequest.responseText, aj);
			} else if (aj.recvType == 'XML') {
				if (!aj.XMLHttpRequest.responseXML || !aj.XMLHttpRequest.responseXML.lastChild || aj.XMLHttpRequest.responseXML.lastChild.localName == 'parsererror') {
					//aj.resultHandle('<a href="' + aj.targetUrl + '" target="_blank" style="color:red">内部错误，无法显示此内容</a>', aj);
				} else {
					aj.resultHandle(aj.XMLHttpRequest.responseXML.lastChild.firstChild.nodeValue, aj);
				}
			} else if (aj.recvType == 'JSON') {
				var res = null;
				try {
					res = (new Function("return (" + aj.XMLHttpRequest.responseText + ")"))();
				} catch (e) {
					res = null;
				}
				aj.resultHandle(res, aj);
			}
		}
	};

	aj.get = function(targetUrl, resultHandle) {
		setTimeout(function() {
			aj.showLoading()
		}, 250);
		aj.targetUrl = targetUrl;
		aj.XMLHttpRequest.onreadystatechange = aj.processHandle;
		aj.resultHandle = resultHandle;
		var attackEvasive = isUndefined(attackEvasive) ? 0 : attackEvasive;
		if (window.XMLHttpRequest) {
			aj.XMLHttpRequest.open('GET', aj.targetUrl);
			aj.XMLHttpRequest.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
			aj.XMLHttpRequest.send(null);
		} else {
			aj.XMLHttpRequest.open("GET", targetUrl, true);
			aj.XMLHttpRequest.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
			aj.XMLHttpRequest.send();
		}
	};
	aj.post = function(targetUrl, sendString, resultHandle) {
		setTimeout(function() {
			aj.showLoading()
		}, 250);
		aj.targetUrl = targetUrl;
		aj.sendString = sendString;
		aj.XMLHttpRequest.onreadystatechange = aj.processHandle;
		aj.resultHandle = resultHandle;
		aj.XMLHttpRequest.open('POST', targetUrl);
		aj.XMLHttpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		aj.XMLHttpRequest.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
		aj.XMLHttpRequest.send(aj.sendString);
	};
	aj.getJSON = function(targetUrl, resultHandle) {
		aj.setRecvType('JSON');
		aj.get(targetUrl + '&ajaxData=json', resultHandle);
	};
	aj.getHTML = function(targetUrl, resultHandle) {
		aj.setRecvType('HTML');
		aj.get(targetUrl + '&ajaxData=html', resultHandle);
	};

	return aj;
}

/**
 * ajax Get请求
 *
 * @param url 请求URL
 * @param showId 请求结果显示区的ID
 * @param waitid “请稍候...”显示区的ID
 * @param loading 正在加载 提示信息
 * @param display 是否显示 显示区 （'' 或 'none'）
 * @param callback 回调函数
 */

/*function ajaxGet(url, showId, waitId, loading, display, callback) {
	waitId = typeof waitId == 'undefined' || waitId === null ? showId : waitId;
	var aj = new Ajax();
	aj.setLoading(loading);
	aj.setWaitId(waitId);
	aj.display = typeof display == 'undefined' || display == null ? '' : display;
	aj.showId = $$(showId);
	if (url.substr(strlen(url) - 1) == '#') {
		url = url.substr(0, strlen(url) - 1);
		aj.autoGoto = 1;
	}

	var url = addUrlParams(url, ['inAjax=1','ajaxTarget=' + showId]);
	aj.get(url, function(res, aj) {
		var evaled = false;
		if (res.indexOf('ajaxError') != -1) {
			evalScript(res);
			evaled = true;
		}
		if (!evaled && (typeof ajaxError == 'undefined' || !ajaxError)) {
			if (aj.showId) {
				aj.showId.style.display = aj.display;
				ajaxInnerhtml(aj.showId, res);
				ajaxUpdateEvents(aj.showId);
				if (aj.autoGoto) scroll(0, aj.showId.offsetTop);
			}
		}

		ajaxError = null;
		if (callback && typeof callback == 'function') {
			callback();
		} else if (callback) {
			eval(callback);
		}
		if (!evaled) evalScript(res);
	});
}*/

/**
 * 批量更新对象事件
 */
function ajaxUpdateEvents(obj, tagName) {
	tagName = tagName ? tagName : 'A';
	var objs = obj.getElementsByTagName(tagName);
	for (k in objs) {
		var o = objs[k];
		ajaxUpdateEvent(o);
	}
}

/**
 * 更新对象事件
 */
function ajaxUpdateEvent(obj) {
	if (typeof obj == 'object' && obj.getAttribute) {
		if (obj.getAttribute('ajaxTarget')) {
			if (!obj.id) obj.id = Math.random();
			var ajaxEvent = obj.getAttribute('ajaxEvent') ? obj.getAttribute('ajaxEvent') : 'click';
			var ajaxUrl = obj.getAttribute('ajaxUrl') ? obj.getAttribute('ajaxUrl') : obj.href;
			_attachEvent(obj, ajaxEvent, newFunction('ajaxGet', ajaxUrl, obj.getAttribute('ajaxTarget'), obj.getAttribute('ajaxWaitId'), obj.getAttribute('ajaxLoading'), obj.getAttribute('ajaxDisplay')));
			if (obj.getAttribute('ajaxFunc')) {
				obj.getAttribute('ajaxFunc').match(/(\w+)\((.+?)\)/);
				_attachEvent(obj, ajaxEvent, newFunction(RegExp.$1, RegExp.$2));
			}
		}
	}
}

/**
 * ajax Post请求
 *
 * @param url 表单ID
 * @param submitBtn 提交按钮ID
 * @param showId 请求结果显示区的ID
 * @param callback 回调函数
 */
function ajaxPost(formId, submitBtn, showId, callback) {
	var ajaxFrameId = 'ajaxFrame';
	var ajaxFrame = $$(ajaxFrameId);
	var curForm = $$(formId);
	var formTarget = curForm.target;

	// 已请求完成
	var handleResult = function() {
		var res = '';
		var evaled = false;
		showLoading('none');

		try {
			res = $$(ajaxFrameId).contentWindow.document.XMLDocument.text;
		} catch (e) {
			try {
				res = $$(ajaxFrameId).contentWindow.document.documentElement.firstChild.wholeText;
			} catch (e) {
				try {
					res = $$(ajaxFrameId).contentWindow.document.documentElement.firstChild.nodeValue;
				} catch (e) {
					//res = '内部错误，无法显示此内容';
				}
			}
		}

		// 处理指定的“错误结果”
		if (res != '' && typeof res === 'string' && res.indexOf('ajaxError') != -1) {
			evalScript(res);
			evaled = true;
		}

		if (submitBtn) {
			$$(submitBtn).disabled = false;
		}
		if (!evaled && (typeof ajaxError == 'undefined' || !ajaxError)) {
			ajaxInnerhtml($$(showId), res);
		}
		ajaxError = null;
		if (typeof callback == 'function') {
			callback();
		} else {
			eval(callback);
		}
		if (!evaled) evalScript(res);
		ajaxFrame.loading = 0;
		if (!BROWSER.firefox || BROWSER.safari) {
			$$('appendParent').removeChild(ajaxFrame.parentNode);
		} else {
			setTimeout(
				function() {
					$$('appendParent').removeChild(ajaxFrame.parentNode);
				}, 100);
		}
	};

	if (!ajaxFrame) {
		var div = document.createElement('div');
		div.style.display = 'none';
		div.innerHTML = '<iframe name="' + ajaxFrameId + '" id="' + ajaxFrameId + '" loading="1"></iframe>';
		$$('appendParent').appendChild(div);
		ajaxFrame = $$(ajaxFrameId);
	} else if (ajaxFrame.loading) { // 表示前面的ajaxiframe还未处理完！
		return false;
	}

	_attachEvent(ajaxFrame, 'load', handleResult);

	//showLoading();  去掉多余的提示
	curForm.target = ajaxFrameId;
	var action = curForm.getAttribute('action');
	curForm.action = action.replace(/\&?inAjax\=1/g, '');
	curForm.action = addUrlParams(curForm.action, ['inAjax=1']);

	curForm.submit();
	if (submitBtn) {
		$$(submitBtn).disabled = true;
	}
	doane();
	return false;
}

/**
 * 显示 请稍候...
 */
function showLoading(display, waiting) {
	var display = display ? display : 'block';
	var waiting = waiting ? waiting : '正在处理中...';
	$$('ajaxWaitId').innerHTML = waiting;
	$$('ajaxWaitId').style.display = display;
}

/**
 * ajax显示文本
 */
function ajaxInnerhtml(showObj, str) {
	if (!showObj) {
		return false;
	}

	if (showObj.tagName != 'TBODY') {
		showObj.innerHTML = str;
	} else {
		while (showObj.firstChild) {
			showObj.firstChild.parentNode.removeChild(showObj.firstChild);
		}
		var div1 = document.createElement('DIV');
		div1.id = showObj.id + '_div';
		div1.innerHTML = '<table><tbody id="' + showObj.id + '_tbody">' + str + '</tbody></table>';
		$$('appendParent').appendChild(div1);
		var trs = div1.getElementsByTagName('TR');
		var len = trs.length;
		for (var i = 0; i < len; i++) {
			showObj.appendChild(trs[0]);
		}
		var inputs = div1.getElementsByTagName('INPUT');
		var l = inputs.length;
		for (var i = 0; i < l; i++) {
			showObj.appendChild(inputs[0]);
		}
		div1.parentNode.removeChild(div1);
	}
}

function setTimeToUrl(url, title, time, type)
{
	if(type == 'success')
	{
		ui.success(title);
	}
	else
	{
		ui.error(title);
	}

	function toUrl()
	{
		window.location.href = url;
	}

	setTimeout(toUrl, time);
}

function dateToTimeStamp($date)
{
	// 获取某个时间格式的时间戳
	var timestamp2 = Date.parse(new Date(stringTime));
	timestamp2 = timestamp2 / 1000;
	return timestamp2;
}