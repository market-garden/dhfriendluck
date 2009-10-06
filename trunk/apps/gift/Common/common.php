<?php
function getC($name){
	$config = ts_cache('gift_config');
	if(empty($value[$name])){
		$config = D('AppConfig')->getConfig();
		ts_cache('gift_config',$config);		
	}
	
	return $config[$name];
}
//返回当前请求地址
function getSelf()
{
	return "'".base64_encode($_SERVER['REQUEST_URI'])."'";
}

function getGiftCategoryName($id)
{
	$GiftCategory = D('GiftCategory');
	$vo = $GiftCategory->find($id);
	return $vo['name'];
}

function getGiftLink($id)
{
	$category	= D("GiftCategory")->find($id);
	$categoryId = $category['id'];
	$name = $category['name'];
	return "<a href=\"{WR}/Gift/index/categoryId/$id\">$name</a>";
}
function getStatus($status,$imageShow=true)
{
    switch($status) {
    	case 0:
            $showText   = '禁用';
            $showImg    = '<IMG SRC="../Public/images/locked.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="禁用">';
            break;
        case 1:
        default:
            $showText   =   '正常';
            $showImg    =   '<IMG SRC="../Public/images/ok.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="正常">';

    }
    return ($imageShow===true)? auto_charset($showImg) : $showText;
}