//播放flash的插件
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

/**
*关闭窗口
*
*/
function closereg(typeId){
	switch(typeId){
		case '1': $("#urlreg").remove(); break;
		case '2': $("#vodeoreg").remove(); break;
		case '3': $("#mp3Reg").remove(); break;
		case '4': $("#swfReg").remove(); break;
		case '7': $("#imgReg").remove(); break;
		default:$("#urlreg").remove(); 
	}
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

function comment(appid){
	$("#").css('display', 'block');	
}