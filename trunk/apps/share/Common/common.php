<?php
/**
* getNewURL
* 更新当前网址
*
* 在不改变当前GET变量的情况下更新或者增加一个新的GET变量
* 以保持以前的查询条件不变.
* @param $value 传递值 $file 传递变量名
* @access public
* @return $newURL 新的网址
*/
function getNewURL($value,$file){
	if(empty($value)){
		$value = 0;
	}
	if(empty($_SERVER["QUERY_STRING"])){
		$newURL = __URL__."/index/action/friends".'/'.$file.'/'.$value;
	}else{
		$arr = explode("/",$_SERVER["QUERY_STRING"]);
		$key = array_search('typeId',$arr);
		if(!$key){
		   $oldurl = rtrim($_SERVER["REQUEST_URI"],"/");
		   $newURL = $oldurl.'/'.$file.'/'.$value;
		}else{
            $arr[$key+1] = $value;
            $res = implode("/",$arr);
			$newURL = $_SERVER["PHP_SELF"].'?'.$res;
		}
	}
	return $newURL;
}

/**
* getSubstr
* 截取字符串
*
* @param $str 被截取字符串 $max 截取长度
* @access public
* @return $str 截取后的字符串
*/
function getSubstr($str,$max){
	$len = strlen($str);
	$maxlen = $max-3;
	if($len<$maxlen){
		return $str;
	}else{
		$str = mb_substr($str, 0, $maxlen, 'utf-8').'...';
		return $str;
	}
}

function getSuburl($url,$max=20){
	$len = strlen($url);
	$maxlen = $max-3;

	if($len<$maxlen){
		return $url;
	}else{
		$arr = explode('/',$url);
		$newUrl = $arr[0].'/'.$arr[1].'/'.$arr[2].'/';
		$len2 = strlen($newUrl);
        $start = $len-30;
		if($len2<$maxlen){			
			$url = mb_substr($url, $start, $len, 'utf-8');
			$newUrl .= '...'.$url;
		}
		return $newUrl;
	}	
}
/*
* alert
* JavaScript提示
*
* @param $title 要提示的内容 $action 提示后的动作
*        back 返回 close 关闭窗口
*        replace 替换页面 redirect 跳转
*        $href 当action为redirect时的URL
* @access public
* @return void
*/
function alert($title,$action='back',$href=NULL){
	header("Content-type: text/html;charset=utf-8");
	$htmlStr .= "<script language='javascript'>";
	$htmlStr .= "alert('$title');";
	switch ($action) {
		case 'back':
			$htmlStr .= "history.back();";
			break;
		case 'close':
			$htmlStr .= "window.close();";
			break;
		case 'replace':
			$htmlStr .= "location.replace(location.href);";
			break;
		case 'redirect':
			if (!empty($href)){
				$htmlStr .= "location.href='$href'";
			}
			break;
		default:
			break;
	}
	$htmlStr .= "</script>";
	echo $htmlStr;
}

/**
* getTypeName
* 通过分类ID取得分类名
*
* @param string $typeId 分类ID
* @return $title 分类名
*/
function getTypeName($typeId){
	
	$types = ts_cache('share_types');	
	if(empty($types)){
		$types = type_cache();
	}
	
	$title = $types[$typeId]['title'];
	
	return $title;
}
function getTypeAlias($typeId){
	
	$types = ts_cache('share_types');	
	if(empty($types)){
		$types = type_cache();
	}
	$alias = $types[$typeId]['alias'];
	return $alias;	
}

function getC($name){
	$config = ts_cache('share_config');
	if(empty($value[$name])){
		$config = D('AppConfig')->getConfig();
		ts_cache('share_config',$config);		
	}
	
	return $config[$name];
}
//根据存储路径，获取照片真实URL
function get_photo_url($savepath) {
	$path	=	str_ireplace(UPLOAD_PATH,'',$savepath);
	$path	=	UPLOAD_URL.$path;
	return $path;
}

function type_cache(){
	$types = D('ShareType')->where("state=0")->order('sort asc')->findAll();
	$arr = array();
	foreach ($types as $k=>$v){
		$id = $v['id'];
		unset($v['id']);
		foreach ($v as $k1=>$v1){
			$arr[$id][$k1] = $v1;
		}
	}
	
	ts_cache('share_types',$arr);
	
	return $arr;
}
?>