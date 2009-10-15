<?php
//获取某模块记录总数
function getCount($model,$id){
	$count = D($model)->count($id);
	return $count;
}
//输出友好时间
function friendlyDate($sTime,$type = 'full',$alt = 'false') {
	//sTime=源时间，cTime=当前时间，dTime=时间差
	$cTime		=	time();
	$dTime		=	$cTime - $sTime;
	$dDay		=	intval(date("Ymd",$cTime)) - intval(date("Ymd",$sTime));
	$dYear		=	intval(date("Y",$cTime)) - intval(date("Y",$sTime));
	//normal：n秒前，n分钟前，n小时前，日期
	if($type=='normal'){
		if( $dTime < 60 ){
			echo $dTime."秒前";
		}elseif( $dTime < 3600 ){
			echo intval($dTime/60)."分钟前";
		}elseif( $dTime >= 3600 && $dDay == 0  ){
			echo intval($dTime/3600)."小时前";
		}elseif($dYear==0){
			echo date("Y-m-d ,H:i",$sTime);
		}else{
			echo date("Y-m-d ,H:i",$sTime);
		}
	//full: Y-m-d , H:i:s
	}elseif($type=='full'){
		echo date("Y-m-d , H:i",$sTime);
	}else{
		if( $dTime < 60 ){
			return $dTime."秒前";
		}elseif( $dTime < 3600 ){
			return intval($dTime/60)."分钟前";
		}elseif( $dTime >= 3600 && $dDay == 0  ){
			return intval($dTime/3600)."小时前";
		}elseif($dYear==0){
			return date("m-d ,H:i",$sTime);
		}else{
			return date("Y-m-d ,H:i",$sTime);
		}
	}
}
//js跳转
function jsGo($path,$msg) {
	if($msg!=""){
		echo "<script>alert('".$msg."');javascript:location.href='".$path."'</script>";
	}else{
		echo "<script>javascript:location.href='".$path."'</script>";
	}
}
//获取评论数
function getCommentCount($recordId,$module) {
	//评论数量
	$map = new HashMap();
	$map->put('recordId',$recordId);
	$map->put('module',$module);
	$dao	= D("Comment");
	$count	= $dao->count($map);
	return $count;
}
//解析UBB标签
function ubb($Text) {
	$Text=trim($Text);
	$Text=htmlspecialchars($Text);
	$Text=ereg_replace("\n","<br>",$Text);
	$Text=preg_replace("/\\t/is","  ",$Text);
	$Text=preg_replace("/\[hr\]/is","<hr>",$Text);
	$Text=preg_replace("/\[separator\]/is","<br/>",$Text);
	$Text=preg_replace("/\[h1\](.+?)\[\/h1\]/is","<h1>\\1</h1>",$Text);
	$Text=preg_replace("/\[h2\](.+?)\[\/h2\]/is","<h2>\\1</h2>",$Text);
	$Text=preg_replace("/\[h3\](.+?)\[\/h3\]/is","<h3>\\1</h3>",$Text);
	$Text=preg_replace("/\[h4\](.+?)\[\/h4\]/is","<h4>\\1</h4>",$Text);
	$Text=preg_replace("/\[h5\](.+?)\[\/h5\]/is","<h5>\\1</h5>",$Text);
	$Text=preg_replace("/\[h6\](.+?)\[\/h6\]/is","<h6>\\1</h6>",$Text);
	$Text=preg_replace("/\[center\](.+?)\[\/center\]/is","<center>\\1</center>",$Text);
	//$Text=preg_replace("/\[url=([^\[]*)\](.+?)\[\/url\]/is","<a href=\\1 target='_blank'>\\2</a>",$Text);
	$Text=preg_replace("/\[url\](.+?)\[\/url\]/is","<a href=\"\\1\" target='_blank'>\\1</a>",$Text);
	$Text=preg_replace("/\[url=(http:\/\/.+?)\](.+?)\[\/url\]/is","<a href='\\1' target='_blank'>\\2</a>",$Text);
	$Text=preg_replace("/\[url=(.+?)\](.+?)\[\/url\]/is","<a href=\\1>\\2</a>",$Text);
	$Text=preg_replace("/\[img\](.+?)\[\/img\]/is","<img src=\\1>",$Text);
	$Text=preg_replace("/\[img\s(.+?)\](.+?)\[\/img\]/is","<img \\1 src=\\2>",$Text);

	$Text=preg_replace("/\[face\](.+?)\[\/face\]/is","<img src=\"".WEB_PUBLIC_URL."/Images/biaoqing/\\1.gif\">",$Text);
	$Text=preg_replace("/\[(.+?)\]/is","<img src=\"".WEB_PUBLIC_URL."/Images/biaoqing/qq/\\1.gif\">",$Text);

	$Text=preg_replace("/\[color=(.+?)\](.+?)\[\/color\]/is","<font color=\\1>\\2</font>",$Text);
	$Text=preg_replace("/\[colorTxt\](.+?)\[\/colorTxt\]/eis","color_txt('\\1')",$Text);
	$Text=preg_replace("/\[style=(.+?)\](.+?)\[\/style\]/is","<div class='\\1'>\\2</div>",$Text);
	$Text=preg_replace("/\[size=(.+?)\](.+?)\[\/size\]/is","<font size=\\1>\\2</font>",$Text);
	$Text=preg_replace("/\[sup\](.+?)\[\/sup\]/is","<sup>\\1</sup>",$Text);
	$Text=preg_replace("/\[sub\](.+?)\[\/sub\]/is","<sub>\\1</sub>",$Text);
	$Text=preg_replace("/\[pre\](.+?)\[\/pre\]/is","<pre>\\1</pre>",$Text);
	$Text=preg_replace("/\[emot\](.+?)\[\/emot\]/eis","emot('\\1')",$Text);
	$Text=preg_replace("/\[email\](.+?)\[\/email\]/is","<a href='mailto:\\1'>\\1</a>",$Text);
	$Text=preg_replace("/\[i\](.+?)\[\/i\]/is","<i>\\1</i>",$Text);
	$Text=preg_replace("/\[u\](.+?)\[\/u\]/is","<u>\\1</u>",$Text);
	$Text=preg_replace("/\[b\](.+?)\[\/b\]/is","<b>\\1</b>",$Text);
	$Text=preg_replace("/\[quote\](.+?)\[\/quote\]/is","<blockquote>引用:<div style='border:1px solid silver;background:#EFFFDF;color:#393939;padding:5px' >\\1</div></blockquote>", $Text);
	$Text=preg_replace("/\[code\](.+?)\[\/code\]/eis","highlight_code('\\1')", $Text);
	$Text=preg_replace("/\[php\](.+?)\[\/php\]/eis","highlight_code('\\1')", $Text);
	$Text=preg_replace("/\[sig\](.+?)\[\/sig\]/is","<div style='text-align: left; color: darkgreen; margin-left: 5%'><br><br>--------------------------<br>\\1<br>--------------------------</div>", $Text);
	return $Text;
}
//过滤脚本代码
function cleanJs($text){
	$text	=	trim($text);
	$text	=	stripslashes($text);
	//完全过滤动态代码
	$text	=	preg_replace('/<\?|\?>/is','',$text);
	//完全过滤js
	$text	=	preg_replace('/<script?.*\/script>/is','',$text);
	//过滤多余html
	$text	=	preg_replace('/<\/?(html|head|meta|link|base|body|title|style|script|form|iframe|frame|frameset)[^><]*>/is','',$text);
	//过滤on事件lang js
	while(preg_match('/(<[^><]+)(lang|onfinish|onmouse|onexit|onerror|onclick|onkey|onload|onchange|onfocus|onblur)[^><]+/is',$text,$mat)){
		$text=str_replace($mat[0],$mat[1],$text);
	}
	while(preg_match('/(<[^><]+)(window\.|javascript:|js:|about:|file:|document\.|vbs:|cookie)([^><]*)/is',$text,$mat)){
		$text=str_replace($mat[0],$mat[1].$mat[3],$text);
	}
	$text	=	str_ireplace('script','s cript',$text);
	return GFW($text);
}
//纯文本输出
function t($text){
	$text	=	cleanJs($text);
	$text	=	strip_tags($text);
	return $text;
}
//输出安全的html
function h($text){
	$text	=	cleanJs($text);
	return $text;
}
//替换非法关键字
function GFW($text) {
	//替换成开头大写字母
	$word		=	D('BbsConfig')->find("bbskey='bantext'",'bbsval')->bbsval;
	$words	=	explode('|',$word);
	foreach($words as $v){
		$text	=	str_ireplace(trim($v),' ** ',$text);
	}
	return $text;
}
//替换非法用户名
function GFW1($text) {//判断昵称中是否有非法字符
	$word		=	D('BbsConfig')->find("bbskey='bannick'",'bbsval')->bbsval;
	$words	=	explode('|',$word);
	return in_array(trim($text),$words);
}
//获取内容中第一条图片
function getFirstImage(&$content)  {
	$content	=	stripslashes(trim($content));
	//获取内容中图片
	//取得第一个匹配的图片路径
	$retimg="";
	$matches=null;
	//标准的src="xxxxx"或者src='xxxxx'写法
	preg_match("/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i", $content, $matches);
	if(isset($matches[2])){
		$retimg=$matches[2];
		unset($matches);
		return $retimg;
	}
	//非标准的src=xxxxx 写法
	unset($matches);
	$matches=null;
	preg_match("/<\s*img\s+[^>]*?src\s*=\s*(.*?)[\s\"\'>][^>]*?\/?\s*>/i", $content, $matches);
	if(isset($matches[1])){
		$retimg=$matches[1];
	}
	unset($matches);
	return $retimg;
}
//匹配内容中的所有图片
function matchImages($content) {
	$src	=	array();
	preg_match_all('/<img.*src=(.*)\\s*.*>/iU',$content,$src);
	if(count($src[1])>0){
		foreach($src[1] as $v){
			$image	=	str_replace("'",'',$v);
			$image	=	str_replace('"','',$image);
			$images[] =	$image;
		}
		return $images;
	}else{
		return false;
	}
}
//删除内容中的所有图片标签
function cleanImages($content) {
	$content	=	stripslashes($content);
	$content	=	preg_replace("/<img.*>/iU"," ",$content);
	return $content;
}
//获取用户信息，缓存在session中.
function getUserInfo($uid){
	if($uid>0){
		//$userinfo	=	Session::get('UserInfo_'.$uid);
		//if(!$userinfo){
			$userinfo	=	D('User')->find($uid);
			$userinfo->whatgroup = $userinfo->isengineer?'专家':'普通用户';
			//Session::set('UserInfo_'.$uid,$userinfo);
		//}
		return $userinfo;
	}else{
		return '';
	}
}
//获取用户名
function getUserName($uid,$type=''){
	if(!intval($uid)) return '';
	if($uid==$_SESSION['mid'] && $type=='me'){
		return '我';
	}else{
		$info	=	getUserInfo($uid);
		return $info->account;
	}
}
//获取用户省份
function getUserProvince($userId){
	$dao	=	D("UserWorks");
	$list	=	$dao->find("userId='$userId'",'province');
	$province	=	$list->province;
	return $province;
}
//获取用户的心情
function getUserMini($userId){
	$dao	=	D("Blog");
	$list	=	$dao->findAll("userId='$userId'",'content','cTime DESC','1');
	$news	=	$list[0]->content;
	return $news;
}
//获取用户的分段HashId，用户分布式存储用户信息
function getSID($userId = '999') {
	$member['id']	=	1000000+intval($userId);
	$member['m']	=	floor($member['id']/1000000);
	$member['k']	=	floor($member['id']/1000);
	return $member;
}
//获取用户头像
function getUserFace($userId,$size='s') {
	//切图 s=48x48 维持比例 m=100x200 b=200x* o=原图大小
	$sizearray	=	array('s','m','b','o');
	if(in_array($size,$sizearray)){
		$s	=	$size;
	}else{
		$s	=	's';
	}
	$sid	=	getSID($userId);
	$face	=	'http://'.$_SERVER['HTTP_HOST'].WEB_PUBLIC_URL.'/Uploads/User/'.$userId.'/face_'.$s.'.jpg';
	$outface=	'./Public/Uploads/User/'.$userId.'/face_'.$s.'.jpg';
	/** /
	//图片分离的时候可以启用 判断远程文件是否存在
	$exists	=	remote_file_exists($face);
	/**/
	//dump($outface);
	if(!file_exists($outface)){
		return WEB_PUBLIC_URL.'/Images/noface.gif';
		exit;
	}
	return $face;
}
//判断远程文件是否存在
function remote_file_exists($url_file){
 //检测输入
 $url_file = trim($url_file);
 if (empty($url_file)) { return false; }
 $url_arr = parse_url($url_file);
 if (!is_array($url_arr) || empty($url_arr)){ return false; }

 //获取请求数据
 $host = $url_arr['host'];
 $path = $url_arr['path'] ."?". $url_arr['query'];
 $port = isset($url_arr['port']) ? $url_arr['port'] : "80";

 //连接服务器
 $fp = fsockopen($host, $port, $err_no, $err_str, 30);
 if (!$fp){ return false; }

 //构造请求协议
 $request_str = "GET ".$path." HTTP/1.1\r\n";
    $request_str .= "Host: ".$host."\r\n";
    $request_str .= "Connection: Close\r\n\r\n";

 //发送请求
    fwrite($fp, $request_str);
 $first_header = fgets($fp, 1024);
    fclose($fp);

 //判断文件是否存在
 if (trim($first_header) == ""){ return false; }
 if (!preg_match("/200/", $first_header)){
  return false;
 }
 return true;
}
//获取用户好友列表
function getUserFriends($userId,$type='array') {
	$friends	=	array();
	$dao	=	D('UserFriend');
	$list	=	$dao->findAll("userId='$userId'");
	if($list){
		foreach($list as $k=>$v){
			$friends[]	=	$v->friendId;
		}
	}
	if($type=='array'){
		return $friends;
	}elseif($type=='string'){
		$fs	=	implode(',',$friends);
		if(!empty($fs)){
			return $fs;
		}else{
			return '0';
		}
	}else{
		return false;
	}
}
//获取用户的性别
function getUserSex($id) {
	$info	=	getUserInfo($id,'sex');
	$sex	=	getSex($list->sex);
	return $sex;
}
//截断文章内容
function getShort($title,$length=40){
	$suf = mb_strlen($title,'utf-8')>$length?true:false;
	return msubstr($title,0,$length,'utf-8',$suf);
}
//通过性别码获取性别的中文显示
function getSex($id) {
	if($id==1){
		echo "男";
	}elseif($id==2){
		echo "女";
	}else{
		echo "未知";
	}
}
//通过地区id获取地区名
function getAreaName($areaId){
	if($areaId==0) {
		return '';
	}
	if(Session::is_set('areaName')) {
		$name	=	Session::get('areaName');
		return $name[$userId];
	}
	$dao	=	D("Area");
	$list	=	$dao->findAll('','areaId,name');
	$nameList	=	$list->getCol('areaId,name');
	$name	=	$nameList[$areaId];
	Session::set('areaName',$nameList);
	return $name;
}
//ipEnCode
function ipEncode($ip) {
	$ip	=	explode('.',$ip);
	//直接位运算会溢出
	foreach($ip as $k=>$v){
		$ipBin	.=	str_pad(decbin($v), 8, "0", STR_PAD_LEFT);
	}
	$ipDec	=	bindec($ipBin);
	return	$ipDec;
}
//ipDeCode
function ipDecode($ipDec) {
	$ipBin	=	decbin($ipDec);
	$a	=	bindec(substr($ipBin,0,8));
	$b	=	bindec(substr($ipBin,8,8));
	$c	=	bindec(substr($ipBin,16,8));
	$d	=	bindec(substr($ipBin,24,8));
	$ip	=	$a.$b.$c.$d;
	return	$ip;
}
function getTagName($tagId) {
	$dao = D('Tag');
	$list = $dao->find($tagId);
	return $list->name;
}
function getTagId($tagName,$module='network') {
	$dao = D('Tag');
	$list = $dao->find("name='$tagName' and module='$module'");
	return $list->id;
}
function getTagNames($tags) {
	$dao = D('Tag');
	$list = $dao->findAll("id in ($tags)");
	foreach($list as $v){
		$names[]	=	" <a href='".__APP__."/Tag/index/module/{$v->module}/tagId/{$v->id}'>{$v->name}</a> ";
	}
	return implode(',',$names);
}
function getImageByFiles($image) {
	$dot_place	=	strrpos($image,'.');
	$imageHash	=	substr($image,0,$dot_place);
	$hashPath	=	substr($imageHash,0,2).'/'.substr($imageHash,2,2).'/'.$image;
	return	$hashPath;
}
//检查并创建多级目录
function checkDir($path){
	$pathArray = explode('/',$path);
	$nowPath = '';
	array_pop($pathArray);
	foreach ($pathArray as $key=>$value){
		if ( ''==$value ){
			unset($pathArray[$key]);
		}else{
			if ( $key == 0 )
				$nowPath .= $value;
			else
				$nowPath .= '/'.$value;
			if ( !is_dir($nowPath) ){
				if ( !mkdir($nowPath, 0777) ) return false;
			}
		}
	}
	return true;
}
//取得文件后缀名
function getSuffix($filename) {
	$dot_place = strrpos($filename,'.');
	$ext_name = substr($filename,$dot_place+1);
	return $ext_name;
}
//检查用户的管理权限
function checkAdmin($name='quan',$id){
	$userId	=	Session::get('mid');
	//取得id的name的管理权限
	if($name == 'Group'){
		$dao	=	D('GroupMember');
		$info	=	$dao->find("userId='$userId' and groupId='$id'");
		if($info->level == 2){
			return true;
		}
	}else
	if($name == 'quan'){
		$dao	=	D('UserPower');
		$power	=	$dao->getBy('userId',$userId);
		if($power->rank==$name && $power->level==1){
			return true;
		}
	}
	return false;
}
//判断是否是我的朋友
function isMyFriend($userId) {
	$mid	=	$_SESSION['mid'];
	$fid	=	$userId;
	$result	=	D('UserFriend')->count("userId='$mid' and friendId='$fid'");
	if($result==0){
		return false;
	}else{
		return true;
	}
}
//判断两个人是否是朋友
function areFriends($uid,$fid) {
	$result	=	D('UserFriend')->count("userId='$uid' and friendId='$fid'");
	if($result==0){
		return false;
	}else{
		return true;
	}
}
//加密函数
function jiami($txt,$key){
	if(empty($key)) $key = C('SECURE_CODE');
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-=+";
	$nh = rand(0,64);
	$ch = $chars[$nh];
	$mdKey = md5($key.$ch);
	$mdKey = substr($mdKey,$nh%8, $nh%8+7);
	$txt = base64_encode($txt);
	$tmp = '';
	$i=0;$j=0;$k = 0;
	for ($i=0; $i<strlen($txt); $i++) {
		$k = $k == strlen($mdKey) ? 0 : $k;
		$j = ($nh+strpos($chars,$txt[$i])+ord($mdKey[$k++]))%64;
		$tmp .= $chars[$j];
	}
	return $ch.$tmp;
}
//解密函数
function jiemi($txt,$key){
	if(empty($key)) $key = C('SECURE_CODE');
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-=+";
	$ch = $txt[0];
	$nh = strpos($chars,$ch);
	$mdKey = md5($key.$ch);
	$mdKey = substr($mdKey,$nh%8, $nh%8+7);
	$txt = substr($txt,1);
	$tmp = '';
	$i=0;$j=0; $k = 0;
	for ($i=0; $i<strlen($txt); $i++) {
		$k = $k == strlen($mdKey) ? 0 : $k;
		$j = strpos($chars,$txt[$i])-$nh - ord($mdKey[$k++]);
		while ($j<0) $j+=64;
		$tmp .= $chars[$j];
	}
	return base64_decode($tmp);
}
//解析TML标签
function parseTML($content,$tagLib='fb',$params) {
	$content	=	'<taglib name="'.$tagLib.'" /> '.$content;
	$output	=	A('Public')->fetchContent($content);
	return $output;
}
function setScore($userId,$score='0',$info='',$params) {
	/* return 1,成功 0,用户Id错误 -1,积分不足 -2,积分减少失败 2,积分减少成功但日志记录失败*/
	if(!empty($userId)){
		$user	=	D('User');
		if($score>0){
			$result	=	$user->setInc('score','id='.$userId,$score);
		}elseif($score<0){
			$leftScore	=	$user->find($userId,'score')->score;
			if($leftScore > $score){
				$result	=	$user->setDec('score','id='.$userId,abs($score));
			}else{
				return -1;
			}
		}
		if($result){
			$dao = D("UserScore");
			$dao->userId	=	$userId;
			$dao->score		=	$score;
			$dao->info		=	t($info); //积分详情
			$dao->cTime		=	NOW;
			if($params['admin']){
				$dao->admin		=	$params['admin']; //处理金币的管理员
			}
			$dao->status	=	1;	//0 未生效 1 生效
			$add = $dao->add();
			if($add){
				return 1;
			}else{
				return 2;
			}
		}else{
			return -2;
		}
	}else{
		return 0;
	}
	/**/
}
//记录用户动态新方法
function addFeed($userId,$action,$title,$info='',$data='') {
	if(!empty($userId)){
		$dao = D("UserFeed");
		$dao->userId	=	$userId;
		$dao->action	=	$action;
		$dao->title		=	$title;
		$dao->info		=	$info;
		$dao->cTime		=	NOW;
		$dao->day		=	date('Y-m-d',NOW);
		$dao->status	=	0;
		$result = $dao->add();
		return $result;
	}else{
		return false;
	}
}
//显示一类标签的列表
function getTagSelect($type,$selected,$call='') {
	$dao = D('Tag');
	$list	=	$dao->findALl("module='$type'");
	if($call!=''){
		$callstring		=	"onchange=\"".$call."(this.value);\"" ;
	}
	$s	=	"<select name=\"$type\" ".$callstring.">\n<option value=\"\"> = 请选择 = </option>";
	foreach($list as $v){
		if($v->id == $selected){
			$s	.=	"<option value=\"$v->id\" selected=\"selected\">$v->name</option>\n";
		}else{
			$s	.=	"<option value=\"$v->id\">$v->name</option>\n";
		}
	}
	$s	.=	'</select>';
	return $s;
}

