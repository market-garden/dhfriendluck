<?php
function friendlyDate($sTime,$type = 'normal',$alt = 'false') {
    if(!$sTime) {
        return '';
    }
    //sTime=源时间，cTime=当前时间，dTime=时间差
    $cTime		=	time();
    $dTime		=	$cTime - $sTime;
    $dDay		=	intval(date("Ymd",$cTime)) - intval(date("Ymd",$sTime));
    $dYear		=	intval(date("Y",$cTime)) - intval(date("Y",$sTime));
    //normal：n秒前，n分钟前，n小时前，日期
    if($type=='normal') {
        if( $dTime < 60 ) {
            return $dTime."秒前";
        }elseif( $dTime < 3600 ) {
            return intval($dTime/60)."分钟前";
        }elseif( $dTime >= 3600 && $dDay == 0  ) {
            return intval($dTime/3600)."小时前";
        }elseif($dYear==0) {
            return date("m-d H:i",$sTime);
        }else {
            return date("y-m-d H:i",$sTime);
        }
    //full: Y-m-d , H:i:s
    }elseif($type=='full') {
        return date("y-m-d H:i",$sTime);
    }elseif($type=='month') {
        return date("m-d H:i",$sTime);
    }else {
        if( $dTime < 60 ) {
            return $dTime."秒前";
        }elseif( $dTime < 3600 ) {
            return intval($dTime/60)."分钟前";
        }elseif( $dTime >= 3600 && $dDay == 0  ) {
            return intval($dTime/3600)."小时前";
        }elseif($dYear==0) {
            return date("y-m-d H:i",$sTime);
        }else {
            return date("y-m-d H:i",$sTime);
        }
    }
}


//入库前的过滤
function  new_addslashes($string) {
    if(!is_array($string)) return addslashes(trim($string));
    foreach($string as $key => $val) $string[$key] = new_addslashes($val);
    return $string;
}

//编辑文本时用的
function forTag($string) {
    return str_replace(array('"',"'"), array('&quot;','&#039;'), $string);
}

//textarea输入
function tt($string,$length="") {
    if(!is_array($string)) {
        $str = htmlspecialchars($string);
        if($length) {
            $str = msubstr($str,0,$length);
        }
        return $str;
    }
    foreach($string as $key => $val) $string[$key] = textarea_output($val);
    return $string;
}

//textarea输出
function textarea_output($string) {
    if(!is_array($string)) return nl2br(htmlspecialchars($string));
    foreach($string as $key => $val) $string[$key] = textarea_output($val);
    return $string;
}

//textarea编辑
function textarea_edit($string) {
    if(!is_array($string)) return forTag($string);
    foreach($string as $key => $val) $string[$key] = textarea_edit($val);
    return $string;
}

//用户Html安全过滤输出
function html_output($str) {
//$str = stripcslashes($str);
    $farr = array(
        "/\s /", //过滤多余的空白
        "/<(\/?)(script|i?frame|style|html|body|title|link|meta\?|\%)([^>]*?)>/isU", //过滤 <script 等可能引入恶意内容或恶意改变显示布局的代码,假如不需要插入flash等,还可以加入<object的过滤
        "/(<[^>]*)on[a-zA-Z] \s*=([^>]*>)/isU", //过滤javascript的on事件

    );
    $tarr = array(
        " ",
        "＜\\1\\2\\3＞", //假如要直接清除不安全的标签，这里可以留空
        "\\1\\2",
    );
    $str = preg_replace( $farr,$tarr,$str);


    //过滤on事件lang js
    while(preg_match('/(<[^><]+)(lang|onfinish|onmouse|onexit|onerror|onkey|onload|onchange|onfocus|onblur)[^><]+/i',$str,$mat)) {
        $str=str_replace($mat[2],"xyz",$str);
    }

    return $str;
}

//用户Html安全过滤输出
function html_output2($str) {
//$str = stripcslashes($str);
    $farr = array(
        "/\s /", //过滤多余的空白
        "/<(\/?)(script|i?frame|style|html|body|title|link|meta\?|\%)([^>]*?)>/isU", //过滤 <script 等可能引入恶意内容或恶意改变显示布局的代码,假如不需要插入flash等,还可以加入<object的过滤
        "/(<[^>]*)on[a-zA-Z] \s*=([^>]*>)/isU", //过滤javascript的on事件

    );
    $tarr = array(
        " ",
        "＜\\1\\2\\3＞", //假如要直接清除不安全的标签，这里可以留空
        "\\1\\2",
    );
    $str = preg_replace( $farr,$tarr,$str);


    //过滤on事件lang js
    while(preg_match('/(<[^><]+)(lang|onfinish|onmouse|onexit|onerror|onkey|onload|onchange|onfocus|onblur)[^><]+/i',$str,$mat)) {
        $str=str_replace($mat[2],"xyz",$str);
    }

    return $str;
}

