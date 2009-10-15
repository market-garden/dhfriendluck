<?php
//由ThinkPHP工具箱生成的配置文件
if (!defined('THINK_PATH')) exit();

//应用配置文件
$photoConfig = array (
	'LANG_SWITCH_ON' => true,
    'DEFAULT_ACTION' => 'index',
);

//全局配置文件
$array = require_once( SITE_PATH.'/config.inc.php' );

//合并配置
$array = array_merge( $photoConfig,$array );
return $array;
?>
