<?php
require_once "friendfeed.php";
class service_const{

    static $service_url_templates = array(
        friendfeed::BLOG_SINA=>"http://blog.sina.com.cn/%1\$s",
        friendfeed::BLOG_163=>"http://blog.163.com/%1\$s",
        friendfeed::BLOG_SOHU=>"http://%1\$s.blog.sohu.com",
        friendfeed::BLOG_BAIDU=>"http://hi.baidu.com/%1\$s",
        friendfeed::BLOG_MSN=>"http://%1\$s.spaces.live.com",
    ); 
    
    static $service_names = array(
        friendfeed::BLOG_SINA=>'新浪博客',
        friendfeed::BLOG_163=>'网易博客',
        friendfeed::BLOG_SOHU=>'搜狐博客',
        friendfeed::BLOG_BAIDU=>'百度空间',
        friendfeed::BLOG_MSN=>'MSN Live Space',
    );   
    
    static $service_urls = array(
        friendfeed::BLOG_SINA=>'http://blog.sina.com.cn/',
        friendfeed::BLOG_163=>'http://blog.163.com/',
        friendfeed::BLOG_SOHU=>'http://blog.sohu.com/',
        friendfeed::BLOG_BAIDU=>'http://hi.baidu.com/',
        friendfeed::BLOG_MSN=>'http://spaces.live.com/',
    );      
    
    static $service_icons = array(
        friendfeed::BLOG_SINA=>'sina.gif',
        friendfeed::BLOG_163=>'163.gif',
        friendfeed::BLOG_SOHU=>'sohu.gif',
        friendfeed::BLOG_BAIDU=>'baidu.gif',
        friendfeed::BLOG_MSN=>'msn.gif',
    );
    
    static $item_actions = array(
        FEED_BLOG=>'发表',
        FEED_IMAGE=>'上传',
        FEED_VIDEO=>'上传',
    );
    
    static $item_as = array(
        FEED_BLOG=>'篇',
        FEED_IMAGE=>'张',
        FEED_VIDEO=>'个',
    );    
    
    static $item_descs = array(
        FEED_BLOG=>'日志',
        FEED_IMAGE=>'照片',
        FEED_VIDEO=>'视频',
    );    
}