//过滤脚本代码
function cleanJs($text) {
    $text	=	trim($text);
    $text	=	stripslashes($text);
    //完全过滤注释
    $text	=	preg_replace('/<!--?.*-->/','',$text);
    //完全过滤动态代码
    $text	=	preg_replace('/<\?|\?>/','',$text);
    //完全过滤js
    $text	=	preg_replace('/<script?.*\/script>/','',$text);
    //过滤多余html
    $text	=	preg_replace('/<\/?(html|head|meta|link|base|body|title|style|script|form|iframe|frame|frameset)[^><]*>/i','',$text);
    //过滤on事件lang js
    while(preg_match('/(<[^><]+)(lang|onfinish|onmouse|onexit|onerror|onkey|onload|onchange|onfocus|onblur|style)[^><]+/i',$text,$mat)) {
        $text=str_replace($mat[0],$mat[1],$text);
    }
    while(preg_match('/(<[^><]+)(window\.|js:|javascript:|about:|file:|document\.|vbs:|vbscript:|cookie)([^><]*)/i',$text,$mat)) {
        $text=str_replace($mat[0],$mat[1].$mat[3],$text);
    }
    //过滤多余空格
    $text	=	str_replace('  ',' ',$text);
    return $text;
}
//纯文本输出
function t($text) {
    $text	=	cleanJs($text);
    $text	=	strip_tags($text);
    $text	=	htmlspecialchars($text);
    return $text;
}
//输出安全的html
function h($text) {
    $text	=	cleanJs($text);
    return $text;
}
//function GFW($text) {
//	//替换成开头大写字母
//	$word	=	"共产党|法轮功|藏独|毛泽东|江泽民|台独|胡锦涛|fuck";
//	$words	=	explode('|',$word);
//	foreach($words as $v){
//		$text	=	str_replace($v,' ** ',$text);
//	}
//	return $text;
//}


//检查并创建多级目录
function checkDir($path) {
    $pathArray = explode('/',$path);
    $nowPath = '';
    array_pop($pathArray);
    foreach ($pathArray as $key=>$value) {
        if ( ''==$value ) {
            unset($pathArray[$key]);
        }else {
            if ( $key == 0 )
                $nowPath .= $value;
            else
                $nowPath .= '/'.$value;
            if ( !is_dir($nowPath) ) {
                if ( !mkdir($nowPath, 0777) ) return false;
            }
        }
    }
    return true;
}


//自定义缓存函数
function ts_cache($key,$value="__secache_get",$expireTime=-1,$type="secache") {

    if($type == "secache") {

        vendor("secache");
        $cache = new secache;
        if(!is_dir(C('Cache_Data'))){
        	mk_dir(C('Cache_Data'));
        }
		$cache->workat(C('Cache_Data'));
        if($value && $value != "__secache_get") {   //赋值
            $var["content"] = $value;
            $var["time"]	= ($expireTime==-1)?-1: (time()+$expireTime);
            return $cache->store(md5($key),$var);
        }elseif(!$value) {  //删除
            return $cache->delete(md5($key));
        }else { //取值
            $cache->fetch(md5($key),$var);
            if($var["time"] <0 || $var["time"]>time()) {
                return $var["content"];
            }else {
                return false;
            }

        }
    }
//以后还可以扩充memcache接口 ...

}


function unescape($str) {
    $str = rawurldecode($str);
    preg_match_all("/(?:%u.{4})|.+/",$str,$r);
    $ar = $r[0];
    foreach($ar as $k=>$v) {
        if(substr($v,0,2) == "%u" && strlen($v) == 6)
            $ar[$k] = iconv("UCS-2","UTF-8",pack("H4",substr($v,-4)));
    }
    return join("",$ar);
}

//发送邮件
function send_email($toEmail,$subject,$body,$type="HTML",$option=array()) {
    vendor('smtp');
    if( empty( $option ) ) {
        $smtp = new smtp("smtp.126.com",25,true,"thiz_lwei@126.com" ,"7897896");
        return $smtp -> sendmail($toEmail, "thiz_lwei@126.com", "thiz_lwei", $subject, $body, $type);
    }else {
        $smtp = new smtp($option['smtp'],$option['port'],true,$option['username'] ,$option['password']);
        return $smtp -> sendmail($toEmail, $option['username'],$option['site_name'] , $subject, $body, $type);
    }
}

