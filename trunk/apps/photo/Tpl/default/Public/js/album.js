//弹出创建相册窗口
function create_album(uid){
	ymPrompt.win({message:APP+'/Manage/create_album_ajax/uid/'+uid,width:340,height:190,title:'创建相册',iframe:true})
}
//ajax执行创建相册操作
function ajax_submit_create_album(){

	var	ran			=	Math.random();
	var name		=	document.create_album_form.name.value;
	var privacy		=	document.create_album_form.privacy.value;
	var password	=	document.create_album_form.privacy_data.value;
	var share		=	document.create_album_form.share.checked;

	if(!name)	{ 
		alert('相册名字不能为空！');
		return false;
	}
	
	$.post(APP+'/Manage/do_create_album',{ajax:1,name:name,privacy:privacy,privacy_data:password,share:share,ran:ran},function(data){
	    if(data){
			//设置数据
			parent.setAlbumOption(data);
			parent.ymPrompt.close();
			parent.ymPrompt.succeedInfo('创建成功！');
		}else{
			parent.ymPrompt.close();
			parent.ymPrompt.errorInfo('创建失败！');
		}
	});
	return false;
}
//添加相册下拉菜单
function setAlbumOption(data){
	var obj	=	eval('(' + data + ')');
	$('#albumlist').append('<option value="'+ obj.albumId +'" selected="selected" style="background-color:yellow">'+ obj.albumName +'</option>');
}