<?php

function getUserLevel($level){
	 if($level){
	 	$info = D('SystemGroup')->where('id='.$level)->find();
	 	return '<font color="red">'.$info['name'].'</font>';
	 }else{
	 	return '普通用户';
	 }
}

function getInfoCate($id){
	$value = D('InfoCate')->where("id=".$id)->find();
	return $value['name'];
}

function formatsize($fileSize) {
	$size = sprintf("%u", $fileSize);
	if($size == 0) {
		return("0 Bytes");
	}
	$sizename = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
	return round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $sizename[$i];

}


?>