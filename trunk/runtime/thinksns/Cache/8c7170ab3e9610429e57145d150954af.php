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






<link href="../Public/basic.css" rel="stylesheet" type="text/css" />

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
<div class=page_title> <!-- page_title begin -->
    <h2><img src="../Public/images/contacts.gif" />修改资料</h2>
<div class="c"></div>
</div><!-- page_title end -->
<div class="tab-menu"><!-- 切换标签 begin  -->
    <ul>
        <li><a href="__URL__"><span>基本资料</span></a></li>
        <li><a href="__URL__/intro" class="on"><span>个人情况</span></a></li>
        <li><a href="__URL__/contact"><span>联系方式</span></a></li>
        <li><a href="__URL__/education"><span>教育情况</span></a></li>
        <li><a href="__URL__/career"><span>工作情况</span></a></li>
        <li><a href="__URL__/face"><span>上传头像</span></a></li>
    </ul>
  </div><!-- 切换标签 end  -->
  <div class="data"><!-- 个人情况 begin  -->
    <?php if($_GET["t"] == 1){ ?><center><b><font color="red">修改成功！</font></b></center> <?php } ?>
<form action="__URL__/doIntro" method="post" class="form_validator" onsubmit="return check()">

    <ul>
      <li class="btmline mb10 cGray2"><div class="left alR" style="width: 15%;">&nbsp;</div><div class="left" style="width: 50%;">&nbsp;</div><div class="left" style="width: 20%;">谁可以看见</div><div class="left" style="width: 15%;">在首页显示</div></li>

  


        <?php if(is_array($item)): ?><?php $i = 0;?><?php $__LIST__ = $item?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$vo): ?><?php ++$i;?><?php $mod = ($i % 2 )?><li>
          <div class="left alR" style="width: 15%;"><?php echo ($vo["display"]); ?>：</div><div class="left" style="width: 50%;">
            <input type="text" id="old_<?php echo ($vo['name']); ?>" class="TextH20" style="width:300px;" onfocus="this.className='Text2'" onblur="this.className='TextH20'"  name="<?php echo ($vo["name"]); ?>" value="<?php echo $info[$vo['name']]; ?>"/>
          </div>
          <div class="left" style="width: 20%;">
                    <select id="old_privacy_<?php echo ($vo["name"]); ?>" name="__privacy_<?php echo ($vo["name"]); ?>">
                        <option value="0"  <?php if($privacy[$vo["name"]] == 0){ ?> selected='true' <?php } ?>>任何人</option>                       <option value="1"  <?php if($privacy[$vo["name"]] == 1){ ?> selected='true' <?php } ?>>仅好友</option>                  <option value="2"  <?php if($privacy[$vo["name"]] == 2){ ?> selected='true' <?php } ?>>私密</option>
                    </select>
                </div>
                <div class="left" style="width: 15%;">
                  <input id="old_display_<?php echo ($vo["name"]); ?>" name="__display_<?php echo ($vo["name"]); ?>" type="checkbox" value="1" <?php if($display[$vo["name"]] == 1){ ?> checked="true" <?php } ?>/>
                </div>
            </li><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>

        <?php foreach($add_more as $k=>$vo){ ?>
         <li class="btmline">
                <div class="left alR" style="width: 15%;">
                  <input name="more_item[<?php echo ($k); ?>]" type="text" value="<?php echo ($vo['name']); ?>" class="TextH20" id="old_more_<?php echo ($vo['name']); ?>_<?php echo ($k); ?>" style="width:90px" onBlur="this.className='TextH20'" onFocus="this.className='Text2'" />：</div>
                  <div class="left" style="width: 50%;">
                    <input name="more_con[<?php echo ($k); ?>]" type="text" id="old_more_<?php echo ($vo['name']); ?>" value="<?php echo ($vo['value']); ?>" class="TextH20" style="width:300px;" onfocus="this.className='Text2'" onblur="this.className='TextH20'" />
                </div>

                <div class="left" style="width: 20%;">
                  <select id="old_more_privacy_<?php echo ($vo['name']); ?>" name="__privacy_more[<?php echo ($k); ?>]">
                        <option value="0" <?php if($vo["privacy"] == 0){ ?> selected='true' <?php } ?> >任何人</option>
                        <option value="1" <?php if($vo["privacy"] == 1){ ?> selected='true' <?php } ?> >仅好友</option>
                        <option value="2" <?php if($vo["privacy"] == 2){ ?> selected='true' <?php } ?> >私密</option>
                    </select>
                </div>

                <div class="left" style="width: 15%;">
                  <input id="old_more_display_<?php echo ($vo['name']); ?>" name="__display_more[<?php echo ($k); ?>]" type="checkbox" value="1" class="is_display" <?php if($vo["display"] == 1){ ?> checked="true" <?php } ?> />
                </div>
            </li>
        <?php } ?>
		</ul>
