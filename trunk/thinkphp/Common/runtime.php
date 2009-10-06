<?php
// +----------------------------------------------------------------------
// | ThinkPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

function mkdirs($dirs,$mode=0777) {
    foreach ($dirs as $dir){
        @mkdir($dir,$mode,true);
    }
}

// 创建项目目录结构
function buildAppDir() {
	if(is_writable(SITE_PATH.'/runtime')){
        mkdirs(array(
            LIB_PATH,
            RUNTIME_PATH,
            CONFIG_PATH,
            COMMON_PATH,
            LANG_PATH,
            CACHE_PATH,
            TMPL_PATH,
            TMPL_PATH.'default/',
            LOG_PATH,
            TEMP_PATH,
            DATA_PATH,
            LIB_PATH.'Model/',
            LIB_PATH.'Action/',
            ));
    }else{
        header("Content-Type:text/html; charset=utf-8");
        exit('<div style=\'font-weight:bold;float:left;width:345px;text-align:center;border:1px solid silver;background:#E8EFFF;padding:8px;color:red;font-size:14px;font-family:Tahoma\'>项目目录不可写，目录无法自动生成！<BR>请使用项目生成器或者手动生成项目目录~</div>');
    }
}
?>