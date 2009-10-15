/**
 * needn't any js framework can work very well
 * @Author		muqiao
 * @Copyright	2008 (c) muqiao (http://hi.baidu.com/emkiao)
 * @Revision	$Id: app.js 16 2008-09-08 20:40:07Z muqiao $
 */

/**
 * top menu json string from initTopMenu 
var appList = {
	'appname1':{'title':'首页','url':'menu/index'},
	'appname2':{'title':'用户','url':'menu/user'},
	'appname3':{'title':'应用','url':'menu/app'},
	'appname4':{'title':'系统','url':'menu/system'}
}
*/

/*
 * in App.getMenu ajax request get json string like
var menuList = {
	'admin':{
		'title':'首页',
		'node':[
			{
				'title':'后台首页',
				'url':'admin/index',
				'selected':true
			} , {
				'title':'修改密码',
				'url':'admin/chpass'
			} , {
				'title':'基本信息',
				'url':'admin/basic'
			} , {
				'title':'查看公告',
				'url':'admin/notice'
			}
		]
	},
	'system':{
		'title':'系统',
		'node':[
			{
				'title':'参数设置',
				'url':'system/config'
			} , {
				'title':'菜单管理',
				'url':'system/menu',
				'selected':true
			} , {
				'title':'权限管理',
				'url':'system/purview'
			} , {
				'title':'系统日志',
				'url':'system/log'
			}
		]
	} ,
	'user':{
		'title':'用户',
		'node':[
			{
				'title':'用户管理',
				'url':'user/user'
			} , {
				'title':'组管理',
				'url':'group',
				'selected':true
			} , {
				'title':'添加用户',
				'url':'addUser'
			} , {
				'title':'添加群组',
				'url':'addGroup'
			}

		]
	}
};
*/
if(/msie/i.test( navigator.userAgent ) && !/opera/i.test( navigator.userAgent )){
	try{
		document.execCommand("BackgroundImageCache", false, true);
	}catch(e){};
}
var $ = function(el){
	return typeof el == 'string' ? document.getElementById(el) : el;
};
var fbind = function(el,type,handle){
	el.addEventListener
		? el.addEventListener(type, handle, false)
		: ( el.attachEvent ? el.attachEvent('on' + type, handle) : '' );
}
var App = {
	/**
	 * css 定义的参数
	 */
	headHeight		: 70,
	bodyPadding		: 61,
	spliterWidth	: 8,
	/**
	 * 系统面板引用
	 */
	sideNav:null,
	spliter:null,
	sidePannel:null,
	mainPannel:null,
	listPannel:null,
	container:null,

	/**
	 * 常量
	 */
	BOOT_URL:null,
	MENU_URL:null,

	/**
	 * 辅助实现
	 */
	expanded:true,
	curAppName:null,
	curTabName:null,
	curButton:null,
	appList:{},
	menuList:{},
	curMenuList:{},
	init:function(){
		App.topNav		= $('topMenu');
		App.locateNav	= $('locateNav');
		App.sideNav		= $('sideNavigator');
		App.spliter		= $('spliter');
		App.sidePannel	= $('sidePannel');
		App.mainPannel	= $('mainPannel');
		App.listPannel	= $('listPannel');
		App.container	= $('container');
	},
	adapt:function(){
		try{
			var p = App.getWindowScroll();
			var sideHeight = p.height - App.headHeight;
			App.sideNav.style.height = sideHeight + 'px';
			App.spliter.style.height = sideHeight + 'px';
			App.listPannel.style.height = (sideHeight - 1) + 'px';
			var mWidth  = p.width - App.spliterWidth - App.sidePannel.offsetWidth;
			App.mainPannel.style.width = mWidth + 'px';
			App.mainPannel.style.height = sideHeight + 'px';
			App.container.style.height = (sideHeight - App.bodyPadding) + 'px';
		} catch (e) {}
	},
	getWindowScroll:function(){
		var T, L, W, H,win = window, dom = document.documentElement, doc = document.body;
		T = dom && dom.scrollTop || doc && doc.scrollTop || 0;
		L = dom && dom.scrollLeft || doc && doc.scrollLeft || 0;
		if(win.innerWidth){
			W = win.innerWidth;
			H = win.innerHeight;
		}else{
			W = dom && dom.clientWidth || doc && doc.clientWidth;
			H = dom && dom.clientHeight || doc && doc.clientHeight;
		}
		return { top: T, left: L, width: W, height: H };
	},
	hover:function(menu, out){
		if(out){
			menu.className = menu.origclass;
		}else{
			menu.origclass = menu.className;
			menu.className = 'menuOver';
		}
	},
	switchExpandSide:function(){
		this.expandSide(App.listPannel.style.display == 'none');
	},
	expandSide:function(expand) {
		if(App.expanded == expand) return;
		App.expanded = expand;
		var el = App.listPannel;
		var mWidth  = App.getWindowScroll().width - App.spliterWidth - App.sideNav.offsetWidth;
		if(expand){
			if(el.style.display != 'none') return;
			el.style.display = 'block';
			App.sidePannel.style.width = App.sideNav.offsetWidth + 8 + el.offsetWidth + 'px';
			App.mainPannel.style.width = mWidth - 8 - el.offsetWidth + 'px';
			App.spliter.className = 'spliter_hide';
		}else{
			App.mainPannel.style.width = mWidth + 'px';
			if(el.style.display == 'none') return;
			el.style.display='none';
			App.sidePannel.style.width = App.sideNav.offsetWidth + 'px';
			App.spliter.className='spliter_expand';
		}
	},
	ajax:function(url,success,error){
		var xhr = window.ActiveXObject ? new ActiveXObject("Microsoft.XMLHTTP") : new XMLHttpRequest();
		xhr.open('GET', url, true);
		try{
			xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
			xhr.setRequestHeader('Accept', 'application/json, text/javascript, */*');
		} catch (e) {}
		var ival = null;
		var onreadystatechange = function(){
			if(xhr.readyState != 4){
				return;
			}
			if(ival){
				clearInterval(ival);
				ival = null;
			}
			try {
				// IE error sometimes returns 1223 when it should be 204 so treat it as success, see #1450
				var ok = !xhr.status && location.protocol == "file:" ||
				( xhr.status >= 200 && xhr.status < 300 ) || xhr.status == 304 || xhr.status == 1223;
			} catch(e) {
				var ok = false;
			}
			ok ? success(xhr.responseText) : error();
		}
		ival = setInterval(onreadystatechange, 13);
		try {
			xhr.send(null);
		} catch(e) {}
	},
	initApp:function(){
		App.ajax(App.MENU_URL, function(responseText){
			App.appList = eval("(" + responseText + ")");
			var indexApp = null;
			for(var appName in App.appList){
				var t = App.appList[appName];
				App.topNav.innerHTML += '<li id="tab_'+appName+'"><em><a onclick="App.goApp(\''+appName+'\')" href="javascript:;">'+t.title+'</a></em></li>';
				(t.selected || !indexApp) && (indexApp = appName);
			}
			App.goApp(indexApp);
		},function(){
			alert('init menu failed!');
		});
	},
	goApp:function(appName){
		if(!(appName in App.appList) || appName == App.curAppName) return;
		if(App.curAppName){
			$('tab_'+App.curAppName).className = '';
		}
		$('tab_'+appName).className = 'navon';
		App.getMenu(appName);
	},
	getMenu:function(appName){
		var menuList;
		if (appName in App.menuList) {
			App.buildMenu(App.menuList[appName], appName);
		} else {
			App.listPannel.innerHTML = '初始化菜单中...';
			if(!(appName in App.appList)){
				App.listPannel.innerHTML = '';
				return;
			}
			App.ajax(App.appList[appName].url, function(responseText){
				var menuList = eval("(" + responseText + ")");
				App.menuList[appName] = menuList;
				App.buildMenu(menuList, appName);
			},function(){
				App.listPannel.innerHTML = '';
			});
		}
	},
	buildMenu:function(menuList,appName){
		var indexTab;
		App.sideNav.innerHTML = '';
		App.listPannel.innerHTML = '';
		for(var tabName in menuList){
			var m = menuList[tabName];
			App.sideNav.innerHTML += '<li id="ch_'+appName+'_'+tabName+'" onclick="App.goTab(\''+
				tabName+'\',\''+appName+'\')"><a href="javascript:;">'+m.title+'</a></li>';
			var nodeList = m.node;
			var nodetext = '<ul class="menuList" id="ml_'+appName+'_'+tabName+'">';
			for(var i=0,l=nodeList.length; i<l; i++){
				nodetext += '<li url="'+nodeList[i].url+'"'
				+(nodeList[i].selected ? 'selected="selected"' : '') +' onclick="App.go(this)" onmouseover="App.hover(this)" onmouseout="App.hover(this,1)">'
					+nodeList[i].title+'</li>';
			}
			nodetext += '</ul>';
			App.listPannel.innerHTML += nodetext;
			(m.selected || !indexTab) && (indexTab = tabName);
		}
		App.curMenuList = menuList;
		App.goTab(indexTab, appName);
	},
	goTab:function(tabName, appName){
		if(!(tabName in App.curMenuList)) return;
		if(appName == App.curAppName){
			if(App.curTabName == tabName){
				App.switchExpandSide();
				return;
			}
			if(App.curTabName){
				$('ch_'+appName+'_'+App.curTabName).className = '';
				$('ml_'+appName+'_'+App.curTabName).style.display = 'none';
			}
		}
		App.curAppName = appName;
		App.expandSide(true);
		App.curTabName = tabName;
		$('ch_'+appName+'_'+tabName).className = 'focus';
		$('ml_'+appName+'_'+tabName).style.display = 'block';
		var lis = $('ml_'+appName+'_'+tabName).getElementsByTagName('li');
		var i = lis.length;
		while(i){
			if(lis[--i].getAttribute('selected') == 'selected'){
				break;
			}
		}
		App.go(lis[i]);
	},
	go:function(el){
		var url;
		if(typeof el != 'string'){
			url = el.getAttribute('url');
			if(el != App.curButton) {
				App.curButton && (App.curButton.className = '');
				App.curButton = el;
				el.className = 'menuOn';
				el.origclass = 'menuOn';
			}
		} else {
			url = el;
			App.curButton && (App.curButton.className = '');
			App.curButton = null;
		}
		$('loader').src = url;
		App.locateNav.innerHTML = App.appList[App.curAppName].title +' &#xBB; '
			+ App.curMenuList[App.curTabName].title +' &#xBB; '
			+ App.curButton.innerHTML;
	},
   refurbish:function(){
		parent.document.getElementById('loader').contentWindow.location.reload();		
   }
};