<?php
//载入公共配置
$config	=	require 'config.inc.php';

//设定项目配置
$array	=	array(
	'ROUTER_ON'			=>	true,
	'_tags_before_login'	=>	array('ubb'),
);

//合并输出配置
return array_merge($config,$array);
?>