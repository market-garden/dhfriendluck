<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo C("SITE_TITLE");?></title>
	<link href="../Public/css/layout.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
<!--
	//指定当前组模块URL地址
	var	URL		=	'__URL__';
	var	APP		=	'__APP__';
	var	PUBLIC	=	'__PUBLIC__';
	var	ROOT	=	'__ROOT__';
	var TS		=	'__TS__';
//-->
</script>	
	<script type="text/javascript" src="__PUBLIC__/js/jquery.js"></script>
	<script type="text/javascript" src="__PUBLIC__/js/json.js"></script>
	<script type="text/javascript" src="__PUBLIC__/js/ts_common.js"></script>
	<script type="text/javascript" src="__PUBLIC__/js/ymPrompt/ymPrompt.js"></script>
	<link rel="stylesheet" id='skin' type="text/css" href="__PUBLIC__/js/ymPrompt/skin/qq/ymPrompt.css" />
<script>
	var Alert	= ymPrompt.alert;
	var Confirm = ymPrompt.confirmInfo;
	var Success = ymPrompt.succeedInfo;
	var Error   = ymPrompt.errorInfo;
	var Win     = ymPrompt.win;
</script>	
<script>
	//选择生日
	function selectMonth(){
		var Year = $('#birthday_year').val();
		var Month = $('#birthday_month').val();
		var monthDay   =  new  Array(31,28,31,30,31,30,31,31,30,31,30,31);
		var monthDayNum;
		if(Year%400==0||(Year%4==0&&Year%100!=0)) monthDay[1]=   29;
		monthDayNum   =   monthDay[Month-1];
		
		var i;
		var daysout = '';
		for(i=1;i<=monthDayNum;i++){
			daysout+='<option value='+i+'>'+i+'</option>';
		}
		$('#birthday_day').html(daysout);
	}

