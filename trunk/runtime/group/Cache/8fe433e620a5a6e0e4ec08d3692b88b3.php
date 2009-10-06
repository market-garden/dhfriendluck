﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php if(($apps_title)  !=  ""): ?><?php echo ($apps_title); ?>-<?php endif; ?><?php echo ($site_opts["site_name"]); ?></title>
<link rel="shortcut icon" href="__THEME__/favicon.ico" />
<meta name="keywords" content="<?php echo ($site_opts["site_keyword"]); ?>" />
<meta name="description" content="<?php echo ($site_opts["site_header"]); ?>" />
<script type="text/javascript">
<!--
	//指定当前组模块URL地址
	var	URL		=	'__URL__';
	var	APP		=	'__APP__';
	var	PUBLIC	=	'__PUBLIC__';
	var	ROOT	=	'__ROOT__';
	var TS		=	'__TS__';
	var MID		=	'<?php echo (intval($mid)); ?>';
	var NEED_LOGIN	=	'<?php echo ($TS_NEED_LOGIN); ?>';
	var expire  = <?php echo C('COOKIE_EXPIRE');?>;
    var TPIS = '<?php echo ($site_opts["tips"]); ?>';

//-->
</script>
<link href="__THEME__/layout.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="__PUBLIC__/js/jquery.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/json.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/shortcuts.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/jquery.hotkeys.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/ymPrompt/ymPrompt.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/scrolltotop/scrolltopcontrol.js"></script>

<script type="text/javascript" src="__PUBLIC__/js/ts_common.js"></script>
<link rel="stylesheet" id='skin' type="text/css" href="__PUBLIC__/js/ymPrompt/skin/qq/ymPrompt.css" />

<script type="text/javascript" src="__PUBLIC__/js/Share.js"></script>


<script>
	var Alert	= ymPrompt.alert;
	var Confirm = ymPrompt.confirmInfo;
	var Success = ymPrompt.succeedInfo;
	var Error   = ymPrompt.errorInfo;
	var Win     = ymPrompt.win;

</script>
<link rel="stylesheet" id='skin' type="text/css" href="__PUBLIC__/js/tip/jquery.cluetip.css" />

<style>a{TEXT-DECORATION:none}</style> 

</head>
<body>
<script>

</script>
<?php if($ad["header"]){ ?>
<div class="ad_top"><?php echo ($ad["header"]); ?></div>
<?php } ?>
<div class="header"><!-- 头部 begin -->
   <div style="width: 1000px;">
    <div class="logo"><a href="__TS__">&nbsp;</a></div>
	<?php if(isset($_SESSION["userInfo"])): ?><div class="nav">
			<ul>
			<li><a href="__TS__/Home" class="fb14">演示首页</a></li>
		   <!--  <li class="on"> -->
		   <li><a href="__TS__/space/<?php echo ($mid); ?>" class="fb14">个人空间</a>
				<!-- <div class="dropmenu"><a href="#">修改资料</a><a href="#">换头像</a><a href="#">我的留言板</a></div> --></li>
			<li onMouseOut="this.className='#'" onmouseover="this.className='on'"><a href="__TS__/Friend/index" class="ico_arrow fb14">好友列表</a>
				<div class="dropmenu"><a href="__TS__/Friend/index">我的好友</a><a href="__TS__/Friend/ping">好友屏蔽</a><a href="__TS__/Friend/track">访问脚印</a><a href="__TS__/Friend/find">查找朋友</a><a href="__TS__/Invite/index
