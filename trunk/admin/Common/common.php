<?php

function getUserLevel($level){
	$level	=	intval($level);
	if($level>0){
		$info = D('SystemGroup')->where("id='$level'")->find();
		return '<font color="red">'.$info['name'].'</font>';
	}else{
		return '普通用户';
	}
}

function getInfoCate($id){
	$value = D('InfoCate')->where("id=".$id)->find();
	return $value['title'];
}

function formatsize($fileSize) {
	$size = sprintf("%u", $fileSize);
	if($size == 0) {
		return("0 Bytes");
	}
	$sizename = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
	return round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $sizename[$i];

}

//删除目录
function rmdirr($dirname) {
	if (!file_exists($dirname)) {
		return false;
	}
	if (is_file($dirname) || is_link($dirname)) {
		return unlink($dirname);
	}
	$dir = dir($dirname);
	while (false !== $entry = $dir->read()) {
		if ($entry == '.' || $entry == '..') {
			continue;
		}
		rmdirr($dirname . DIRECTORY_SEPARATOR . $entry);
	}
	$dir->close();
	return rmdir($dirname);
}

?>