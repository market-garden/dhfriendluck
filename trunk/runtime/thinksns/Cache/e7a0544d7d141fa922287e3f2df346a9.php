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



<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../Public/space.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../Public/space_index.js"></script>
<script type="text/javascript" src="../Public/feed.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/jquery.scrollTo.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/Share.js"></script>
<style>
        .ta_wqfw {
                background:transparent url(../Public/images/privacy_bg.gif) no-repeat scroll center top;
                font-size:14px;
                height:60px;
                overflow:hidden;
                padding:25px 0 0 75px;
        }
</style>
<link href="__PUBLIC__/js/pagination/pagination.css" rel="stylesheet" type="text/css" />

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


        <div class="main"> <!-- 右侧内容 begin  -->

                
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
                        <div class="user_info"><!-- 用户资料 begin  -->
                                <div class="user_img">
                                        <div class="img" id="host_face"><img src="<?php echo (getUserFace($uid,'middle')); ?>" /></div>
                                        <div id="my_face" style="display:none"><img src="<?php echo (getUserFace($mid,'small')); ?>" /></div>
                                        <div class="menu bg01">
                                                <?php if($uid == $mid){ ?>
                                                <a href="__APP__/Info/face" title="发短消息">更改头像</a>
                                                <a href="__TS__/Privacy">隐私设置</a>
                                                <a href="__TS__/Account">修改账号</a>
                                                <a href="__TS__/Info">修改资料</a>
                                                <?php }elseif($mid){ ?>
                                                <?php if( $is_friend ){ ?>
                                                <?php if( $space_privacy ){ ?>
                                                <a href="__APP__/Space/detail/uid/<?php echo ($uid); ?>" title="详细资料">详细资料</a>
                                                <a href='javascript:go_wall()' title="给<?php echo ($show_sex); ?>留言">给<?php echo ($show_sex); ?>留言</a>
                                                <?php } ?>
                                                <a href="__APP__/Notify/write/uid/<?php echo ($uid); ?>" title="发短消息">发短消息</a>
                                                <a href="javascript:void(0);" onclick="ts_sharePop('<?php echo ($uid); ?>','__URL__')" title="分享<?php echo ($show_sex); ?>">分享好友</a>
                                                <?php }else{ ?>
                                                <?php if( $space_privacy ){ ?>
                                                <a href="__APP__/Space/detail/uid/<?php echo ($uid); ?>" title="详细资料">详细资料</a>
                                                <a href='javascript:go_wall()' title="给<?php echo ($show_sex); ?>留言">给<?php echo ($show_sex); ?>留言</a>
                                                <?php } ?>
                                                <a href="__APP__/Notify/write/uid/<?php echo ($uid); ?>" title="发短消息">发短消息</a>
                                                <a href="javascript:Win({message:'__APP__/Friend/isAdd/uid/<?php echo $uid; ?>',width:392,height:220,title:'加为好友',handler:handlerIframe,autoClose:false,iframe:true,allowRightMenu:true});" title="加为好友">加为好友</a>
                                                <?php } ?>
                                                <?php } ?>
                                                <div class="c"></div>
                                        </div>
                                </div>
                                <div class="Linfo">
                                        <div class="info">
                                                <h2 id="host_name"><?php echo (getUserName($uid)); ?> <?php echo (getUserGroupIcon($uid)); ?></h2>
                                                <h2 id="my_name" style="display:none"><?php echo ($my_name); ?></h2>
                                                <?php if($mid != $uid){
                                                                $href = "__ROOT__/apps/mini/index.php?s=/Index/friends/uid/".$the_mini['uid'];
                                                                }else{
                                                                $href = "__ROOT__/apps/mini/index.php?s=/Index/my";
                                                                } ?>
                                                <?php if($space_privacy){ ?><p><span><?php echo ($the_mini["content"]); ?></span><span><em><?php echo (friendlyDate($the_mini["cTime"])); ?></em></span><span><a href="<?php echo ($href); ?>">更多</a></span></p><?php } ?>

                                                <?php if($space_privacy){ ?>     <!--隐私控制-->
                                                <ul>
                                                        <?php if(!empty($rank)){ ?><li><span class="l cGray2">等级：</span><span class="r cBlue" style="margin-top:6px;"><img src="<?php echo THEME_URL; ?>/images/group/<?php echo ($rank['icon']); ?>" title="<?php echo ($rank['name']); ?>" alt="<?php echo ($rank['name']); ?>"/></span></li><?php } ?>
                                                        <?php if(is_array($credit)): ?><?php $i = 0;?><?php $__LIST__ = $credit?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$vo): ?><?php ++$i;?><?php $mod = ($i % 2 )?><li><span class="l cGray2"><?php echo ($key); ?>：</span><span class="r cBlue"><?php echo ($vo); ?></span></li><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
                                                </ul>
                                                <ul>
                                                        <?php foreach($userInfo as $k=>$v){ ?>
                                                        <li><span class="l cGray2"><?php echo ($k); ?>：</span><span class="r cBlue"><?php echo ($v); ?></span></li>
                                                        <?php } ?>
                                                </ul>
                                                <?php } ?>
                                        </div>
                                </div>
                        </div><!-- 用户资料 end  -->
                        <!--用户应用-->
                        <div class="system_info">
                                <?php if(is_array($user_apps)): ?><?php $i = 0;?><?php $__LIST__ = $user_apps?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$vo): ?><?php ++$i;?><?php $mod = ($i % 2 )?><?php $app_num = isset( $apps_num[$vo['enname']] )?$apps_num[$vo['enname']]:0;
			   $vo['name'] = '相册' == $vo['name'] ? '相片':$vo['name']; ?>
                                        <span><img src="<?php echo ($vo["icon"]); ?>" /><a href="<?php echo ($vo["uid_url"]); ?><?php echo ($uid); ?>"><?php echo ($app_num); ?>个<?php echo ($vo["name"]); ?></a></span><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
                        </div>
                        <!--用户应用end-->

                        <?php if(!$space_privacy){ ?>
                        <br/>
                        <?php if($is_hide){ ?>
                        <div style="display: block;" class="ta_wqfw" id="limitdiv"><?php echo (getUserName($uid)); ?>的个人主页目前处于隐藏状态。</div>
                        <?php }else{ ?>
                        <div style="display: block;" class="ta_wqfw" id="limitdiv">由于对方的隐私设置，你没有权限查看。</div>
                        <?php } ?>


                        <?php } ?>


                        <?php if($space_privacy){ ?>     <!--隐私控制-->

                        <div class="Feed"><!-- 个人动态 begin  -->
                                <div class="tab-menu"><!-- 切换标签 begin  -->
                                        <div class="right lh35"><a href='__APP__/Home/allFeed/type/all/uid/<?php echo ($uid); ?>'>查看全部动态</a></div>
                                        <ul>
                                                <li><a class="on feed_item" ><span><?php echo ($show_sex); ?>的动态</span></a></li>
                                        </ul>
                                </div><!-- 切换标签 end  -->
                                <div class="FList">
                                        <?php if(empty($fri_feeds)){ ?>
<div class="cGray2 lh35 alC f14px" style="margin:20px 0">暂时没有动态</div>
<?php }else{ ?>

<?php if(is_array($fri_feeds)): ?><?php $i = 0;?><?php $__LIST__ = $fri_feeds?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$feed): ?><?php ++$i;?><?php $mod = ($i % 2 )?><div class="Fli" id="a_feed_<?php echo ($feed["id"]); ?>">
        <div class="ico_img"><img src="<?php echo ($feed["icon"]); ?>" /></div>
        <div class="LC" style="overflow:hidden;">
            <!--动态title-->
            <div class="tit" alt="<?php echo ($feed["id"]); ?>" id="feed_title_<?php echo ($feed["id"]); ?>">
                <div class="cl"><?php echo ($feed["title"]); ?></div>

                <div class="cr">
                    <div class="lh20"><em><?php echo (friendlyDate($feed["cTime"],'month')); ?></em></div>
                    <?php if(MODULE_NAME == "Home" || ($mid == $uid) ){ ?>
                        <?php if(MODULE_NAME == "Home"){ ?>
                            <div class="pt5"> <a href="javascript:del_feed(<?php echo ($feed["id"]); ?>);" class="del">删除</a></div>
                        <?php }else{ ?>
                            <div class="pt5"> <a href="javascript:del_feed(<?php echo ($feed["id"]); ?>,1);" class="del">删除</a></div>
                        <?php } ?>
                    <?php } ?>
                </div>

                <div class="c"></div>
            </div>
            <!--end-->
            <!--动态body-->
            <div class="RC" id="feed_body_<?php echo ($feed["id"]); ?>">
                <div <?php if($feed["type"] == "mini"){ ?> class="bg_huifu" <?php } ?> >
                    <?php if($feed['type'] == 'mini'){ ?>
                    
<input id="mini_comm_num_<?php echo ($feed['id']); ?>" value="<?php echo ($feed['comment_num']); ?>" type="hidden">
<div id="mini_comment_item_<?php echo ($feed['id']); ?>">
  <?php if(is_array($feed['comment'])): ?><?php $k = 0;?><?php $__LIST__ = $feed['comment']?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$comm): ?><?php ++$k;?><?php $mod = ($k % 2 )?><?php if($k ==2 && $feed["comment_num"] != 2){ ?>
    <span id="feed_comm_middle_<?php echo ($feed['id']); ?>">
    <div class="RLI bg01 btmline"><a href="javascript:getOtherComm(<?php echo ($feed['id']); ?>)" id="zhan_kai_<?php echo ($feed['id']); ?>">显示全部<?php echo ($feed["comment_num"]); ?>条</a></div>
    </span>
    <?php } ?>
    <div class="RLI bg01 btmline">
      <div class="user_img"><a href="__APP__/space/<?php echo ($comm["uid"]); ?>"><img src="<?php echo (getUserFace($comm["uid"])); ?>" /></a></div>
      <div class="RLC">
        <h4><a href="__APP__/space/<?php echo ($comm["uid"]); ?>"><strong><?php echo (getUserName($comm["uid"])); ?></strong></a><span class="cGray2"><?php echo (friendlyDate($comm["cTime"])); ?></span></h4>
        <p><?php echo ($comm["comment"]); ?><a href="javascript:huifu_other(<?php echo ($feed['id']); ?>,<?php echo ($comm["uid"]); ?>,'<?php echo (getUserName($comm["uid"])); ?>');">回复</a></p>
      </div>
      <div class="c"></div>
    </div><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
</div>
<div class="RLI bg01" id="post_con_<?php echo ($feed['id']); ?>" style="display:none;">
  <div class="user_img"><img src="<?php echo (getUserFace($mid)); ?>" /></div>
  <div class="RLC">
    <div class="input_box" style="width:370px">
      <textarea name="textarea" cols="" style="height:50px; line-height:18px; width:368px;"  id="mini_con_<?php echo ($feed['id']); ?>" onblur="blur_mini_con(<?php echo ($feed['id']); ?>);"></textarea>
      <input type="button" class="btn_b" value="回 复" onclick="post_mini_con(<?php echo ($feed['id']); ?>);"/>
    </div>
  </div>
  <div class="c"></div>
</div>
<div class="input_box bg01" id="small_con_<?php echo ($feed['id']); ?>">
  <textarea name="textarea2" cols="" rows="3" style="height:25px; line-height:25px; margin:5px; width:412px;" class="cGray2" onclick="pre_comment(this,<?php echo ($feed['id']); ?>);"> 添加回复</textarea>
</div>
<input type="hidden" id="hf_other_uid_<?php echo ($feed['id']); ?>">
 <?php echo ($feed["body"]); ?>
                    <?php }else{ ?>
                    <?php echo (html_output2($feed["body"])); ?>
                    <?php } ?>
                </div>
                <div class="c"></div>

            </div>
            <!--end-->
        </div>

    </div><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
