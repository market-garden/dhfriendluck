<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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

</head>
<body>
<script>

</script>
<?php if($ad["header"]){ ?>
<div class="ad_top"><?php echo ($ad["header"]); ?></div>
<?php } ?>
<div class="header"><!-- 头部 begin -->
    <div class="logo"><a href="__TS__">&nbsp;</a></div>
	<?php if(isset($_SESSION["userInfo"])): ?><div class="nav">
			<ul>
			<li><a href="__TS__/Home" class="fb14">首页</a></li>
		   <!--  <li class="on"> -->
		   <li><a href="__TS__/space/<?php echo ($mid); ?>" class="fb14">个人空间</a>
				<!-- <div class="dropmenu"><a href="#">修改资料</a><a href="#">换头像</a><a href="#">我的留言板</a></div> --></li>
			<li onMouseOut="this.className='#'" onmouseover="this.className='on'"><a href="__TS__/Friend/index" class="ico_arrow fb14">好友</a>
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
			
			<li onMouseOut="this.className='#'" onmouseover="this.className='on'"><a href="__TS__/Notify/inbox" class="ico_arrow fb14 ">消息</a>
			
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
			<li><a href="__TS__/Index/index/" class="fb14">首页</a></li>
		   <!--  <li class="on"> -->
		   <li><a href="__TS__/Home/network" class="fb14">随便看看</a></li>
			</ul>
		</div><?php endif; ?>
    <div class="nav_sub">
		<?php if( !isset($_SESSION["userInfo"])): ?><a href="__TS__/Index/reg">注册</a>&nbsp;┆&nbsp;<a href="__TS__/Index/login">登陆</a>&nbsp;┆&nbsp;<a href="__TS__/Information/help">帮助</a><?php endif; ?>
		<?php if(isset($_SESSION["userInfo"])): ?><a href="__TS__/Invite">邀请</a>&nbsp;┆&nbsp;<a href="__TS__/Account">账号</a>&nbsp;┆&nbsp;<a href="__TS__/Info">资料</a>&nbsp;┆&nbsp;<a href="__TS__/Privacy">隐私</a>&nbsp;┆&nbsp;<a href="__TS__/Index/logout">退出</a><?php endif; ?>
	</div>
</div><!-- 头部 end -->



<script type="text/javascript" charset="utf-8">
var mini_zishu = '<?php echo ($stringcount); ?>';
</script>

<link href="../Public/index.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../Public/home_index.js"></script>
<script type="text/javascript" src="../Public/feed.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/Share.js"></script>
<div class="content"><!-- 内容 begin  -->
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
    <div class="main"><!-- 画布 begin  -->
			<?php if($ad["contenttop"]){ ?>
					<div class="ad_r_top"><?php echo ($ad["contenttop"]); ?></div>
			<?php } ?>
		
        <div>
            
<div class="cr"><!-- cr begin  -->
<div class="UserList">
   <form action="__APP__" method="get"  id="list_fri" class="form_validator">
            <input type="hidden" name="s" value="/Friend/lists" >
            <input type="hidden" name="type" value="info" id="sub_type">
    <div class="tit">搜索用户</div>
    <div class="ListBox">
    <div ><div style="float:left; width:170px; padding-left:10px;"><input name="name" class="TextH20" style="width:165px; margin-right:5px;" type="text"  onblur="this.className='TextH20'" onfocus="this.className='Text2'" /></div><div style="float:left;" ><input type="submit" class="btn_b hander" value="找 人" /></div>
    </div>
    </div>
    <div class="btm"></div>