//edit　by clear
function getfcredits($type = 'top') {
	$credits = D('Threads')->getCredits($type);
	return $credits;
}

function getfnews($type ='top') {
	$news = D('Threads')->getNews($type);
	return $news;
}

function getfhots($type ='top') {
	$news = D('Threads')->getHots($type);
	return $news;
}

function getfperfects($type='top') {
	$perfects = D('Threads')->getfperfects($type);
	return $perfects;
}


function getflibraries($type = 'top') {
	$libraries = D('Threads')->getflibrary($type);
	return $libraries;
}

function getfmythreads($type ='top') {
	$mythreads = D('MyThreads')->getMythreads($type);
	return $mythreads;
}

function getfmyposts($type ='top') {
	$myposts = D('MyPosts')->getMyposts($type);
	return $myposts;
}

/* 跟上传相关的函数 */
function getPhotoUploadsPath($albumId = ''){
	//每100个相册放到一个文件夹
	$photo_upload_path	=	C('UPLOAD_PATH').'photos/'.ceil($albumId/100).'/album_'.ceil($albumId/100).'_'.$albumId.'/';
	checkDir($photo_upload_path);
	return $photo_upload_path;
}

function getUploadsPath($module='attach'){
	//每100个相册放到一个文件夹
	$upload_path	=	C('UPLOAD_PATH').$module.'/'.date('Y',time()).'/'.date('m-d',time()).'/';
	checkDir($upload_path);
	return $upload_path;
}
function getfdeals() {
	$deals		= D('Threads')->getdeals(1,5);
	return $deals;
}

