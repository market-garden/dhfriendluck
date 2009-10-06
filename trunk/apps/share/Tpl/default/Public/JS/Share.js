function playflash(o){
		$(o).hide('slow');
		$(o).prev().hide('slow');
		var info = $(o).attr("rel");
		var video = '<embed height="390px" width="480px" src="'+info+'" scale="ShowAll" loop="loop" menu="menu" wmode="Window" quality="1" type="application/x-shockwave-flash"></embed>';
		var id = $(o).attr("id");
		var video_id = "#flash_video_"+id;
		var video_close = "#video_close_"+id;
		$(video_id).html(video);
		$(video_close).show();
}
/**
 * 视频关闭
 *
 */
function video_close(_this){
	var id = $(_this).attr("rel");
	var video_id = "#flash_video_"+id;
	$(video_id).html('');
	$("#"+id).show('slow');
	$(_this).hide();

}
// 停止音乐flash
function stopMusic(preID, playerID) {
	var musicFlash = preID.toString() + '_' + playerID.toString();
	if($(musicFlash)) {		
		thisMovie(musicFlash).SetVariable('closePlayer',1);
	}
}
function thisMovie(movieName) {
    if (navigator.appName.indexOf("Microsoft") != -1)
     {
        return window[movieName]
    } else {
        return document[movieName]
    }
}
//播放音乐插件
function playmusic(musicurl, o, id) {
	if('' == musicurl) {
		alert('音乐地址错误，不能为空');
		return false;
	}
	musicurl = encodeURI(musicurl);

	var musicFlash = '<object id="audioplayer_'+id+'" height="24" width="290" data="'+ROOT+'/apps/share/Tpl/default/Public/images/player.swf" type="application/x-shockwave-flash">'
		+ '<param value="'+ROOT+'/apps/share/Tpl/default/Public/images/player.swf" name="movie"/>'
		+ '<param value="autostart=yes&loop=yes&&bg=0xF7F7F7&leftbg=0xEFEFEF&lefticon=0x666666&rightbg=0xcccccc&rightbghover=0x999999&righticon=0x666666&righticonhover=0xFFFFFF&text=0x666666&slider=0x666666&track=0xFFFFFF&border=0x666666&loader=0x9FFFB8&soundFile='+musicurl+'" name="FlashVars"/>'
		+ '<param value="high" name="quality"/>'
		+ '<param value="false" name="menu"/>'
		+ '<param value="#FFFFFF" name="bgcolor"/>'
	    + '</object>';
	var musicMedia = '<object height="64" width="290" data="'+musicurl+'" type="audio/x-ms-wma">'
	    + '<param value="'+musicurl+'" name=""/>'
	    + '<param value="1" name="autostart"/>'
	    + '<param value="true" name="controller"/>'
	    + '</object>';

	var mp3Reg = new RegExp('.mp3$', 'ig');
	musicHtml = musicMedia;
	videoMp3 = false
	if(mp3Reg.test(musicurl)) {
		videoMp3 = true;
		musicHtml = musicFlash;
	}
	
	var musicObj = document.createElement('div');
	musicObj.id =  'player'+id;
	o.parentNode.insertBefore(musicObj, o);
	musicObj.innerHTML = musicHtml;
	o.style.display = 'none';
	var hideObj = document.createElement('div');
	hideObj.id =  'hide_' + id;
	var nodetxt = document.createTextNode("收起");
	hideObj.appendChild(nodetxt);
	o.parentNode.insertBefore(hideObj, o);
	hideObj.style.cursor = 'pointer';
	hideObj.onclick = function() {
		if(true == videoMp3) {
			stopMusic('audioplayer', id);
			musicObj.parentNode.removeChild(musicObj);
			hideObj.parentNode.removeChild(hideObj);
		} else {
			musicObj.style.display = 'none';
			hideObj.style.display = 'none';
		}
		o.style.display = '';				
	}
}

/**
 *  删除分享
 *
 */
function doAjax(rel,id){
	$('#QC').html("<b><font size=2 color=green>正在提交...</font></b>");
	var xid = id.substr(3,id.length-1);
	var delId="#"+"share-"+xid;
	$.post(rel,{id:xid},function(txt){
		  if(txt){
		  	var num = parseInt($("#share_num").text())-1;
			$("#share_num").text(num);
			$(delId).fadeOut('slow');
			//提示信息
			$('#QC').html("<b><font size=2 color=blue>操作成功!</font></b>");
			setTimeout(function(){$('#QC').remove();},500);
		}else{
			ymPrompt.errorInfo("操作失败!");
			setTimeout(function(){$('#QC').remove();},500);
		}
	});
}