</form>
</div>
    <?php if(!isset($space_privacy) || $space_privacy){ ?>

    <?php if($visitors){ ?>
            <div class="UserList">
                <div class="tit">
                  <?php if( MODULE_NAME == 'Home' || $uid == $mid ){ ?>
                    <span class="right"><a href="__APP__/Friend/track" class="cGray2">&gt;&gt;更多</a></span>
                    <?php } ?>
                    最近来访<span>（<?php echo ($visitor_num); ?>）</span></div>
                <div class="ListBox">
                    <ul>
                        <?php if(is_array($visitors)): ?><?php $i = 0;?><?php $__LIST__ = $visitors?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$user): ?><?php ++$i;?><?php $mod = ($i % 2 )?><li style="height:100px; overflow:hidden;">
                                <span><a href="__APP__/space/<?php echo ($user["visitId"]); ?>" class="tips" rel="__TS__/Index/userInfo/uid/<?php echo ($user["visitId"]); ?>"><img src="<?php echo (getUserFace($user["visitId"])); ?>" /></a></span>
                                <div class="name"><a href="__APP__/space/<?php echo ($user["visitId"]); ?>"><?php echo (isOnlineIcon($user["visitId"])); ?><?php echo ($user["name"]); ?></a></div>
                                <?php if(MODULE_NAME != 'Space'){ ?><em><?php echo (friendlyDate($user["cTime"])); ?></em><?php } ?>
                            </li><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
                    </ul>
              </div>
                <div class="btm"></div>
            </div>
    <?php } ?>


    <?php } ?>
    <?php if(MODULE_NAME == "Home"){ ?>
    <div class="UserList">
        <div class="tit"><span class="right"></span>你可能认识的人</div>
        <div class="ListBox">
            <ul>
                <?php if(is_array($may_users)): ?><?php $i = 0;?><?php $__LIST__ = $may_users?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$user): ?><?php ++$i;?><?php $mod = ($i % 2 )?><li>
                        <span><a href="__APP__/space/<?php echo ($user["id"]); ?>" class="tips" rel="__TS__/Index/userInfo/uid/<?php echo ($user["id"]); ?>"><img src="<?php echo (getUserFace($user["id"])); ?>" /></a></span>
                        <div class="name"><a href="__APP__/space/<?php echo ($user["id"]); ?>"><?php echo (isOnlineIcon($user["id"])); ?><?php echo ($user["name"]); ?></a></div>
                    </li><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>

            </ul>
        </div>
        <div class="btm"></div>
    </div>
    <?php } ?>
    <div class="UserList">
        <div class="tit"><?php if(!isset($space_privacy) || $space_privacy){ ?><span class="right"><a href="__APP__/Friend/index/uid/<?php echo ($uid); ?>" class="cGray2">&gt;&gt;更多</a></span><?php } ?><?php echo (getUserWo($uid,$mid)); ?>的好友</div>
        <div class="ListBox">
            <ul>
                <?php if(is_array($u_fris)): ?><?php $i = 0;?><?php $__LIST__ = $u_fris?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$user): ?><?php ++$i;?><?php $mod = ($i % 2 )?><li>
                        <span><?php if(!isset($space_privacy) || $space_privacy){ ?><a href="__APP__/space/<?php echo ($user["id"]); ?>" class="tips" rel="__TS__/Index/userInfo/uid/<?php echo ($user["id"]); ?>"><?php } ?><img src="<?php echo (getUserFace($user["id"])); ?>" /><?php if(!isset($space_privacy) || $space_privacy){ ?></a><?php } ?></span>
                        <?php if(!isset($space_privacy) || $space_privacy){ ?>
                            <div class="name"><a href="__APP__/space/<?php echo ($user["id"]); ?>"><?php echo ($user["name"]); ?></a></div>
                        <?php }else{ ?>
                            <div class="name"><?php echo (isOnlineIcon($user["id"])); ?><?php echo ($user["name"]); ?></div>
                        <?php } ?>
                    </li><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
            </ul>
        </div>
        <div class="btm"></div>
    </div>