function getfundeals() {
	$undeals	= D('Threads')->getUndeals(1,5);
	return $undeals;
}
//获取登录用户名和信息
function getUserCredit($uid,$href=false){
	$info	=	getUserInfo($uid);
	return $href?'<a href='.__APP__.'/Forum/input'.'>'.$info->credit.'</a>':$info->credit;
}
//获取用户积分头衔
function getUserCreditText($uid,$href = false) {
	if($uid>0){
		$info	= getUserInfo($uid);
		//$array	= array('一级'=>'100','二级'=>'500','三级'=>'1000','四级'=>'2500','五级'=>'5000','六级'=>'8000','七级'=>'12000','八级'=>'16000','九级'=>'20000','十级'=>'25000','十一级'=>'35000','十二级'=>'50000','十三级'=>'80000','十四级'=>'120000','十五级'=>'180000','十六级'=>'250000','十七级'=>'400000');

		//读出头衔-积分信息
		//2009-4-2 增加 缓存头衔数组
		$touxian	=	Session::get('BbsInfo_touxian');
		if(!$touxian){
			$dao = D("Touxian");
			$txs = $dao->order("fen asc")->findAll();
			foreach($txs as $v){
				$array[$v->tx] = $v->fen;
			}
			$touxian	=	Session::set('BbsInfo_touxian',$array);
		}
		$credit = $info->credit;
		foreach($touxian as $k=>$v){
			if(	intval($v) >$credit ){
				$ret = $k;
				break;
			}
		}
		if(!$ret) $ret = '十八级';

		return $href?'<a href=>'.__APP__.''.'>'.$ret.'</a>':$ret;
	}else{
		return '';
	}
}

