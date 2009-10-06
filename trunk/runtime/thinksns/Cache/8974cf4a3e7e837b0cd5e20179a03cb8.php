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
<script>
    //删除教育信息
    function del_school(id) {
        Confirm({message:'确定删除这条教育信息？',handler:function(tp){
                if(tp=='ok'){
                    $.post(URL+"/delEdu",{id:id,type:'education'},function(txt){
                        if(txt){
                            $("#school_"+id).hide("slow");
                            location.reload();
                        }else{
                            Alert("删除失败!");
                        }
                    });
                }
                if(tp=='cancel'){
                    ymPrompt.close();
                }
                if(tp=='close'){
                    ymPrompt.close();
                }
            }});
    }
</script>

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
                <li><a href="__URL__/intro"><span>个人情况</span></a></li>
                <li><a href="__URL__/contact"><span>联系方式</span></a></li>
                <li><a href="__URL__/education" class="on"><span>教育情况</span></a></li>
                <li><a href="__URL__/career"><span>工作情况</span></a></li>
                <li><a href="__URL__/face"><span>上传头像</span></a></li>
            </ul>
        </div><!-- 切换标签 end  -->


        <div class="data"><!-- 教育情况 begin  -->
                        
            <div class="mt10">
                <h2 class="lh35 f14px btmline"><strong>你的教育情况一览</strong>:</h2>
					<?php if(empty($info)){ ?>
                    
                <div class="cGray2 lh30" style="padding:0 0 50px 0">暂无教育信息，立刻添加!</div>
                <?php }else{ ?>
                <ul>
                    <li class="btmlineD">
                        <DIV class="left cGray2" style="width:200px;">&nbsp;</DIV>
                        <DIV class="left cGray2" style="width: 80px;">入学年份</DIV>
                        <DIV class="left cGray2" style="PADDING-LEFT: 55px; width:120px;">谁可以看见</DIV>
                        <DIV class="left cGray2" style="width:100px;">在首页显示</DIV>
                        <DIV class="left cGray2"></DIV>
                    </li>
                    <form action="__URL__/doSetEdu" method="post" class="form_validator">
                        <?php for($i=0;$i<count($info);$i++){ ?>
                            <li class="btmlineD" id="school_<?php echo ($vo["id"]); ?>">
                                <DIV class="left" style="width:200px;"><?php echo ($info[$i]); ?>  <?php echo ($class[$i]); ?></DIV>
                                <DIV class="left" style="width: 80px;"><?php echo ($year[$i]); ?></DIV>
                                
                                <DIV class="left" style="PADDING-LEFT: 55px; width:120px;"><span class="left" style="width:100px;">
                                        <select name="__privacy_school[<?php echo ($id[$i]); ?>]">
                                            <option value="0" <?php if($privacy[$i] == 0){ ?> selected="true" <?php } ?>>任何人</option>
                                            <option value="1" <?php if($privacy[$i] == 1){ ?> selected="true" <?php } ?>>仅好友</option>
                                            <option value="2" <?php if($privacy[$i] == 2){ ?> selected="true" <?php } ?>>私密</option>
                                        </select>
                                </span></DIV>
                                <DIV class="left" style="width:100px;">
                                    <input name="__display_school[<?php echo ($id[$i]); ?>]" type="checkbox" value="1"  <?php if($display[$i] == 1){ ?> checked="true" <?php } ?>/>
                                </DIV>
                                <DIV class="left"><a href="javascript:del_school(<?php echo ($id[$i]); ?>)">[删除]</a></DIV>
                            </li>
                            
                        <?php } ?>
                                                    <input type="hidden" name="type" value="education">
                        <li><input type="submit" class="btn_b" value="保存设置" />
                        </li>
                    </form>

                </ul>
             <?php } ?>

            </div>
   
            <div>
                <h2 class="lh35 f14px btmline"><strong>添加教育情况：</strong></h2>
                <form action="__URL__/doEdu" method="post" class="form_validator">


                    <ul>
                        <li>
                            <DIV class="left alR" style="width:15%;">学校名称：</DIV>
                            <DIV class="left cGray2" style="width:85%;">
                                <input name="school" type="text" class="TextH20" id="textfield12" style="width:200px;" onBlur="this.className='TextH20'" onFocus="this.className='Text2'" dataType="LimitB" min="1" max="300" msg="学校名称不能为空!" />
                            </DIV>
                            <DIV class="left cGray2"></DIV>
                        </li>
                        <li>
                            <DIV class="left alR" style="width:15%;">专业：</DIV>
                            <DIV class="left" style="width: 85%;">
                                <input name="class" type="text" class="TextH20" id="textfield13" style="width:200px;" onBlur="this.className='TextH20'" onFocus="this.className='Text2'" dataType="LimitB" min="1" max="300" msg="班级名称不能为空!" />
                            </DIV>
                        </li>
                        <li>
                            <DIV class="left alR" style="width:15%">入学年份：</DIV>
                            <DIV class="left" style="width:85%;">
                                <select name="year" dataType="Require"  msg="未选择入学年份">
                                    <option value="" selected="selected"></option>
                                    <option value="2009">2009</option>
                                    <option value="2008">2008</option>
                                    <option value="2007">2007</option>
                                    <option value="2006">2006</option>
                                    <option value="2005">2005</option>
                                    <option value="2004">2004</option>
                                    <option value="2003">2003</option>
                                    <option value="2002">2002</option>
                                    <option value="2001">2001</option>
                                    <option value="2000">2000</option>
                                    <option value="1999">1999</option>
                                    <option value="1998">1998</option>
                                    <option value="1997">1997</option>
                                    <option value="1996">1996</option>
                                    <option value="1995">1995</option>
                                    <option value="1994">1994</option>
                                    <option value="1993">1993</option>
                                    <option value="1992">1992</option>
                                    <option value="1991">1991</option>
                                    <option value="1990">1990</option>
                                    <option value="1989">1989</option>
                                    <option value="1988">1988</option>
                                    <option value="1987">1987</option>
                                    <option value="1986">1986</option>
                                    <option value="1985">1985</option>
                                    <option value="1984">1984</option>
                                    <option value="1983">1983</option>
                                    <option value="1982">1982</option>
                                    <option value="1981">1981</option>
                                    <option value="1980">1980</option>
                                    <option value="1979">1979</option>
                                    <option value="1978">1978</option>
                                    <option value="1977">1977</option>
                                    <option value="1976">1976</option>
                                    <option value="1975">1975</option>
                                    <option value="1974">1974</option>
                                    <option value="1973">1973</option>
                                    <option value="1972">1972</option>
                                    <option value="1971">1971</option>
                                    <option value="1970">1970</option>
                                    <option value="1969">1969</option>
                                    <option value="1968">1968</option>
                                    <option value="1967">1967</option>
                                    <option value="1966">1966</option>
                                    <option value="1965">1965</option>
                                    <option value="1964">1964</option>
                                    <option value="1963">1963</option>
                                    <option value="1962">1962</option>
                                    <option value="1961">1961</option>
                                    <option value="1960">1960</option>
                                    <option value="1959">1959</option>
                                    <option value="1958">1958</option>
                                    <option value="1957">1957</option>
                                    <option value="1956">1956</option>
                                    <option value="1955">1955</option>
                                    <option value="1954">1954</option>
                                    <option value="1953">1953</option>
                                    <option value="1952">1952</option>
                                    <option value="1951">1951</option>
                                    <option value="1950">1950</option>
                                    <option value="1949">1949</option>
                                    <option value="1948">1948</option>
                                    <option value="1947">1947</option>
                                    <option value="1946">1946</option>
                                    <option value="1945">1945</option>
                                    <option value="1944">1944</option>
                                    <option value="1943">1943</option>
                                    <option value="1942">1942</option>
                                    <option value="1941">1941</option>
                                    <option value="1940">1940</option>
                                    <option value="1939">1939</option>
                                    <option value="1938">1938</option>
                                    <option value="1937">1937</option>
                                    <option value="1936">1936</option>
                                    <option value="1935">1935</option>
                                    <option value="1934">1934</option>
                                    <option value="1933">1933</option>
                                    <option value="1932">1932</option>
                                    <option value="1931">1931</option>
                                    <option value="1930">1930</option>
                                    <option value="1929">1929</option>
                                    <option value="1928">1928</option>
                                    <option value="1927">1927</option>
                                    <option value="1926">1926</option>
                                    <option value="1925">1925</option>
                                    <option value="1924">1924</option>
                                    <option value="1923">1923</option>
                                    <option value="1922">1922</option>
                                    <option value="1921">1921</option>
                                    <option value="1920">1920</option>
                                    <option value="1919">1919</option>
                                    <option value="1918">1918</option>
                                    <option value="1917">1917</option>
                                    <option value="1916">1916</option>
                                    <option value="1915">1915</option>
                                    <option value="1914">1914</option>
                                    <option value="1913">1913</option>
                                    <option value="1912">1912</option>
                                    <option value="1911">1911</option>
                                    <option value="1910">1910</option>
                                </select>
                            年</DIV>
                        </li>
                        <li>
                            <DIV class="left alR" style="width:15%;">&nbsp;</DIV>
                            <input type="hidden" name="type" value="education">
                            <DIV class="left" style="width: 85%;"><input type="submit" class="btn_b" value="添 加" />
                            </DIV>
                        </li>
                    </ul>
                </form>
            </div>
        </div><!-- 教育情况 end  -->




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