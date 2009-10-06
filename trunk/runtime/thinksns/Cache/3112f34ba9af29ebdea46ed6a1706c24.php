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




<link href="../Public/basic.css" rel="stylesheet" type="text/css" />

<!--日期-->
<script  type="text/javascript" src="__PUBLIC__/js/rcalendar.js" ></script>
<!--所在城市-->
<script type="text/javascript" src="__PUBLIC__/js/form_check/jquery.validator.reg.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/form_check/jquery.corners.min.js"></script>

<!--本页-->
<script type="text/javascript" src="../Public/reg.js" ></script>

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

<?php $info['area'] = getAreaInfo("$info[current_province],$info[current_city]"); ?>
<?php $info['hometown'] = getAreaInfo("$info[home_province],$info[home_city]"); ?>
<?php $birthday = explode('-',$info['birthday']); ?>
 
    <div class="main"> <!-- 右侧内容 begin  -->
        <div class=page_title> <!-- page_title begin -->
            <h2><img src="../Public/images/contacts.gif" />修改资料</h2>
        <div class="c"></div>
</div><!-- page_title end -->
        <div class="tab-menu"><!-- 切换标签 begin  -->
            <ul>
                <li><a href="__URL__" class="on"><span>基本资料</span></a></li>
                <li><a href="__URL__/intro"><span>个人情况</span></a></li>
                <li><a href="__URL__/contact"><span>联系方式</span></a></li>
                <li><a href="__URL__/education"><span>教育情况</span></a></li>
                <li><a href="__URL__/career"><span>工作情况</span></a></li>
                <li><a href="__URL__/face"><span>上传头像</span></a></li>
            </ul>
        </div><!-- 切换标签 end  -->
        <div class="data"><!-- 基本资料 begin  -->
         <br/>
            <?php if($_GET["t"] == 1){ ?><center><b><font color="red">修改成功！</font></b></center> <?php } ?>
            <ul>
                <form action="__URL__/doIndex" method="post" class="form_validator" id="regform">

                <li class="btmline mb10 cGray2"><div class="left alR" style="width: 15%;">&nbsp;</div><div class="left" style="width: 50%;">&nbsp;</div><div class="left" style="width: 20%;">谁可以看见</div><div class="left" style="width: 15%;">在首页显示</div></li>
                <li>
                    <div class="left alR" style="width: 15%;">姓名：</div>
                    <div class="left" style="width: 50%;">
                      <div class="left mr5"><input   require="true" datatype="limit|ajax" min="6" max="12" url="__APP__/Index/checkRealName" msg="请输入真实姓名|请输入真实姓名!" type="text" class="TextH20" style="width:200px;" onfocus="this.className='Text2'" onblur="this.className='TextH20'"  name="name" value="<?php echo $info['name']; ?>" dataType="LimitB" msg="姓名不能为空"/></div>
                      <div class="left"> 
                          <div id="success_name"  style="display:none;"> 
                              <span><img src="../Public/images/fzcg_dh[1].gif" /></span> 
                          </div> 
                          <div class="r error_name" style="position: relative;display:none;"> 
                                  <span><img src="../Public/images/th_ju[1].gif" /></span> 
                                  <span id="error_name" class="cRed"></span>
                          </div> 
                      </div> 
                    </div>

                    <div class="left" style="width: 20%;">
                        <select  disabled="disabled">
                            <option value="0">任何人</option>
                            <option value="1">仅好友</option>
                            <option value="2">私密</option>
                            谁可以看见
                     </select>
                     <input type="hidden" name="__privacy_name" value ="0">
                    </div><div class="left" style="width: 15%;">
                        <input name="" type="checkbox" value="" checked="checked" disabled="disabled" />
                        <input type="hidden" name="__display_name" value ="1">
                    </div>
                </li>
                <li>
                    <div class="left alR" style="width: 15%;">性别：</div><div class="left" style="width: 50%;">
                        <label><input name="sex" type="radio" value="1" <?php if($info["sex"] == 1){ ?> checked="true" <?php } ?>/> 男</label> &nbsp;&nbsp;&nbsp;
                        <label><input name="sex" type="radio" value="0" <?php if($info["sex"] == 0){ ?> checked="true" <?php } ?> dataType="Group"  msg="必须选定一个性别" /> 女</label>
                    </div>
                    <div class="left" style="width: 20%;">
                        <select name="" disabled="disabled">
                            <option value="0">任何人</option>
                            <option value="1">仅好友</option>
                            <option value="2">私密</option>
                            谁可以看见
                    </select>
                    <input type="hidden" name="__privacy_sex" value ="0">
                    </div><div class="left" style="width: 15%;">
                    <input name="__display_sex" type="checkbox" value="1" <?php if($display["sex"] == 1){ ?> checked="true" <?php } ?>/></div>
                </li>
                <li>
                    <div class="left alR" style="width: 15%;">出生日期：</div>                       
                    <div class="left" style="width: 50%;">
                                              <select name="birthday_year" id="birthday_year" >
                        	<?php for($i=1930;$i<=2009;$i++){ ?>
                        		<?php if($i==$birthday[0]){ ?>
                        			<option value="<?php echo ($i); ?>" selected="selected"><?php echo ($i); ?></option>
                        		<?php }else{ ?>
                        			<option value="<?php echo ($i); ?>" ><?php echo ($i); ?></option>
                        		<?php } ?>
                        	<?php } ?>
                        </select>年 
                        <select name="birthday_month" id="birthday_month" >

                        	<?php for($i=1;$i<=12;$i++){ ?>
                     			<?php if($i==$birthday[1]){ ?>
                        			<option value="<?php echo ($i); ?>" selected="selected"><?php echo ($i); ?></option>
                        		<?php }else{ ?>
                        			<option value="<?php echo ($i); ?>"><?php echo ($i); ?></option>
                        		<?php } ?>
                        	<?php } ?>
                        </select>月 
                        <select name="birthday_day" id="birthday_day" >
                        	<?php for($i=1;$i<=31;$i++){ ?>
                     			<?php if($i==$birthday[2]){ ?>
                        			<option value="<?php echo ($i); ?>" selected="selected"><?php echo ($i); ?></option>
                        		<?php }else{ ?>
                        			<option value="<?php echo ($i); ?>"><?php echo ($i); ?></option>
                        		<?php } ?>
                        	<?php } ?>
                        </select>日
                   </div>
                    <div class="left" style="width: 20%;"><select name="__privacy_birthday">
                            <option value="0"  <?php if($privacy["birthday"] == 0){ ?> selected='true' <?php } ?>>任何人</option>
                            <option value="1"  <?php if($privacy["birthday"] == 1){ ?> selected='true' <?php } ?>>仅好友</option>
                            <option value="2"  <?php if($privacy["birthday"] == 2){ ?> selected='true' <?php } ?>>私密</option>
                            谁可以看见
                    </select></div><div class="left" style="width: 15%;">
                        <input name="__display_birthday" type="checkbox" value="1" <?php if($display["birthday"] == 1){ ?> checked="true" <?php } ?> /></div>
                </li>
                <!--
                <li>
                    <div class="left alR" style="width: 15%;">星座：</div>                       
                    <div class="left" style="width: 50%;"><select name="birthday_stro" disabled><option id="astro">&nbsp;</option></select>                   </div>
                    <div class="left" style="width: 20%;"><select name="__privacy_birthday">
                            <option value="0"  <?php if($privacy["birthday"] == 0){ ?> selected='true' <?php } ?>>任何人</option>
                            <option value="1"  <?php if($privacy["birthday"] == 1){ ?> selected='true' <?php } ?>>仅好友</option>
                            <option value="2"  <?php if($privacy["birthday"] == 2){ ?> selected='true' <?php } ?>>私密</option>
                            谁可以看见
                    </select></div><div class="left" style="width: 15%;">
                        <input name="__display_birthday" type="checkbox" value="1" <?php if($display["birthday"] == 1){ ?> checked="true" <?php } ?> /></div>
                </li>
                -->

                <!--<li>
                    <div class="left alR" style="width: 15%;">目前身份：</div><div class="left" style="width: 50%;">
                        <label>
                            <input name="status" type="radio" value="2"  onclick="work_check();" <?php if($info["status"] == 2){ ?>  checked="true" <?php } ?>/>已工作
                        </label>　
                        <label>
                            <input  name="status" value="1" type="radio" onclick="school_check();" <?php if($info["status"] == 1){ ?> checked="true" <?php } ?>/>学生
                        </label>
                        <label>
                            <input  name="status" value="0" type="radio"  onclick="other_check();" <?php if($info["status"] == 0){ ?> checked="true" <?php } ?>/> 其他
                        </label>
                        <script>
                            $(function () {
                                    <?php switch($info["status"]){
                                    case 0: echo "other_check();";  break;
                                    case 1: echo "school_check();";  break;
                                    case 2: echo "work_check();";  break;
                                } ?>
                                });

                        </script>

                    </div>
                    <div class="left" style="width: 20%;">
                        <select name="__privacy_status">
                            <option value="0"  <?php if($privacy["status"] == 0){ ?> echo 'selected=true'; <?php } ?>>任何人</option>
                            <option value="1"  <?php if($privacy["status"] == 1){ ?> echo 'selected=true'; <?php } ?>>仅好友</option>
                            <option value="2"  <?php if($privacy["status"] == 2){ ?> echo 'selected=true'; <?php } ?>>私密</option>
                            谁可以看见
                    </select></div><div class="left" style="width: 15%;">
                        <input name="__display_status" type="checkbox" value="1" <?php if($display["status"] == 1){ ?> checked="true" <?php } ?>/></div>
                </li>

                <li style="display:none;" id="work_check" class="the_check">
                    <div class="left alR" style="width: 15%;">工作单位：</div><div class="left" style="width: 50%;">
                        <input id="work_name" type="text" class="text1" style="width:250px;" onBlur="this.className='text1'" onFocus="this.className='text2'" name="company" value="<?php echo ($info["company"]); ?>"/>
                    </div>
                    <div class="left" style="width: 20%;"></div>
                    <div class="left" style="width: 15%;"></div>
                </li>
                <li style="display:none;" id="school_check" class="the_check">
                    <div class="left alR" style="width: 15%;">所在学校：</div><div class="left" style="width: 50%;">
                        <input type="text" class="text1" style="width:250px;" onBlur="this.className='text1'" onFocus="this.className='text2'" onclick="$.facebox.popup('请选择大学','__URL__/school');" name="school"  id="school_name" value="<?php echo ($info["shool"]); ?>"/>
                    </div>
                    <div class="left" style="width: 20%;"></div>
                    <div class="left" style="width: 15%;"></div>
                </li>

                -->
                <li>
                    <div class="left alR" style="width: 15%;">居住城市：</div><div class="left" style="width: 50%;">
                                        <input type="hidden" name="ts_areaval" id="ts_areaval" value="<?php echo ($info["ts_areaval"]); ?>"/>
						<input type="text" class="TextH20" style="width:200px;" onfocus="this.className='Text2'" onblur="this.className='TextH20'" value="<?php echo ($info["area"]); ?>" name="areaval" disabled  id="areaval" require="true" datatype="require" msg="请输入你的居住城市！"/> <input type="button" class="btn_b" value="选择地区" selectArea="true" areatype="areaval" >

                   </div>
                    <div class="left" style="width: 20%;">
                        <select name="__privacy_ts_areaval">
                            <option value="0" <?php if($privacy["ts_areaval"] == 0){ ?> selected="true" <?php } ?>>任何人</option>
                            <option value="1" <?php if($privacy["ts_areaval"] == 1){ ?> selected="true" <?php } ?>>仅好友</option>
                            <option value="2" <?php if($privacy["ts_areaval"] == 2){ ?> selected="true" <?php } ?>>私密</option>
                            谁可以看见
                    </select></div><div class="left" style="width: 15%;">
                        <input name="__display_ts_areaval" type="checkbox" value="1"  <?php if($display["ts_areaval"] == 1){ ?> checked="true" <?php } ?>/></div>
                </li>

                <li>
                    <div class="left alR" style="width: 15%;">血型：</div>
                        <div class="left" style="width: 50%;">
                        	<select name="bloodtype">
                        		<option value="O型">O型</option>
                        		<option value="A型">A型</option>
                        		<option value="B型">B型</option>
                        		<option value="AB型">AB型</option>
                        		<option value="AB型">稀有血型</option>
                        	</select>    
                   		</div>
                    <div class="left" style="width: 20%;">
                        <select name="__privacy_bloodtype">
                            <option value="0" <?php if($privacy["bloodtype"] == 0){ ?> selected="true" <?php } ?>>任何人</option>
                            <option value="1" <?php if($privacy["bloodtype"] == 1){ ?> selected="true" <?php } ?>>仅好友</option>
                            <option value="2" <?php if($privacy["bloodtype"] == 2){ ?> selected="true" <?php } ?>>私密</option>
                            谁可以看见
                    </select></div><div class="left" style="width: 15%;">
                        <input name="__display_bloodtype" type="checkbox" value="1"  <?php if($display["bloodtype"] == 1){ ?> checked="true" <?php } ?>/></div>
                </li>                

                <li>
                    <div class="left alR" style="width: 15%;">家乡：</div><div class="left" style="width: 50%;">
                                        <input type="hidden" name="ts_hometown" id="ts_hometown" value="<?php echo ($info["ts_hometown"]); ?>"/>
						<input type="text" class="TextH20" style="width:200px;" onfocus="this.className='Text2'" onblur="this.className='TextH20'" value="<?php echo ($info["hometown"]); ?>" disabled name="hometown" id="hometown"/>  <input type="button" class="btn_b" value="选择地区" selectArea="true" areatype="hometown" >

                   </div>
                    <div class="left" style="width: 20%;">
                        <select name="__privacy_ts_hometown">
                            <option value="0" <?php if($privacy["ts_hometown"] == 0){ ?> selected="true" <?php } ?>>任何人</option>
                            <option value="1" <?php if($privacy["ts_hometown"] == 1){ ?> selected="true" <?php } ?>>仅好友</option>
                            <option value="2" <?php if($privacy["ts_hometown"] == 2){ ?> selected="true" <?php } ?>>私密</option>
                            谁可以看见
                    </select></div><div class="left" style="width: 15%;">
                        <input name="__display_ts_hometown" type="checkbox" value="1"  <?php if($display["ts_hometown"] == 1){ ?> checked="true" <?php } ?>/></div>
                </li>


                <li><div class="left alR" style="width: 15%;">&nbsp;</div><div class="left" style="width: 50%;"><input type="submit" class="btn_b" value="保存修改" />
                </div><div class="left" style="width: 20%;">&nbsp;</div><div class="left" style="width: 15%;">&nbsp;</div></li>
            </form> 
           </ul>


        </div><!-- 基本资料 end  -->