/*function getUserCreditText($uid,$href = false) {
	$info	= getUserInfo($uid);
	$array	= array('一级'=>'100','二级'=>'500','三级'=>'1000','四级'=>'2500','五级'=>'5000','六级'=>'8000','七级'=>'12000','八级'=>'16000','九级'=>'20000','十级'=>'25000','十一级'=>'35000','十二级'=>'50000','十三级'=>'80000','十四级'=>'120000','十五级'=>'180000','十六级'=>'250000','十七级'=>'400000');
	$credit = $info->credit;
	foreach($array as $k=>$v){
		if(	intval($v) >$credit ){
			$ret = $k;
			break;
		}
	}
	if(!$ret) $ret = '十八级';

	return $href?'<a href=>'.__APP__.''.'>'.$ret.'</a>':$ret;
}
*/
//获取积分设置
function getCreditConfig($key = '') {
	$where = "name='credit'";
	$credits = D('SystemConfig')->find($where,'value')->value;
	$credits = str_replace('\\','',$credits);
	$credits = unserialize($credits);
	return $key?intval($credits[$key]):$credits;
}
//获取主题内容
function getThreadContent($tid){
	/* 2009-4-2 更新增加空置判断 */
	if($tid>0){
		$content = D("Posts")->find("tid=$tid","cont")->cont;
		return $content;
	}else{
		return 0;
	}
}
//补充函数，增加bmp支持
function imagecreatefrombmp($fname) {

	$buf=@file_get_contents($fname);

	if(strlen($buf)<54) return false;

	$file_header=unpack("sbfType/LbfSize/sbfReserved1/sbfReserved2/LbfOffBits",substr($buf,0,14));

	if($file_header["bfType"]!=19778) return false;
	$info_header=unpack("LbiSize/lbiWidth/lbiHeight/sbiPlanes/sbiBitCountLbiCompression/LbiSizeImage/lbiXPelsPerMeter/lbiYPelsPerMeter/LbiClrUsed/LbiClrImportant",substr($buf,14,40));
	if($info_header["biBitCountLbiCompression"]==2) return false;
	$line_len=round($info_header["biWidth"]*$info_header["biBitCountLbiCompression"]/8);
	$x=$line_len%4;
	if($x>0) $line_len+=4-$x;

	$img=imagecreatetruecolor($info_header["biWidth"],$info_header["biHeight"]);
	   switch($info_header["biBitCountLbiCompression"]){
	  case 4:
	   $colorset=unpack("L*",substr($buf,54,64));
	   for($y=0;$y<$info_header["biHeight"];$y++){
		$colors=array();
		$y_pos=$y*$line_len+$file_header["bfOffBits"];
		for($x=0;$x<$info_header["biWidth"];$x++){
		 if($x%2)
		  $colors[]=$colorset[(ord($buf[$y_pos+($x+1)/2])&0xf)+1];
		 else
		  $colors[]=$colorset[((ord($buf[$y_pos+$x/2+1])>>4)&0xf)+1];
		}
		imagesetstyle($img,$colors);
		imageline($img,0,$info_header["biHeight"]-$y-1,$info_header["biWidth"],$info_header["biHeight"]-$y-1,IMG_COLOR_STYLED);
	   }
	   break;
	  case 8:
	   $colorset=unpack("L*",substr($buf,54,1024));
	   for($y=0;$y<$info_header["biHeight"];$y++){
		$colors=array();
		$y_pos=$y*$line_len+$file_header["bfOffBits"];
		for($x=0;$x<$info_header["biWidth"];$x++){
		 $colors[]=$colorset[ord($buf[$y_pos+$x])+1];
		}
		imagesetstyle($img,$colors);
		imageline($img,0,$info_header["biHeight"]-$y-1,$info_header["biWidth"],$info_header["biHeight"]-$y-1,IMG_COLOR_STYLED);
	   }
	   break;
	  case 16:
	   for($y=0;$y<$info_header["biHeight"];$y++){
	   $colors=array();
	   $y_pos=$y*$line_len+$file_header["bfOffBits"];
	   for($x=0;$x<$info_header["biWidth"];$x++){
		$i=$x*2;
		$color=ord($buf[$y_pos+$i])|(ord($buf[$y_pos+$i+1])<<8);
		$colors[]=imagecolorallocate($img,(($color>>10)&0x1f)*0xff/0x1f,(($color>>5)&0x1f)*0xff/0x1f,($color&0x1f)*0xff/0x1f);
	   }
	   imagesetstyle($img,$colors);
	   imageline($img,0,$info_header["biHeight"]-$y-1,$info_header["biWidth"],$info_header["biHeight"]-$y-1,IMG_COLOR_STYLED);
	   }
	   break;
	  case 24:
	   for($y=0;$y<$info_header["biHeight"];$y++){
		$colors=array();
		$y_pos=$y*$line_len+$file_header["bfOffBits"];
		for($x=0;$x<$info_header["biWidth"];$x++){
		 $i=$x*3;
		 $colors[]=imagecolorallocate($img,ord($buf[$y_pos+$i+2]),ord($buf[$y_pos+$i+1]),ord($buf[$y_pos+$i]));
		}
		imagesetstyle($img,$colors);
		imageline($img,0,$info_header["biHeight"]-$y-1,$info_header["biWidth"],$info_header["biHeight"]-$y-1,IMG_COLOR_STYLED);
	   }
	   break;
	  default:
	   return false;
	   break;
	}
	return $img;
}
//判断用户是否是工程师
function isAdmin($uid) {
	$user = getUserInfo($uid);
	return $user->isengineer?true:false;
}
//判断用户是否被锁定
function islock($uid) {
	$user = getUserInfo($uid);
	return $user->islockid?true:false;
}
//获取主题的分类
function getThreadCat($tid = 0,$pid = 0) {

	if($pid){
		$post = D('Posts')->find($pid);
		if(!$post){
			return false;
		} else {
			$tid = $post->tid;
		}
	}

	$thread = D('Threads')->find($tid);
	if($thread){
		$forum	= D('BbsForums')->find($thread->fid);//小类别
		$group	= D('BbsForums')->find($forum->pid); //大类别
	}else{
		return false;
	}

   // if($big) return $group["name"];
    //else return $forum["name"];
	return compact('group','forum');
}
//获取完美答案相关信息
function getPerfectUid($tid) {
	if( !$thread = D('Threads')->find($tid) ){
		$error[] = '帖子不存在';
	}

	if(!$thread->ttype =='common'){
		$error[] = '该帖子为普通贴';
	}

	if(	$thread->tstatus !='userperfect' && $thread->tstatus !='adminperfect'	){
		$error[] = '该帖子尚未解决';
	}

	if($error){
		return false;
	}else{
		$post = D('Posts')->find('tid='.$tid.' AND isperfect=1');
		return getUserInfo($post->uid);
	}
}
//
function dealtextarea($text) {
	return nl2br(t($text));
}