function doURL(){
	if($("#reginfo").css('display')=='block'){
		$("#reginfo").css('display', 'none');
	}else{
		var url = $("#url").val();
		if(url==''||url=='http://'){
			ymPrompt.errorInfo('请正确填写网址!');
			return 0;		
		}
		$("#reginfo").css('display', 'block');
	}   
}

/**
 * 把分享增加到数据库
 *
 */
function doAdd(){
	var info = $("#info").val();
	var url = $("#url").val();

	var fids = $("#ui_fri_ids").val();
	
	if(url==''||url=='http://'){
		ymPrompt.errorInfo('请正确填写网址!');
		return 0;		
	}	
	
	$("#msg").css('display', 'block').fadeIn("normal");
	$("#msg").html("正在提交......");
	$("#reginfo").css('display', 'none');

	url	=  url.replace(/^\s+|\s+$/g,"");
		
	var share_type = 1;
	url = url.replace(/^http:\/\//g,"");
	url = "http://"+url;
	
	//--FLASH
	var swfReg = /^http:\/\/.*\.swf$/i;
	if(swfReg.test(url)){
		share_type = 4;
	}else{
		//音乐
		var mp3Reg = /^http:\/\/.*\.(mp3|wma)$/i;
		if(mp3Reg.test(url)){
			share_type = 3;		
		}else{
			//图片
			var imgReg = /^http:\/\/.*\.(jpg|bmp|gif|png)$/i;
			if(imgReg.test(url)){
				 share_type = 14;
			}else {
				//视频
				//youku
				var youkuReg = /^http:\/\/v.youku.com\/v_show\/.*\.html$/i;
				if(youkuReg.test(url)){
					url = url.replace(/v\.youku\.com\/v_show\/id_/g,'player.youku.com/player.php/sid/');
					url = url.replace(/\.html/g,'/v.swf');
					share_type = 2;
				}			
			   //Youtube
				var youtubeReg = /^http:\/\/www.youtube.com\/.*\/v\=([\w\-]+)/i;
				if(youtubeReg.test(url)&&share_type!=2){
					var rr = youtubeReg.exec(url);
					url = 'http://www.youtube.com/v/'+rr[1];
					share_type = 2;
				}else{
					var youtubeReg = /^http:\/\/www.youtube.com\/watch*/i;
					if(youtubeReg.test(url)&&share_type!=2){
						url = url.replace(/watch\?v=/g,'v\/');
						share_type = 2;
					}				
				}
				//--土豆
				var tudouReg = /^http:\/\/www.tudou.com\/programs\/view\/.*$/i;
				if(tudouReg.test(url)&&share_type!=2){
					url = url.substring(0,url.length-1);
					url = url.replace(/programs\/view/g,'v');
					share_type = 2;
				}
				//新浪
				var sina =  /^http:\/\/.*\.sina.com\/.*\/(\d+)-(\d+)\.html/i;
				if(sina.test(url)&&share_type!=2){
					//http://you.video.sina.com.cn/b/16776316-1338697621.html
					var rr = sina.exec(url);
					url = 'http://vhead.blog.sina.com.cn/player/outer_player.swf?vid='+rr[1];
					share_type = 2;
				}				
				//--酷六				
				//http://v.ku6.com/show/fzVc5EfrOsOfCsVv.html
				var ku6Reg = /^http:\/\/v.ku6.com\/show\/.*\.html$/i;				
				if(ku6Reg.test(url)&&share_type!=2){
					url = url.replace(/v\.ku6.com\/show/g,'player.ku6.com/refer');
					url = url.replace(/\.html/g,'/v.swf');		
					share_type = 2;
				}else{
					//http://v.ku6.com/special/show_3681503/uleBskm_QGCAsBlp.html
					var show = /^http:\/\/v.ku6.com\/.*\/([\w\-]+)\.html/i;
					if(show.test(url)&&share_type!=2){
						var rr = show.exec(url);
						url = 'http://player.ku6.com/refer/'+rr[1]+'/v.swf';
						share_type = 2;						
					}
				}

				//搜狐
				//http://v.blog.sohu.com/u/pw/257232#n1_v1
				var sohu = /^http:\/\/v.blog.sohu.com\/.*\/(\d+)\/*$/i;
				if(sohu.test(url)&&share_type!=2){
					var rr = sohu.exec(url);
					url = 'http://v.blog.sohu.com/fo/v4/'+rr[1];
					share_type = 2;
				}
				//mofile
				//http://v.mofile.com/show/PWPM94LJ.shtml
				var mofile = /.*\.mofile.com\/.*\/(\w+)\/*$/i;
				if(mofile.test(url)&&share_type!=2){
					var rr = mofile.exec(url);
					url = 'http://tv.mofile.com/cn/xplayer.swf?v='+rr[1];
					share_type = 2;
				}
				//5show
				var show = /^http:\/\/www.5show.com\/.*\/(\d+)\.shtml/i;
				if(show.test(url)&&share_type!=2){
					var rr = show.exec(url);
					url = 'http://www.5show.com/swf/5show_player.swf?flv_id='+rr[1];
					share_type = 2;
				}				
			}
		}
	}
	
	var dataInfo = "url="+escape(url)+"&typeId="+share_type;
	if(info) dataInfo += "&info="+info;
	if(fids) dataInfo += "&fids="+fids;
	
	$.ajax(
		   {    
		       url: APP+'/Index/doAddURL',
			   data: dataInfo,
			   type: 'POST', 
			   timeout: 10000,
			   dataType: 'json', 
			   success: function(txt){
				   $("#msg").remove(); 
				   //-2 分享的文件不存在 -1 网址为空 0 失败 1 成功
				   if(txt==0){
					   ymPrompt.errorInfo('分享失败!');
				   }else if(txt==-1){
					   ymPrompt.errorInfo('您已经分享过,请不要重复分享!');
				   }else if(txt==-2){
					   ymPrompt.errorInfo('分享类型出错!');
				   }else if(txt==-3){
					   ymPrompt.errorInfo('分享内容不能为空!');			   
				   }else if(txt==-4){
					   ymPrompt.errorInfo('请不要分享自己发布的东西!');	
				   }else if(txt==-5){
					   ymPrompt.errorInfo('参数出错!');				   
				   }else if(txt==-10){
					   ymPrompt.errorInfo('描述不能超过100个字!');			   
				   }else{
					   ymPrompt.succeedInfo('分享成功!',null,null,null,refurbish);			   
				   }
			   },			   
			   error: function(){
				   $("#msg").remove(); 
				   //alert("url="+url+"&typeId="+share_type+"&info="+info+"&fids="+fids);
				   ymPrompt.errorInfo({message:'提交超时,分享失败!'});
			   }
			 }
		);		
}

/**
 * 删除分享
 *
 */
function del(id){
    var delId = id;
	ymPrompt.confirmInfo('确认删除此分享',null,null,null,function(tp){	
		if(tp=='ok'){
			$.post(APP+'/Index/delAajax',{id:delId},function(txt){ 
				window.location.href=window.location.href;
			});
		}});	
}


function commentSuccess(json){
	//计数
	$.post(APP+'/Index/commentSuccess',{data:json},function(result){
    //返回评论数.
			});
}


function sharePop(id,url){
	$.post(url+"/add_share_check/", {tid:id}, function(txt){
		   if(txt==1){
			   ymPrompt.win(url+'/add_share/id/'+id,500,'315','分享',null,null,null,{id:'a'});
		   }else if(txt==-1){
			   ymPrompt.errorInfo('您已经分享过,请不要重复分享!');
		   }else if(txt==-2){
			   ymPrompt.errorInfo('请不要分享自己发布的东西!');
		   }else if(txt==-4){
			   ymPrompt.errorInfo('自己不能分享自己!');			   
		   }else{
			   ymPrompt.errorInfo('参数出错,请重试!');
		   }
	});
}


function doadd_share(url,id,info,fids){
	ymPrompt.close();
	$.post(url+"/doadd_share/", {id:id,info:info,fids:fids}, function(txt){

	/* @return  0 失败 1 成功 -1 已经分享 -2 分类出错 -3 内容为空  -4 不能分享自己的东西*/
		   if(txt==0){
			   ymPrompt.errorInfo('分享失败!');
		   }else if(txt==-1){
			   ymPrompt.errorInfo('您已经分享过,请不要重复分享!');
		   }else if(txt==-2){
			   ymPrompt.errorInfo('分享类型出错!');
		   }else if(txt==-3){
			   ymPrompt.errorInfo('分享内容不能为空!');			   
		   }else if(txt==-4){
			   ymPrompt.errorInfo('请不要分享自己发布的东西!');	
		   }else if(txt==-5){
			   ymPrompt.errorInfo('参数出错!');				   
		   }else if(txt==-10){
			   ymPrompt.errorInfo('描述不能超过100个字!');			   
		   }else{
			   ymPrompt.succeedInfo('分享成功!',null,null,null,refurbish);
		   }
	});	
	
}

function refurbish(){
   window.location.href=window.location.href;	
}