">邀请好友</a></div></li>
			<li><a href="__TS__/Home/network" class="fb14">随便看看</a></li>

            <?php if($APPMENUS_TOP){ ?>
				<li onMouseOut="this.className='#'" onmouseover="this.className='on'"><a href="#" class="ico_arrow fb14 ">应用</a>
				
				<div class="dropmenu">
					<?php if(is_array($APPMENUS_TOP)): ?><?php $i = 0;?><?php $__LIST__ = $APPMENUS_TOP?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$app): ?><?php ++$i;?><?php $mod = ($i % 2 )?><a href="<?php echo ($app["url"]); ?>"><img src="<?php echo ($app["icon"]); ?>" height="16" align="absmiddle"/> <?php echo ($app["name"]); ?> </a><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
	              </div>
				</li>
			<?php } ?>		
			
			<li onMouseOut="this.className='#'" onmouseover="this.className='on'"><a href="__TS__/Notify/inbox" class="ico_arrow fb14 ">消息中心</a>
			
			<div class="dropmenu">
                                  <a href="__TS__/Notify/inbox">短消息<span class="cRed fB" id='message<?php echo ($mid); ?>' ></span></a>
                                  <a href="__TS__/Notify/index/t/sys">系统通知<span class="cRed fB" id='notification<?php echo ($mid); ?>'></span></a>
                                  <a href="__TS__/Notify/index/t/fri">好友请求<span class="cRed fB" id='friend<?php echo ($mid); ?>'></span></a>
                                  <a href="__TS__/Space/#wall">留言板<span class="cRed fB" id='wall<?php echo ($mid); ?>'></span></a>
                </div>
                    <div class="msg_top" id='msg_top' style='display:none'><a href="" id='msg_top_href'></a></div>
				
			</li>
			
			</ul>
		</div>
		
		
            <input type="hidden" id="js_token" value="<?php echo ($js_token); ?>"><?php endif; ?>
  <?php if( !isset($_SESSION["userInfo"])): ?><div class="nav">
			<ul>
			<li><a href="__TS__/Index/index/" class="fb14">演示首页</a></li>
		   <!--  <li class="on"> -->
		   <li><a href="__TS__/Home/network" class="fb14">随便看看</a></li>
			</ul>
		</div><?php endif; ?>
    <div class="nav_sub">
		<?php if( !isset($_SESSION["userInfo"])): ?><a href="__TS__/Index/reg">注册</a>&nbsp;┆&nbsp;<a href="__TS__/Index/login">登陆</a>&nbsp;┆&nbsp;<a href="__TS__/Information/help">帮助</a><?php endif; ?>
		<?php if(isset($_SESSION["userInfo"])): ?><a href="__TS__/Invite">邀请</a>&nbsp;┆&nbsp;<a href="__TS__/Account">账号</a>&nbsp;┆&nbsp;<a href="__TS__/Info">资料</a>&nbsp;┆&nbsp;<a href="__TS__/Privacy">隐私</a>&nbsp;┆&nbsp;<a href="__TS__/Index/logout">退出</a><?php endif; ?>
	</div></div>
</div><!-- 头部 end -->





<script type="text/javascript" src="../Public/Js/Common.js"></script>
<link  href="../Public/group.css" rel="stylesheet" type="text/css"/>
</head>

<body>



<div class="content"><!-- 内容 begin  -->
   <!-- 用户组件列表 begin -->
   <?php if($mid === 0 || !isset($mid)){ ?>
	<script>
		function changeverify(){
			var date = new Date();
			var ttime = date.getTime();
			$('#verifyimg').attr('src',APP+'/Index/verify/time/'+ttime);
		}

	</script>
	<div class="user_app"><!-- 用户组件列表 begin -->
		<div class="user_app_top"></div>
		<div class="left_login_box">
		<h2>登录站点</h2>
		<form action="__APP__/Index/doLogin" method="post">
		<div style="width:55px;">Email：</div><div style="width:120px; margin-bottom:5px;"><input name="email" type="text" class="TextH20" id="textfield3" onfocus="this.className='Text2'" onblur="this.className='TextH20'" style="width:100%;"/>
		</div>
		<p><div style="width:55px;">密码：</div><div style="width:120px; margin-bottom:5px;"><input name="passwd" type="password" class="TextH20" id="textfield3" onfocus="this.className='Text2'" onblur="this.className='TextH20'"  style="width:100%;"/></div>
		</p>
		<?php $verify_allow = unserialize($site_opts["verify"]);
			$login_verify_allow = $verify_allow['login']; ?>
 <?php if($login_verify_allow){ ?>
		<div style="width:55px;">验证码：</div>
        <div style="width:130px; margin-bottom:5px;">
        <div class="left"><input name="verify" type="text" class="TextH20" id="textfield3" onfocus="this.className='Text2'" onblur="this.className='TextH20'" style="width:50px;" /></div>
		 <a href="###" onclick="changeverify()"><img src="__APP__/Index/verify" id="verifyimg" alt="换一张" /></a>
		</div>
 <?php } ?>

		<div style="width:130px; margin-bottom:5px;"><input name="remembor" type="checkbox" value="1" />下次自动登录</div>
		 <div style="width:130px; margin-bottom:5px;"><input name="button" type="submit" class="btn_b left_do" id="button" value="登 录" /></div>
		</form>
	</div>
	<?php if($ad["leftmenu"]){ ?>
    <div class="ad_app"><?php echo ($ad["leftmenu"]); ?></div>
	<?php } ?>
		<div class="user_app_btm"></div>
	</div><!-- 用户组件列表 end -->
<?php }else{ ?>

<div class="user_app"><!-- 用户组件列表 begin -->
	<div class="user_app_top"></div>
	<div class="user_app_list">
		<ul>
			<?php if(is_array($APPMENUS_LEFT)): ?><?php $i = 0;?><?php $__LIST__ = $APPMENUS_LEFT?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$app): ?><?php ++$i;?><?php $mod = ($i % 2 )?><li>
					<a href="<?php echo ($app["url"]); ?>" class="a14"><img src="<?php echo ($app["icon"]); ?>" /><?php echo ($app["name"]); ?></a>
					<?php if($app["add_name"]){ ?>
						<span><a href="<?php echo ($app["add_url"]); ?>"><?php echo ($app["add_name"]); ?></a></span>
					<?php } ?>
				</li><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>			
		</ul>
	</div>
	<div class="app_add"><a href="__TS__/App/">添加或删除组件</a></div>
	<?php if($ad["leftmenu"]){ ?>
    <div class="ad_app"><?php echo ($ad["leftmenu"]); ?></div>
	<?php } ?>
	<div class="user_app_btm"></div>
	</div><!-- 用户组件列表 end -->