function gettodaytime() {
	return strtotime(date('Y-m-d',time()));
}

//识别汉字编码
function safeEncoding($string,$outEncoding = 'UTF-8'){
    $encoding = "UTF-8";
    for($i=0;$i<strlen($string);$i++)
    {
        if(ord($string{$i})<128)
            continue;

        if((ord($string{$i})&224)==224)
        {
            //第一个字节判断通过
            $char = $string{++$i};
            if((ord($char)&128)==128)
            {
                //第二个字节判断通过
                $char = $string{++$i};
                if((ord($char)&128)==128)
                {
                    $encoding = "UTF-8";
                    break;
                }
            }
        }
        if((ord($string{$i})&192)==192)
        {
            //第一个字节判断通过
            $char = $string{++$i};
            if((ord($char)&128)==128)
            {
                //第二个字节判断通过
                $encoding = "GB2312";
                break;
            }
        }
    }

    if(strtoupper($encoding) == strtoupper($outEncoding))
        return $string;
    else
        return iconv($encoding,$outEncoding,$string);
}

function size_format( $bytes, $decimals = null ) {
	$quant = array(
		// ========================= Origin ====
		'TB' => 1099511627776,  // pow( 1024, 4)
		'GB' => 1073741824,     // pow( 1024, 3)
		'MB' => 1048576,        // pow( 1024, 2)
		'kB' => 1024,           // pow( 1024, 1)
		'B ' => 1,              // pow( 1024, 0)
	);

	foreach ( $quant as $unit => $mag )
		if ( doubleval($bytes) >= $mag )
			return number_format_i18n( $bytes / $mag, $decimals ) . ' ' . $unit;

	return false;
}