$(document).ready(function()
{
	$("[selectArea='true']").bind("click", function(){
		var type = $(this).attr('areatype');
		var typevalue = $("#ts_"+type).val();
		ymPrompt.win({message:APP+'/Index/network/type/'+type+'/selected/'+typevalue,width:600,height:290,title:'选择地区',handler:function(){ymPrompt.close();},autoClose:false,iframe:true,allowRightMenu:true});
	}); 	
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
	</head>
	<body>
	<?php $birthday = explode('-',$info['birthday']); ?>
		<div id="container">
			<table class="tableborder" width="100%">
				<tbody>
					<tr class="top">
						<td class="border" colspan="2"></td>
					</tr>
					<tr>
						<th class="header" colspan="2">用户:  <?php echo ($info["name"]); ?> 的基本信息</th>
					</tr> 
					<tr class="label">
						<th style="width:250px;">说明</th>
						<th>设置</th>
					</tr>
					<tr class="cell">
						<td class="altbg1">
							<b>头像:</b>
						</td>
						<td class="altbg2"><img src="<?php echo (getUserFace($info["id"],'middle')); ?>" style="height:60px;">&nbsp;<a href="">清空头像</a></td>
					</tr>
					<tr class="cell">
						<td class="altbg1">
							<b>注册邮箱:</b>
							<br/>
							<span class="smalltxt">用户注册时使用的邮箱地址</span>
						</td>
						<td class="altbg2">
							<?php echo ($info["email"]); ?>
						</td>
					</tr>
					<tr class="cell">
						<td class="altbg1">
							<b>性别:</b>
							<br/>
							<span class="smalltxt">用户性别</span>
						</td>
						<td class="altbg2">
							<input type="radio" name="sex" value="1" <?php if((is_array($info)?$info["sex"]:$info->sex)  ==  "1"): ?>checked<?php endif; ?> >男 <input type="radio" name="sex" value="0" <?php if((is_array($info)?$info["sex"]:$info->sex)  ==  "0"): ?>checked<?php endif; ?> >女
						</td>
					</tr>
					<tr class="cell">
						<td class="altbg1">
							<b>出生日期:</b>
							<br/>
							<span class="smalltxt">用户出生日期</span>
						</td>
						<td class="altbg2">
                                               <select name="birthday_year" id="birthday_year" onchange="selectMonth(this)">
                        	<?php for($i=1980;$i<=2009;$i++){ ?>
                        		<?php if($i==$birthday['0']){ ?>
                        			<option value="<?php echo ($i); ?>" selected="selected"><?php echo ($i); ?></option>
                        		<?php }else{ ?>
                        			<option value="<?php echo ($i); ?>"><?php echo ($i); ?></option>
                        		<?php } ?>
                        	<?php } ?>
                        </select>年 
                        <select name="birthday_month" id="birthday_month" onchange="selectMonth(this);adjustAstro()">

                        	<?php for($i=1;$i<=12;$i++){ ?>
                     			<?php if($i==$birthday[1]){ ?>
                        			<option value="<?php echo ($i); ?>" selected="selected"><?php echo ($i); ?></option>
                        		<?php }else{ ?>
                        			<option value="<?php echo ($i); ?>"><?php echo ($i); ?></option>
                        		<?php } ?>
                        	<?php } ?>
                        </select>月 
                        <select name="birthday_day" id="birthday_day" onchange="adjustAstro()">
                        	<?php for($i=1;$i<=31;$i++){ ?>
                     			<?php if($i==$birthday[2]){ ?>
                        			<option value="<?php echo ($i); ?>" selected="selected"><?php echo ($i); ?></option>
                        		<?php }else{ ?>
                        			<option value="<?php echo ($i); ?>"><?php echo ($i); ?></option>
                        		<?php } ?>
                        	<?php } ?>
                        </select>日
						</td>
					</tr>
					<tr class="cell">
						<td class="altbg1">
							<b>星座:</b>
							<br/>
							<span class="smalltxt">用户出生日期</span>
						</td>
						<td class="altbg2">
							<select name="birthday_stro" disabled><option id="astro">&nbsp;</option></select>
						</td>
					</tr>						
					<tr class="cell">
						<td class="altbg1">
							<b>血型:</b>
							<br/>
						</td>
						<td class="altbg2">
                     	<select name="bloodtype">
                        		<option value="O型">O型</option>
                        		<option value="A型">A型</option>
                        		<option value="B型">B型</option>
                        		<option value="AB型">AB型</option>
                        		<option value="AB型">稀有血型</option>
                        	</select>  
						</td>
					</tr>					
					<tr class="cell">
						<td class="altbg1"><b>居住城市:</b></td>
						<td class="altbg2">
							 <input type="hidden" name="ts_areaval" id="ts_areaval" value="<?php echo ($info["ts_areaval"]); ?>"/>
							<input type="text" class="text1" style="width:165px;float:left;" disabled onBlur="this.className='text1'" onFocus="this.className='text2'"  value="<?php echo (getAreaInfo($info["ts_areaval"])); ?>" name="areaval" id="areaval" /> <input type="button" class="btn_b" value="选择地区" selectArea="true" style="float:left; margin-left:5px;" areatype="areaval" >
						</td>
					</tr>	
					<tr class="cell">
						<td class="altbg1"><b>家乡:</b></td>
						<td class="altbg2">
							<input type="hidden" name="ts_hometown" id="ts_hometown" value="<?php echo ($info["ts_hometown"]); ?>"/>
							<input type="text" class="TextH20" style="width:200px;" onfocus="this.className='Text2'" onblur="this.className='TextH20'" value="<?php echo (getAreaInfo($info["ts_hometown"])); ?>" disabled name="hometown" id="hometown"/>  <input type="button" class="btn_b" value="选择地区" selectArea="true" areatype="hometown" >
						</td>
					</tr>
					<tr>
						<td class="footer" colspan="2">
							<input type="button" class="button" value="确定" />
						</td>
					</tr>										
				</tbody>
			</table>
			
		<table class="tableborder" width="100%">
		<form action="__APP__/User/doedit" method="POST">
		<input type="hidden" name="type" value="accounts">
		<input type="hidden" name="id" value="<?php echo ($info["id"]); ?>">
				<tbody>
					<tr class="top">
						<td class="border" colspan="2"></td>
					</tr>
					<tr>
						<th class="header" colspan="2">帐号设置</th>
					</tr> 
					<tr class="label">
						<th>说明</th>
						<th>设置</th>
					</tr>
					<tr class="cell">
						<td class="altbg1">
							<b>邮箱:</b>
						</td>
						<td class="altbg2"><input type="text" name="email" value="<?php echo ($info["email"]); ?>" ></td>
					</tr>					
					<tr class="cell">
						<td class="altbg1">
							<b>新密码:</b>
						</td>
						<td class="altbg2"><input type="text" name="password" ></td>
					</tr>
					<tr>
						<td class="footer" colspan="2">
							<input type="submit" class="button" value="确定" />
						</td>
					</tr>
				</tbody>
				</form>
			</table>
			

		<table class="tableborder" width="100%">
		<form action="__APP__/User/doedit" method="POST">
		<input type="hidden" name="type" value="set">
		<input type="hidden" name="id" value="<?php echo ($info["id"]); ?>">
				<tbody>
					<tr class="top">
						<td class="border" colspan="2"></td>
					</tr>
					<tr>
						<th class="header" colspan="2">状态设置</th>
					</tr> 
					<tr class="label">
						<th>说明</th>
						<th>设置</th>
					</tr>
					<tr class="cell">
						<td class="altbg1">
							<b>状态:</b>
						</td>
						<td class="altbg2">
										<select name="active">
											<option value="1" <?php if((is_array($tinfo)?$tinfo["active"]:$tinfo->active)  ==  "1"): ?>selected<?php endif; ?> >已激活</option>
											<option value="0" <?php if((is_array($tinfo)?$tinfo["active"]:$tinfo->active)  ==  "0"): ?>selected<?php endif; ?> >未激活</option>
										  </select>						
						</td>
					</tr>					
					<tr class="cell">
						<td class="altbg1">
							<b>用户组:</b>
						</td>
						<td class="altbg2">
										<select name="level">
											<option value="0" <?php if((is_array($tinfo)?$tinfo["admin_level"]:$tinfo->admin_level)  ==  "0"): ?>selected<?php endif; ?> />普通用户组</option>
											<?php if(is_array($group)): ?><?php $i = 0;?><?php $__LIST__ = $group?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$vo): ?><?php ++$i;?><?php $mod = ($i % 2 )?><?php if($tinfo['admin_level']==$vo['id']){ ?>
												<option value="<?php echo ($vo["id"]); ?>" selected="true"><?php echo ($vo["name"]); ?></option>
											<?php }else{ ?>
												<option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></option>
											<?php } ?><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
										  </select>										  
					    </td>
					</tr>
					<tr>
						<td class="footer" colspan="2">
							<input type="submit" class="button" value="确定" />
						</td>
					</tr>
				</tbody>
				</form>
			</table>			
		</div>
	</body>
</html>