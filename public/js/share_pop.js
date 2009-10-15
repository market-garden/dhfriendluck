function ts_sharePop(id,url,type){
	var classId = '#BtnShare_'+id;
	$(classId).attr('disabled','true');
	
	$.post(url+"/addShare_check/", {aimId:id}, function(txt){
			if(txt==1){
					ymPrompt.win(url+'/addShare/aimId/'+id+'/type/'+type,500,'315','分享',null,null,null,{id:'a'});
			}else if(txt==-1){
					ymPrompt.errorInfo('请不要分享自己发布的东西!');
			}else if(txt==-2){
					ymPrompt.errorInfo('您已经分享过,请不要重复分享!');
			}else if(txt==-3){
					ymPrompt.errorInfo('您没有权限分享!');
			}else{
					ymPrompt.errorInfo('参数出错,请重试!');
			}
			
			$(classId).attr('disabled','');
	});
}