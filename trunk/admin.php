<?php
//载入全局定义
require 'define.inc.php';

//载入ThinkSNS模式定义文件

define('APP_NAME'	, 'admin');
define('APP_PATH'	, SITE_PATH.'/'.APP_NAME);
define('APP_URL'	, SITE_URL.'/'.APP_NAME);
define('APP_ROOT'	, SITE_URL);

//载入核心文件
require(THINK_PATH."/ThinkSNS.php");


//实例化一个网站应用实例
$App = new App();
$App->run();




?>