<script>
$(document).ready(function()
{
	var bithday='<?php echo ($info["birthday"]); ?>'.split('-');
	var v_astro = getAstro(bithday[1],bithday[2]);
	$("#astro").html(v_astro);
	$("#astro").val(v_astro);
});


function adjustAstro()
{
	var Month = $('#birthday_month').val();
	var day = $('#birthday_day').val();	
	var v_astro = getAstro(Month,day);
	$("#astro").html(v_astro);
	$("#astro").val(v_astro);
}


function getAstro(v_month, v_day)
{
//	v_month = parseInt(v_month , 10)
//	v_day = parseInt(v_day , 10);
	
	if ((v_month == 12 && v_day >= 22)
		|| (v_month == 1 && v_day <= 20))
	{
		return "魔羯座";
	}
	else if ((v_month == 1 && v_day >= 21)
		|| (v_month == 2 && v_day <= 19))
	{
		return "水瓶座";
	}
	else if ((v_month == 2 && v_day >= 20)
		|| (v_month == 3 && v_day <= 20))
	{
		return "双鱼座";
	}
	else if ((v_month == 3 && v_day >= 21)
		|| (v_month == 4 && v_day <= 20))
	{
		return "白羊座";
	}
	else if ((v_month == 4 && v_day >= 21)
		|| (v_month == 5 && v_day <= 21))
	{
		return "金牛座";
	}
	else if ((v_month == 5 && v_day >= 22)
		|| (v_month == 6 && v_day <= 21))
	{
		return "双子座";
	}
	else if ((v_month == 6 && v_day >= 22)
		|| (v_month == 7 && v_day <= 22))
	{
		return "巨蟹座";
	}
	else if ((v_month == 7 && v_day >= 23)
		|| (v_month == 8 && v_day <= 23))
	{
		return "狮子座";
	}
	else if ((v_month == 8 && v_day >= 24)
		|| (v_month == 9 && v_day <= 23))
	{
		return "处女座";
	}
	else if ((v_month == 9 && v_day >= 24)
		|| (v_month == 10 && v_day <= 23))
	{
		return "天秤座";
	}
	else if ((v_month == 10 && v_day >= 24)
		|| (v_month == 11 && v_day <= 22))
	{
		return "天蝎座";
	}
	else if ((v_month == 11 && v_day >= 23)
		|| (v_month == 12 && v_day <= 21))
	{
		return "射手座";
	}
	return "";
}

</script>
    </div><!-- 右侧内容 end  -->

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