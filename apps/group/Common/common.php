<?php
//根据存储路径，获取照片真实URL
function get_photo_url($savepath) {
	if(empty($savepath)) $savepath = 'default.gif';
	$path	=	str_ireplace(UPLOAD_PATH,'',$savepath);
	$path	=	UPLOAD_URL.$path;
	return $path;
}

//获取相册封面
function get_album_cover($albumId,$albumInfo='') {

	//获取相册详细信息
	if(empty($albumInfo) || $albumId!=$albumInfo['id']){
		$albumInfo	=	D('Album')->find($albumId);
	}

	//照片封面
	if(!empty($albumInfo['coverImagePath'])){
		$cover	=	SITE_URL.'/thumb.php?w=120&h=100&url='.get_photo_url($albumInfo['coverImagePath']);
	}else{
		$cover	=	APP_PUBLIC_URL.'/images/photo_zwzp.gif';
	}
	return $cover;
}

//获取好友

function getfriendlist($uid) {
	$uid = intval($uid);
	if(!$uid) return false;
	return D('Friend')->field('fuid')->where('uid='.$uid)->findAll();

}


function render_in($arr,$key,$array=0) {

	$in_str = array();
	$count = count($arr);
	if($count == 0) return '';
	for($i=0; $i<$count;$i++) {
		$in_arr[] = $arr[$i][$key];
	}
	if($array) return $in_arr;
	$in_str = !empty($in_arr) ? '('.implode(',',$in_arr).')' : '';
	return $in_str;
}

//获取群组信息
function getgroupinfo($id,$field) {
	$data = D('Group')->find($id);
	if(empty($data)) return '';
	return $data[$field];
}

//获取帖子信息
function gettopic($id, $field='') {
	$data = D('Topic')->find($id);
	if(empty($data)) return '';
	if($field) return $data[$field];
	return $data;
}

//获取帖子信息
function getPost($id, $field='') {
	$data = D('Post')->find($id);
	if(empty($data)) return '';
	if($field) return $data[$field];
	return $data;
}


function formatsize($fileSize) {
	$size = sprintf("%u", $fileSize);
	if($size == 0) {
		return("0 Bytes");
	}
	$sizename = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
	return round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $sizename[$i];

}

//判读是不是创建者
function iscreater($uid,$gid) {
	if(!$uid) return false;
	return D('Member')->where("uid=$uid AND gid=$gid AND level=1")->count();
}


//判读是不是管理员
function isadmin($uid,$gid) {
	if(!$uid) return false;
	$ret = D('Member')->where("uid=$uid AND gid=$gid AND (level=1 OR level=2)")->count();

	return $ret;
}

//判读是不是成员
function ismember($uid,$gid) {
	if(!$uid) return false;
	return D('Member')->where("uid=$uid AND gid=$gid AND level=3")->count();
}

//判读是不是在群里面
function isJoinGroup($uid,$gid) {
	if(!$uid) return false;
	return D('Member')->where("uid=$uid AND gid=$gid AND level != 0")->count();
}

//获取member表内容
function getLevel($uid,$gid){
	if(!$uid) return false;
	return D('Member')->getField('level',"uid=$uid AND gid=$gid");
}
//获取等待审核通过的人数
function getApplyCount($gid){
	return D('Member')->where('gid='.$gid.' AND level=0')->count();
}


//获取分类

function getCategorySelect($pid=0) {

	$tree	=	json_encode(D('Category')->_makeTree($pid));
	return $tree;
}

//给城市下拉菜单赋值
function getCategoryTree($id,$onlyShowPid=false) {
	$tree	=	D('Category')->_makeParentTree($id,$onlyShowPid);
	return $tree;
}

//获取分类名称
function getCategoryName($id) {
	$title = D('Category')->getField('title','id='.$id);
	if(empty($title)) $title = '未分类';
	return $title;
}