function number_format_i18n( $number, $decimals = null ) {
	return number_format( $number, $decimals );
}

function getgroupundeal($gid) {
	/* 2009-4-2 增加空值判断 */
	if(!$gid) return ;

	$fids = D('BbsForums')->findAll('pid='.$gid);
	foreach($fids as $k=>$v){
		$fid[] = $v->id;
	}
	$where = " isdel = 0 AND ttype !='common' AND isperfect = 0 ";
	$where .= 'AND fid in ('.implode(',',$fid).')';
	$num = D('Threads')->count($where);
	return $num;
}

function getgroupdeal($gid) {
	/* 2009-4-2 增加空值判断 */
	if(!$gid) return ;

	$fids = D('BbsForums')->findAll('pid='.$gid);
	foreach($fids as $k=>$v){
		$fid[] = $v->id;
	}
	$where = "	isdel = 0 AND ttype !='common' AND isperfect = 1";
	$where .= 'AND fid in ('.implode(',',$fid).')';
	$num = D('Threads')->count($where);
	return $num;
}

function getgroupshare($gid) {
	/* 2009-4-2 增加空值判断 */
	if(!$gid) return ;

	$fids = D('BbsForums')->findAll('pid='.$gid);
	foreach($fids as $k=>$v){
		$fid[] = $v->id;
	}
	$where = "isdel = 0 AND ttype ='common'";
	$where .= 'AND fid in ('.implode(',',$fid).')';
	$num = D('Threads')->count($where);
	return $num;
}

//通过论坛大类返回论坛小类
function getfids($gid = 0,$return = 'arr') {
	if(!$gid) return ;

	$forums = D('BbsForums')->findAll('pid='.$gid);

	if(!$forums) return ;

	foreach($forums as $k=>$v){
		$fids[] = $v->id;
	}
	return strtolower($return) == 'arr'?$fids:implode(',',$fids);
}

//返回操作系统下拉惨淡
function getosselect() {
	$array = array('Windows 2003'=>'Windows 2003','Windows XP'=>'Windows XP','Windows 2000'=>'Windows 2000','Windows Vista'=>'Windows Vista','Windows NT'=>'Windows NT','Windows ME'=>'Windows ME','Windows 98'=>'Windows 98','Windows 95'=>'Windows 95','Windows 32'=>'Windows 32','Windows CE'=>'Windows CE','Linux'=>'Linux','Unix'=>'Unix','SunOS'=>'SunOS','IBM OS/2'=>'IBM OS/2','Macintosh'=>'Macintosh','PowerPC'=>'PowerPC','AIX'=>'AIX','HPUX'=>'HPUX','NetBSD'=>'NetBSD','BSD'=>'BSD','OSF1'=>'OSF1','IRIX'=>'IRIX','FreeBSD'=>'FreeBSD');
	return $array;
}