<?php } ?>      
  <!-- 用户组件列表 end -->
  
  <div class="main"> <!-- 右侧内容 begin  -->
  <div class=page_title> <!-- page_title begin -->
  <h2><img src="../Public/images/ico_app05.gif" />群组</h2>
  <div class="c"></div>
</div><!-- page_title end -->
  
  <div class="tab-menu"><!-- 切换标签 begin  -->
      <ul>
      <li><a href="__URL__/index/" class="on"><span> 我的群组</span></a></li>
      <li><a href="__URL__/flist/"><span>好友的群组</span></a></li>
      <li><a href="__URL__/search"><span>全部群组</span></a></li>
      <li><a href="__URL__/allTopic"><span>最新话题</span></a></li>
       <li><a href="__URL__/issue"><span><div class="ico_add">&nbsp;</div>发表话题</span></a></li>
      <li><a href="__URL__/add"><span><div class="ico_add">&nbsp;</div>创建新群</span></a></li>
      </ul>
  </div><!-- 切换标签 end  -->
  <div class="MenuSub"><a href="__URL__/index/" class="cGray">所在群组的最新动态</a>|<a href="__URL__/newtopic/">最新话题</a></div>
  
  
  <div class="groupBox">
  	<div class="sidebar">
    	<div class="FSort">
    	<div class="tit">我管理的群组</div>
    	<?php if(is_array($mymanagegroup)): ?><?php $i = 0;?><?php $__LIST__ = $mymanagegroup?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$group): ?><?php ++$i;?><?php $mod = ($i % 2 )?><div class="rlist">
    		<?php if((is_array($group)?$group["type"]:$group->type)  ==  "close"): ?><div class="left alR" style="width:15%">&nbsp;&nbsp;<img src="../Public/images/ico_lock.gif" /></div><?php endif; ?>
    		<div class="left lh20" style="padding-left:15px;"><a href="__APP__/Group/index/gid/<?php echo ($group['id']); ?>"><?php echo (msubstr($group['name'],0,15)); ?>(<?php echo ($group['membercount']); ?>)</a></div>
    		<?php if((is_array($group)?$group["type"]:$group->type)  ==  "close"): ?><div class="left" style="width:10%"><img src="../Public/images/ico_amend.gif" /></div><?php endif; ?>
    	</div><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
		<div class="btm"></div>
        </div>
        
        <div class="FSort">
    	<div class="tit"> <?php if($mid != $uid){ ?><?php echo (getUserName($uid)); ?><?php } else { ?> 我<?php } ?>加入的群组</div>
    	
    	<?php if(is_array($myjoingroup)): ?><?php $i = 0;?><?php $__LIST__ = $myjoingroup?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$group): ?><?php ++$i;?><?php $mod = ($i % 2 )?><div class="rlist">
    		<?php if((is_array($group)?$group["type"]:$group->type)  ==  "close"): ?><div class="left alR" style="width:15%">&nbsp;&nbsp;<img src="../Public/images/ico_lock.gif" /></div><?php endif; ?>
    		<div class="left lh20" style="padding-left:15px;"><a href="__APP__/Group/index/gid/<?php echo ($group['id']); ?>"><?php echo (msubstr($group['name'],0,15)); ?>(<?php echo ($group['membercount']); ?>)</a></div>
    	    <?php if((is_array($group)?$group["type"]:$group->type)  ==  "close"): ?><div class="left" style="width:10%"><img src="../Public/images/ico_amend.gif" /></div><?php endif; ?>
    	</div><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
        <div class="btm"></div>
        </div>
        
    </div>
  <div class="boxL"><!-- 好友日志 begin  -->
  		<ul>
  		<?php if(is_array($volist['data'])): ?><?php $i = 0;?><?php $__LIST__ = $volist['data']?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$list): ?><?php ++$i;?><?php $mod = ($i % 2 )?><?php if(count($list['feed'])){ ?>
       	  <li style="margin-bottom:10px;">
          		<div class="left" style="width: 70px;"><span class="headpic50"><a href="__APP__/Group/index/gid/<?php echo ($list['gid']); ?>"><img src="__ROOT__/thumb.php?w=50&h=50&url=<?php echo (get_photo_url(getgroupinfo($list["gid"],'logo'))); ?>"/></a></span></div>

          		<div class="left" style="width: 550px;">
       			  <h3 class="f14px lh30 btmlineD"><span class="right"> <a href="__APP__/Group/index/gid/<?php echo ($list['gid']); ?>" class="f12px">更多&gt;&gt;</a></span><a href="__APP__/Group/index/gid/<?php echo ($list['gid']); ?>"><strong><?php echo (msubstr(getgroupinfo($list['gid'],'name'),0,20)); ?></strong> </a>
       			  <?php if(isadmin($uid,$list['gid'])){ ?><span class="amend"><a href="__APP__/Manage/index/gid/<?php echo ($list['gid']); ?>">管理</a></span>  <?php } ?> </h3>
                <ul class="list">
                	  <?php if(is_array($list['feed'])): ?><?php $i = 0;?><?php $__LIST__ = $list['feed']?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$data): ?><?php ++$i;?><?php $mod = ($i % 2 )?><?php if($data['type'] == 'group_join' ): ?><li class="btmlineD"><div class="right"><em><?php echo date('m-d H:i',$data['cTime']);?></em></div><?php echo (stripGroupName($data["title"])); ?></li>
                	  			
                	  		<?php else: ?>                     
                       			<li class="btmlineD"><div class="right"><em><?php echo date('m-d H:i',$data['cTime']);?></em></div><?php echo (stripGroupName($data["title"])); ?>: <?php echo ($data["body"]); ?> </li><?php endif; ?><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
                      
               </ul>
            </div>
			<div class="clear"/>
       	  </li>
       	   <?php } ?><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
        </ul>
       <div class="page"><?php echo ($volist['html']); ?></div>
  </div><!-- 好友日志 end  -->
  </div>  </div><!-- 右侧内容 end  -->
  <div class="c"></div>
