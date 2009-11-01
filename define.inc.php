<?php
//由于多应用情况下，目录由应用自己设置，核心系统就需要定义主程序的路径
define("SITE_PATH"	,	'E:/wamp/www/thinksns');

//应用跳转到核心需要的路径
define('SITE_URL'	,	'http://localhost/thinksns');

//兼容旧系统
define('ROOT_PATH'	,	SITE_URL);

//ThinkPHP框架目录
define('THINK_PATH'	,	SITE_PATH.'/thinkphp');
define('THINK_MODE'	,	'ThinkSNS');

//公共目录和风格目录
define('PUBLIC_PATH',	SITE_PATH."/public");
define('PUBLIC_URL'	,	SITE_URL."/public");
define('__PUBLIC__'	,	SITE_URL."/public");

//附件上传目录
define('UPLOAD_PATH',	SITE_PATH."/data/uploads/");	// 结尾有 /
define('UPLOAD_URL'	,	SITE_URL."/data/uploads/");		// 结尾有 /
?>