<?php
if (!defined('THINK_PATH')) exit();
return array(
	'DB_TYPE'		=>	'mysql',			//数据库类型
	'DB_HOST'		=>	'localhost',		//服务器地址
	'DB_NAME'		=>	'xysns1_3',	//数据库名
	'DB_USER'		=>	'deroot',				//用户名
	'DB_PWD'		=>	'dedata',					//密码
	'DB_PREFIX'		=>	'ts_',				//数据库表前缀
	'DB_CHARSET'	=>	'utf8',
	'URL_MODEL'     =>  3,
	'Cache_Data'	=>	SITE_PATH."/data/cache/secache_data",
	'ROUTER_ON'		=>	true,
    'DEBUG_MODE'	=>  false,
	'TS_URL'     	=>  SITE_URL,
    'UPLOAD_PATH'   =>  UPLOAD_PATH,
    'UPLOAD_URL'    =>	UPLOAD_URL,
	//'COOKIE_DOMAIN'	=>	'.thinksns.com',	//cookie域,请替换成你自己的域名 以.开头
	'TOKEN_ON'		=>	true,
	'TOKEN_NAME'	=>	'thinksns_html_token',
	'TOKEN_TYPE'	=>	'md5',
    'OTHER_TOKEN'	=>	'js',
);
?>