//加密函数
function jiami($txt,$key=null) {
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
function jiemi($txt,$key) {
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


//根据uid获取头像函数,size为full,small,middle
function getUserFace($uid, $size = 'small') {
    $size = in_array($size, array('big', 'middle', 'small',"yuan")) ? $size : 'middle';
    $uid = abs(intval($uid));
    $face_path = getFacePath($uid);
    $face_file = $face_path.$uid."_".$size."_face.jpg";


    if(!file_exists($face_file)) {
    //  $face_path =  __PUBLIC__.'/images/noface/';
    //$face_path2  =  C("TS_URL").'/Public/images/noface/';
    //$uid       =  "noface";

        $uid  =  abs(intval($uid));
        $api = new TS_API();
        $info =  $api->user_getInfo($uid,"sex");
        if( $info['sex'] ) {
            return __THEME__."/images/pic2.gif";
        }else {
            return __THEME__."/images/pic1.gif";
        }

    }else {
        $face_path2 = getFaceUrl($uid);
    }

    return $face_path2.$uid."_".$size."_face.jpg";
}
function getUserFaceId($uid) {
    if(!$uid) return 0;
    $map['uid']	=	$uid;
    $map['attach_type']	=	'face';
    D('Attach')->find();
}
function getFacePath($uid) {

    $uid = abs(intval($uid));
    $uid = sprintf("%09d", $uid);
    $dir1 = substr($uid, 0, 3);
    $dir2 = substr($uid, 3, 2);
    $dir3 = substr($uid, 5, 2);
    $path	=	 SITE_PATH.'/data/userface/'.$dir1.'/'.$dir2.'/'.$dir3.'/';
    checkDir($path);
    return $path;
}

function getFaceUrl($uid) {

    $uid = abs(intval($uid));
    $uid = sprintf("%09d", $uid);
    $dir1 = substr($uid, 0, 3);
    $dir2 = substr($uid, 3, 2);
    $dir3 = substr($uid, 5, 2);
    $path	=	 C("TS_URL").'/data/userface/'.$dir1.'/'.$dir2.'/'.$dir3.'/';
    checkDir($path);
    return $path;
}


function getUserName($uid) {
    if($uid && is_numeric($uid)) {
        $api = new TS_API();
        $info =  $api->user_getInfo($uid,"name");
        $name = getValue($info["name"]);
        return $name?$name:"没有这个人吧~~";
    }

}

function getUserWo($uid,$mid = null) {

    if($uid == $mid) return "我";

    $uid  =  abs(intval($uid));
    $info =  D("User")->field( 'sex' )->find($uid);

    $sex = explode("-",$info['sex']);
    return $sex[0] ? "他":"她";

}


function getUserSex($uid) {

    $uid  =  abs(intval($uid));
    $info =  D("User")->find($uid);
    return  $info["sex"]?'男':'女';
}

function getUserSexArr($uid) {

    $uid  =  abs(intval($uid));
    $info =  D("User")->find($uid);
    $sex_arr["sex"] = getValue($info["sex"])?'男':'女';
    $sex_arr["ta"]  = getValue($info["sex"])?'他':'她';

    return $sex_arr;
}

function getUserCity($uid) {
    $uid  =  abs(intval($uid));
    $info =  D("User")->field('current_city,current_province')->find($uid);
    return  getAreaInfo($info['current_province'].','.$info["current_city"]);
}


function get_ip() {
    if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
        $ip = getenv('HTTP_CLIENT_IP');
    }
    elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
    }
    elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
        $ip = getenv('REMOTE_ADDR');
    }
    elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return preg_match("/[\d\.]{7,15}/", $ip, $matches) ? $matches[0] : 'unknown';
}


//判断输入密码的强弱
function checkStrong($sPW) {
    if (strlen($sPW) < 6) return 0; //密码太短
    $Modes = 0;
    for ($i = 0; $i < strlen($sPW); $i++) { //密码模式

        $iN = ord($sPW{$i});

        if ($iN >= 48 && $iN <= 57) //数字
            $xxx = 1;
        elseif ($iN >= 65 && $iN <= 90) //大写
            $xxx = 2;
        elseif ($iN >= 97 && $iN <= 122) //小写
            $xxx = 4;
        else $xxx = 8;

        $Modes |= $xxx;
    }
    $num = $Modes;

    $modesx = 0;
    for ($i = 0; $i < 4; $i++) {
        if ($num & 1) $modesx++;
        $num >>= 1;
    }

    switch($modesx) {
        case 1: return "差";break;
        case 2: return "中等";break;
        case 3: return "强";
    }
}

