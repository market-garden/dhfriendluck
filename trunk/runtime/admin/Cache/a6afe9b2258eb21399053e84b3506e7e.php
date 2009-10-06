<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo C("SITE_TITLE");?></title>
	<link href="../Public/css/layout.css" rel="stylesheet" type="text/css" />
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
				location.href = "__URL__/delnotify/id/"+id;
			},
			edit:function(id){
				location.href = "__URL__/notify/id/"+id+"#edit";
			}
		}
		var c = null;
	</script>
	</head>
	<body>
		<div id="container">
			<table class="tableborder" width="100%">
				<tbody>
					<tr class="top">
						<td class="border"></td>
					</tr>
					<tr>
						<th class="header">tips</th>
					</tr>
					<tr>
						<td class="tips">
							<ul>
								<li>通知模板</li>
							</ul>
						</td>
					</tr>
					<tr class="bottom">
						<td class="border" colspan="5">&nbsp;</td>
					</tr>
				</tbody>
			</table>
			<table class="tableborder" width="100%">
				<tbody>
					<tr class="top">
						<td class="border" colspan="7"></td>
					</tr>
					<tr>
						<th class="header" colspan="7">标题</th>
					</tr>
					<tr class="label">
						<th style="width:10%">编号</th>
						<th style="width:10%;">类型</th>
						<th style="width:10%;">类型名称</th>
						<th style="width:20%;">标题</th>
						<th style="width:20%;">DEAL</th>						
						<th style="width:15%;">内容</th>
						<th style="width:15%;">操作</th>
					</tr>
<?php if(is_array($list)): ?><?php $i = 0;?><?php $__LIST__ = $list?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$vo): ?><?php ++$i;?><?php $mod = ($i % 2 )?><tr class="cell altbg2">
						<td><?php echo ($vo["id"]); ?></td>
						<td><?php echo ($vo["type"]); ?></td>
						<td><?php echo ($vo["type_cn"]); ?></td>
						<td><?php echo ($vo["title"]); ?></td>
						<td><?php echo ($vo["deal"]); ?></td>						
						<td><?php echo ($vo["body"]); ?></td>
						<td><button type="button" onclick="c.edit(<?php echo ($vo["id"]); ?>)">编辑</button><button type="button" onclick="c.del(<?php echo ($vo["id"]); ?>)">删除</button></td>
					</tr><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>

					<tr class="bottom">
						<td class="border" colspan="7">&nbsp;</td>
					</tr>
				</tbody>
			</table>
			
		<table class="tableborder" width="100%" id="edit">
			<form action="__URL__/donotify" method="POST">
				<tbody>
					<tr class="top">
						<td class="border" colspan="2"></td>
					</tr>
					<tr>
					<?php if($info != ''): ?><th class="header" colspan="2">编辑通知模板 <a href="__URL__/feed#edit">添加</a></th>
					<?php else: ?>
						<th class="header" colspan="2">添加通知模板</th><?php endif; ?>
					</tr>
					<tr class="label">
						<th>说明</th>
						<th>设置</th>
					</tr>

					<tr class="cell">
						<td class="altbg1">
							<b>类型:</b>
							<br/>
							<span class="smalltxt">通知显示的类型</span>
						</td>
						<td class="altbg2"><input name="type" value="<?php echo ($info["type"]); ?>" class="txt"/></td>
					</tr>		
										
					<tr class="cell">
						<td class="altbg1">
							<b>类型名称:</b>
							<br/>
							<span class="smalltxt">通知的类型名称</span>
						</td>
						<td class="altbg2">
						<input name="type_cn" value="<?php echo ($info["type_cn"]); ?>" class="txt"/>
						</td>
					</tr>

					<tr class="cell">
						<td class="altbg1">
							<b>标题:</b>
							<br/>
							<span class="smalltxt">通知的类型名称</span>
						</td>
						<td class="altbg2">
						<input name="title" value="<?php echo ($info["title"]); ?>" class="txt"/>
						</td>
					</tr>									

					<tr class="cell">
						<td class="altbg1">
							<b>DEAL:</b>
							<br/>
							<span class="smalltxt"></span>
						</td>
						<td class="altbg2">
						<input name="deal" value="<?php echo ($info["deal"]); ?>" class="txt" size="40"/>
						</td>
					</tr>
					
					<tr class="cell">
						<td class="altbg1">
							<b>内容:</b>
							<br/>
							<span class="smalltxt">内容</span>
						</td>
						<td class="altbg2">
							<textarea style="font-size:12px;" cols="80" name="body" rows="5" ><?php echo ($info["body"]); ?></textarea>
						</td>
					</tr>
					<tr>
						<td class="footer" colspan="2">
							<input type="submit" class="button" value="<?php if($info != ''): ?>编辑<?php else: ?>添加<?php endif; ?>" />
						</td>
					</tr>
					<tr class="bottom">
						<td class="border" colspan="5">&nbsp;</td>
					</tr>
				</tbody>
			  </form>
			</table>
						
		</div>
		<script type="text/javascript">
			c = new ctrl('id[]');
		</script>
	</body>
</html>