</div><!-- cr end  -->

            <div class="cc">
			<?php if($gonggao && $gonggao_open){ ?>
                <div class="ad_info" id='gonggao' style='display:none;'><div class="right"><a href="javascript:void(0)" onclick='closeGongGao(<?php echo ($mid); ?>)' style="display:block;"><img src="../Public/images/ico_del_b.gif" width="9" height="9" alt="关闭" /></a></div><div style="width:506px;"><?php echo ($gonggao); ?></div></div>
			<?php } ?>
                <div class="user_info"><!-- 用户资料 begin  -->
                    <div class="user_img">
                        <span id="my_face"><img src="<?php echo (getUserFace($mid,'middle')); ?>?<?php echo time();?>" /></span>
                        <a href="__APP__/Info/face" class="a" title="更换头像">更换头像</a>
                    </div>
                    <div class="Linfo">
                        <div class="info">
                            <h2 class="h21" onmouseover="this.className='h22'" onmouseout="this.className='h21'" ><div class="progress right" style="display:none;"><a href="#" class="hand cGray2"><span>资料完整度</span><span class="percent"><span style="background-color: #ffd9a0;width: 30%;">&nbsp;</span></span>&nbsp;30%</a> <a href="__APP__/Info" class="U">完善资料</a></div><span id="my_name"><?php echo ($my_name); ?></span> <?php echo (getUserGroupIcon($uid)); ?></h2>
                          <p><span id="my_mini"><?php echo ($my_mini["content"]); ?></span><span id="my_mini_time"><em><?php echo (friendlyDate($my_mini["cTime"])); ?></em></span><span><a href="__ROOT__/apps/mini/index.php?s=/Index/my/">更多</a></span></p>
                        </div>
                        <div class="edit_box">
                            <div class="box1" style="display:none">这里是弹出的内容</div>
                            <div class="alR" style="width:350px; margin-bottom:-5px;"><span id="mb-hint"><b><span id="zishu"><?php echo ($stringcount); ?></span></b>/<?php echo ($stringcount); ?></span></div>
                            <div>
                            <div class="status_edit"><!-- 状态编辑框 begin -->
                                <div>
                                <textarea id="xq_con" name="textarea3" rows="" wrap="virtual" class="WB" onclick='bq_show();' onKeyDown="fot(this)" onKeyUp="fot(this)"></textarea>
                               </div>                               
                              <div class="phiz_box">
                                <div class="phiz" style="display:none;top: 2px;left: 0px;" id="smileylist">
                                    <?php if(is_array($bq_emotion)): ?><?php $k = 0;?><?php $__LIST__ = $bq_emotion?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$bq): ?><?php ++$k;?><?php $mod = ($k % 2 )?><div class="ico_link hand">
                                          <img src="__PUBLIC__/images/biaoqing/<?php echo ($smiletype); ?>/<?php echo ($bq["filename"]); ?>" emotion="<?php echo ($bq["emotion"]); ?>" title="<?php echo ($bq["title"]); ?>" onclick="insertBQ(this,<?php echo ($k-1); ?>);"/>                                    </div><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
                                </div>
                                </div>
                          </div><!-- 状态编辑框 end -->
                           <div class="left pt5 pl5" style="width:75px;"> <input type="button" value="发 布" class="btn_big" onclick="post_mini()"/></div>
                          <div class="c"></div>
                          </div>
                          <div class="submenu" style="display:none;"><a href="#" title="说明文字"><img src="../Public/images/ico_app03_16.gif" alt="图片说明" /></a><a href="#" title="说明文字"><img src="../Public/images/ico_app02_16.gif" alt="图片说明" /></a><a href="#" title="说明文字"><img src="../Public/images/ico_app01_16.gif" alt="图片说明" /></a><a href="#" title="说明文字"><img src="../Public/images/ico_app04_16.gif" alt="图片说明" /></a></div>
                        </div>
                    </div>
                </div><!-- 用户资料 end  -->
                <div class="system_info">
                    <div style="width:23%">
                        <a href="__APP__/Notify/inbox">短消息<span class="<?php if($notify_num['message']){ echo 'cRed fB f14px'; }else{ echo 'cGray2'; } ?> hand"><?php echo ($notify_num["message"]); ?></span>条
                        </a>
                    </div>
                    <div style="width:26%">
                        <a href="__APP__/Notify/index/t/sys">系统通知<span class="<?php if($notify_num['notification']){ echo 'cRed fB f14px'; }else{ echo 'cGray2'; } ?> hand"><?php echo ($notify_num["notification"]); ?></span>条 </a>
                    </div>
                    <div style="width:26%"><a href="__APP__/Notify/index/t/fri" >好友请求<span class="<?php if($notify_num['friend']){ echo 'cRed fB f14px'; }else{ echo 'cGray2'; } ?> hand"><?php echo ($notify_num["friend"]); ?></span>条
                        </a>
                    </div>
                    <div style="width:23%"><a href="__APP__/space/<?php echo ($mid); ?>#wall">留言板<span class="<?php if($notify_num['wall']){ echo 'cRed fB f14px'; }else{ echo 'cGray2'; } ?> hand"><?php echo ($notify_num["wall"]); ?>
                            </span>条</a>
                    </div>
                </div>
                <div class="tab-menu"><!-- 切换标签 begin  -->
                    <div class="right" style="display:none;"><img src="../Public/images/ico_shezhi.gif" /> <a href="#">设置</a></div>
                    <ul>
                        <li><a href="javascript:void(0)" class="on feed_item" rel="all" id="feed_all_item"><span>全部动态</span></a></li>
                        
                        <li><a href="javascript:void(0)" class="feed_item" rel="3"><span>心情</span></a></li>
                        <li><a href="javascript:void(0)" class="feed_item" rel="10"><span>相册</span></a></li>
                        <li><a href="javascript:void(0)" class="feed_item" rel="4"><span>日志</span></a></li>
                        <li><a href="javascript:void(0)" class="feed_item" rel="11"><span>分享</span></a></li>
                        <li><a href="javascript:void(0)" class="feed_item" rel="13"><span>投票</span></a></li> 
                        <li><a href="javascript:void(0)" class="feed_item" rel="14"><span>活动</span></a></li>
                        <li><a href="javascript:void(0)" class="feed_item" rel="12"> <span>群组</span></a></li>
                    </ul>
                </div><!-- 切换标签 end  -->
                <div class="Friend" ><!-- 好友心情 begin  -->
    
                    <div class="FList" id="feed_content">
    
    
                    </div>
    
                    
                    <div class="alR lh35">
                        <a href="###" id='getMoreFeed' class="U">点击查看更多...</a>
                    </div>
                </div><!-- 好友心情 end  -->
            </div>
		</div>
	 <div class="c"></div>
    </div><!-- 画布 end  -->
  <div class="c"></div>
</div><!-- 内容 end -->
<?php if($mid && !$is_im_closed && $site_opts["is_im_open"]){ ?>
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
  Copyright © ThinkSNS <?php echo ($site_opts["icp"]); ?> <a href="http://www.thinksns.com" target="_blank">Powered by <strong style="color:#555; font-family:Verdana, Arial, Helvetica, sans-serif;">Th<span style="color:#00CC00;">i</span>nk<span style="color:#0066FF;">SNS</span>™</strong></a>
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