//识别汉字编码,因为YBlog用的是utf-8,如果引用通告发过来的是gb2312的编码的话,需要可以识别并完成编码转换
function safeEncoding($string,$outEncoding = 'UTF-8') {
    $encoding = "UTF-8";
    for($i=0;$i<strlen($string);$i++) {
        if(ord($string{$i})<128)
            continue;

        if((ord($string{$i})&224)==224) {
        //第一个字节判断通过
            $char = $string{++$i};
            if((ord($char)&128)==128) {
            //第二个字节判断通过
                $char = $string{++$i};
                if((ord($char)&128)==128) {
                    $encoding = "UTF-8";
                    break;
                }
            }
        }
        if((ord($string{$i})&192)==192) {
        //第一个字节判断通过
            $char = $string{++$i};
            if((ord($char)&128)==128) {
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


//获取好友分组
function getFriGroup($uid,$fuid) {

//好友的分组ID
    $map["uid"]  = $uid;
    $map["fuid"] = $fuid;
    $g = D("Fg")->where($map)->findAll();
    foreach($g as $key=>$v) {
        $gids[] = $v["gid"];
    }

    //好友分组ID的名字
    $map2["id"] = array("IN",$gids);
    $gnames = D("FriendGroup")->where($map2)->field("name")->findAll();

    foreach($gnames as $key=>$v) {
        $result .= $result?"，".$v["name"]:$v["name"];
    }

    return $result;

}

//获取好友分组num
function getGroupNum($gid,$uid) {
    if($gid) $map["gid"] = $gid;
    $map["uid"] = $uid;

    $xxx =  D("Fg")->where($map)->field("DISTINCT fuid")->findAll();
    $num = $xxx?count($xxx):0;

    return $num;

}

//获取某个人的心情
function getUserMini($uid) {

    $appconfig	=	D('AppConfig');
    $appconfig->setAppname('mini');
    $bq_config	=	$appconfig->getConfig();

    //从缓存中读出表情
    $bq_emotion = D('Smile')->getSmile($bq_config['smiletype']);
    //我的心情
    $mini = D("Mini")->getOneMini($uid,$bq_emotion,$bq_config['smiletype']);

    return $mini["content"];

}


//分页函数,$p--当前页数,$count--总页数
function page($count,$p=1) {


    if($count <= 1) return;
    $php_self = "http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
    $p_pos = strpos($php_self,"/p");
    if($p_pos !== false ) {
        $php_self = substr($php_self,0,$p_pos);
    }

    $p= $_GET["p"]?intval($_GET["p"]):1;

    $off_set=4;//偏移

    $page_pel=9;//面板容量

    //$count=ceil($pg_last/$pagesize);   //总页数

    if($count<=$page_pel) {

        $minpage=1;                    //分页导航最小页值

        $maxpage=$count;               //分页导航最大页值

    }

    if($count>$page_pel) {

        if(($p-$off_set)>0) {

            $minpage=$p-$off_set;

        }else {

            $minpage=1;

        }

        if(($p+$off_set)<=$count) {

            $maxpage=$p+$off_set;

        }else {

            $minpage=$count-$off_set*2;

            $maxpage=$count;

        }

        if(($p+$off_set)<$page_pel) {

            $minpage=1;

            $maxpage=$page_pel;

        }

    }

    $url='';

    $url.="<div id='page' class='page'>";

    if($p==1) {

        $url.="<span class='disabled'>首页</span>";

    }else {

        $url.="<a href='$php_self/p/1'>首页</a>";

    }

    if($p>1) {

        $url.="<a href='$php_self/p/".($p-1)."'>上一页</a>";

    }else {

        $url.="<span class='disabled'>上一页</span>";

    }

    for($i=$minpage;$i<=$maxpage;$i++) {

        if($i==$p) {

            $url.="<span class='current'>".$i."</span>";

        }else {

            $url.="<a href='$php_self/p/".$i."'>".$i."</a>";

        }

    }
    if($p<$count) {

        $url.="<a href='$php_self/p/".($p+1)."'>下一页</a><a href='$php_self/p/".$count."'>尾页</a>";

    }else {

        $url.="<span class='disabled'>下一页</span><span class='disabled'>尾页</span>";

    }

    $url.="</div>";

    return $url;

}


function simplode($ids) {
    return "'".implode("','", $ids)."'";
}

/**
 * msubstr
 * 字符串截断。支持中文和utf-8编码
 * @param mixed $str
 * @param int $start
 * @param mixed $length
 * @param string $charset
 * @param mixed $suffix
 * @access public
 * @return void
 */
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true) {
    if(function_exists("mb_substr"))
        return mb_substr($str, $start, $length, $charset);
    elseif(function_exists('iconv_substr')) {
        return iconv_substr($str,$start,$length,$charset);
    }
    $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
    $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
    $re['gbk']	  = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
    $re['big5']	  = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
    preg_match_all($re[$charset], $str, $match);
    $slice = join("",array_slice($match[0], $start, $length));
    if($suffix) return $slice."...";
    return $slice;
}

function getFieldName($field) {
//$data = D("FieldName")->where("field='$field'")->find();

    $item["address"]       = "地址";
    $item["postcode"]      = "邮编";
    $item["phone"]         = "电话";
    $item["cellphone"]     = "手机";
    $item["qq"]            = "QQ";
    $item["msn"]           = "MSN";
    $item["birthday"]      = "生日";
    $item["jiejiao"]       = "我想结交";
    $item["interest"]      = "兴趣爱好";
    $item["book"]          = "喜欢的书";
    $item["film"]          = "喜欢的电影";
    $item["idol"]          = "偶像";
    $item["motto"]         = "座右铭";
    $item["wish"]          = "最近心愿";
    $item["summary"]       = "我的简介";
    $item["education"]     = "教育信息";
    $item["career"]        = "工作信息";
    $item["ts_areaval"]    = "居住地区";
    $item["ts_hometown"]   = "家乡";
    $item["sex"]           = "性别";
    $item["bloodtype"]     = "血型";
    $item["birthday_stro"] = "星座";
    $item['name']          = "姓名";
    return $item[$field]?$item[$field]:$field;
}

function getPrivacy($v,$mid,$uid) {
    $full	 = explode("-", $v);
    $num	 = count($full);

    $privacy = $full[$num-2];
    $display = $full[$num-1];
    if($display == 0) {  //设置为不显示，当然返回false喽~~
        return false;
    }else {
        if($mid == $uid) {   //如果是看自己空间，肯定也显示
            return true;
        }else {              //看别人空间
            switch($privacy) {
                case 0:  return true;                           //任何人
                case 1: {
                        $api = new TS_API();
                        return $api->friend_areFriends($mid,$uid); //仅好友
                    }
                case 2 : return false;                          //他自己可看
            }
        }

    }
}

function getValue($v) {
    $val  =  explode("-", $v, -2);
    if(!$val) {
        $info = $v;
    }else {
        $info =  implode("-", $val);
    }

    return $info;
}

function getPrivNum($arr) {
    unset($arr[0]);
    $str = "-".implode("-",$arr);
    return $str;
}

/**
 * StrLenW
 * 计算长度
 * @param mixed $str
 * @access public
 * @return void
 */
function StrLenW($str) {
    $i = 0;
    $count = 0;
    $len = strlen ($str);
    while ($i < $len) {
        $chr = ord ($str[$i]);
        $count++;
        $i++;
        if($i >= $len) break;
        if($chr & 0x80) {
            $chr <<= 1;
            while ($chr & 0x80) {
                $i++;
                $chr <<= 1;
            }
        }
    }
    return $count;

}

/**
 * browseCount
 * 浏览计数
 * 返回ture为可以计数。返回false为不需要计数
 * @param mixed $type 应用名，请用APP_NAME做参数
 * @param mixed $id 应用ID
 * @param mixed $uid 当前访问者ID
 * @param mixed $lifttime 防刷新时间.单位秒
 * @access public
 * @return true|false
 */
function browseCount( $type,$id,$uid,$lifttime = 30 ) {
    $options = array( 'id'=>$id,'uid'=>$uid,'type'=>$type,'lefttime'=>$lifttime );
    return B( 'Browse',$options );
}


function filterReply($con) {
    $con_arr = explode(":",$con,2);

    return (count($con_arr) == 2)?$con_arr[1]:$con;
}


function getShort($title,$length=40) {
    return msubstr($title,0,$length);
}






function forDisIp($ip_reg) {
    $ips = str_replace("\d+","*",$ip_reg);
    $ips = str_replace("\.",".", $ips);
    $ips = str_replace("|","\n", $ips);
    return $ips;
}

function ip_banned($deny_reg,$allow_reg) {
    $user_ip = get_ip();

    //允许
    if($allow_reg) {
        $r1 = preg_match("/^(".$allow_reg.")$/", $user_ip);
        if(!$r1) {
            session_destroy();
            setcookie('remembor',"",time()-3600,"/");
            header("Content-type: text/html;charset=utf-8");
            exit("你的IP不在允许范围内!");
        }
    }


    //禁止
    if($deny_reg) {
        $r2 = preg_match("/^(".$deny_reg.")$/", $user_ip);
        if($r2) {
            session_destroy();
            setcookie('remembor',"",time()-3600,"/");
            header("Content-type: text/html;charset=utf-8");
            exit("你的IP被禁止了!");
        }
    }

}

/**
 * thumb
 * 缩略图函数
 * @param mixed $path  图片路径
 * @param mixed $width 缩略的宽
 * @param mixed $hight 缩略的告
 * @access public
 * @return void
 */
function thumb( $path,$width,$height ) {
    return sprintf( "%s/Thumb/?w=%s&h=%s&url=%s",__PUBLIC,$width,$height,$url );
}

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
    switch($info_header["biBitCountLbiCompression"]) {
        case 4:
            $colorset=unpack("L*",substr($buf,54,64));
            for($y=0;$y<$info_header["biHeight"];$y++) {
                $colors=array();
                $y_pos=$y*$line_len+$file_header["bfOffBits"];
                for($x=0;$x<$info_header["biWidth"];$x++) {
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
            for($y=0;$y<$info_header["biHeight"];$y++) {
                $colors=array();
                $y_pos=$y*$line_len+$file_header["bfOffBits"];
                for($x=0;$x<$info_header["biWidth"];$x++) {
                    $colors[]=$colorset[ord($buf[$y_pos+$x])+1];
                }
                imagesetstyle($img,$colors);
                imageline($img,0,$info_header["biHeight"]-$y-1,$info_header["biWidth"],$info_header["biHeight"]-$y-1,IMG_COLOR_STYLED);
            }
            break;
        case 16:
            for($y=0;$y<$info_header["biHeight"];$y++) {
                $colors=array();
                $y_pos=$y*$line_len+$file_header["bfOffBits"];
                for($x=0;$x<$info_header["biWidth"];$x++) {
                    $i=$x*2;
                    $color=ord($buf[$y_pos+$i])|(ord($buf[$y_pos+$i+1])<<8);
                    $colors[]=imagecolorallocate($img,(($color>>10)&0x1f)*0xff/0x1f,(($color>>5)&0x1f)*0xff/0x1f,($color&0x1f)*0xff/0x1f);
                }
                imagesetstyle($img,$colors);
                imageline($img,0,$info_header["biHeight"]-$y-1,$info_header["biWidth"],$info_header["biHeight"]-$y-1,IMG_COLOR_STYLED);
            }
            break;
        case 24:
            for($y=0;$y<$info_header["biHeight"];$y++) {
                $colors=array();
                $y_pos=$y*$line_len+$file_header["bfOffBits"];
                for($x=0;$x<$info_header["biWidth"];$x++) {
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
function imagebmp(&$im, $filename = '', $bit = 8, $compression = 0) {
    if (!in_array($bit, array(1, 4, 8, 16, 24, 32))) {
        $bit = 8;

    }
    else if ($bit == 32) // todo:32 bit
        {
            $bit = 24;
        }

    $bits = pow(2, $bit);

    // 调整调色板
    imagetruecolortopalette($im, true, $bits);
    $width = imagesx($im);
    $height = imagesy($im);
    $colors_num = imagecolorstotal($im);

    if ($bit <= 8) {
    // 颜色索引
        $rgb_quad = '';
        for ($i = 0; $i < $colors_num; $i ++) {
            $colors = imagecolorsforindex($im, $i);
            $rgb_quad .= chr($colors['blue']) . chr($colors['green']) . chr($colors['red']) . "\0";         }

        // 位图数据
        $bmp_data = '';

        // 非压缩
        if ($compression == 0 || $bit < 8) {
            if (!in_array($bit, array(1, 4, 8))) {
                $bit = 8;
            }

            $compression = 0;

            // 每行字节数必须为4的倍数，补齐。


            $extra = '';
            $padding = 4 - ceil($width / (8 / $bit)) % 4;
            if ($padding % 4 != 0) {
                $extra = str_repeat("\0", $padding);
            }

            for ($j = $height - 1; $j >= 0; $j --) {
                $i = 0;
                while ($i < $width) {
                    $bin = 0;
                    $limit = $width - $i < 8 / $bit ? (8 / $bit - $width + $i) * $bit : 0;

                    for ($k = 8 - $bit; $k >= $limit; $k -= $bit) {
                        $index = imagecolorat($im, $i, $j);
                        $bin |= $index << $k;
                        $i ++;
                    }

                    $bmp_data .= chr($bin);
                }

                $bmp_data .= $extra;
            }
        }
        // RLE8 压缩
        else if ($compression == 1 && $bit == 8) {
                for ($j = $height - 1; $j >= 0; $j --) {
                    $last_index = "\0";
                    $same_num   = 0;
                    for ($i = 0; $i <= $width; $i ++) {
                        $index = imagecolorat($im, $i, $j);
                        if ($index !== $last_index || $same_num > 255) {
                            if ($same_num != 0) {
                                $bmp_data .= chr($same_num) . chr($last_index);
                            }

                            $last_index = $index;
                            $same_num = 1;
                        }
                        else {
                            $same_num ++;
                        }
                    }

                    $bmp_data .= "\0\0";
                }

                $bmp_data .= "\0\1";
            }

        $size_quad = strlen($rgb_quad);
        $size_data = strlen($bmp_data);
    }
    else {
    // 每行字节数必须为4的倍数，补齐。
        $extra = '';
        $padding = 4 - ($width * ($bit / 8)) % 4;
        if ($padding % 4 != 0) {
            $extra = str_repeat("\0", $padding);
        }

        // 位图数据
        $bmp_data = '';

        for ($j = $height - 1; $j >= 0; $j --) {
            for ($i = 0; $i < $width; $i ++) {
                $index = imagecolorat($im, $i, $j);
                $colors = imagecolorsforindex($im, $index);

                if ($bit == 16) {
                    $bin = 0 << $bit;

                    $bin |= ($colors['red'] >> 3) << 10;
                    $bin |= ($colors['green'] >> 3) << 5;
                    $bin |= $colors['blue'] >> 3;

                    $bmp_data .= pack("v", $bin);
                }
                else {
                    $bmp_data .= pack("c*", $colors['blue'], $colors['green'], $colors['red']);
                }

            // todo: 32bit;
            }

            $bmp_data .= $extra;
        }

        $size_quad = 0;
        $size_data = strlen($bmp_data);
        $colors_num = 0;
    }

    // 位图文件头
    $file_header = "BM" . pack("V3", 54 + $size_quad + $size_data, 0, 54 + $size_quad);

    // 位图信息头
    $info_header = pack("V3v2V*", 0x28, $width, $height, 1, $bit, $compression, $size_data, 0, 0, $colors_num, 0);
    // 写入文件
    if ($filename != '') {
        $fp = fopen("test.bmp", "wb");

        fwrite($fp, $file_header);
        fwrite($fp, $info_header);
        fwrite($fp, $rgb_quad);
        fwrite($fp, $bmp_data);
        fclose($fp);

        return 1;
    }

    // 浏览器输出
    header("Content-Type: image/bmp");
    echo $file_header . $info_header;
    echo $rgb_quad;
    echo $bmp_data;

    return 1;
}

function GFW($string) {

    if(!is_array($string)) {
        $api = new TS_API();
        $site_opts = $api->option_get();
        $badkey = $site_opts["gfw_keywords"];
        $gfw_rep = $site_opts["gfw_rep"];
        $string = preg_replace("/$badkey/i",$gfw_rep,$string);
        return $string;
    }else {
        foreach($string as $key => $val) $string[$key] = GFW($val);
        return $string;
    }
}



function is_email($email) {
    $pattern="/^([\w\.-]+)@([a-zA-Z0-9-]+)(\.[a-zA-Z\.]+)$/i";//包含字母、数字、下划线_和点.的名字的email
    if(preg_match($pattern,$email,$matches)) {
        return true;
    }else {
        return false;
    }
}



function getAppNum($uid,$fun) {
    if(function_exists($fun)) {
        eval("\$num = ".$fun."(".$uid.");");
    }else {
        $num = 0;
    }
    return $num;
}





function getAppNumx($uid,$left_apps) {
    $app_map["日志"] = "Blog";
    $app_map["相册"] = "Album";
    $app_map["视频"] = "Video";
    $app_map["分享"] = "Share";
    $app_map["心情"] = "Mini";
    $app_map["投票"] = "Vote";
    $app_map["群组"] = "Groups";
    $app_map["活动"] = "Event";
    $app_map["电影"] = "Movie";

    foreach($left_apps as $k=>$v) {
        $type = $app_map[$v['name']];
        if(function_exists("get".$type."Num")) {
            eval("\$num = get".$type."Num(".$uid.");");
            $apps_num[$type]["num"] = $num;
            $apps_num[$type]['name'] = $v['name'];
            $apps_num[$type]['icon'] = $v['icon'];
            $apps_num[$type]["url"] = getAppUrl($uid,$type);
        }else {
            $apps_num[$type]["num"] = 0;
            $apps_num[$type]['name'] = $v['name'];
            $apps_num[$type]['icon'] = $v['icon'];
            $apps_num[$type]["url"] = getAppUrl($uid,$type);
        }
    }

    return $apps_num;

}




function getAppUrl($uid,$type) {
    switch($type) {
        case  "Blog" : return __ROOT__."/apps/blog/index.php?s=/Index/personal/uid/".$uid; break;
        case  "Album" : return __ROOT__."/apps/photo/index.php?s=/Index/albums/uid/".$uid; break;
        case  "Vote" : return __ROOT__."/apps/vote/index.php?s=/Index/personal/uid/".$uid; break;
        case  "Share" : return __ROOT__."/apps/Share/index.php?s=/Index/personal/uid/".$uid; break;
        case  "Groups" : return __ROOT__."/apps/group/index.php?s=/Index/index/uid/".$uid; break;
        case  "Mini" : return __ROOT__."/apps/mini/index.php?s=/Index/friends/uid/".$uid; break;
    }
}


function getShareNum($uid) {
    $api = new TS_API();
    $num = $api->share_getShareNum($uid);
    return $num;
}

//获取群组名称
function getGroupName($gid) {
    $data = D('Group',"group")->find($gid);
    if(empty($data)) return '';
    return $data["name"];
}



/**
 * getTitle
 * 获取网页的标题
 * @param mixed $url 网站域名 如www.sina.com
 * @param mixed $page 网站具体页面，去除域名以后的部分。如整个网址是 blog.sina.com.cn/s/blog_4852f30a0100dyj1.html.这里需要传递的参数就是/s/blog_4852f30a0100dyj1.html
 * @param int $port
 * @param int $deadline
 * @access public
 * @return void
 */
function getTitle( $url,$page, $port = 80,$deadline=60) {
    $data = "";
    $fp = fsockopen( $url,$port,$errno,$errstr,$deadline );
    if( !$fp ) {
        $error_info = sprintf( "错误:%s-%s<br />\n",$errno,$errstr );
        echo $error_info;
    }else {

        $out = "GET ".$page." HTTP/1.0\r\n";
        $out .= "Host: ".$url."\r\n";
        $out .= "Content-Type: text/xml; charset=utf-8\r\n";
        $out .= "Connection: Close\r\n\r\n";

        fwrite($fp, $out);
        while( !$hasTitle ) {
            $lines_string = fgetss( $fp,1024,'<title>' ); //读取一行
            eregi("<title>(.*)</title>", $lines_string, $head); //正则匹配
            $data = trim($head[1]);
            $hasTitle = empty( $data )?false:true;
        }
    }
    return $data;
}


function isAppAdd($appid,$uid) {

    $map["appid"] = $appid;
    $map["uid"] = $uid;

    $r = D("UserApp")->where($map)->count();

    return $r;
}

/**
 * 获取完整的地区
 *
 * @param unknown_type $area   '23,45,64' 字串形式传入
 * @param unknown_type $type
 */
function getAreaInfo($area) {
    $pNetwork = D('Network');
    $arrArea = explode(',',$area);
    foreach ($arrArea as $key=>$val) {
        if($val) {
            $areName = $pNetwork->where('id='.$val)->field('title')->find();;
            $str[] = $areName['title'];
        }
    }
    return implode(' ',$str);
}

/**
 * 获取用户组图标
 * $uid 用户ID
 */
function getUserGroupIcon($uid) {
    $groupId = TS_D('User')->where('id='.$uid)->field('admin_level')->find();
    if($uid) {
        $info = TS_D('SystemGroup')->where('id='.$groupId['admin_level'])->find();
        if($info['icon']) {
            return '<img src='.__THEME__.'/images/icon/groupicon/'.$info['icon'].' Alt="'.$info['showname'].'" />';
        }
    }
}

/**
 * 返回APP应用的相关信息
 *
 * @param int $appid 传入的APPID
 * @param string $field  APP_NAME,APP_ICON,APP_URL,APP_ID
 * @return mix
 */
function getAppInfo($appId,$field='') {
    $api = new TS_API();
    return $api->App_getAppInfo($appId,$field);
}

function isOnlineIcon($uid) {
    $api = new TS_API();
    if($api->UserOnline_isOnline($uid)) {
        return "<img src=".__THEME__."/images/ico_zx.gif alt='在线' width='11' height='8' border='0'>";
    }
}

//新的积分控制系统
function setScore($uid,$action) {
    $uid	=	intval($uid);
    if(!$action || !$uid) {
    //参数不正确
        return "-1";
    }
    $api = new TS_API();
    //添加积分动作的信息
    $credit_info	=	$api->CreditSetting_getCredit($uid,$action);

    $action = $credit_info['id'];    //动作ID

    if('no_have_action'  == $credit_info) return '1'; //没有这个动作
    if ('not_user' === $credit_info)  return '0'; //错误的用户id

    //操作积分
    $score_result = $api->UserScore_setScore($uid,$credit_info);
    if ($score_result) {
        return '200'; //添加记录成功
    }
    return '404';
}
function getCredit($uid,$api){
         $type = $api->CreditSetting_getCreditType();
         $credit = $api->UserInfo_getCredit($uid);
         foreach ($type as $key => $value){
                 $result[$value] =  isset($credit[$key])?$credit[$key]:0;
         }
        return $result;
}
function getUserRank($uid){
        $api = new TS_API();
        //获得等级规则
        $rank_rule = $api->SystemUserRank_getAllRule();
        //获得用户积分
        $credit = $api->UserInfo_getCredit($uid);
        //如果积分为空则是初始等级
        if(empty($credit)) return $rank_rule[0];
        foreach ($rank_rule as $key=>$rankValue){
                foreach ($credit as $k2=>$score){
                           $min = $rankValue['rulemin'][$k2];
                           $max = $rankValue['rulemax'][$k2];
                          $comm[$k2] =  !($min==0 && $max == 0 )? (( $min<= $score)  && ($score< $max  )):true;
                          if(!$comm[$k2]) unset($rank_rule[$key]);
                }
                if(count (array_filter($comm)) != count($credit) ){
                        continue ;
                }else{
                        return $rank_rule[$key];
                }
        }
        //不满足任何条件。则为最高等级
        return array_pop($rank_rule);

}

function setUserScore($uid,$credit){
        $api = new TS_API();
        $array = array();
        foreach ($credit['credit'] as $key=>$score){
                $temp = $api->UserScore_checkScore($uid,$key,$score);
                $array[] = $temp === 0 ? true:$temp ;
                $credit_rule[$key] = $score;
        }
        if(count(array_filter($array)) != count($array)) return false;
        $credit_rule['action'] = $credit['action'];
        $credit_rule['actioncn'] = $credit['actioncn'];
        $credit_rule['info'] = isset($credit['info']) && !empty($credit['info'])?$credit['info']:'{action}{sign}了{score}分{typecn}';
         //操作积分
         $score_result = $api->UserScore_setScore($uid,$credit_rule);
         return $score_result;
}



	function getShortMini($title,$length=40){
		$title = preg_replace("/<img .*>/i", "", $title);
		return msubstr($title,0,$length);
	}

     function sendMsg($fromUserId,$toUserId,$subject,$msg) {


		$data["fromUserId"] = $fromUserId;
		$data["toUserId"] = $toUserId;
		$data["subject"] = $subject;
		$data["content"] = $msg;
		$data["cTime"] = time();


		return  D("Msg")->add($data);		

	}

?>