//返回操作系统
function get_os() {
	if (empty($_SERVER['HTTP_USER_AGENT']))
	{
		return 'Unknown';
	}

	$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
	$os    = '';

	if (strpos($agent, 'win') !== false)
	{
		if (strpos($agent, 'nt 5.1') !== false)
		{
			$os = 'Windows XP';
		}
		elseif (strpos($agent, 'nt 5.2') !== false)
		{
			$os = 'Windows 2003';
		}
		elseif (strpos($agent, 'nt 5.0') !== false)
		{
			$os = 'Windows 2000';
		}
		elseif (strpos($agent, 'nt 6.0') !== false)
		{
			$os = 'Windows Vista';
		}
		elseif (strpos($agent, 'nt') !== false)
		{
			$os = 'Windows NT';
		}
		elseif (strpos($agent, 'win 9x') !== false && strpos($agent, '4.90') !== false)
		{
			$os = 'Windows ME';
		}
		elseif (strpos($agent, '98') !== false)
		{
			$os = 'Windows 98';
		}
		elseif (strpos($agent, '95') !== false)
		{
			$os = 'Windows 95';
		}
		elseif (strpos($agent, '32') !== false)
		{
			$os = 'Windows 32';
		}
		elseif (strpos($agent, 'ce') !== false)
		{
			$os = 'Windows CE';
		}
	}
	elseif (strpos($agent, 'linux') !== false)
	{
		$os = 'Linux';
	}
	elseif (strpos($agent, 'unix') !== false)
	{
		$os = 'Unix';
	}
	elseif (strpos($agent, 'sun') !== false && strpos($agent, 'os') !== false)
	{
		$os = 'SunOS';
	}
	elseif (strpos($agent, 'ibm') !== false && strpos($agent, 'os') !== false)
	{
		$os = 'IBM OS/2';
	}
	elseif (strpos($agent, 'mac') !== false && strpos($agent, 'pc') !== false)
	{
		$os = 'Macintosh';
	}
	elseif (strpos($agent, 'powerpc') !== false)
	{
		$os = 'PowerPC';
	}
	elseif (strpos($agent, 'aix') !== false)
	{
		$os = 'AIX';
	}
	elseif (strpos($agent, 'hpux') !== false)
	{
		$os = 'HPUX';
	}
	elseif (strpos($agent, 'netbsd') !== false)
	{
		$os = 'NetBSD';
	}
	elseif (strpos($agent, 'bsd') !== false)
	{
		$os = 'BSD';
	}
	elseif (strpos($agent, 'osf1') !== false)
	{
		$os = 'OSF1';
	}
	elseif (strpos($agent, 'irix') !== false)
	{
		$os = 'IRIX';
	}
	elseif (strpos($agent, 'freebsd') !== false)
	{
		$os = 'FreeBSD';
	}
	else
	{
		$os = 'Unknown';
	}

	return $os;
}

function buildselect($arr=array(),$selected = '') {
		$str = '';
		foreach($arr as $k=>$v){
			$str .= '<option value="'.$v.'"';
			if($v == $selected)$str .= ' selected';
			$str .= '>'.$v.'</option>';
		}
		return $str;
}

function buildforum() {
	$forums = D('BbsForums')->findAll('pid!=0');
	foreach($forums as $k=>$v){
		$ret[$v->id] = $v->name;
	}
	return $ret;
}

function getgroupselect() {
	$groups = D('BbsForums')->findAll('pid=0');
	foreach($groups as $k=>$v){
		$ret[$v->id] = $v->name;
	}
	return $ret;
}

function buildjsonsort($userques=array()) {
		foreach($userques as $k=>$v){
			$temparr['t'] = $v->name;
			$temparr['a'] = $v->id;
			if($v->child){
				$temparr['d'] = buildjsonsort($v->child);
			}
			$result[] = $temparr;
			unset($temparr);
		}
		return $result;
}

function checktodel($text) {
	$todel = D('BbsConfig')->find("bbskey='todel'",'bbsval')->bbsval;
	$todel = explode('|',$todel);

	foreach($todel as $k=>$v){
		$v  = strip_tags(trim($v));
		if(  strpos($text,$v)  !==false )return true;
	}
	return false;
}

function isverifyshow() {
	if($uid = $_SESSION['mid']){
		$user = getUserInfo($uid);
		if($user->isengineer) return false;
	}
	$today  = getdate(time());
	$hour	= $today['hours'];
	return ($hour<9 || $hour>17);
	//return true;
}

//是否显示菜单
function isShowMenu($uid,$menu) {
	return D('AdminUser')->isShowMenu($uid,$menu);
}
//是否拥有操作权限
function isUserCan($uid,$action) {
	return D('AdminUser')->isUserCan($uid,$action);
}


function getFavoCate($fid){
	if($fid == 0){
		return "默认分类";
	}else{
		return D("FavoCate")->find($fid)->name;
	}

}
//获取收藏数
function getFavoCount($id,$uid){
	//2009-4-2 增加对ID的判断
	if( $id >=0 && $uid >0 ){
		$map["cate_id"] = $id;
		$map["user_id"] = $uid;
		return D("Favorite")->count($map,'1');
	}else{
		return false;
	}
}


function isAppUser($aid,$uid) {
	if(!$aid || !$uid) return false;

	return D('AppUser')->find('aid='.$aid.' AND uid='.$uid)?true:false;
}

function getAlertName($type){
	switch($type) {
		case "vote"					: return "参与投票";break;
		case "vote_comm"			: return "投票评论";break;
		case "record_comm"			: return "记录评论";break;
		case "mess"					: return "短消息";break;
		case "friend" or "agree_fri": return "好友请求";break;

	}
}


/*
 * 系统消息
 *
 */