function checkPriv($op,$value,$mid,$gid) {
	if($op == 'invite'){   //邀请

		if($value == 0 && iscreater($mid,$gid)) {
			return true; //关闭邀请
		}elseif($value == 1) {
			if(isadmin($mid,$gid)) return true;  //管理者邀请
		}elseif($value == 2) {
			if(isJoinGroup($mid,$gid)) return true;
		}
	}
	if($op == 'review'){  //审核
		if($value == 0) return true;
		if($value == 1){
			if(isadmin($mid,$gid)) return true;  //管理者审核
		}
	}
	if($op == 'say'){  //什么人可以发言
		if($value == 1 && isJoinGroup($mid,$gid))  return true;  //成员
		if($value == 0) return true;
	}

	if($op == 'browse'){  //什么人可以浏览
		if($value == -1) return true;
		if($value == 1 && isJoinGroup($mid,$gid)) return true;
		if($value == 0) return true;
	}
	return false;
}

//加红标题
function red($string, $words, $color = 'red', $strlen = 0)
{
	if($string == '' || $words == '') return '';
	$position = $search = $replace = array();
	if(!is_array($words)) $words = explode(' ', $words);
	foreach($words as $k=>$word)
	{
		$pos = strpos($string, $word);
		if($pos === false) continue;
		$search[$k] = $word;
		$replace[$k] = '<font color="'.$color.'">'.$word.'</font>';
		if($k == 2) break;
	}
	if($strlen) $string = msubstr($string, $strlen);
	return str_replace($search, $replace, $string);
}
//加红内容

function redContent($string, $words, $color = 'red', $strlen = 0)
{

	if($string == '' || $words == '') return '';
	$position = $search = $replace = array();
	if(!is_array($words)) $words = explode(' ', $words);
	$ret = false;
	foreach($words as $k=>$word)
	{
		$pos = strpos($string, $word);
		if($pos === false) continue;
		$search[$k] = $word;
		$replace[$k] = '<font color="'.$color.'">'.$word.'</font>';
		if($k == 2) break;
		break;
	}

	$str = str_replace($search, $replace, $string);
	return msubstr($str,intval($pos/3),intval($pos/3));
}





//去掉群组名称
function stripGroupName($title,$appid=0){
	//在 ggg 中
	$pattern = "/在?\s(.*?)\s+\中?(.*?)/i";
	$replacement = "\${2}";
    return preg_replace($pattern, $replacement, $title);


}



//ubb过滤
//TODO 每一个应用可以应用一套表情
function ubb($content){

	if(empty($content)) return '';
     $path = __PUBLIC__."/images/biaoqing/mini/";//路径
            //TODO 多应用表情
     $smile = ts_cache( "smile_mini" );
            //循环替换掉文本中所有ubb表情
     foreach( $smile as $value ){
          $img = sprintf("<img title='%s' src='%s%s'>",$value['title'],$path,$value['filename']);
          $content = str_replace( $value['emotion'],$img,$content );
     }
    return $content;
}


function replaceSpecialChar($code){


			$code = str_replace("&nbsp;", "", $code);

			$code = str_replace("<br>", "", $code);

			$code = str_replace("<br />", "", $code);

			$code = str_replace("<P>",  "", $code);

			$code = str_replace("</P>","",$code);

			return trim($code);
}


function getSiteTitle(){
	return array(
		'my_group'=>'所在群组的最新动态-我的群组-',
		'my_group_new_topic' => '最新话题-我的群组-',
		'my_friend_group' => '好友的群组-',
		'all_group' => '全部群组-',
		'newTopic_all' => '所有话题-最新话题-',
		'newTopic_my_post' => '最新话题-我发表的话题-',
		'newTopic_my_reply' => '我回复的话题-最新话题-',
		'newTopic_my_collect' => '我收藏的话题-最新话题-',
		'issue_topic' => '发布话题-',
		'create_group' => '创建群组-',
		'add_topic' => '发表话题',
		'search_topic' => '搜索话题',
		'dist_topic'  =>'精华话题',
		'edit_topic' =>'编辑话题',
		'topic_index'=>'话题-',
		'album_index'=>'相册',
		'upload_pic'=>'上传图片',
		'all_photo'=>'全部照片',
		'all_album'=>'群相册',
		'file_index'=>'文件',
		'file_upload'=>'上传文件',
		'member_index'=>'成员',

	);
}
?>