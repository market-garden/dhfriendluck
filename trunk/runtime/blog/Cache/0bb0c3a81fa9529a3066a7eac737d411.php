<script>
function addGroup() {
	Confirm({icoCls:'',msgCls:'confirm',message:"<br />分组的名称：<input type='text' id='myInput' onfocus='this.select()' />",title:'分组的名称',height:150,handler:getInput,autoClose:false});
}
		function getInput(tp){
			if(tp!='ok') return ymPrompt.close();
			var v=$('#myInput').val();
			if(v=='')
				alert('请输入分组名称！')
			else{
				
				$.post(APP+"/Friend/addGroup",{name:v},function(gid){
				    if(gid){
						$(".confirm").html("添加成功!");
						setTimeout(function(){ ymPrompt.close(); },800);
						//增加一条
						$("#f_group").append('<li id="fli_'+gid+'"><a href="__ACTION__/gid/'+gid+'">'+v+'(0)</a><a href="javascript:del_group('+gid+')">删除</a></li>');
				    }else{
				    
				    }	
				});
			}
		}

function del_group(id) {

		Confirm({message:'确定删除这个分组？',handler:function(tp){
				if(tp=='ok'){
					$.post(APP+"/Friend/delGroup",{id:id},function(txt){
						if(txt){
							$("#fli_"+id).hide("slow");
							$(".confirm").html("删除成功!");
							setTimeout(function(){ ymPrompt.close(); },800);
              location.reload();

						}else{
							Alert("忽略失败!");
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
<div class="FSort">
    <?php if($type == "friend"){ ?>
          <div class="tit"><a href="javascript:addGroup()" class="f12px fn right mr5">添加分组</a>好友分组</div>
          <?php }else{ ?>
          <div class="tit">好友分组</div>
          <?php } ?>
	<ul id="f_group">
		<li  <?php if(!$_GET["gid"]) echo 'class="on"'; ?>><a href="<?php echo ($cur_url); ?>">所有好友(<?php echo getGroupNum(null,$mid);?>)</a></li>
		<?php if(is_array($groups)): ?><?php $i = 0;?><?php $__LIST__ = $groups?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$g): ?><?php ++$i;?><?php $mod = ($i % 2 )?><li id="fli_<?php echo ($g["id"]); ?>" <?php if($g["id"] == $_GET["gid"]) echo 'class="on"'; ?>><a href="<?php echo ($cur_url); ?>/gid/<?php echo ($g["id"]); ?>"><?php echo ($g["name"]); ?>(<?php echo (getGroupNum($g["id"],$mid)); ?>)</a><?php if($g["uid"]!=0){ ?><a href="javascript:del_group(<?php echo ($g["id"]); ?>)">删除</a><?php } ?></li><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
	</ul>
	<div class="btm"></div>
</div>