</div><!-- 内容 end -->

﻿<?php if($mid && !$is_im_closed && $site_opts["is_im_open"]){ ?>
	<link href="__THEME__/im/css/im.css" rel="stylesheet" />
<div id="imbox" class="min">
          <h1><strong>我的好友</strong>
		  <em><img class="im_ico_msg" src="__THEME__/im/images/ico_msg0.gif" border="0" id="im_ico_msg" style="display:none;cursor:point;"/> </em>
		  <span><img id="right_top" src="__THEME__/im/images/maximize.gif" /></span> </h1>
          <h2>
              <ul id="friend_list">
              </ul>
          </h2>
          <h3>
              <span id="online_num"></span>
          </h3>
	<div id="im_notification"><span>3</span></div>
</div>
<script>
	var site_url = '<?php echo SITE_URL; ?>';
	var theme_url = '<?php echo THEME_URL; ?>';

</script>
<script type="text/javascript" charset="utf-8" src="__THEME__/im/js/jquery.js"></script>
<script type="text/javascript" charset="utf-8" src="__THEME__/im/js/im.js"></script>

<script>



</script>
<?php } ?>

<div class="app_bbg" style="display:none;"></div>
<?php if($ad["contentbottom"]){ ?>
<div class="ad_footer"><?php echo ($ad["contentbottom"]); ?></div>
<?php } ?>
<div style="height:15px;"></div>
<div class="footer_bg">
<div class="footer">
	<div class="menu">
		<a href="__TS__/Information/index/cid/2">关于我们</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="__TS__/Information/index/cid/3">联系方式</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="__TS__/Information/index/flink/1">友情链接</a></div>
  Copyright © ThinkSNS <?php echo ($site_opts["icp"]); ?> <a href="http://www.thinksns.net" target="_blank">Powered by <strong style="color:#555; font-family:Verdana, Arial, Helvetica, sans-serif;">Th<span style="color:#00CC00;">i</span>nk<span style="color:#0066FF;">SNS</span>™</strong></a>
</div>
<div style="height:30px;" >&nbsp;</div>
</div>
<?php if($ad["footer"]){ ?>
<div class="ad_footer"><?php echo ($ad["footer"]); ?></div>
<?php } ?>

<div style="display:none;">

<?php if($site_opts['cnzz_id']){ ?>
<script src="http://s87.cnzz.com/stat.php?id=<?php echo ($site_opts['cnzz_id']); ?>&web_id=<?php echo ($site_opts['cnzz_id']); ?>" language="JavaScript" charset="gb2312"></script>
<?php } ?>
<script type="text/javascript" src="__PUBLIC__/js/tip/jquery.cluetip.js"></script>

</div>

</body>
</html>

</body>
</html>