<?php } ?>
<input type='hidden' id='feed_type' value="<?php echo ($type); ?>">


                                </div>
                                <div class="alR lh35"><a href='__APP__/Home/allFeed/type/all/uid/<?php echo ($uid); ?>'>查看全部动态</a></div>
                        </div><!-- 个人动态 end  -->


                        <?php if($wall_privacy){ ?>
                        <div class="Guestbook" id="wall"><!-- 留言板 begin  -->
                                <div class="tit"><span class="pl5">留言板</span></div>
                                <div class="GB_box">
                                        <textarea name="textarea2" cols="" id="wall_con"></textarea>
                                        <input type="button" class="btn_b" value="留 言" id="sub_button" onclick="wall()"/>
                                        (每条最多2000字)     
                                        <label><input type="checkbox" name="privacy" id="wall_privacy" value="1"/>悄悄话</label>
                                        <input type="hidden" name="uid" id="space_uid" value="<?php echo ($uid); ?>">
                                        <input type="hidden" id="my_name2" value="<?php echo ($my_name); ?>">
                                        <span style="display:none" id="my_face2"><img src="<?php echo (getUserFace($mid,'middle')); ?>" /></span>
                                </div>
                        </div>
                        <div class="GBList" id="list_wall">

                                <?php if(is_array($my_walls['data'])): ?><?php $i = 0;?><?php $__LIST__ = $my_walls['data']?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$wall): ?><?php ++$i;?><?php $mod = ($i % 2 )?><div class="Gli" id="wall_item_<?php echo ($wall["id"]); ?>">
                                                <div class="user_img"><span class="headpic50"><a href='__TS__/space/<?php echo ($wall["fromUserId"]); ?>' class="tips" rel="__TS__/Index/userInfo/uid/<?php echo ($wall["fromUserId"]); ?>"><img src="<?php echo (getUserFace($wall["fromUserId"])); ?>" /></a></span></div>
                                                <div class="LC">
                                                        <div class="MC">
                                                                <h4 class="tit_Critique lh25 mb5 pl5"><span class="right"><a href="javascript:wall_reply_dis(<?php echo ($wall["id"]); ?>)">回复</a>
                                                                                <?php if($mid == $uid || $mid == $wall["fromUserId"]){ ?>&nbsp;<a href="javascript:wall_del(<?php echo ($wall["id"]); ?>)">删除</a>
                                                                                <?php } ?></span><a href="__TS__/space/<?php echo ($wall["fromUserId"]); ?>"><strong><?php echo ($wall["fromUserName"]); ?></strong></a><span class="cGray2"><?php echo (friendlyDate($wall["cTime"],"full")); ?></span><span><?php if($wall["privacy"] == 1){ ?><font color="red">【悄悄话】</font><?php } ?></span></h4>
                                                                <p class="WB">
                                                                        <?php echo (textarea_output($wall["content"])); ?>
                                                                </p>
                                                                <a id="d-<?php echo ($vo["id"]); ?>"href='###' onclick="deleteMini('<?php echo ($vo["id"]); ?>')" class="del" title="删除" style="display:none;">删除</a>            </div>
                                                        <div class="RC">
                                                                <span id="wall_reply_list_<?php echo ($wall["id"]); ?>">
                                                                        <?php if(is_array($wall['replys'])): ?><?php $i = 0;?><?php $__LIST__ = $wall['replys']?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$reply): ?><?php ++$i;?><?php $mod = ($i % 2 )?><div class="RLI">
                                                                                        <div class="user_img"><span class="pic38"><a href="__APP__/space/<?php echo ($reply["fromUserId"]); ?>" class="tips" rel="__TS__/Index/userInfo/uid/<?php echo ($reply["fromUserId"]); ?>" ><img src="<?php echo (getUserFace($reply["fromUserId"])); ?>" /></a></span></div>
                                                                                        <div class="RLC">
                                                                                                <h4 class="tit_Critique lh20 mb5 pl5"> <a href="__APP__/space/<?php echo ($reply["fromUserId"]); ?>"><strong><?php echo ($reply["fromUserName"]); ?></strong></a><span class="cGray2"><?php echo (friendlyDate($reply["cTime"],"full")); ?></span></h4>
                                                                                                <p><?php echo (textarea_output($reply["content"])); ?></p>
                                                                                        </div>
                                                                                        <div class="c"></div>
                                                                                </div><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
                                                                </span>



                                                                <div class="RLI bg01" style="display:none" id="wall_reply_<?php echo ($wall["id"]); ?>">
                                                                        <div class="user_img"><span class="pic38"><img src="<?php echo (getUserFace($mid)); ?>" /></span></div>
                                                                        <div class="RLC">
                                                                                <div class="input_box">
                                                                                        <textarea name="textarea" cols="" style="height:50px; line-height:18px; width:99%" id="wall_reply_con_<?php echo ($wall["id"]); ?>"></textarea>
                                                                                        <?php if($wall["privacy"] == 1){ ?>
                                                                                        <input type="checkbox" name="privacy" id="wall_privacy_<?php echo ($wall["id"]); ?>" value="1" checked="true" disabled="true"/>悄悄话
                                                                                        <?php } ?>
                                                                                        <input type="button" id="reply_button" class="btn_b mt5" value="回 复" onclick="wall_reply(<?php echo ($wall["id"]); ?>)"/><input type="button" class="btn_w mt5" value="取 消" onclick="wall_reply_cancel(<?php echo ($wall["id"]); ?>)"/>
                                                                                </div>
                                                                        </div>
                                                                        <div class="c"></div>
                                                                </div>

                                                        </div>
                                                </div>
                                                <div class="c"></div>
                                        </div><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
                                <div id="Pagination" class="pagination"><?php echo ($my_walls['html']); ?></div>
                        </div>
                        <!-- 留言板 end  -->
                        <?php } ?>


                        <?php } ?> <!--隐私控制end-->

                </div>


                <div class="c"></div>
        </div><!-- 右侧内容 end  -->

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