function getAlertInfo($alert){


	switch($alert->type) {
		/*-------------------------------------
		= 加好友
		-------------------------------------*/
		case "friend" : {
			$from_user_name =	getUserName($alert->fromUserId);
			//$from_user_url	=	__APP__."/Home/index/id/".$alert->fromUserId;
			$from_user_url	=	"#";
			$agree_url		=	__APP__."/Home/agree/fid/".$alert->fromUserId."/aid/".$alert->id;

			$result1 = '<a href="'.$from_user_url.'">'.$from_user_name.'</a>请求加你为好友。&nbsp;'."<br/>".$alert->info;
			if($alert->status == 1)
				$result2 = '<a href="'.$agree_url.'">同意</a>';
			else
				$result2 = '<span style="background:yellow">你已同意对方的好友请求，并将对方加为你的好友</span>';

			return $result1.$result2;
		}
		/*-------------------------------------
		= 同意好友
		-------------------------------------*/
		case "agree_fri" : {
			$from_user_name =	getUserName($alert->fromUserId);
			//$from_user_url	=	__APP__."/Home/index/id/".$alert->fromUserId;
			$from_user_url	=	"#";
			$result1 = '<a href="'.$from_user_url.'">'.$from_user_name.'</a>通过了你的好友请求，并把你加为好友。&nbsp;'."<br/>";

			return $result1;
		}

		/*-------------------------------------
		= 参与投票
		-------------------------------------*/
		case "vote" : {
			$data = unserialize($alert->info);
			$from_user_name =	getUserName($alert->fromUserId);
			//$from_user_url	=	__APP__."/Home/index/id/".$alert->fromUserId;
			$from_user_url	=	"#";
			$vote			=	"<a href='".__APP__."/Vote/detail/id/".$data["id"]."'>".$data["title"]."</a>";

			$result1 = '<a href="'.$from_user_url.'">'.$from_user_name.'</a>参与了你的投票。'.$vote."<br>".''.$data["info"].'&nbsp;'."<br/>";

			return $result1;
		}

		/*-------------------------------------
		= 评论投票
		-------------------------------------*/
		case "vote_comm" : {
			$data = unserialize($alert->info);
			$from_user_name =	getUserName($alert->fromUserId);
			//$from_user_url	=	__APP__."/Home/index/id/".$alert->fromUserId;
			$from_user_url	=	"###";
			$vote			=	"<a href='".__APP__."/Vote/detail/id/".$data["id"]."#comment'>".$data["title"]."</a>";

			$result1 = '<a href="'.$from_user_url.'">'.$from_user_name.'</a>评论了你的投票。'.$vote."<br>".'“'.$data["info"].'”&nbsp;'."<br/>";

			return $result1;
		}

		/*-------------------------------------
		= 评论记录
		-------------------------------------*/
		case "record_comm" : {
			$data = unserialize($alert->info);
			$from_user_name =	getUserName($alert->fromUserId);
			//$from_user_url	=	__APP__."/Home/index/id/".$alert->fromUserId;
			$from_user_url	=	"###";
			$vote			=	"<a href='".__APP__."/Record/mine'>".$data["title"]."</a>";

			$result1 = '<a href="'.$from_user_url.'">'.$from_user_name.'</a>评论了你的记录。'.$vote."<br>".'“'.$data["info"].'”&nbsp;'."<br/>";

			return $result1;
		}


	}
}


	/*
	 * 动态
	 *
	 */
	function getFeedInfo($type,$info,$uid) {
		$info = unserialize($info);

		switch($type) {
			/*-------------------------------------
			= 投票
			-------------------------------------*/
			case "vote":{
				return "发起了一个投票<a href='".__APP__."/Vote/detail/id/".$info["id"]."'>".getShort($info["title"],20)."</a>";
			}

 			/*-------------------------------------
			= 参与投票
			-------------------------------------*/
			case "cy_vote":{
				return "参与了一个投票<a href='".__APP__."/Vote/detail/id/".$info["id"]."'>".getShort($info["title"],20)."</a>";
			}

			/*-------------------------------------
			= 记录
			-------------------------------------*/
			case "record":{
				return "记录道：“".$info["title"]."”<a href='".__APP__."/Record/friend/uid/".$uid."'>查看>></a>";
			}
			/*-------------------------------------
			= 修改头像
			-------------------------------------*/
			case "head":{
				return "修改了头像 <a href='".__APP__."/home/".$info["id"]."'>".getNewUserFace2($info["id"],32)."</a>";
			}
			/*-------------------------------------
			= 更新资料
			-------------------------------------*/
			case "modify_zl":{
				return "更新了详细资料。<a href='".__APP__."/home/".$info["id"]."'>查看>></a>";
			}
			/*-------------------------------------
			= 添加收藏
			-------------------------------------*/
			case "favorite":{
				return "添加了一条收藏<a href='".$info["url"]."'>".$info["title"]."</a>"."    <a href='".__APP__."/Favorite/friend/uid/".$info["id"]."'>查看>></a>";
			}
			/*-------------------------------------
			= 发起帖子
			-------------------------------------*/
			case "add_tie":{
				return "在问吧发起了".getTieName($info["type"])."<a href='".__APP__."/detail/".$info["id"]."'>".$info["title"]."</a>";
			}
			/*-------------------------------------
			= 回复帖子
			-------------------------------------*/
			case "hf_tie":{
				return "在问吧回复了<a href='".__APP__."/detail/".$info["id"]."'>".$info["title"]."</a>";
			}

			/*-------------------------------------
			= 收藏帖子
			-------------------------------------*/
			case "sc_tie":{
				return "在问吧收藏了<a href='".__APP__."/detail/".$info["id"]."'>".$info["title"]."</a>";
			}

		}

	}

	function getTieName($type) {
		switch($type) {
			case "question": return "问题帖：";break;
			case "credit": return "悬赏帖：";break;
			case "common": return "分享帖：";break;
		}
	}



	//返回是否投过票了
	function getIsVote($vid,$mid){
		//2009-4-2 增加空值判断
		if(!$vid || !$mid) return false;

         $voteUserDao = D("VoteUser");
         $vote_id = intval($vid);
         $count = $voteUserDao->count("vote_id='$vote_id' AND user_id='$mid'");
         if($count>0){
             return "，你已经投过票了！";
         }else{
			 return "";
		 }

	}

	//lsi返回用户头像
	function getNewUserFace($uid,$size) {
		 $uid = intval($uid);
		 $member = getSID($uid);
		 $sid = $member['k'];

		 $host = "http://".$_SERVER["HTTP_HOST"].$_SERVER["SCRIPT_NAME"];
		 $host = str_replace("index.php","",$host);

		 echo $r = '<img src="'.$host.'data/uploads/face/'.$sid.'/'.$uid.'_s.jpg?'.time().'" onerror="javascript:this.src=\''.$host.'Public/Images/noface.gif\'" width="'.$size.'" height="'.$size.'"/> ';
		 //dump($r);
		 //return $r;
	}

	function getNewUserFace2($uid,$size) {
		 $uid = intval($uid);
		 $member = getSID($uid);
		 $sid = $member['k'];

		 $host = "http://".$_SERVER["HTTP_HOST"].$_SERVER["SCRIPT_NAME"];
		 $host = str_replace("index.php","",$host);

		 $r = '<img src="'.$host.'data/uploads/face/'.$sid.'/'.$uid.'_s.jpg?'.time().'" onerror="javascript:this.src=\''.$host.'Public/Images/noface.gif\'" width="'.$size.'" height="'.$size.'"/> ';

		 return $r;
	}