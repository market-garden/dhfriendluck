<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo C("SITE_TITLE");?></title>
	<link href="../Public/css/layout.css" rel="stylesheet" type="text/css" />
	<link href="__PUBLIC__/js/pagination/pagination.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="__PUBLIC__/js/jquery.js"></script>
	<script type="text/javascript" src="__PUBLIC__/js/jquery.form.js"></script>
	<script type="text/javascript">
		var ctrl = function(name){
			this.elements = document.getElementsByName('id[]');
			this.length = this.elements.length;
		}
		ctrl.prototype = {
			reverse:function(){
				for(i=0;i<this.length;i++){
					this.elements[i].checked= !this.elements[i].checked;
				}
			},
			all:function(){
				for(i=0;i<this.length;i++){
					this.elements[i].checked = true;
				}
			},
			unAll:function(){
				for(i=0;i<this.length;i++){
					this.elements[i].checked = false;
				}
			},
			toggle:function(obj){
				this[obj.checked ? 'all' : 'unAll']();
			},
			del:function(id){
				if(id==undefined){
					id = [];
					for(i=0;i<this.length;i++){
						this.elements[i].checked && id.push(this.elements[i].value);
					}
					id = id.join(',');
				}
				alert('您选择了id为:'+id);
			},
			edit:function(id){
				location.href = "__URL__/edit/id/"+id;
			},
			commend:function(id,type){
				location.href = "__URL__/commend/id/"+id+"/type/"+type;
			}
		}
		var c = null;
	</script>
	</head>
	<body>
		<div id="container">
    <table class="tableborder" width="100%">
    	<form method="POST" action="__URL__/index/">
          <tbody>
            <tr class="top">
              <td class="border" colspan="2"></td>
            </tr>
            <tr>
              <th class="header" colspan="2">查询</th>
            </tr>
            <tr class="label">
              <th>说明</th>
              <th>条件</th>
            </tr>
            
            <tr class="cell">
              <td class="altbg1">
                <b>用户名:</b>
                <br/>
                <span class="smalltxt">根据用户名查询</span>
              </td>
              <td class="altbg2"><input name="name" class="txt" value="<?php echo ($name); ?>"/></td>
            </tr>
		
            <tr class="cell">
              <td class="altbg1">
                <b>用户Id</b>
                <br/>
                <span class="smalltxt">根据用户id</span>
              </td>
              <td class="altbg2"><input name="uid" class="txt" value="<?php echo ($uid); ?>"/> 多个用户用逗号隔开</td>
            </tr>
            
            <tr class="cell">
              <td class="altbg1">
                <b>email</b>
                <br/>
                <span class="smalltxt">根据用户email</span>
              </td>
              <td class="altbg2"><input name="email" class="txt" value="<?php echo ($email); ?>"/> </td>
            </tr>            
            
            <tr class="cell">
              <td class="altbg1">
                <b>用户组</b>
                <br/>
                <span class="smalltxt">用户所在组</span>
              </td>
              <td class="altbg2">
               	<select name="groupid">
              		<option value="9" >全部</option>
              		<option value="0" <?php if(($groupid)  ==  "0"): ?>selected<?php endif; ?> >普通用户</option>
					<?php if(is_array($grounlist)): ?><?php $i = 0;?><?php $__LIST__ = $grounlist?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$vo): ?><?php ++$i;?><?php $mod = ($i % 2 )?><?php if($vo['id']==$groupid){ ?>
					 	<option value="<?php echo ($vo["id"]); ?>" selected><?php echo ($vo["name"]); ?></option>					 
					 <?php }else{ ?>
						<option value="<?php echo ($vo["id"]); ?>" ><?php echo ($vo["name"]); ?></option>
					 <?php } ?><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
              	</select>             
              </td>
            </tr>            

            <tr class="cell">
              <td class="altbg1">
                <b>注册时间</b>
                <br/>
                <span class="smalltxt">根据注册时间段查询。</span>
              </td>
              <td class="altbg2"><input name="bDate" class="txt" value="<?php echo ($bDate); ?>"/> - <input name="eDate" class="txt" value="<?php echo ($eDate); ?>"/></td>
            </tr>
		
            <tr class="cell">
              <td class="altbg1">
                <b>用户状态</b>
                <br/>
                <span class="smalltxt">根据用户状态查询。</span>
              </td>
              <td class="altbg2">
              	<select name="status">
              		<option value="9" >全部</option>
              		<option value="0" <?php if(($status)  ==  "0"): ?>selected<?php endif; ?>>待激活</option>
              		<option value="1" <?php if(($status)  ==  "1"): ?>selected<?php endif; ?>>已激活</option>
              	</select>
              </td>
            </tr>
            
            <tr class="cell">
              <td class="altbg1">
                <b>推荐状态</b>
                <br/>
                <span class="smalltxt">根据用户状态查询。</span>
              </td>
              <td class="altbg2">
 				<input type="checkbox" name="commend" value="1" <?php if(($commend)  ==  "1"): ?>checked<?php endif; ?> > 已推荐
              </td>
            </tr>            

            <tr class="cell">
              <td class="altbg1">
                <b>结果排序</b>
                <br/>
              </td>
              <td class="altbg2">
                <select name="field">
                  <option value = "cTime" <?php if(($field)  ==  "cTime"): ?>selected<?php endif; ?> >注册时间排序</option>
                  <option value = "id" <?php if(($field)  ==  "id"): ?>selected<?php endif; ?> >注册id排序</option>
                </select>
                <select name="order">
                  <option value = "DESC" <?php if(($order)  ==  "DESC"): ?>selected<?php endif; ?> >降序</option>
                  <option value = "ASC" <?php if(($order)  ==  "ASC"): ?>selected<?php endif; ?> >升序</option>
                </select>
                <select name="limit">
                  <option value = "10" <?php if(($limit)  ==  "10"): ?>selected<?php endif; ?> >每页显示10条</option>
                  <option value = "20" <?php if(($limit)  ==  "20"): ?>selected<?php endif; ?> >每页显示20条</option>
                  <option value = "30" <?php if(($limit)  ==  "30"): ?>selected<?php endif; ?> >每页显示30条</option>
                </select>
              </td>
            </tr>            
            <tr>
              <td class="footer" colspan="2">
                <input type="submit" class="submit" value="提交" />
              </td>
            </tr>
          </tbody>
          </form>
        </table>


