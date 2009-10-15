
$(function(){
	//绑定checkbox
	$(".checkbox").click(function(){
		var ids = getSelectValues();
		if(ids){
			$(".bj_all").removeClass("cGray2");
		}else{
			$(".bj_all").addClass("cGray2");
		}
		
	});
});




/*
 * 删除某条消息
 *
 */
function del_msg(id) {
	Confirm({message:'确定要删除这条消息吗？',handler:function(tp){
			if(tp=='ok'){
				$.post(APP+"/Notify/delMsg",{id:id},function(txt){
					if(txt){
						$("#msg_"+id).remove();
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




/*
 * 全选，反选之类的
 *
 */
function select_change(_this) {
	var xxx = $(_this).val();
	
	switch (xxx) {
		case "all": {
			selectAll("checkbox");
			$(".bj_all").removeClass("cGray2");
			break;
		}

		case "unAll":{
			unSelectAll("checkbox");
			$(".bj_all").addClass("cGray2");
			break;		
		}

		case "read":{
			unSelectAll("checkbox");
			selectAll("read");
			$(".bj_all").removeClass("cGray2");
			$(".bj_read").addClass("cGray2");
			break;		
		}

		case "unread":{
			unSelectAll("checkbox");
			selectAll("unread");
			$(".bj_all").removeClass("cGray2");
			$(".bj_unread").addClass("cGray2");
			break;		
		}
	
	}
}

/*
 * 删除
 *
 */
function del_some() {
	var ids = getSelectValues();
	if(ids){

		Confirm({message:'确定要删除这条消息吗？',handler:function(tp){
				if(tp=='ok'){
					$.post(APP+"/Notify/delSomeMsg",{ids:ids},function(txt){
						if(txt){

							ids_arr = ids.split(",");
							for(i=0;i<ids_arr.length;i++){
								$("#msg_"+ids_arr[i]).remove();
							}
							
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
}

/*
 * 标记已读
 *
 */
function bj_read() {
	var ids = getSelectValues();
	if(ids){
		$.post(APP+"/Notify/bjReadSomeMsg",{ids:ids,is_read:1},function(txt){
			if(txt){

				ids_arr = ids.split(",");
				for(i=0;i<ids_arr.length;i++){
					$("#bj_pic_"+ids_arr[i]).html('<img src="'+PUBLIC+'/images/ico_mail3.gif" alt="已读" width="14" height="10" />');
					$("#msg_"+ids_arr[i]).removeClass("bg01");
				}
				
			}else{
				Alert("标记已读失败!");
			}
		});	
	}	
}

/*
 * 标记未读
 *
 */
function bj_unread() {
	var ids = getSelectValues();
	if(ids){
		$.post(APP+"/Notify/bjReadSomeMsg",{ids:ids,is_read:0},function(txt){
			if(txt){

				ids_arr = ids.split(",");
				for(i=0;i<ids_arr.length;i++){
					$("#bj_pic_"+ids_arr[i]).html('<img src="'+PUBLIC+'/images/ico_mail1.gif" alt="未读" width="14" height="10" />');
					$("#msg_"+ids_arr[i]).addClass("bg01");
				}
				
			}else{
				Alert("标记未读失败!");
			}
		});	
	}	
}



