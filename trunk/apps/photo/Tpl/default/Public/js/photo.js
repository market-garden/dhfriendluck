//显示照片的exif信息
function exif(imgpath){
	ymPrompt.win({message:APP+'/Index/show_exif/img/'+imgpath,width:500,height:360,title:'显示照片EXIF信息',iframe:true})
}
//设为头像
function setface(photoid){
	ymPrompt.confirmInfo({message:'你要将这张照片设置为头像么？',handler:ajax_set_face});
}
function ajax_set_face(e){
	if(e=='ok'){
		$.post(APP+'/Manage/set_face',{ajax:1,photoId:photo_id,albumId:album_id},function(data){
			if(data){
				//设置数据
				ymPrompt.close();
				ymPrompt.succeedInfo('头像设置成功！');
			}else{
				ymPrompt.close();
				ymPrompt.errorInfo('头像设置失败！');
			}
		});
	}else{
		ymPrompt.close();
	}
	return false;
}

//将我的一张照片设置为该专辑的封面
function setcover(photoid){
	ymPrompt.confirmInfo({message:'你要将这张照片设置为封面么？',handler:ajax_set_cover});
}
function ajax_set_cover(e){
	if(e=='ok'){
		$.post(APP+'/Manage/set_cover',{ajax:1,photoId:photo_id,albumId:album_id},function(data){
			//alert(data);
			if(data==1){
				//设置数据
				ymPrompt.close();
				ymPrompt.succeedInfo('封面设置成功！');
			}else{
				ymPrompt.close();
				ymPrompt.errorInfo('封面设置失败！');
			}
		});
	}else{
		ymPrompt.close();
	}
	return false;
}

//编辑照片
function editphoto(){
	ymPrompt.win({message:APP+'/Manage/edit_photo/aid/'+album_id+'/pid/'+photo_id+'/uid/'+uid,width:340,height:160,title:'编辑照片',iframe:true})
}
function ajax_submit_update_photo(){

	var	ran		=	Math.random();
	var id		=	document.update_photo.photoId.value;
	var name	=	document.update_photo.name.value;
	var albumId	=	document.update_photo.albumId.value;
	var uid		=	document.update_photo.uid.value;
	if(!name)	{ 
		alert('照片名字不能为空！');
		return false;
	}
	
	$.post(APP+'/Manage/do_update_photo',{ajax:1,id:id,name:name,albumId:albumId,ran:ran},function(data){
	    if(data){
			//刷新页面
			parent.location.href = APP+'/Index/photo/id/'+id+'/aid/'+albumId+'/uid/'+uid;
			//parent.ymPrompt.close();
			//parent.ymPrompt.succeedInfo('修改成功！');
		}else{
			parent.ymPrompt.close();
			parent.ymPrompt.errorInfo('修改失败！');
		}
	});
	return false;
}

//删除单张照片
function delphoto(){
	ymPrompt.confirmInfo({message:'你确定要删除这张照片么？',handler:ajax_delete_photo});
}
function ajax_delete_photo(e){
	if(e=='ok'){
		$.post(APP+'/Manage/delete_photo',{ajax:1,id:photo_id,albumId:album_id},function(data){
			if(data==1){
				//设置数据
				parent.location.href = APP+'/Index/album/id/'+album_id+'/uid/'+uid;
				ymPrompt.close();
				ymPrompt.succeedInfo('删除成功！');
			}else{
				ymPrompt.close();
				ymPrompt.errorInfo('删除失败！');
			}
		});
	}else{
		ymPrompt.close();
	}
	return false;
}

//分享照片
function sharePop(id,url,type){
	$.post(url+"/addShare_check/", {aimId:id,type:type}, function(txt){
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
	});
}

//幻灯播放
/**
var scroll_start	=	false;
if(now_play){
	scroll_start	=	true;
}
function play(from){
	if(scroll_start == true && from=='button'){
		$('#play_button').html('幻灯播放');
		return;
	}

	$('#play_button').html('暂停播放');
	scroll_start	=	true;
	next	=	'__APP__/Index/photo/id/{$next.id}/aid/{$next.albumId}/uid/{$next.userId}<neq name="type" value="">/type/{$type}</neq>/play/1';
	setTimeout(window.location.href=next,5000);
}
**/

var QUANREN_XYXY,POST_XYXY,QUAN_ING=0;


function start_quanren(){
    QUAN_ING = 1;
	$("#start-quanren").hide();
    $("#finish-quanren").css("display","inline");
	$("#big_pic").css("cursor","crosshair");
	$("#quanren").show();
	$("#quanren").draggable({handle:"#frameDiv"});
}

function show_friend(img, selection)
{
    //alert('width: ' + selection.width + '; height: ' + selection.height);
  //alert('x1: ' + selection.x1 + '; x2: ' + selection.x2+'y1: ' + selection.y1 + '; y2: ' + selection.y2);
   QUANREN_XYXY = "'"+selection.x1+'-'+selection.y1+'-'+selection.x2+'-'+selection.y2+"'";
   POST_XYXY = selection.x1+'-'+selection.y1+'-'+selection.x2+'-'+selection.y2;
	$("#showFriends").click();
}

function finish_quanren(_this){
	var q_name = $(_this).attr("rel");
	var q_id = $(_this).attr("id");

	var isEmpty = $("#photo-tlist-wrapper").text();

	if(isEmpty.Trim() == ""){
		var quan_user = '<h4>照片中有：</h4>\
						<span id="list_quanren">\
							<a href="'+APP+'/space/'+q_id+'"  onmouseover="show_quanren('+QUANREN_XYXY+')" onmouseout="hide_quanren()">'+q_name+'</a>\
						</span>';
		$("#photo-tlist-wrapper").html(quan_user);
	}else{
		var quan_user = '，<a href="'+APP+'/space/'+q_id+'"  onmouseover="show_quanren('+QUANREN_XYXY+')" onmouseout="hide_quanren()">'+q_name+'</a>';
		$("#list_quanren").append(quan_user);
	}
	$('img#big_pic').imgAreaSelect({ hide: true });
	$.facebox.close();

	//ajax到后台
	var photoId = $("#big_pic").attr("rel");
	var photoPath = $("#photoPath").val();
	var photoInfo = $(photoInfo).val();
	var url = APP+"/Photo/quanren";
	$.post(url,{photoId:photoId,userId:q_id,xyxy:POST_XYXY,photoInfo:photoInfo,photoPath:photoPath},function(txt){
		if(!txt){
			alert("圈人失败!请稍后再试!");
		}
	});
}


function cancel_quanren(){
    QUAN_ING = 0;
	$("#finish-quanren").hide("");
    $("#start-quanren").css("display","inline");
	$('img#big_pic').imgAreaSelect({ enable: false });
	$("#big_pic").css("cursor","pointer");
}