<?php $k=$k+1; ?>
        <ul id="more_list">
            <li id="more_info">
                <div class="left alR" style="width: 15%;">
                <input name="more_item[<?php echo ($k); ?>]" type="text" class="TextH20 item" id="textfield7" style="width:90px" onBlur="this.className='TextH20'" onFocus="this.className='Text2'" onkeyup="change2( $( this ) )"/>：</div>
				<div class="left" style="width: 50%;">
                <input name="more_con[<?php echo ($k); ?>]" type="text" id="textfield6" class="TextH20" style="width:300px;" onfocus="this.className='Text2'" onblur="this.className='TextH20'"  onkeyup="change( $( this ) )" />
                </div>
                <div class="left" style="width: 20%;">
                  <select name="__privacy_more[<?php echo ($k); ?>]" class="__privacy">
                        <option value="0">任何人</option>
                        <option value="1">仅好友</option>
                        <option value="2">私密</option>
                    </select>
                </div>
                <div class="left" style="width: 15%;">
                    <input name="__display_more[<?php echo ($k); ?>]" type="checkbox" value="1" class="is_display"/>
                </div>
            </li>
            
			
        </ul>
		<ul>
        <li  class="topline">
        <div class="left alR" style="width: 15%;">&nbsp;</div><div class="left" style="width: 50%;">
            <input type="submit" class="btn_b" value="保存修改" />
            <input type="button" class="btn_b" value="添 加" onclick="add_more(<?php echo ($k); ?>)" />
        </div>
        <div class="left" style="width: 20%;">&nbsp;</div><div class="left" style="width: 15%;">&nbsp;</div></li>
</ul>

</form>
</div><!-- 个人情况 end  -->


</div><!-- 右侧内容 end  -->


<div class="c"></div>
</div><!-- 内容 end -->

<script>
var key=0;
var hasChange = false;
var hasChange2 = false;
    //添加更多
function add_more(k) {
		if(key==0) key = k+1;
        var more = $("#more_info").clone().find(".TextH20").val("").end().find(".item").attr("name",'more_item['+key+']').end().find('.tcon').attr('name','more_con['+key+']').end().find(".__privacy").attr("name",'__privacy_more['+key+']').end().find(".is_display").attr("checked",false).attr("name",'__display_more['+key+']').end();
        $("#more_list").append(more);
        key=key+1;
    }

function change(_this){
  if(_this.val() != "") {
    hasChange = true;
  }else{
    hasChange = false;
  }
}

function change2(_this){
  if(_this.val() != "") {
    hasChange2 = true;
  }else{
    hasChange2 = false;
  }
}

function check(){
  //if( key !=0  ) return true;


  var item = $("#more_info").find(".item").val();
  var con = $("#more_info").find(".tcon").val();
  var nochange3 = true;
  if( item && con ) nochange3 = false;

  var nochange2 = false;
  <?php foreach( $add_more as $key=>$value ){ ?>
    var old_more_<?php echo ($value['name']); ?> = "<?php echo ($value['value']); ?>";
    var old_more_privacy_<?php echo ($value['name']); ?> = "<?php echo ($value['privacy']); ?>";
    var old_more_display_<?php echo ($value['name']); ?> = "<?php echo ($value['display']); ?>";
  <?php } ?>

    <?php foreach( $add_more as $key=>$value ){ ?>
      if( $( "#old_more_<?php echo ($value['name']); ?>" ).val() == old_more_<?php echo ($value['name']); ?> &&  $( "#old_more_privacy_<?php echo ($value['name']); ?>" ).val()  == old_more_privacy_<?php echo ($value['name']); ?> &&  $( "#old_more_display_<?php echo ($value['name']); ?>" ).attr( 'checked' )  == old_more_display_<?php echo ($value['name']); ?>  && $( "#old_more_<?php echo ($value['name']); ?>_<?php echo ($key); ?>" ).val() == "<?php echo ($value['name']); ?>")
  <?php } ?>
  nochange2 = true;

  <?php foreach( $item as $value ){ ?>
    var old_<?php echo ($value['name']); ?>         = "<?php echo ($info[$value['name']]); ?>";
    var old_privacy_<?php echo ($value['name']); ?> = "<?php echo ($privacy[$value['name']]); ?>";
    var old_display_<?php echo ($value['name']); ?> = "<?php echo ($display[$value['name']]); ?>";
  <?php } ?>

  var nochange = false;
  <?php foreach( $item as $value ){ ?>
    if( $( "#old_<?php echo ($value['name']); ?>" ).val() == old_<?php echo ($value['name']); ?> &&  $( "#old_privacy_<?php echo ($value['name']); ?>" ).val()  == old_privacy_<?php echo ($value['name']); ?> &&  $( "#old_display_<?php echo ($value['name']); ?>" ).attr( 'checked' )  == old_display_<?php echo ($value['name']); ?> )
  <?php } ?>
    nochange = true;
  if( nochange && nochange2 && nochange3 ){
    Alert( "没有修改任何值" );
    return false;
  }else{
    return true;
  }
}
</script>
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