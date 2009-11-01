<?php
//根据存储路径，获取照片真实URL
function get_photo_url($savepath) {
	$path	=	str_ireplace(UPLOAD_PATH,'',$savepath);
	$path	=	UPLOAD_URL.$path;
	return $path;
}

//根据存储路径，获取照片真实路径
function get_photo_path($savepath) {
	$path	=	str_ireplace(UPLOAD_PATH,'',$savepath);
	$path	=	UPLOAD_PATH.$path;
	return $path;
}

//根据文件名，去除后缀，获取照片名称
function get_photo_filename($filename) {
	return substr($filename,'0',strpos($filename,'.'));
}

//获取相册封面
function get_album_cover($albumId,$albumInfo='') {

	//获取相册详细信息
	if(empty($albumInfo) || $albumId!=$albumInfo['id']){
		$albumInfo	=	D('Album')->find($albumId);
	}

	//照片封面
	if(intval($albumInfo['photoCount'])>0 && !empty($albumInfo['coverImagePath'])){
		$cover	=	SITE_URL.'/thumb.php?w=120&h=100&url='.get_photo_url($albumInfo['coverImagePath']);
	}elseif(intval($albumInfo['photoCount'])==0){
		$covr	=	APP_PUBLIC_URL.'/images/photo_zwzp.gif';
	}else{
		$cover	=	APP_PUBLIC_URL.'/images/photo_bg.gif';
	}

	//根据隐私情况，判断相册封面
	if($albumInfo['privacy']==4){
		//密码可见
		$cover		=	APP_PUBLIC_URL.'/images/photo_mima.gif';
	}elseif($albumInfo['privacy']==3){
		//主人可见
		$cover		=	APP_PUBLIC_URL.'/images/photo_zrkj.gif';
	}elseif($albumInfo['privacy']==2){
		//显示相册只有他的好友可见
		$cover		=	APP_PUBLIC_URL.'/images/photo_hykj.gif';
	}
	return $cover;
}

//获取相册的可见度
function get_album_privacy($albumId,$privacy=0) {

	//获取相册详细信息
	if($privacy==0){
		$albumInfo		=	D('Album')->find($albumId);
		$privacy		=	$albumInfo['privacy'];
	}
	return get_privacy($privacy);
}

//获取照隐私
function get_privacy_code($privacy) {
	//根据隐私情况，显示相册隐私
	if($privacy==4){
		//持密码可见
		return 'password';
	}elseif($privacy==3){
		//仅主人可见
		return 'self';
	}elseif($privacy==2){
		//仅朋友可见
		return 'friend';
	}else{
		//任何人都可见
		return 'everyone';
	}
}


//获取照隐私
function get_privacy($privacy) {
	//根据隐私情况，显示相册隐私
	if($privacy==4){
		//持密码可见
		return '持密码可见';
	}elseif($privacy==3){
		//仅主人可见
		return '仅主人可见';
	}elseif($privacy==2){
		//仅朋友可见
		return '仅朋友可见';
	}else{
		//任何人都可见
		return '任何人都可见';
	}
}

//验证关系
function check_relationship($uid,$mid=0) {
	$api	=	new TS_API();
	if(!$mid){
		$mid	=	$api->user_getLoggedInUser();
	}
	if($uid==$mid){
		return 'self';
	}elseif($api->friend_areFriends($uid,$mid)){
		return 'friend';
	}else{
		return 'stranger';
	}
}
?>