<table class="tableborder" width="100%">
<form id="userform" action="__URL__/doBatch" method="POST">
				<tbody>
					<tr class="top">
						<td class="border" colspan="9"></td>
					</tr>
					<tr>
						<th class="header" colspan="9">标题</th>
					</tr>
					<tr>
					
						<td class="footer" colspan="9" style="text-align:left;color:blue;">
						一共有 <font color="red"><?php echo ($count); ?></font> 用户
						</td>
					</tr>					
					<tr class="label">
						<th> 选择 <input type="checkbox" onclick="c.toggle(this)" class="checkbox"/></th>
						<th>id</th>
						<th>头像</th>
						<th>用户名</th>
						<th>email</th>
						<th>用户组</th>
						<th>注册/更新</th>
						<th>状态</th>
						<th>操作</th>
					</tr>
<?php if(is_array($list)): ?><?php $i = 0;?><?php $__LIST__ = $list?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$vo): ?><?php ++$i;?><?php $mod = ($i % 2 )?><tr class="cell altbg1">
						<td style="text-align:center;"><input type="checkbox" class="checkbox" name="id[]" value="<?php echo ($vo["id"]); ?>" /></td>
						<td><?php echo ($vo["id"]); ?></td>
						<td><a href="__TS__/space/<?php echo ($vo["id"]); ?>" target="_blank"><img src="<?php echo (getUserFace($vo["id"],'small')); ?>" style="height:60px;"/></a></td>
						<td><a href="__TS__/space/<?php echo ($vo["id"]); ?>" target="_blank"><?php echo ($vo["name"]); ?></a></td>
						<td><?php echo ($vo["email"]); ?></td>
						<td><?php echo (getUserLevel($vo["admin_level"])); ?></td>
						<td><?php echo (date("Y-m-d",$vo["cTime"])); ?></td>
						<td><?php if((is_array($vo)?$vo["active"]:$vo->active) == '1'): ?><font color="blue">已激活</font><?php else: ?><font color="red">未激活</font><?php endif; ?></td>
						<td>
							<button type="button" onclick="c.edit(<?php echo ($vo["id"]); ?>)">编辑</button><br><button type="button" onclick="c.del(1)">删除</button>
							<?php if((is_array($vo)?$vo["commend"]:$vo->commend) == '1'): ?><button type="button" onclick="c.commend(<?php echo ($vo["id"]); ?>,0)">取消推荐</button>
							<?php else: ?>
								<button type="button" onclick="c.commend(<?php echo ($vo["id"]); ?>,1)" style="color:blue">推荐</button><?php endif; ?>
						</td>
					</tr><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
					<tr class="bottom">
						<td class="border" colspan="6"> </td>
					</tr>
					<tr>
					<td colspan="5"><div style="float:left">全选<input type="checkbox" onclick="c.toggle(this)" class="checkbox"/>&nbsp;&nbsp;&nbsp;操作:<select name="dotype" style="margin-right:10px;" onchange="changeDoType(this);">
						<option value="commend">推荐</option>
						<option value="uncommend">取消推荐</option>
						<option value="active">已激活</option>
						<option value="unactive">取消激活</option>
						<option value="movegroup">转移到用户组</option>
				    </select><span name="grouplists" style="display:none;style="margin-right:10px;">用户组:<select  name="togroupId" >
				    <option value="0">普通用户组</option>
				    <?php if(is_array($grounlist)): ?><?php $i = 0;?><?php $__LIST__ = $grounlist?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$vo): ?><?php ++$i;?><?php $mod = ($i % 2 )?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
				    </select>
				    </span>
				    <input type="submit" value="提交">
						
					</div>
					
					</td>
						<td class="footer" colspan="4">
						<div class="pagination" style="float:right;">
							<?php echo ($pages); ?>
						</div>
						</td>
					</tr>
					<tr class="bottom">
						<td class="border" colspan="6">&nbsp;</td>
					</tr>
				</tbody>
				</form>
			</table>        
		</div>
		<script type="text/javascript">

			function changeDoType(o){
				var obj= $("span[name=grouplists]");
				if($(o).val()=='movegroup'){
					obj.show();
				}else{
					obj.hide();
				}
			}
			c = new ctrl('id[]');
		</script>
	</body>
</html>