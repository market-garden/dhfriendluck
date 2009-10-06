 drop table if exists ts_ad;
CREATE TABLE `ts_ad` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `place` varchar(25) NOT NULL,
  `use` tinyint(1) NOT NULL,
  `ad` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_app;
CREATE TABLE `ts_app` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `enname` varchar(150) NOT NULL,
  `icon` text NOT NULL,
  `url` text NOT NULL,
  `url_exp` text,
  `url_admin` text,
  `uid_url` varchar(255) default NULL,
  `add_url` varchar(255) default NULL,
  `add_name` varchar(255) default NULL,
  `author` varchar(255) default 'thinksns',
  `description` text NOT NULL,
  `order2` int(11) NOT NULL default '1',
  `place` tinyint(1) NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '0',
  `canvas_url` varchar(255) default NULL,
  `type` tinyint(3) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

INSERT INTO ts_app VALUES ('3','心情','mini','http://{APP_URL}/appinfo/ico_app04.gif','http://{APPS_URL}/mini','index.php?s=','http://{APP_URL}/index.php?s=Admin','http://{APP_URL}/index.php?s=/Index/friends/uid/','','','sam','心情','5','0','0','','');
INSERT INTO ts_app VALUES ('4','日志','blog','http://{APP_URL}/appinfo/ico_app01.gif','http://{APPS_URL}/blog','index.php?s=','http://{APP_URL}/index.php?s=Admin','http://{APP_URL}/index.php?s=/Index/personal/uid/','http://{APP_URL}/index.php?s=Index/addBlog','发表','sam','想分享你的文章给你的好友么，快来记录日志吧','1','0','0','','');
INSERT INTO ts_app VALUES ('10','相册','photo','http://{APP_URL}/appinfo/ico_app02.gif','http://{APPS_URL}/photo','index.php?s=','http://{APP_URL}/index.php?s=Admin','http://{APP_URL}/index.php?s=/Index/photos/uid/','http://{APP_URL}/index.php/Upload/flash','上传','sam','分享你的图片给好友吧','4','0','0','','');
INSERT INTO ts_app VALUES ('11','分享','share','http://{APP_URL}/appinfo/ico_app_share.gif','http://{APPS_URL}/share','index.php?s=','http://{APP_URL}/index.php?s=Admin','http://{APP_URL}/index.php?s=/Index/personal/uid/','','','wxh','分享视频，网址，图片','2','0','0','','');
INSERT INTO ts_app VALUES ('12','群组','group','http://{APP_URL}/appinfo/ico_app05.gif','http://{APPS_URL}/group','index.php?s=','http://{APP_URL}/index.php?s=Admin','http://{APP_URL}/index.php?s=/SomeOne/index/uid/','','','shg','创建你自己群组，邀请三五好友，讨论你们感兴趣的话题吧','7','0','0','','');
INSERT INTO ts_app VALUES ('13','投票','vote','http://{APP_URL}/appinfo/ico_app06.gif','http://{APPS_URL}/vote','index.php?s=','http://{APP_URL}/index.php?s=Admin','http://{APP_URL}/index.php?s=/Index/personal/uid/','http://{APP_URL}/index.php?s=/Index/addPoll','发起','sam','投票','6','0','0','','');
INSERT INTO ts_app VALUES ('14','活动','event','http://{APP_URL}/appinfo/ico_app07.gif','http://{APPS_URL}/event','index.php?s=','http://{APP_URL}/index.php?s=Admin','http://{APP_URL}/index.php?s=/Index/personal/uid/','http://{APP_URL}/index.php?s=/Index/addEvent','发起','sam','想组织你的站内好友一起来活动么，快快来参与吧！','8','0','0','','');
INSERT INTO ts_app VALUES ('17','礼品','gift','http://{APP_URL}/appinfo/ico_apply.gif','http://{APPS_URL}/gift','index.php?s=','http://{APP_URL}/index.php?s=Admin','http://{APP_URL}/index.php?s=/Index/personal/uid/','http://{APP_URL}/index.php?s=/Index/index/uid/','','水上人','礼品','1','0','0','','');

 drop table if exists ts_app_user;
CREATE TABLE `ts_app_user` (
  `appid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `open` tinyint(1) NOT NULL default '1',
  `publish_to_profile` tinyint(1) NOT NULL default '0',
  `activited` tinyint(1) NOT NULL default '0',
  `login_times` int(11) NOT NULL,
  `last_login_time` int(11) NOT NULL,
  `invitor` int(11) default NULL,
  PRIMARY KEY  (`appid`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_attach;
CREATE TABLE `ts_attach` (
  `id` int(11) NOT NULL auto_increment,
  `attach_type` varchar(50) NOT NULL default 'attach',
  `userId` int(11) unsigned default NULL,
  `uploadTime` int(11) unsigned default NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `size` varchar(20) NOT NULL,
  `extension` varchar(20) NOT NULL,
  `hash` varchar(32) NOT NULL,
  `private` tinyint(1) default '0',
  `isDel` tinyint(1) default '0',
  `savepath` varchar(255) NOT NULL,
  `savename` varchar(255) NOT NULL,
  `savedomain` tinyint(3) default '0',
  PRIMARY KEY  (`id`),
  KEY `userId` (`userId`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

INSERT INTO ts_attach VALUES ('1','photo','2','1254731461','1.jpg','image/pjpeg','13463','jpg','1b615398e3119fed175d2b167e26e78b','0','0','20091005/16/','4ac9aec580a34.jpg','0');
INSERT INTO ts_attach VALUES ('2','face','3','1254731998','DSCF0802.JPG','image/pjpeg','1248604','JPG','6b060cd89dd746255a0a51a6bc028401','0','0','20091005/16/','4ac9b0de547b7.JPG','0');
INSERT INTO ts_attach VALUES ('3','face','3','1254732215','DSCF0756.JPG','image/pjpeg','1307962','JPG','59832fad5568256abc498dcf67ba2ad3','0','0','20091005/16/','4ac9b1b7df2b4.JPG','0');
INSERT INTO ts_attach VALUES ('4','face','3','1254732422','DSCF0776.JPG','image/pjpeg','1260542','JPG','e3a40c1b1d589b265d1359b88b4a0b29','0','0','20091005/16/','4ac9b2864ce6d.JPG','0');
INSERT INTO ts_attach VALUES ('5','photo','4','1254733051','DSC02463.JPG','application/octet-stream','882738','JPG','92d217fd9e0a8cea002251eaad861367','0','0','20091005/16/','4ac9b4fb5121d.JPG','0');
INSERT INTO ts_attach VALUES ('6','photo','4','1254733082','DSC02417.JPG','application/octet-stream','644775','JPG','dfe101ce6b7395c2044555aab796b805','0','0','20091005/16/','4ac9b51a38a8d.JPG','0');
INSERT INTO ts_attach VALUES ('7','photo','4','1254733117','DSC02418.JPG','application/octet-stream','727547','JPG','a1e3333858e96a6d7a6ec432b28e1954','0','0','20091005/16/','4ac9b53d05937.JPG','0');
INSERT INTO ts_attach VALUES ('8','photo','4','1254733155','DSC02419.JPG','application/octet-stream','803512','JPG','240f4c2e642785234a1f9b6b62b0d6a6','0','0','20091005/16/','4ac9b5635b44d.JPG','0');
INSERT INTO ts_attach VALUES ('9','photo','4','1254733187','DSC02420.JPG','application/octet-stream','652336','JPG','6fc72ee69c6204f584fc9f6a4a9f067f','0','0','20091005/16/','4ac9b5834c688.JPG','0');
INSERT INTO ts_attach VALUES ('10','photo','4','1254733226','DSC02423.JPG','application/octet-stream','752742','JPG','bfdfcc452d6e89fbf61c205e36a9d4b8','0','0','20091005/17/','4ac9b5aa377e4.JPG','0');
INSERT INTO ts_attach VALUES ('11','photo','4','1254733256','DSC02445.JPG','application/octet-stream','611781','JPG','f3ba4ee9a3829c19284c0debb61b32f0','0','0','20091005/17/','4ac9b5c8611d0.JPG','0');
INSERT INTO ts_attach VALUES ('12','photo','4','1254733294','DSC02446.JPG','application/octet-stream','795241','JPG','37b622cf92d6548378fc3cf3967e87c2','0','0','20091005/17/','4ac9b5ee53a93.JPG','0');
INSERT INTO ts_attach VALUES ('13','photo','4','1254733333','DSC02447.JPG','application/octet-stream','836491','JPG','1da168d39bb9e3e611081eddf05b62be','0','0','20091005/17/','4ac9b61549c0f.JPG','0');
INSERT INTO ts_attach VALUES ('14','photo','4','1254733371','DSC02448.JPG','application/octet-stream','815370','JPG','0c24d4b0705e1484e5a34fddaa711e57','0','0','20091005/17/','4ac9b63b5ab90.JPG','0');
INSERT INTO ts_attach VALUES ('15','photo','4','1254733412','DSC02451.JPG','application/octet-stream','841104','JPG','7b5bb0366ea89eb6e9cafab84d149a57','0','0','20091005/17/','4ac9b66429849.JPG','0');
INSERT INTO ts_attach VALUES ('16','photo','4','1254733452','DSC02453.JPG','application/octet-stream','813241','JPG','cfe45ca8b9be28a5ad00a94d51d59732','0','0','20091005/17/','4ac9b68cd97f1.JPG','0');
INSERT INTO ts_attach VALUES ('17','photo','4','1254733494','DSC02454.JPG','application/octet-stream','854853','JPG','7cc5af3b6a75db4fee6031ae579f9c21','0','0','20091005/17/','4ac9b6b6b87a0.JPG','0');
INSERT INTO ts_attach VALUES ('18','photo','4','1254733541','DSC02455.JPG','application/octet-stream','820526','JPG','1525e5d2d13396e816cc461ba562c6b0','0','0','20091005/17/','4ac9b6e5558f7.JPG','0');

 drop table if exists ts_blog;
CREATE TABLE `ts_blog` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL default '0',
  `name` varchar(20) NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` mediumint(5) NOT NULL,
  `cover` varchar(255) default NULL,
  `content` longtext NOT NULL,
  `readCount` int(11) NOT NULL default '0',
  `commentCount` int(11) NOT NULL default '0',
  `recommendCount` int(11) NOT NULL default '0',
  `tags` varchar(255) default NULL,
  `cTime` int(11) NOT NULL,
  `mTime` int(11) NOT NULL,
  `rTime` int(11) NOT NULL default '0',
  `isHot` varchar(1) NOT NULL default '0',
  `type` int(1) NOT NULL,
  `status` varchar(1) NOT NULL default '1',
  `private` tinyint(1) NOT NULL default '0',
  `private_data` varchar(255) NOT NULL,
  `hot` int(11) NOT NULL default '0',
  `canableComment` tinyint(1) NOT NULL default '1',
  `attach` text,
  PRIMARY KEY  (`id`),
  KEY `hot` (`hot`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_blog_category;
CREATE TABLE `ts_blog_category` (
  `id` mediumint(5) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `uid` int(11) NOT NULL,
  `pid` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO ts_blog_category VALUES ('1','未分类','0','0');

 drop table if exists ts_blog_config;
CREATE TABLE `ts_blog_config` (
  `variable` char(20) character set latin1 NOT NULL,
  `value` text character set latin1 NOT NULL,
  PRIMARY KEY  (`variable`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_blog_item;
CREATE TABLE `ts_blog_item` (
  `id` int(11) NOT NULL auto_increment,
  `sourceId` int(11) NOT NULL,
  `snapday` int(11) NOT NULL,
  `pubdate` int(11) NOT NULL,
  `boot` tinyint(1) NOT NULL default '0',
  `title` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `summary` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `source_id` (`sourceId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_blog_mention;
CREATE TABLE `ts_blog_mention` (
  `id` int(11) NOT NULL auto_increment,
  `blogid` int(20) NOT NULL,
  `uid` int(11) NOT NULL,
  `name` varchar(20) character set utf8 collate utf8_unicode_ci NOT NULL,
  `type` varchar(20) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `blogid` (`blogid`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_blog_outline;
CREATE TABLE `ts_blog_outline` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL default '0',
  `name` varchar(20) NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` mediumint(5) NOT NULL,
  `cover` varchar(255) default NULL,
  `content` longtext NOT NULL,
  `readCount` int(11) NOT NULL default '0',
  `commentCount` int(11) NOT NULL default '0',
  `tags` varchar(255) default NULL,
  `cTime` int(11) NOT NULL,
  `mTime` int(11) NOT NULL,
  `isHot` varchar(1) NOT NULL default '0',
  `type` int(1) NOT NULL,
  `status` varchar(1) NOT NULL default '1',
  `private` tinyint(1) NOT NULL default '0',
  `hot` int(11) NOT NULL default '0',
  `friendId` text,
  `canableComment` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `hot` (`hot`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_blog_source;
CREATE TABLE `ts_blog_source` (
  `id` int(11) NOT NULL auto_increment,
  `service` varchar(10) character set gbk NOT NULL,
  `username` char(30) character set gbk NOT NULL,
  `cTime` int(11) NOT NULL,
  `lastSnap` int(11) NOT NULL,
  `isNew` tinyint(1) NOT NULL,
  `changes` varchar(255) character set gbk NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_blog_subscribe;
CREATE TABLE `ts_blog_subscribe` (
  `id` int(11) NOT NULL auto_increment,
  `sourceId` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `type` tinyint(4) default '0',
  `newNum` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `sourceId` (`sourceId`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_chat;
CREATE TABLE `ts_chat` (
  `id` int(11) NOT NULL auto_increment,
  `fromUserId` int(11) NOT NULL,
  `toUserId` int(11) NOT NULL,
  `msg` text NOT NULL,
  `disTime` varchar(25) NOT NULL,
  `flagNew` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_comment;
CREATE TABLE `ts_comment` (
  `id` int(11) NOT NULL auto_increment,
  `type` char(15) character set gbk NOT NULL,
  `appid` int(11) NOT NULL,
  `name` varchar(30) character set gbk NOT NULL,
  `uid` int(11) NOT NULL,
  `comment` text character set gbk NOT NULL,
  `cTime` int(12) NOT NULL,
  `toId` int(11) NOT NULL default '0',
  `status` int(1) default '0',
  `quietly` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`),
  KEY `appid` (`appid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_credit_setting;
CREATE TABLE `ts_credit_setting` (
  `id` int(11) NOT NULL auto_increment,
  `action` varchar(50) NOT NULL,
  `actioncn` varchar(255) NOT NULL,
  `type` varchar(30) NOT NULL default 'user',
  `info` text NOT NULL,
  `score` int(11) NOT NULL default '0',
  `experience` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=79 DEFAULT CHARSET=utf8;

INSERT INTO ts_credit_setting VALUES ('33','delete_blog','删除博客','user','{action}{sign}了{score}{typecn}','-3','-5');
INSERT INTO ts_credit_setting VALUES ('34','add_blog','发表博客','user','{action}{sign}了{score}{typecn}','5','5');
INSERT INTO ts_credit_setting VALUES ('35','update_face','更换头像','user','{action}{sign}了{score}{typecn}','1','1');
INSERT INTO ts_credit_setting VALUES ('36','email_active','邮箱激活','user','{action}{sign}了{score}{typecn}','4','4');
INSERT INTO ts_credit_setting VALUES ('37','invite_friend','邀请好友','user','{action}{sign}了{score}{typecn}','10','10');
INSERT INTO ts_credit_setting VALUES ('38','report_user','成功举报','user','{action}{sign}了{score}{typecn}','2','2');
INSERT INTO ts_credit_setting VALUES ('39','add_mini','发布心情','user','{action}{sign}了{score}{typecn}','1','2');
INSERT INTO ts_credit_setting VALUES ('40','user_login','用户登录','user','{action}{sign}了{score}{typecn}','0','1');
INSERT INTO ts_credit_setting VALUES ('41','visit_space','访问他人空间','user','{action}{sign}了{score}{typecn}','0','2');
INSERT INTO ts_credit_setting VALUES ('42','user_visited','空间被访问','user','{action}{sign}了{score}{typecn}','1','1');
INSERT INTO ts_credit_setting VALUES ('43','wall','留言','user','{action}{sign}了{score}{typecn}','1','3');
INSERT INTO ts_credit_setting VALUES ('44','walled','被留言','user','{action}{sign}了{score}{typecn}','1','1');
INSERT INTO ts_credit_setting VALUES ('45','creat_vote','发起投票','user','{action}{sign}了{score}{typecn}','20','20');
INSERT INTO ts_credit_setting VALUES ('46','join_vote','参与投票','user','{action}{sign}了{score}{typecn}','1','5');
INSERT INTO ts_credit_setting VALUES ('47','joined_vote','投票被参与','user','{action}{sign}了{score}{typecn}','1','1');
INSERT INTO ts_credit_setting VALUES ('48','creat_event','发起活动','user','{action}{sign}了{score}{typecn}','20','20');
INSERT INTO ts_credit_setting VALUES ('49','join_event','参与活动','user','{action}{sign}了{score}{typecn}','1','2');
INSERT INTO ts_credit_setting VALUES ('50','att_event','关注活动','user','{action}{sign}了{score}{typecn}','1','1');
INSERT INTO ts_credit_setting VALUES ('51','corresond_event','活动被响应','user','{action}{sign}了{score}{typecn}','10','10');
INSERT INTO ts_credit_setting VALUES ('52','add_share','发起分享','user','{action}{sign}了{score}{typecn}','5','5');
INSERT INTO ts_credit_setting VALUES ('53','visit_share','分享被查看','user','{action}{sign}了{score}{typecn}','2','2');
INSERT INTO ts_credit_setting VALUES ('54','shared','被分享','user','{action}{sign}了{score}{typecn}','10','10');
INSERT INTO ts_credit_setting VALUES ('55','attach_down','附件下载','user','{action}{sign}了{score}{typecn}','-20','10');
INSERT INTO ts_credit_setting VALUES ('56','photo_creat','创建相册','user','{action}{sign}了{score}{typecn}','10','10');
INSERT INTO ts_credit_setting VALUES ('57','upload_attach','上传附件','user','{action}{sign}了{score}{typecn}','1','1');
INSERT INTO ts_credit_setting VALUES ('58','add_app','添加应用','user','{action}{sign}了{score}{typecn}','100','50');
INSERT INTO ts_credit_setting VALUES ('59','comment','评论','user','{action}{sign}了{score}{typecn}','1','1');
INSERT INTO ts_credit_setting VALUES ('60','comment_comment','回复别人的评论','user','{action}{sign}了{score}{typecn}','1','1');
INSERT INTO ts_credit_setting VALUES ('61','commented','评论被评论','user','{action}{sign}了{score}{typecn}','2','2');
INSERT INTO ts_credit_setting VALUES ('62','mentioned','被人在应用里提到','user','{action}{sign}了{score}{typecn}','1','1');
INSERT INTO ts_credit_setting VALUES ('63','replay_mini','回复心情','user','{action}{sign}了{score}{typecn}','1','1');
INSERT INTO ts_credit_setting VALUES ('64','replayed_mini','心情被回复','user','{action}{sign}了{score}{typecn}','1','1');
INSERT INTO ts_credit_setting VALUES ('65','commented','应用被评论','user','{action}{sign}了{score}{typecn}','1','2');
INSERT INTO ts_credit_setting VALUES ('66','group_create','群组创建','user','{action}{sign}了{score}{typecn}','-2','0');
INSERT INTO ts_credit_setting VALUES ('67','group_topic_add','群组发布话题','user','{action}{sign}了{score}{typecn}','-2','0');
INSERT INTO ts_credit_setting VALUES ('68','group_topic_reply','群组话题回复','user','{action}{sign}了{score}{typecn}','2','0');
INSERT INTO ts_credit_setting VALUES ('69','group_topic_top','群组话题置顶','user','{action}{sign}了{score}{typecn}','2','0');
INSERT INTO ts_credit_setting VALUES ('70','group_topic_dist','群组话题精华','user','{action}{sign}了{score}{typecn}','2','2');
INSERT INTO ts_credit_setting VALUES ('71','group_file_upload','群组文件上传','user','{action}{sign}了{score}{typecn}','2','0');
INSERT INTO ts_credit_setting VALUES ('72','group_photo_upload','群组图片上传','user','{action}{sign}了{score}{typecn}','2','0');
INSERT INTO ts_credit_setting VALUES ('73','group_topic_delete','话题的删除','user','{action}{sign}了{score}{typecn}','-2','0');
INSERT INTO ts_credit_setting VALUES ('74','group_topic_cancel_top','群组话题取消置顶','user','{action}{sign}了{score}{typecn}','-2','0');
INSERT INTO ts_credit_setting VALUES ('75','group_topic_cancel_dist','群组话题取消精华','user','{action}{sign}了{score}{typecn}','-2','0');
INSERT INTO ts_credit_setting VALUES ('76','group_file_delete','群组删除文件','user','{action}{sign}了{score}{typecn}','-2','0');
INSERT INTO ts_credit_setting VALUES ('77','group_photo_delete','群组删除图片','user','{action}{sign}了{score}{typecn}','-2','0');
INSERT INTO ts_credit_setting VALUES ('78','delete','删除应用','user','{action}{sign}了{score}{typecn}','-80','-30');

 drop table if exists ts_credit_type;
CREATE TABLE `ts_credit_type` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL,
  `alias` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

INSERT INTO ts_credit_type VALUES ('12','score','积分');
INSERT INTO ts_credit_type VALUES ('15','experience','经验');

 drop table if exists ts_edu_search;
CREATE TABLE `ts_edu_search` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `school` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `year` varchar(255) NOT NULL,
  `extra1` varchar(255) NOT NULL,
  `extra2` varchar(255) NOT NULL,
  `privacy` int(1) NOT NULL,
  `home` int(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_event;
CREATE TABLE `ts_event` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `title` text NOT NULL,
  `explain` text NOT NULL,
  `contact` varchar(32) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `sTime` int(11) default NULL,
  `eTime` int(11) default NULL,
  `address` varchar(255) default NULL,
  `cTime` int(11) NOT NULL,
  `deadline` int(11) NOT NULL,
  `joinCount` int(11) NOT NULL default '0',
  `attentionCount` int(11) NOT NULL default '0',
  `limitCount` int(11) NOT NULL default '0',
  `commentCount` int(11) NOT NULL default '0',
  `coverId` int(11) NOT NULL default '0',
  `optsId` int(11) NOT NULL default '0',
  `feedId` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_event_config;
CREATE TABLE `ts_event_config` (
  `variable` char(20) character set latin1 NOT NULL,
  `value` text character set latin1 NOT NULL,
  PRIMARY KEY  (`variable`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_event_opts;
CREATE TABLE `ts_event_opts` (
  `id` int(11) NOT NULL auto_increment,
  `cost` char(10) NOT NULL default '0',
  `costExplain` varchar(255) default '0',
  `province` char(10) default NULL,
  `city` char(10) default NULL,
  `area` varchar(10) default NULL,
  `opts` varchar(50) NOT NULL default '0',
  `isHot` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_event_photo;
CREATE TABLE `ts_event_photo` (
  `id` int(11) NOT NULL auto_increment,
  `eventId` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `name` char(10) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `filepath` varchar(255) NOT NULL,
  `savename` varchar(255) NOT NULL,
  `aid` int(11) NOT NULL,
  `cTime` int(11) NOT NULL,
  `commentCount` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_event_type;
CREATE TABLE `ts_event_type` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

INSERT INTO ts_event_type VALUES ('1','音乐/演出');
INSERT INTO ts_event_type VALUES ('2','展览');
INSERT INTO ts_event_type VALUES ('3','电影');
INSERT INTO ts_event_type VALUES ('4','讲座/沙龙');
INSERT INTO ts_event_type VALUES ('5','戏剧/曲艺');
INSERT INTO ts_event_type VALUES ('8','体育');
INSERT INTO ts_event_type VALUES ('9','旅行');
INSERT INTO ts_event_type VALUES ('10','公益');
INSERT INTO ts_event_type VALUES ('11','其他');

 drop table if exists ts_event_user;
CREATE TABLE `ts_event_user` (
  `id` int(11) NOT NULL auto_increment,
  `eventId` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `contact` text,
  `action` char(10) NOT NULL default 'attention',
  `status` tinyint(1) NOT NULL default '1',
  `cTime` int(11) NOT NULL,
  `feedId` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_feed;
CREATE TABLE `ts_feed` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `title_data` text NOT NULL,
  `body_data` text NOT NULL,
  `cTime` int(11) default NULL,
  `appid` varchar(25) default '',
  `feedtype` tinyint(3) NOT NULL default '0',
  `fid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

INSERT INTO ts_feed VALUES ('1','1','管理员','add_friend','a:2:{s:4:\"user\";s:40:\"<a href=\'{__TS__}/space/1\'>管理员</a>\";s:5:\"fuser\";s:43:\"<a href=\'{__TS__}/space/2\'>fengzhiqiang</a>\";}','0','1254731069','0','0','0');
INSERT INTO ts_feed VALUES ('2','2','fengzhiqiang','add_friend','a:2:{s:4:\"user\";s:43:\"<a href=\'{__TS__}/space/2\'>fengzhiqiang</a>\";s:5:\"fuser\";s:40:\"<a href=\'{__TS__}/space/1\'>管理员</a>\";}','0','1254731069','0','0','0');
INSERT INTO ts_feed VALUES ('3','4','侯光成','mini','a:1:{s:7:\"content\";s:21:\"没事干，很无聊\";}','a:3:{s:2:\"id\";i:1;s:3:\"uid\";i:4;s:3:\"con\";s:21:\"没事干，很无聊\";}','1254732832','3','0','0');
INSERT INTO ts_feed VALUES ('4','3','冯志强','add_friend','a:2:{s:4:\"user\";s:40:\"<a href=\'{__TS__}/space/3\'>冯志强</a>\";s:5:\"fuser\";s:40:\"<a href=\'{__TS__}/space/4\'>侯光成</a>\";}','0','1254732954','0','0','0');
INSERT INTO ts_feed VALUES ('5','4','侯光成','add_friend','a:2:{s:4:\"user\";s:40:\"<a href=\'{__TS__}/space/4\'>侯光成</a>\";s:5:\"fuser\";s:40:\"<a href=\'{__TS__}/space/3\'>冯志强</a>\";}','0','1254732954','0','0','0');
INSERT INTO ts_feed VALUES ('6','4','侯光成','photo','a:2:{s:3:\"num\";i:14;s:5:\"album\";s:81:\"<a href=\"{SITE_URL}/apps/photo/index.php/Index/album/id/2/uid/4\">我的相册</a>\";}','a:2:{s:3:\"pic\";s:976:\"<span style=\"margin:2px;\"><a href=\"{SITE_URL}/apps/photo/index.php//Index/photo/id/15/aid/2/uid/4\"><img src=\"{SITE_URL}/thumb.php?w=120&w=100&t=f&url={UPLOAD_URL}20091005/17/4ac9b6e5558f7.JPG\" width=80 /></a></span><span style=\"margin:2px;\"><a href=\"{SITE_URL}/apps/photo/index.php//Index/photo/id/14/aid/2/uid/4\"><img src=\"{SITE_URL}/thumb.php?w=120&w=100&t=f&url={UPLOAD_URL}20091005/17/4ac9b6b6b87a0.JPG\" width=80 /></a></span><span style=\"margin:2px;\"><a href=\"{SITE_URL}/apps/photo/index.php//Index/photo/id/13/aid/2/uid/4\"><img src=\"{SITE_URL}/thumb.php?w=120&w=100&t=f&url={UPLOAD_URL}20091005/17/4ac9b68cd97f1.JPG\" width=80 /></a></span><span style=\"margin:2px;\"><a href=\"{SITE_URL}/apps/photo/index.php//Index/photo/id/12/aid/2/uid/4\"><img src=\"{SITE_URL}/thumb.php?w=120&w=100&t=f&url={UPLOAD_URL}20091005/17/4ac9b66429849.JPG\" width=80 /></a></span><span style=\"margin:2px;\"><a href=\"{SITE_URL}/apps/photo/index.php/Index/album/id/2/uid/4\">全部照片>></a></span>\";s:8:\"pic_data\";a:14:{i:0;a:4:{s:3:\"pid\";s:2:\"15\";s:3:\"uid\";s:1:\"4\";s:3:\"aid\";s:1:\"2\";s:8:\"savepath\";s:29:\"20091005/17/4ac9b6e5558f7.JPG\";}i:1;a:4:{s:3:\"pid\";s:2:\"14\";s:3:\"uid\";s:1:\"4\";s:3:\"aid\";s:1:\"2\";s:8:\"savepath\";s:29:\"20091005/17/4ac9b6b6b87a0.JPG\";}i:2;a:4:{s:3:\"pid\";s:2:\"13\";s:3:\"uid\";s:1:\"4\";s:3:\"aid\";s:1:\"2\";s:8:\"savepath\";s:29:\"20091005/17/4ac9b68cd97f1.JPG\";}i:3;a:4:{s:3:\"pid\";s:2:\"12\";s:3:\"uid\";s:1:\"4\";s:3:\"aid\";s:1:\"2\";s:8:\"savepath\";s:29:\"20091005/17/4ac9b66429849.JPG\";}i:4;a:4:{s:3:\"pid\";s:2:\"11\";s:3:\"uid\";s:1:\"4\";s:3:\"aid\";s:1:\"2\";s:8:\"savepath\";s:29:\"20091005/17/4ac9b63b5ab90.JPG\";}i:5;a:4:{s:3:\"pid\";s:2:\"10\";s:3:\"uid\";s:1:\"4\";s:3:\"aid\";s:1:\"2\";s:8:\"savepath\";s:29:\"20091005/17/4ac9b61549c0f.JPG\";}i:6;a:4:{s:3:\"pid\";s:1:\"9\";s:3:\"uid\";s:1:\"4\";s:3:\"aid\";s:1:\"2\";s:8:\"savepath\";s:29:\"20091005/17/4ac9b5ee53a93.JPG\";}i:7;a:4:{s:3:\"pid\";s:1:\"8\";s:3:\"uid\";s:1:\"4\";s:3:\"aid\";s:1:\"2\";s:8:\"savepath\";s:29:\"20091005/17/4ac9b5c8611d0.JPG\";}i:8;a:4:{s:3:\"pid\";s:1:\"7\";s:3:\"uid\";s:1:\"4\";s:3:\"aid\";s:1:\"2\";s:8:\"savepath\";s:29:\"20091005/17/4ac9b5aa377e4.JPG\";}i:9;a:4:{s:3:\"pid\";s:1:\"6\";s:3:\"uid\";s:1:\"4\";s:3:\"aid\";s:1:\"2\";s:8:\"savepath\";s:29:\"20091005/16/4ac9b5834c688.JPG\";}i:10;a:4:{s:3:\"pid\";s:1:\"5\";s:3:\"uid\";s:1:\"4\";s:3:\"aid\";s:1:\"2\";s:8:\"savepath\";s:29:\"20091005/16/4ac9b5635b44d.JPG\";}i:11;a:4:{s:3:\"pid\";s:1:\"4\";s:3:\"uid\";s:1:\"4\";s:3:\"aid\";s:1:\"2\";s:8:\"savepath\";s:29:\"20091005/16/4ac9b53d05937.JPG\";}i:12;a:4:{s:3:\"pid\";s:1:\"3\";s:3:\"uid\";s:1:\"4\";s:3:\"aid\";s:1:\"2\";s:8:\"savepath\";s:29:\"20091005/16/4ac9b51a38a8d.JPG\";}i:13;a:4:{s:3:\"pid\";s:1:\"2\";s:3:\"uid\";s:1:\"4\";s:3:\"aid\";s:1:\"2\";s:8:\"savepath\";s:29:\"20091005/16/4ac9b4fb5121d.JPG\";}}}','1254733551','10','0','0');
INSERT INTO ts_feed VALUES ('7','1','管理员','add_friend','a:2:{s:4:\"user\";s:40:\"<a href=\'{__TS__}/space/1\'>管理员</a>\";s:5:\"fuser\";s:40:\"<a href=\'{__TS__}/space/4\'>侯光成</a>\";}','0','1254733725','0','0','0');
INSERT INTO ts_feed VALUES ('8','4','侯光成','add_friend','a:2:{s:4:\"user\";s:40:\"<a href=\'{__TS__}/space/4\'>侯光成</a>\";s:5:\"fuser\";s:40:\"<a href=\'{__TS__}/space/1\'>管理员</a>\";}','0','1254733725','0','0','0');
INSERT INTO ts_feed VALUES ('9','1','管理员','add_friend','a:2:{s:4:\"user\";s:40:\"<a href=\'{__TS__}/space/1\'>管理员</a>\";s:5:\"fuser\";s:40:\"<a href=\'{__TS__}/space/3\'>冯志强</a>\";}','0','1254733747','0','0','0');
INSERT INTO ts_feed VALUES ('10','3','冯志强','add_friend','a:2:{s:4:\"user\";s:40:\"<a href=\'{__TS__}/space/3\'>冯志强</a>\";s:5:\"fuser\";s:40:\"<a href=\'{__TS__}/space/1\'>管理员</a>\";}','0','1254733747','0','0','0');

 drop table if exists ts_feed_del;
CREATE TABLE `ts_feed_del` (
  `uid` int(11) NOT NULL,
  `feedId` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_feed_template;
CREATE TABLE `ts_feed_template` (
  `id` int(11) NOT NULL auto_increment,
  `type` varchar(255) default NULL,
  `title` text NOT NULL,
  `body` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=55 DEFAULT CHARSET=utf8;

INSERT INTO ts_feed_template VALUES ('13','photo','{actor}上传了{num}张照片至{album}','<div class=\"left mr5\">{pic}</div>');
INSERT INTO ts_feed_template VALUES ('5','friend','{actor} 和 {touser} 成为了好友','');
INSERT INTO ts_feed_template VALUES ('14','record','{actor} 发了一条记录了: {content}','');
INSERT INTO ts_feed_template VALUES ('15','parking','{actor}把{car}停在了{xxx}的车位上~~~','');
INSERT INTO ts_feed_template VALUES ('16','share_url','{actor} 分享了一个网址 (<a href=\'{WR}/apps/share/index.php?s=/Index/content/id/{id}\'>评论</a>)','<div class=\"lh30\"><a href=\'{url}\' target=\'_blank\'>{title}</a></div>\r\n\r\n<div class=\"lh30\"><a href=\'{url}\' target=\'_blank\'>{url}</a></div>\r\n\r\n<div class=\"quote\"><p>{info}<span class=\"quoteR\"> </span></p></div>');
INSERT INTO ts_feed_template VALUES ('17','mini','{actor}：{content}  <a href=\"javascript:void(0)\" onclick=\"hide_reply(this);\">收起回复</a>','<input type=\"hidden\" value=\"{con}\" class=\"mini_con\"><input type=\"hidden\" value=\"{id}\" class=\"mini_id\"><input type=\"hidden\" value=\"{uid}\" class=\"mini_uid\">');
INSERT INTO ts_feed_template VALUES ('28','vote_add','{actor} 发起了投票 {title}','{body}\r\n{url}');
INSERT INTO ts_feed_template VALUES ('19','blog','{actor} 发表了一篇日志：{title}','{content}');
INSERT INTO ts_feed_template VALUES ('20','head','{actor} 上传了新头像','<img height=\"50\" width=\"50\" src=\"{src}\"/>');
INSERT INTO ts_feed_template VALUES ('27','info','{actor} {content}','');
INSERT INTO ts_feed_template VALUES ('29','vote_in','{actor} 参与了投票 {title}','');
INSERT INTO ts_feed_template VALUES ('30','event','{actor} 发起了一个活动: {title}','');
INSERT INTO ts_feed_template VALUES ('31','share_music','{actor} 分享了一个音乐 (<a href=\'{WR}/apps/share/index.php?s=/Index/content/id/{id}\'>评论</a>)','<img class=\"hand\" src=\"{WR}/apps/share/Tpl/default/Public/images/music.gif\" alt=\"音乐\" onclick=\"javascript:playmusic(\'{url}\', this, \'{id}\');\" /> <p id=\"flash_mp3_{id}\"></p> <a class=\"video-close-link\" id=\"mp3_close_{id}\" href=\"javascript:void(0)\"  onClick=\"mp3_close(this)\" rel=\"{id}\" style=\"display:none\">收起</a>\r\n\r\n<div class=\"quote\"><p>{info}<span class=\"quoteR\"> </span></p></div>');
INSERT INTO ts_feed_template VALUES ('32','share','{actor} 分享了一个 {type}','{title}<br />\r\n{content}');
INSERT INTO ts_feed_template VALUES ('33','share_video','{actor} 分享了一个视频 (<a href=\'{WR}/apps/share/index.php?s=/Index/content/id/{id}\'>评论</a>)','<div class=\"playbutton lh30\" id=\"{id}\" rel=\"{url}\" onclick=\"playflash(this);\"> <img class=\"hand\" src=\"{WR}/apps/share/Tpl/default/Public/images/video_img.gif\" style=\"cursor:pointer\"/></div> <p id=\"flash_video_{id}\"></p> \r\n\r\n<a class=\"video-close-link\" id=\"video_close_{id}\" href=\"javascript:void(0)\" onClick=\"video_close(this)\" rel=\"{id}\" style=\"display:none\">收起</a>\r\n\r\n<div class=\"quote\"><p>{info}<span class=\"quoteR\"> </span></p></div>');
INSERT INTO ts_feed_template VALUES ('34','share_flash','{actor} 分享了一个Flash (<a href=\'{WR}/apps/share/index.php?s=/Index/content/id/{id}\'>评论</a>)','<div class=\"playbutton lh30\" id=\"{id}\" rel=\"{url}\" onclick=\"playflash(this);\"> <img class=\"hand\" src=\"{WR}/apps/share/Tpl/default/Public/images/video_img.gif\" style=\"cursor:pointer\"/></div> <p id=\"flash_video_{id}\"></p> \r\n\r\n<a class=\"video-close-link\" id=\"video_close_{id}\" href=\"javascript:void(0)\" onClick=\"video_close(this)\" rel=\"{id}\" style=\"display:none\">收起</a>\r\n\r\n<div class=\"quote\"><p>{info}<span class=\"quoteR\"> </span></p></div>');
INSERT INTO ts_feed_template VALUES ('35','share_blog','{actor} 分享了一个日志 (<a href=\'{WR}/apps/share/index.php?s=/Index/content/id/{id}\'>评论</a>)','<div class=\"lh30 fB\"><a href=\'{WR}/apps/blog/index.php?s=/Index/show/id/{aimId}/mid/{uid}\'>{title}</a></div> \r\n<div class=\"lh20\">来自:<a href=\'{WR}/index.php?s=/space/{uid}\'>{name}</a></div>\r\n<div class=\"lh20 cGray2\">{intro}</div>\r\n<div class=\"quote\"><p>{info}<span class=\"quoteR\"> </span></p></div>');
INSERT INTO ts_feed_template VALUES ('42','share_user','{actor} 分享了一个用户 (<a href=\'{WR}/apps/share/index.php?s=/Index/content/id/{id}\'>评论</a>)','<div class=\"clear: both;padding:5px 0;display:table;\">\r\n<div class=\"left\" style=\"width:70px; padding-top:10px;\">\r\n<span class=\"headpic50\">\r\n<a href=\"{WR}/index.php?s=/space/{uid}\"><img src=\"{userface}\" /></a>\r\n</span>\r\n</div>\r\n<div class=\"left\" style=\"width:300px; padding-top:10px;\">\r\n<a href=\"{WR}/index.php?s=/space/{uid}\"><strong>{username}</strong></a>\r\n<div class=\"lh20\">{mini}</div></div></div>\r\n\r\n<div class=\"quote\"><p>{info}<span class=\"quoteR\">&nbsp;</span></p></div>');
INSERT INTO ts_feed_template VALUES ('36','share_photo','{actor} 分享了一张相片 (<a href=\'{WR}/apps/share/index.php?s=/Index/content/id/{id}\'>评论</a>)','<div class=\"clear: both;padding:5px 0;\">\r\n<div class=\"left pic140\" style=\"width:140px; padding-top:10px;\">\r\n<span class=\"unLine\"> <a href=\"{WR}/apps/photo/index.php?s=/Index/photo/id/{aimId}/aid/{albumId}/uid/{userId}/type/mAll\"> <img style=\"padding: 2px;border: 1px solid #CCC;\" src=\"{WR}/thumb.php?w=120&h=100&url={photo}\" /> </a>\r\n</span>\r\n</div>\r\n<div class=\"left\" style=\"width:200px; padding:10px 0 0 10px;\"> \r\n<div> 相片：<a href=\"{WR}/apps/photo/index.php?s=/Index/photo/id/{aimId}/aid/{albumId}/uid/{userId}/type/mAll\"> {name}</a>\r\n</div>\r\n<div> 相册：<a href=\"{WR}/apps/photo/index.php?s=/Index/album/id/{albumId}/uid/{userId}\"> {albumName}</a> \r\n</div> \r\n<div class=\"lh20\">创建人：\r\n<a href=\"{WR}/index.php?s=/space/{userId}\">{username}</a>\r\n</div>\r\n</div>\r\n </div>\r\n\r\n<div class=\"quote\"><p>{info}<span class=\"quoteR\">&nbsp;</span></p></div>');
INSERT INTO ts_feed_template VALUES ('37','share_event','{actor} 分享了一个活动 (<a href=\'{WR}/apps/share/index.php?s=/Index/content/id/{id}\'>评论</a>)','<a href=\"{url}\">{title}</a>\r\n发起人::<a href=\'{WR}/index.php?s=/space/{uid}\'>{name}</a>\r\n\r\n<div class=\"quote\"><p>{info}<span class=\"quoteR\">&nbsp;</span></p></div>');
INSERT INTO ts_feed_template VALUES ('38','group_create','{actor} 创建了一个新群组',' <a href=\'{SITE_URL}/Group/index/gid/{gid}\'>{group_name}</a>');
INSERT INTO ts_feed_template VALUES ('39','group_topic','{actor} 在 <a href=\'{SITE_URL}/Group/index/gid/{gid}\'>{group_name}</a> 中发表了一个新话题','<a href=\"{SITE_URL}/Topic/topic/gid/{gid}/tid/{tid}\">{title}</a>');
INSERT INTO ts_feed_template VALUES ('40','group_file',' {actor} 在 <a href=\'{SITE_URL}/Group/index/gid/{gid}\'> {group_name}</a> 中上传了一个新文件 ','<a href=\"{SITE_URL}/Dir/file/gid/{gid}/fid/{fid}\">{name}</a>');
INSERT INTO ts_feed_template VALUES ('44','share_picture','{actor} 分享了一张图片 (<a href=\'{WR}/apps/share/index.php?s=/Index/content/id/{id}\'>评论</a>)','<div class=\"lh20\"><a href=\'{WR}/apps/Share/index.php?s=/Index/content/id/{id}\'><img src=\'{WR}/thumb.php?w=120&h=100&url={url}\' /></a></div>\r\n\r\n<div class=\"quote\"><p>{info}<span class=\"quoteR\"> </span></p></div>');
INSERT INTO ts_feed_template VALUES ('43','share_vote','{actor} 分享了一个投票 (<a href=\'{WR}/apps/share/index.php?s=/Index/content/id/{id}\'>评论</a>)','<div class=\"lh30\"><a href=\'{WR}/apps/vote/index.php?s=/Index/pollDetail/id/{aimId}\' target=\'_blank\'>{title}</a></div>\r\n\r\n<div class=\"lh30\">来自:<a href=\'{WR}/index.php?s=/space/{uid}\'>{name}</a></div>\r\n\r\n<div class=\"quote\"><p>{info}<span class=\"quoteR\">&nbsp;</span></p></div>');
INSERT INTO ts_feed_template VALUES ('45','share_group','{actor} 分享了一个群组 (<a href=\'{WR}/apps/share/index.php?s=/Index/content/id/{id}\'>评论</a>)','<div class=\"clear: both;padding:5px 0;\">\r\n<div class=\"left\" style=\"width:70px; padding-top:10px;\"><span class=\"headpic50\"><a href=\"{WR}/apps/group/index.php?s=/Group/index/gid/{aimId}\"><img src=\"{WR}/thumb.php?w=50&h=50&url={logo}\" /></a> </span>\r\n</div>\r\n<div class=\"left\" style=\"width:350px; padding-top:10px;\">\r\n<div><a href=\"{WR}/apps/group/index.php?s=/Group/index/gid/{aimId}\">{name}</a></div>\r\n<div class=\"lh20\">{catagory}</div>\r\n<div class=\"lh20\">现有 {membercount} 名成员</div>\r\n<div class=\"c\"></div>\r\n</div>\r\n<div class=\"quote\"><p>{info}<span class=\"quoteR\">&nbsp;</span></p></div>\r\n</div>');
INSERT INTO ts_feed_template VALUES ('46','share_topic','{actor} 分享了一个话题 (<a href=\'{WR}/apps/share/index.php?s=/Index/content/id/{id}\'>评论</a>)','<div class=\"lh30 fB\"><a href=\"{WR}/apps/group/index.php?s=/Topic/topic/gid/{gid}/tid/{aimId}\">{title}</a></div>\r\n<div class=\"lh20\"><a href=\'{WR}/index.php?s=/space/{uid}\'>{name}</a></div>\r\n<div class=\"lh20\">群组: <a href=\"{WR}/apps/group/index.php?s=/Group/index/gid/{gid}\">{groupName}</a></div>\r\n<div class=\"quote\"><p>{info}<span class=\"quoteR\">&nbsp;</span></p></div>');
INSERT INTO ts_feed_template VALUES ('47','share_album','{actor} 分享了一个相册 (<a href=\'{WR}/apps/share/index.php?s=/Index/content/id/{id}\'>评论</a>)','<div class=\"clear: both;padding:5px 0;display:table;\">\r\n<div class=\"left\" style=\"width:140px; padding-top:10px;\">\r\n<span class=\"photo135 unLine\"> <a style=\"display:block;\" href=\"{WR}/apps/photo/index.php?s=/Index/album/id/{aimId}/uid/{userId}\"> <img src=\"{WR}/thumb.php?w=120&h=100&url={cover}\" /> </a>\r\n</span>\r\n</div>\r\n<div class=\"left\" style=\"width:200px; padding:10px 0 0 10px;\">\r\n<div>相册： <a href=\"{WR}/apps/photo/index.php?s=/Index/album/id/{aimId}/uid/{userId}\">{name}</a> \r\n</div> \r\n<div class=\"lh20\">创建人：<a href=\"{WR}/index.php?s=/space/{userId}\">{username}</a>\r\n</div>\r\n</div>\r\n\r\n</div>\r\n<div class=\"quote\"><p>{info}<span class=\"quoteR\">&nbsp;</span></p></div>');
INSERT INTO ts_feed_template VALUES ('53','blog_import','{actor}导入了一批日志','{title}');
INSERT INTO ts_feed_template VALUES ('48','group_album','{actor} 在 <a href=\'{SITE_URL}/Group/index/gid/{gid}\'> {group_name}</a> 中创建了一个相册','<a href=\"{SITE_URL}/Album/getAlbum/gid/{gid}/albumId/{albumId}\">{title}</a> ');
INSERT INTO ts_feed_template VALUES ('49','group_photo','{actor} 在 <a href=\'{SITE_URL}/Group/index/gid/{gid}\'>{group_name}</a> 中上传了{num}张新照片','{url}');
INSERT INTO ts_feed_template VALUES ('51','group_join','{actor} {title} ','<a href=\'{SITE_URL}/Group/index/gid/{gid}\'>{group_name}</a>');
INSERT INTO ts_feed_template VALUES ('50','app_add','{actor} 安装了应用 {title}','{content}');
INSERT INTO ts_feed_template VALUES ('52','gift','{actor} 送给 {user} 一个礼物','{img}</br>\r\n <div class=\"quote\"><p><span class=\"quoteR\">{content}</span></p></div>');
INSERT INTO ts_feed_template VALUES ('54','add_friend','{user} 和 {fuser} 成为好友','');

 drop table if exists ts_fg;
CREATE TABLE `ts_fg` (
  `uid` int(11) NOT NULL,
  `fuid` int(11) NOT NULL,
  `gid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_field_name;
CREATE TABLE `ts_field_name` (
  `id` int(11) NOT NULL auto_increment,
  `field` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

INSERT INTO ts_field_name VALUES ('1','name','姓名');
INSERT INTO ts_field_name VALUES ('2','sex','性别');
INSERT INTO ts_field_name VALUES ('3','birthday','出生日期');
INSERT INTO ts_field_name VALUES ('4','status','目前身份');
INSERT INTO ts_field_name VALUES ('5','company','工作单位');
INSERT INTO ts_field_name VALUES ('6','current','居住城市');
INSERT INTO ts_field_name VALUES ('7','jiejiao','我想结交');
INSERT INTO ts_field_name VALUES ('8','interest','兴趣爱好');
INSERT INTO ts_field_name VALUES ('9','book','喜欢的书');
INSERT INTO ts_field_name VALUES ('10','film','喜欢的电影');
INSERT INTO ts_field_name VALUES ('11','idol','偶像');
INSERT INTO ts_field_name VALUES ('12','motto','座右铭');
INSERT INTO ts_field_name VALUES ('13','wish','最近心愿');
INSERT INTO ts_field_name VALUES ('14','summary','我的简介');
INSERT INTO ts_field_name VALUES ('15','address','地址');
INSERT INTO ts_field_name VALUES ('16','postcode','邮编');
INSERT INTO ts_field_name VALUES ('17','phone','电话');
INSERT INTO ts_field_name VALUES ('18','cellphone','手机');
INSERT INTO ts_field_name VALUES ('19','qq','QQ');
INSERT INTO ts_field_name VALUES ('20','msn','MSN');

 drop table if exists ts_friend;
CREATE TABLE `ts_friend` (
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `fuid` mediumint(8) unsigned NOT NULL default '0',
  `fusername` char(15) NOT NULL default '',
  `status` tinyint(1) NOT NULL default '0',
  `note` char(50) NOT NULL default '',
  `dateline` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`uid`,`fuid`),
  KEY `fuid` (`fuid`),
  KEY `status` (`uid`,`status`,`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO ts_friend VALUES ('2','1','管理员','1','sfds','1254730616');
INSERT INTO ts_friend VALUES ('1','2','fengzhiqiang','1','','1254731068');
INSERT INTO ts_friend VALUES ('4','3','冯志强','1','kkk','1254732647');
INSERT INTO ts_friend VALUES ('4','1','管理员','1','看看','1254732719');
INSERT INTO ts_friend VALUES ('4','2','fengzhiqiang','0','加为好友','1254732769');
INSERT INTO ts_friend VALUES ('3','1','管理员','1','d','1254732903');
INSERT INTO ts_friend VALUES ('3','4','侯光成','1','','1254732953');
INSERT INTO ts_friend VALUES ('3','2','fengzhiqiang','0','s','1254732981');
INSERT INTO ts_friend VALUES ('1','4','侯光成','1','','1254733725');
INSERT INTO ts_friend VALUES ('1','3','冯志强','1','','1254733747');

 drop table if exists ts_friend_black;
CREATE TABLE `ts_friend_black` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `fuid` int(11) NOT NULL,
  `fname` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_friend_group;
CREATE TABLE `ts_friend_group` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

INSERT INTO ts_friend_group VALUES ('1','0','未分组');
INSERT INTO ts_friend_group VALUES ('2','0','通过本站认识');
INSERT INTO ts_friend_group VALUES ('3','0','通过活动认识');
INSERT INTO ts_friend_group VALUES ('4','0','通过朋友认识');
INSERT INTO ts_friend_group VALUES ('5','0','亲人');
INSERT INTO ts_friend_group VALUES ('6','0','同事');
INSERT INTO ts_friend_group VALUES ('7','0','同学');
INSERT INTO ts_friend_group VALUES ('8','0','不认识');
INSERT INTO ts_friend_group VALUES ('9','0','其他');

 drop table if exists ts_friend_hide;
CREATE TABLE `ts_friend_hide` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `fuid` int(11) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `type` varchar(25) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_friend_ping;
CREATE TABLE `ts_friend_ping` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `fuid` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_friend_tip;
CREATE TABLE `ts_friend_tip` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `content` varchar(25) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_gift;
CREATE TABLE `ts_gift` (
  `id` int(11) NOT NULL auto_increment,
  `categoryId` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `num` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `img` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL default '1',
  `cTime` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=79 DEFAULT CHARSET=utf8;

INSERT INTO ts_gift VALUES ('56','2','冰块','998','50','4a6ff66da5b9f.gif','1','1248851565');
INSERT INTO ts_gift VALUES ('22','1','玫瑰','972','28','birth1.gif','1','1214839221');
INSERT INTO ts_gift VALUES ('23','1','开心蛋糕','946','38','birth2.gif','1','1214839221');
INSERT INTO ts_gift VALUES ('24','1','钻石','964','50','birth3.gif','1','1214839221');
INSERT INTO ts_gift VALUES ('25','1','金元宝','979','50','birth4.gif','1','1214839221');
INSERT INTO ts_gift VALUES ('26','1','宝贝熊','989','36','birth5.gif','1','1214839221');
INSERT INTO ts_gift VALUES ('27','1','香槟','975','22','birth6.gif','1','1214839221');
INSERT INTO ts_gift VALUES ('28','1','心愿','999','20','birth7.gif','1','1214839221');
INSERT INTO ts_gift VALUES ('29','1','浓情棒棒糖','993','20','birth8.gif','1','1214839221');
INSERT INTO ts_gift VALUES ('30','1','女人最爱','956','33','birth9.gif','1','1214839221');
INSERT INTO ts_gift VALUES ('31','1','男人期待','981','33','birth10.gif','1','1214839221');
INSERT INTO ts_gift VALUES ('32','2','衬衣','999','20','new1.gif','1','1214839221');
INSERT INTO ts_gift VALUES ('33','2','哇财','988','45','new2.gif','1','1214839221');
INSERT INTO ts_gift VALUES ('34','2','口红','1000','20','new3.gif','1','1214839221');
INSERT INTO ts_gift VALUES ('35','2','洗衣板','1000','22','new4.gif','1','1214839221');
INSERT INTO ts_gift VALUES ('36','2','性感肚兜','999','30','new5.gif','1','1214839221');
INSERT INTO ts_gift VALUES ('37','2','靓丽高跟鞋','1000','35','new6.gif','1','1214839221');
INSERT INTO ts_gift VALUES ('38','2','浓情红玫瑰','1000','26','new7.gif','1','1214839221');
INSERT INTO ts_gift VALUES ('39','2','剃须刀','1000','28','new8.gif','1','1214839221');
INSERT INTO ts_gift VALUES ('40','2','真爱冰激淋','1000','20','new9.gif','1','1214839221');
INSERT INTO ts_gift VALUES ('41','2','奶嘴','997','20','new10.gif','1','1214839221');
INSERT INTO ts_gift VALUES ('43','1','雷公','872','22','birth11.gif','1','1214839221');
INSERT INTO ts_gift VALUES ('44','1','电母','885','22','birth12.gif','1','1214839221');
INSERT INTO ts_gift VALUES ('45','1','协会','885','25','birth13.gif','1','1214839221');
INSERT INTO ts_gift VALUES ('46','1','雷语','992','22','birth14.gif','1','1214839221');
INSERT INTO ts_gift VALUES ('47','1','小队长','888','20','birth15.gif','1','1214839221');
INSERT INTO ts_gift VALUES ('48','1','中队长','886','20','birth16.gif','1','1214839221');
INSERT INTO ts_gift VALUES ('49','1','大队长','878','20','birth17.gif','1','1214839221');
INSERT INTO ts_gift VALUES ('50','2','帅哥证','999','26','new11.gif','1','1214839221');
INSERT INTO ts_gift VALUES ('51','2','美女证','1000','26','new12.gif','1','1214839221');
INSERT INTO ts_gift VALUES ('52','2','公章','1000','28','new13.gif','1','1214839221');
INSERT INTO ts_gift VALUES ('53','2','公章','1000','28','new14.gif','1','1214839221');
INSERT INTO ts_gift VALUES ('54','2','公章','1000','28','new15.gif','1','1214839221');
INSERT INTO ts_gift VALUES ('55','1','豪华跑车','876','45','birth18.gif','1','1214839221');
INSERT INTO ts_gift VALUES ('57','2','啤酒','988','50','4a6ff694f1a7a.gif','1','1248851604');
INSERT INTO ts_gift VALUES ('58','1','礼物盒','995','50','4a6ff7c85bd99.gif','1','1248851912');
INSERT INTO ts_gift VALUES ('59','2','乒乓球拍','997','50','4a6ffa25bb600.gif','1','1248852517');
INSERT INTO ts_gift VALUES ('60','2','网球','999','50','4a6ffa3b53591.gif','1','1248852539');
INSERT INTO ts_gift VALUES ('61','2','高尔夫球','998','50','4a6ffa4e50ea3.gif','1','1248852558');
INSERT INTO ts_gift VALUES ('62','2','橄榄球','999','50','4a6ffa69b46dd.gif','1','1248852585');
INSERT INTO ts_gift VALUES ('63','2','排球','998','50','4a6ffa7c62a7a.gif','1','1248852604');
INSERT INTO ts_gift VALUES ('64','2','篮球','996','50','4a6ffa94366a0.gif','1','1248852628');
INSERT INTO ts_gift VALUES ('65','2','足球','987','50','4a6ffa9ee5d18.gif','1','1248852638');
INSERT INTO ts_gift VALUES ('66','1','红枣粽子','997','50','4a6ffc7d10214.gif','1','1248853117');
INSERT INTO ts_gift VALUES ('67','1','运动鞋','994','100','4a6ffe72c1046.gif','1','1248853618');
INSERT INTO ts_gift VALUES ('68','1','披萨','996','100','4a700398492ca.gif','1','1248854936');
INSERT INTO ts_gift VALUES ('69','1','购物袋','994','100','4a7004032f310.gif','1','1248855043');
INSERT INTO ts_gift VALUES ('70','2','吸血蝙蝠','999','100','4a70046342824.gif','1','1248855139');
INSERT INTO ts_gift VALUES ('71','1','MP3','990','100','4a700508e3c92.gif','1','1248855304');
INSERT INTO ts_gift VALUES ('72','1','香水','987','100','4a700724e1fa1.gif','1','1248855844');
INSERT INTO ts_gift VALUES ('73','1','游戏机','999','100','4a70079505d66.gif','1','1248855957');
INSERT INTO ts_gift VALUES ('74','1','数码相机','996','200','4a7007a6923ea.gif','1','1248855974');
INSERT INTO ts_gift VALUES ('75','2','小笼包','997','100','4a700a2f649b4.gif','1','1248856623');
INSERT INTO ts_gift VALUES ('76','2','滑板','997','100','4a700a42a35b1.gif','1','1248856642');
INSERT INTO ts_gift VALUES ('77','1','红色跑车','84','200','4a700ae34514a.gif','1','1248856803');
INSERT INTO ts_gift VALUES ('78','1','急速跑车','72','200','4a700afee7d2e.gif','1','1248856830');

 drop table if exists ts_gift_category;
CREATE TABLE `ts_gift_category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL default '1',
  `cTime` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO ts_gift_category VALUES ('1','热门礼物','1','0');
INSERT INTO ts_gift_category VALUES ('2','最新上架','1','0');

 drop table if exists ts_group;
CREATE TABLE `ts_group` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `uid` int(11) unsigned NOT NULL default '0',
  `name` varchar(32) NOT NULL,
  `intro` text NOT NULL,
  `logo` varchar(255) NOT NULL,
  `announce` text NOT NULL,
  `cid0` smallint(6) unsigned NOT NULL,
  `cid1` smallint(6) unsigned NOT NULL,
  `membercount` smallint(6) unsigned NOT NULL default '0',
  `threadcount` smallint(6) unsigned NOT NULL default '0',
  `type` enum('open','limit','close') NOT NULL,
  `need_invite` tinyint(1) NOT NULL default '0',
  `need_verify` tinyint(4) NOT NULL,
  `actor_level` tinyint(4) NOT NULL,
  `brower_level` tinyint(4) NOT NULL,
  `openUploadFile` tinyint(1) NOT NULL default '0',
  `whoUploadFile` tinyint(1) NOT NULL default '0',
  `openAlbum` tinyint(1) NOT NULL default '0',
  `whoCreateAlbum` tinyint(1) NOT NULL default '0',
  `whoUploadPic` tinyint(1) NOT NULL default '0',
  `anno` tinyint(1) NOT NULL default '0',
  `ipshow` tinyint(1) NOT NULL default '0',
  `invitepriv` tinyint(1) NOT NULL default '0',
  `createalbumpriv` tinyint(1) NOT NULL default '0',
  `uploadpicpriv` tinyint(1) NOT NULL default '0',
  `ctime` int(11) NOT NULL default '0',
  `mtime` int(11) unsigned NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '1',
  `isrecom` tinyint(1) NOT NULL default '0',
  `is_del` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_group_album;
CREATE TABLE `ts_group_album` (
  `id` int(11) NOT NULL auto_increment,
  `gid` int(11) NOT NULL,
  `userId` int(11) default NULL,
  `name` varchar(255) default NULL,
  `info` text,
  `cTime` int(11) unsigned default NULL,
  `mTime` int(11) unsigned default NULL,
  `coverImageId` int(11) NOT NULL,
  `coverImagePath` varchar(255) default NULL,
  `photoCount` int(11) default '0',
  `status` tinyint(2) unsigned NOT NULL default '1',
  `share` tinyint(1) NOT NULL default '0',
  `is_del` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `uid` (`userId`),
  KEY `cTime` (`cTime`),
  KEY `mTime` (`mTime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_group_attachement;
CREATE TABLE `ts_group_attachement` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `gid` int(11) unsigned NOT NULL,
  `uid` int(11) unsigned NOT NULL,
  `attachId` int(11) unsigned NOT NULL,
  `name` varchar(32) NOT NULL,
  `note` text NOT NULL,
  `filesize` int(10) NOT NULL default '0',
  `filetype` varchar(10) NOT NULL,
  `fileurl` varchar(255) NOT NULL,
  `totaldowns` mediumint(6) NOT NULL default '0',
  `ctime` int(11) NOT NULL,
  `mtime` varchar(11) NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '0',
  `is_del` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `gid` (`gid`),
  KEY `gid_2` (`gid`,`attachId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_group_category;
CREATE TABLE `ts_group_category` (
  `id` mediumint(5) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `type` tinyint(1) NOT NULL default '1',
  `pid` tinyint(3) NOT NULL default '0',
  `module` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

INSERT INTO ts_group_category VALUES ('1','社团','1','0','');
INSERT INTO ts_group_category VALUES ('2','学校/同学录','1','0','');
INSERT INTO ts_group_category VALUES ('3','同乡会','1','0','');
INSERT INTO ts_group_category VALUES ('4','同城交友','1','0','');
INSERT INTO ts_group_category VALUES ('5','情感','1','0','');
INSERT INTO ts_group_category VALUES ('6','星座','1','0','');
INSERT INTO ts_group_category VALUES ('7','旅游','1','0','');
INSERT INTO ts_group_category VALUES ('8','美食','1','0','');
INSERT INTO ts_group_category VALUES ('9','影视','1','0','');
INSERT INTO ts_group_category VALUES ('10','音乐','1','0','');
INSERT INTO ts_group_category VALUES ('11','读书','1','0','');
INSERT INTO ts_group_category VALUES ('12','体育','1','0','');
INSERT INTO ts_group_category VALUES ('13','动漫','1','0','');
INSERT INTO ts_group_category VALUES ('14','游戏','1','0','');
INSERT INTO ts_group_category VALUES ('15','时尚生活','1','0','');
INSERT INTO ts_group_category VALUES ('16','明星名人','1','0','');
INSERT INTO ts_group_category VALUES ('17','投资理财','1','0','');
INSERT INTO ts_group_category VALUES ('18','健康','1','0','');
INSERT INTO ts_group_category VALUES ('19','美容','1','0','');
INSERT INTO ts_group_category VALUES ('20','育儿','1','0','');
INSERT INTO ts_group_category VALUES ('21','宠物','1','0','');
INSERT INTO ts_group_category VALUES ('22','汽车','1','0','');
INSERT INTO ts_group_category VALUES ('23','家居','1','0','');
INSERT INTO ts_group_category VALUES ('24','学习考试','1','0','');
INSERT INTO ts_group_category VALUES ('25','电脑数码','1','0','');
INSERT INTO ts_group_category VALUES ('26','人文历史','1','0','');
INSERT INTO ts_group_category VALUES ('27','艺术','1','0','');
INSERT INTO ts_group_category VALUES ('28','科技','1','0','');
INSERT INTO ts_group_category VALUES ('29','军事','1','0','');
INSERT INTO ts_group_category VALUES ('30','其它','1','0','');

 drop table if exists ts_group_log;
CREATE TABLE `ts_group_log` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `gid` int(11) unsigned NOT NULL,
  `uid` int(11) unsigned NOT NULL,
  `type` varchar(10) NOT NULL,
  `content` text NOT NULL,
  `ctime` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_group_member;
CREATE TABLE `ts_group_member` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `gid` int(11) unsigned NOT NULL,
  `uid` int(11) unsigned NOT NULL default '0',
  `name` char(10) NOT NULL,
  `reason` text NOT NULL,
  `status` tinyint(1) default '1',
  `level` tinyint(2) unsigned default '1',
  `ctime` int(11) NOT NULL default '0',
  `mtime` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `gid` (`gid`,`uid`),
  KEY `mid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_group_photo;
CREATE TABLE `ts_group_photo` (
  `id` int(11) NOT NULL auto_increment,
  `gid` int(11) NOT NULL,
  `attachId` int(11) NOT NULL,
  `albumId` int(11) NOT NULL,
  `userId` int(11) default NULL,
  `status` tinyint(2) unsigned NOT NULL default '1',
  `name` varchar(255) NOT NULL,
  `cTime` int(11) unsigned default NULL,
  `mTime` int(11) unsigned default NULL,
  `info` text,
  `commentCount` int(11) unsigned default '0',
  `readCount` int(11) unsigned default '0',
  `savepath` varchar(255) NOT NULL,
  `size` int(11) NOT NULL default '0',
  `tags` text,
  `order` int(11) NOT NULL,
  `is_del` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `gid` (`gid`,`albumId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_group_post;
CREATE TABLE `ts_group_post` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `gid` int(11) unsigned NOT NULL,
  `uid` int(11) unsigned NOT NULL,
  `tid` int(11) unsigned NOT NULL,
  `content` text NOT NULL,
  `ip` char(16) NOT NULL,
  `istopic` tinyint(1) NOT NULL default '0',
  `ctime` int(11) NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '0',
  `quote` int(11) unsigned NOT NULL default '0',
  `is_del` varchar(1) NOT NULL default '0',
  `attach` text,
  PRIMARY KEY  (`id`),
  KEY `gid` (`gid`,`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_group_setting;
CREATE TABLE `ts_group_setting` (
  `name` varchar(36) NOT NULL,
  `value` varchar(36) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO ts_group_setting VALUES ('createMaxGroup','3');
INSERT INTO ts_group_setting VALUES ('joinMaxGroup','10');
INSERT INTO ts_group_setting VALUES ('openAlbum','1');
INSERT INTO ts_group_setting VALUES ('whoCreateAlbum','2');
INSERT INTO ts_group_setting VALUES ('whoUploadPic','2');
INSERT INTO ts_group_setting VALUES ('openUploadFile','1');
INSERT INTO ts_group_setting VALUES ('simpleFileSize','1');
INSERT INTO ts_group_setting VALUES ('spaceSize','10');
INSERT INTO ts_group_setting VALUES ('uploadFileType','jpg|gif|doc');
INSERT INTO ts_group_setting VALUES ('whoUploadFile','1');
INSERT INTO ts_group_setting VALUES ('open_isInvite','0');
INSERT INTO ts_group_setting VALUES ('open_invite','2');
INSERT INTO ts_group_setting VALUES ('open_review','0');
INSERT INTO ts_group_setting VALUES ('open_sayMember','0');
INSERT INTO ts_group_setting VALUES ('open_viewMember','-1');
INSERT INTO ts_group_setting VALUES ('close_isInvite','1');
INSERT INTO ts_group_setting VALUES ('close_invite','2');
INSERT INTO ts_group_setting VALUES ('close_review','1');
INSERT INTO ts_group_setting VALUES ('close_sayMember','1');
INSERT INTO ts_group_setting VALUES ('close_viewMember','1');
INSERT INTO ts_group_setting VALUES ('isLimit','0');
INSERT INTO ts_group_setting VALUES ('userGroup','1');
INSERT INTO ts_group_setting VALUES ('userScore','9');
INSERT INTO ts_group_setting VALUES ('userThreadCount','1');
INSERT INTO ts_group_setting VALUES ('openScore','1');
INSERT INTO ts_group_setting VALUES ('postThreadScore','9');
INSERT INTO ts_group_setting VALUES ('uploadPicScore','9');
INSERT INTO ts_group_setting VALUES ('uploadFileScore','9');
INSERT INTO ts_group_setting VALUES ('commentScore','9');
INSERT INTO ts_group_setting VALUES ('reportScore','9');

 drop table if exists ts_group_topic;
CREATE TABLE `ts_group_topic` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `gid` int(11) unsigned NOT NULL,
  `uid` int(11) unsigned NOT NULL,
  `name` varchar(36) NOT NULL,
  `title` varchar(255) NOT NULL,
  `viewcount` smallint(6) unsigned NOT NULL default '0',
  `replycount` smallint(6) unsigned NOT NULL default '0',
  `dist` tinyint(1) NOT NULL default '0',
  `top` tinyint(1) NOT NULL default '0',
  `lock` tinyint(1) NOT NULL default '0',
  `addtime` int(11) NOT NULL default '0',
  `replytime` int(11) NOT NULL default '0',
  `mtime` int(11) unsigned NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '0',
  `isrecom` tinyint(1) NOT NULL default '0',
  `is_del` tinyint(1) NOT NULL default '0',
  `attach` text,
  PRIMARY KEY  (`id`),
  KEY `gid` (`gid`),
  KEY `gid_2` (`gid`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_group_topic_collect;
CREATE TABLE `ts_group_topic_collect` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `tid` int(11) unsigned NOT NULL default '0',
  `mid` int(11) unsigned NOT NULL default '0',
  `addtime` int(11) unsigned NOT NULL default '0',
  `is_del` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `tid` (`tid`,`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_info_category;
CREATE TABLE `ts_info_category` (
  `id` mediumint(5) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `pid` int(11) NOT NULL,
  `addcontent_tpl` varchar(255) default NULL,
  `content_tpl` varchar(255) default NULL,
  `category_tpl` varchar(255) default NULL,
  `data_source` varchar(100) default NULL,
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

INSERT INTO ts_info_category VALUES ('1','info','网站信息','0','','','','');
INSERT INTO ts_info_category VALUES ('2','about','关于我们','1','','','','');
INSERT INTO ts_info_category VALUES ('3','contact','联系方式','1','','','','');
INSERT INTO ts_info_category VALUES ('6','help','帮助','0','','','','');
INSERT INTO ts_info_category VALUES ('7','','注册成为会员','6','','','','');
INSERT INTO ts_info_category VALUES ('8','','网站功能','6','','','','');
INSERT INTO ts_info_category VALUES ('9','','其它','6','','','','');
INSERT INTO ts_info_category VALUES ('10','','网站公告','0','','','','');
INSERT INTO ts_info_category VALUES ('11','broadcast','官方公告','0','','','','');

 drop table if exists ts_info_content;
CREATE TABLE `ts_info_content` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `publish_time` int(11) NOT NULL,
  `category` mediumint(5) NOT NULL default '0',
  `cate_tree` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `author` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `hits` int(11) NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '1',
  `url` varchar(255) default NULL,
  `img` varchar(255) default NULL,
  `param_1` varchar(250) default NULL,
  `param_2` varchar(250) default NULL,
  `param_3` varchar(250) default NULL,
  `param_4` varchar(250) default NULL,
  `param_5` varchar(250) default NULL,
  `newicon` tinyint(1) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21216 DEFAULT CHARSET=utf8;

INSERT INTO ts_info_content VALUES ('21199','关于我们','1251015078','2','1,2','<H3>ThinkSNS</H3>\r\n<P>ThinkSNS是国内最具潜力的互联网产品服务公司，为客户提供社会化网络产品、技术和服务在内的一系列解决方案和平台化网络建设。</P>\r\n<P>ThinkSNS从一开始是就秉承优秀的软件开源思想，注重知识分享和团队协作，以一种开放、共赢、互利和友好的态度面向所有需要ThinkSNS，支持ThinkSNS，喜爱ThinkSNS的个人和社会团体。</P>\r\n<P>ThinkSNS不单单是一个人性化的产品，同时也是整个公司灵魂化的象征，我们积聚创新，不断成长和完备，汲取更多先进性的技术革新，创造更具价值的优势产品，为更多ThinkSNS的应用爱好者提供动力支持和技术源泉。</P>','冯涛','52','0','1','','','','','','','','0');
INSERT INTO ts_info_content VALUES ('21201','联系我们','1251016440','3','1,3','<P><B>客户服务</B> 如果您在使用过程中对功能产生疑问或出现错误提示，请进入我们的在线社区进行咨询。<BR>电子邮箱：<a href=\\\"mailto:qiujun@thinksns.com\\\" target=\\\"_blank\\\">qiujun@thinksns.com</A></P>\r\n<P><B>广告销售</B> 如果您有意在ThinkSNS投放广告或进行产品植入式推广，请简要描述您的需求及推广品牌，发送电子邮件到下面的地址，我们会根据您的具体情况尽快安排相关人员与您联系。<BR>电子邮箱：<a href=\\\"mailto:wangqinglong@thinksns.com\\\" target=_blank target=\\\"_blank\\\">wangqinglong@thinksns.com</A> </P>\r\n<P><B>商务合作</B> 欢迎优势互补的公司与我们合作开拓业务，请简要描述您的合作意向发送到下面的邮件地址。<BR>电子邮箱：<a href=\\\"mailto:fengtao@thinksns.com\\\" target=_blank target=\\\"_blank\\\">fengtao@thinksns.com</A> </P>\r\n<P><B>公关合作</B> 如果您有媒体采访需求，请将媒体名称、采访提纲、联系方式发送到下面的邮件地址。<BR>电子邮箱：<a href=\\\"mailto:qiujun@thinksns.com\\\" target=\\\"_blank\\\">qiujun@thinksns.com</A>&nbsp;</P>\r\n<P><STRONG>找到我们</STRONG></P>\r\n<UL>\r\n<LI><B>公司地址</B> 北京市海淀区上地10街辉煌国际大厦2号楼2301&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 邮政编码：100085 \r\n<LI><B>乘车路线</B>&nbsp;城铁13号线西二旗站下车，向西步行300米 </LI></UL>\r\n<P><IMG src=\\\"http://www.thinksns.com/images/map/2009-09-04_133321.png\\\"></P>','冯涛','10001','0','1','','','','','','','','0');
INSERT INTO ts_info_content VALUES ('21207','ThinkSNS定于9月5日发送邀请进行产品公测','1252043221','0','0','','管理员','36','0','1','','','','','','','','0');
INSERT INTO ts_info_content VALUES ('21212','ThinkSNS如期开始邀请测试','1252073469','10','10','','管理员','10001','0','1','','','','','','','','0');
INSERT INTO ts_info_content VALUES ('21205','隐私声明','1251084258','4','1,4','<P>人人网非常重视对用户隐私权的保护，承诺不会在未获得用户许可的情况下擅自将用户的个人资料信息出租或出售给任何第三方，但以下情况除外:</P>\r\n<UL class=courseList>\r\n<LI>您同意让第三方共享资料； \r\n<LI>您同意公开你的个人资料，享受为您提供的产品和服务； \r\n<LI>本站需要听从法庭传票、法律命令或遵循法律程序； \r\n<LI>本站发现您违反了本站服务条款或本站其它使用规定。 </LI></UL>\r\n<H3>使用说明</H3>\r\n<P>本站的隐私设置分为\\\\\\\"个人资料\\\\\\\"、\\\\\\\"联系方式\\\\\\\"、\\\\\\\"日志\\\\\\\"以及\\\\\\\"黑名单\\\\\\\"。</P>\r\n<P>用户可以选择其个人资料、联系方式和日志的开放程度。例如，用户可以选择将个人资料向好友、同校的人或者所有人开放。请注意，人人网对你同意共享资料用户或第三方对你资料的任何使用或传播行为不负责。</P>\r\n<P>此外，用户可以选择将其它某些用户加入黑名单，以更好地保护隐私。</P>\r\n<H3>修改资料</H3>\r\n<P>在成功登陆之后，用户可以在\\\\\\\"账号信息\\\\\\\"下修改其隐私设置或更新个人信息及密码。</P>\r\n<H3>联系我们</H3>\r\n<P>如果您对此隐私政策有任何疑问或建议，请通过以下方式联系我们:<a href=\\\\\\\"mailto:admin@renren.com\\\\\\\" target=\\\\\\\"_blank\\\\\\\"><FONT color=#005eac>admin@renren.com</FONT></A> 。</P>','管理员','52','0','1','','','','','','','','0');
INSERT INTO ts_info_content VALUES ('21213','ThinkSNS1.6后台页面抢先看','1252231892','10','10','','管理员','10001','0','1','http://i.thinksns.com/apps/blog/index.php?s=/Index/show/id/85/mid/10000','','','','','','','0');
INSERT INTO ts_info_content VALUES ('21214','最新版发布时间确定','1253259145','11','11','将于10月20号发布，望大家周知','管理员','10001','0','1','','','','','','','','0');
INSERT INTO ts_info_content VALUES ('21215','WebIM如约上线，欢迎大家来体验','1253259160','0','0','WebIM如约上线，欢迎大家来体验','管理员','10001','0','1','','','','','','','','0');

 drop table if exists ts_links;
CREATE TABLE `ts_links` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `intro` varchar(255) default NULL,
  `logo` varchar(255) default NULL,
  `status` tinyint(1) NOT NULL default '0',
  `sort` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO ts_links VALUES ('1','ThinkSNS','http://www.thinksns.com','ThinkSNS','','1','0');

 drop table if exists ts_login_record;
CREATE TABLE `ts_login_record` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) default NULL,
  `login_ip` varchar(15) NOT NULL,
  `login_time` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

INSERT INTO ts_login_record VALUES ('1','1','92.225.108.57','1254731811');
INSERT INTO ts_login_record VALUES ('2','1','92.225.108.176','1254730989');
INSERT INTO ts_login_record VALUES ('3','3','113.132.74.225','1254734095');
INSERT INTO ts_login_record VALUES ('4','4','113.132.74.225','1254734338');

 drop table if exists ts_mini;
CREATE TABLE `ts_mini` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `name` char(10) NOT NULL,
  `type` int(1) NOT NULL default '1',
  `content` text NOT NULL,
  `tagId` int(11) default NULL,
  `cTime` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL default '1',
  `replay_numbel` int(11) NOT NULL default '0',
  `feedId` int(11) default '0',
  PRIMARY KEY  (`id`),
  KEY `userId` (`uid`),
  KEY `replay_numbel` (`replay_numbel`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO ts_mini VALUES ('1','4','侯光成','1','没事干，很无聊','','1254732832','0','0','3');

 drop table if exists ts_mini_config;
CREATE TABLE `ts_mini_config` (
  `variable` char(20) NOT NULL,
  `value` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO ts_mini_config VALUES ('delete','0');
INSERT INTO ts_mini_config VALUES ('all','1');
INSERT INTO ts_mini_config VALUES ('fileaway','1');
INSERT INTO ts_mini_config VALUES ('fileawaypage','6');
INSERT INTO ts_mini_config VALUES ('replay','1');
INSERT INTO ts_mini_config VALUES ('smiletype','mini');
INSERT INTO ts_mini_config VALUES ('pagenum','20');
INSERT INTO ts_mini_config VALUES ('stringcount','150');

 drop table if exists ts_msg;
CREATE TABLE `ts_msg` (
  `id` int(11) NOT NULL auto_increment,
  `fromUserId` int(11) NOT NULL,
  `toUserId` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `cTime` int(11) NOT NULL,
  `is_read` tinyint(1) NOT NULL default '0',
  `replyMsgId` int(11) NOT NULL,
  `is_new` tinyint(1) NOT NULL default '1',
  `is_del` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_network;
CREATE TABLE `ts_network` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `tag` varchar(255) NOT NULL,
  `info` varchar(255) NOT NULL,
  `pid` int(11) NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=398 DEFAULT CHARSET=utf8;

INSERT INTO ts_network VALUES ('2','北京市','地区网络','','0','1');
INSERT INTO ts_network VALUES ('3','天津市','地区网络','','0','1');
INSERT INTO ts_network VALUES ('4','河北省','地区网络','','0','1');
INSERT INTO ts_network VALUES ('5','山西省','地区网络','','0','1');
INSERT INTO ts_network VALUES ('6','内蒙古','地区网络','','0','1');
INSERT INTO ts_network VALUES ('7','辽宁省','地区网络','','0','1');
INSERT INTO ts_network VALUES ('8','吉林省','地区网络','','0','1');
INSERT INTO ts_network VALUES ('9','黑龙江省','地区网络','','0','1');
INSERT INTO ts_network VALUES ('10','上海市','地区网络','','0','1');
INSERT INTO ts_network VALUES ('11','江苏省','地区网络','','0','1');
INSERT INTO ts_network VALUES ('12','浙江省','地区网络','','0','1');
INSERT INTO ts_network VALUES ('13','安徽省','地区网络','','0','1');
INSERT INTO ts_network VALUES ('14','福建省','地区网络','','0','1');
INSERT INTO ts_network VALUES ('15','江西省','地区网络','','0','1');
INSERT INTO ts_network VALUES ('16','山东省','地区网络','','0','1');
INSERT INTO ts_network VALUES ('17','河南省','地区网络','','0','1');
INSERT INTO ts_network VALUES ('18','湖北省','地区网络','','0','1');
INSERT INTO ts_network VALUES ('19','湖南省','地区网络','','0','1');
INSERT INTO ts_network VALUES ('20','广东省','地区网络','','0','1');
INSERT INTO ts_network VALUES ('21','广西','地区网络','','0','1');
INSERT INTO ts_network VALUES ('22','海南省','地区网络','','0','1');
INSERT INTO ts_network VALUES ('23','重庆市','地区网络','','0','1');
INSERT INTO ts_network VALUES ('24','四川省','地区网络','','0','1');
INSERT INTO ts_network VALUES ('25','贵州省','地区网络','','0','1');
INSERT INTO ts_network VALUES ('26','云南省','地区网络','','0','1');
INSERT INTO ts_network VALUES ('27','西藏','地区网络','','0','1');
INSERT INTO ts_network VALUES ('28','陕西省','地区网络','','0','1');
INSERT INTO ts_network VALUES ('29','甘肃省','地区网络','','0','1');
INSERT INTO ts_network VALUES ('30','青海省','地区网络','','0','1');
INSERT INTO ts_network VALUES ('31','宁夏','地区网络','','0','1');
INSERT INTO ts_network VALUES ('32','新疆','地区网络','','0','1');
INSERT INTO ts_network VALUES ('33','香港','地区网络','','0','1');
INSERT INTO ts_network VALUES ('34','澳门','地区网络','','0','1');
INSERT INTO ts_network VALUES ('35','台湾省','地区网络','','0','1');
INSERT INTO ts_network VALUES ('36','其它','地区网络','','0','1');
INSERT INTO ts_network VALUES ('38','益阳市','益阳市','','19','1');
INSERT INTO ts_network VALUES ('39','长沙市','长沙市','','19','1');
INSERT INTO ts_network VALUES ('40','成都市','成都市','','24','1');
INSERT INTO ts_network VALUES ('41','东城区','东城区','','2','1');
INSERT INTO ts_network VALUES ('42','西城区','西城区','','2','1');
INSERT INTO ts_network VALUES ('43','崇文区','崇文区','','2','1');
INSERT INTO ts_network VALUES ('44','宣武区','宣武区','','2','1');
INSERT INTO ts_network VALUES ('45','朝阳区','朝阳区','','2','1');
INSERT INTO ts_network VALUES ('46','丰台区','丰台区','','2','1');
INSERT INTO ts_network VALUES ('47','石景山区','石景山区','','2','1');
INSERT INTO ts_network VALUES ('48','海淀区','海淀区','','2','1');
INSERT INTO ts_network VALUES ('49','门头沟区','门头沟区','','2','1');
INSERT INTO ts_network VALUES ('50','房山区','房山区','','2','1');
INSERT INTO ts_network VALUES ('51','通州区','通州区','','2','1');
INSERT INTO ts_network VALUES ('52','顺义区','顺义区','','2','1');
INSERT INTO ts_network VALUES ('53','昌平区','昌平区','','2','1');
INSERT INTO ts_network VALUES ('54','大兴区','大兴区','','2','1');
INSERT INTO ts_network VALUES ('55','怀柔区','怀柔区','','2','1');
INSERT INTO ts_network VALUES ('56','平谷区','平谷区','','2','1');
INSERT INTO ts_network VALUES ('57','天津市','天津市','','3','1');
INSERT INTO ts_network VALUES ('58','石家庄','石家庄','','4','1');
INSERT INTO ts_network VALUES ('59','唐山市','唐山市','','4','1');
INSERT INTO ts_network VALUES ('60','秦皇岛','秦皇岛','','4','1');
INSERT INTO ts_network VALUES ('61','邯郸市','邯郸市','','4','1');
INSERT INTO ts_network VALUES ('62','邢台市','邢台市','','4','1');
INSERT INTO ts_network VALUES ('63','保定市','保定市','','4','1');
INSERT INTO ts_network VALUES ('64','张家口市','张家口市','','4','1');
INSERT INTO ts_network VALUES ('65','承德市','承德市','','4','1');
INSERT INTO ts_network VALUES ('66','沧州市','沧州市','','4','1');
INSERT INTO ts_network VALUES ('67','廊坊市','廊坊市','','4','1');
INSERT INTO ts_network VALUES ('68','衡水市','衡水市','','4','1');
INSERT INTO ts_network VALUES ('69','太原市','太原市','','5','1');
INSERT INTO ts_network VALUES ('70','大同市','大同市','','5','1');
INSERT INTO ts_network VALUES ('71','阳泉市','阳泉市','','5','1');
INSERT INTO ts_network VALUES ('72','长治市','长治市','','5','1');
INSERT INTO ts_network VALUES ('73','晋城市','晋城市','','5','1');
INSERT INTO ts_network VALUES ('74','朔州市','朔州市','','5','1');
INSERT INTO ts_network VALUES ('75','晋中市','晋中市','','5','1');
INSERT INTO ts_network VALUES ('76','运城市','运城市','','5','1');
INSERT INTO ts_network VALUES ('77','忻州市','忻州市','','5','1');
INSERT INTO ts_network VALUES ('78','吕梁市','吕梁市','','5','1');
INSERT INTO ts_network VALUES ('79','呼和浩特市','呼和浩特市','','6','1');
INSERT INTO ts_network VALUES ('80','包头市','包头市','','6','1');
INSERT INTO ts_network VALUES ('81','乌海市','乌海市','','6','1');
INSERT INTO ts_network VALUES ('82','赤峰市','赤峰市','','6','1');
INSERT INTO ts_network VALUES ('83','通辽市','通辽市','','6','1');
INSERT INTO ts_network VALUES ('84','鄂尔多斯市','鄂尔多斯市','','6','1');
INSERT INTO ts_network VALUES ('85','呼伦贝尔市','呼伦贝尔市','','6','1');
INSERT INTO ts_network VALUES ('86','巴彦淖尔市','巴彦淖尔市','','6','1');
INSERT INTO ts_network VALUES ('87','乌兰察布市','乌兰察布市','','6','1');
INSERT INTO ts_network VALUES ('88','兴安盟','兴安盟','','6','1');
INSERT INTO ts_network VALUES ('89','阿拉善盟','阿拉善盟','','6','1');
INSERT INTO ts_network VALUES ('90','锡林郭勒盟','锡林郭勒盟','','6','1');
INSERT INTO ts_network VALUES ('91','沈阳市','沈阳市','','7','1');
INSERT INTO ts_network VALUES ('92','大连市','大连市','','7','1');
INSERT INTO ts_network VALUES ('93','鞍山市','鞍山市','','7','1');
INSERT INTO ts_network VALUES ('94','抚顺市','抚顺市','','7','1');
INSERT INTO ts_network VALUES ('95','本溪市','本溪市','','7','1');
INSERT INTO ts_network VALUES ('96','丹东市','丹东市','','7','1');
INSERT INTO ts_network VALUES ('97','锦州市','锦州市','','7','1');
INSERT INTO ts_network VALUES ('98','营口市','营口市','','7','1');
INSERT INTO ts_network VALUES ('99','阜新市','阜新市','','7','1');
INSERT INTO ts_network VALUES ('100','辽阳市','辽阳市','','7','1');
INSERT INTO ts_network VALUES ('101','盘锦市','盘锦市','','7','1');
INSERT INTO ts_network VALUES ('102','铁岭市','铁岭市','','7','1');
INSERT INTO ts_network VALUES ('103','朝阳市','朝阳市','','7','1');
INSERT INTO ts_network VALUES ('104','葫芦岛市','葫芦岛市','','7','1');
INSERT INTO ts_network VALUES ('105','长春市','长春市','','8','1');
INSERT INTO ts_network VALUES ('106','吉林市','吉林市','','8','1');
INSERT INTO ts_network VALUES ('107','四平市','四平市','','8','1');
INSERT INTO ts_network VALUES ('108','辽源市','辽源市','','8','1');
INSERT INTO ts_network VALUES ('109','通化市','通化市','','8','1');
INSERT INTO ts_network VALUES ('110','白山市','白山市','','8','1');
INSERT INTO ts_network VALUES ('111','松原市','松原市','','8','1');
INSERT INTO ts_network VALUES ('112','延边朝鲜自治州','延边朝鲜自治州','','8','1');
INSERT INTO ts_network VALUES ('113','哈尔滨市','哈尔滨市','','9','1');
INSERT INTO ts_network VALUES ('114','齐齐哈尔','齐齐哈尔','','9','1');
INSERT INTO ts_network VALUES ('115','鸡西市','鸡西市','','9','1');
INSERT INTO ts_network VALUES ('116','鹤岗市','鹤岗市','','9','1');
INSERT INTO ts_network VALUES ('117','双鸭山市','双鸭山市','','9','1');
INSERT INTO ts_network VALUES ('118','伊春市','伊春市','','9','1');
INSERT INTO ts_network VALUES ('119','佳木斯','佳木斯','','9','1');
INSERT INTO ts_network VALUES ('120','七台河','七台河','','9','1');
INSERT INTO ts_network VALUES ('121','牡丹江','牡丹江','','9','1');
INSERT INTO ts_network VALUES ('122','黑河','黑河','','9','1');
INSERT INTO ts_network VALUES ('123','绥化','绥化','','9','1');
INSERT INTO ts_network VALUES ('124','大兴安岭','大兴安岭','','9','1');
INSERT INTO ts_network VALUES ('125','上海市','上海市','','10','1');
INSERT INTO ts_network VALUES ('126','南京市','南京市','','11','1');
INSERT INTO ts_network VALUES ('127','无锡市','无锡市','','11','1');
INSERT INTO ts_network VALUES ('128','徐州市','徐州市','','11','1');
INSERT INTO ts_network VALUES ('129','常州市','常州市','','11','1');
INSERT INTO ts_network VALUES ('130','苏州市','苏州市','','11','1');
INSERT INTO ts_network VALUES ('131','南通市','南通市','','11','1');
INSERT INTO ts_network VALUES ('132','连云港市','连云港市','','11','1');
INSERT INTO ts_network VALUES ('133','淮安市','淮安市','','11','1');
INSERT INTO ts_network VALUES ('134','盐城市','盐城市','','11','1');
INSERT INTO ts_network VALUES ('135','扬州市','扬州市','','11','1');
INSERT INTO ts_network VALUES ('136','镇江市','镇江市','','11','1');
INSERT INTO ts_network VALUES ('137','泰州市','泰州市','','11','1');
INSERT INTO ts_network VALUES ('138','宿迁市','宿迁市','','11','1');
INSERT INTO ts_network VALUES ('139','杭州市','杭州市','','12','1');
INSERT INTO ts_network VALUES ('140','宁波市','宁波市','','12','1');
INSERT INTO ts_network VALUES ('141','嘉兴市','嘉兴市','','12','1');
INSERT INTO ts_network VALUES ('142','温州市','温州市','','12','1');
INSERT INTO ts_network VALUES ('143','湖州市','湖州市','','12','1');
INSERT INTO ts_network VALUES ('144','绍兴市','绍兴市','','12','1');
INSERT INTO ts_network VALUES ('145','金华市','金华市','','12','1');
INSERT INTO ts_network VALUES ('146','衢州市','衢州市','','12','1');
INSERT INTO ts_network VALUES ('147','舟山市','舟山市','','12','1');
INSERT INTO ts_network VALUES ('148','台州市','台州市','','12','1');
INSERT INTO ts_network VALUES ('149','丽水市','丽水市','','12','1');
INSERT INTO ts_network VALUES ('150','合肥市','合肥市','','13','1');
INSERT INTO ts_network VALUES ('151','芜湖市','芜湖市','','13','1');
INSERT INTO ts_network VALUES ('152','蚌埠市','蚌埠市','','13','1');
INSERT INTO ts_network VALUES ('153','淮南市','淮南市','','13','1');
INSERT INTO ts_network VALUES ('154','马鞍山市','马鞍山市','','13','1');
INSERT INTO ts_network VALUES ('155','淮北市','淮北市','','13','1');
INSERT INTO ts_network VALUES ('156','铜陵市','铜陵市','','13','1');
INSERT INTO ts_network VALUES ('157','安庆市','安庆市','','13','1');
INSERT INTO ts_network VALUES ('158','黄山市','黄山市','','13','1');
INSERT INTO ts_network VALUES ('159','滁州市','滁州市','','13','1');
INSERT INTO ts_network VALUES ('160','阜阳市','阜阳市','','13','1');
INSERT INTO ts_network VALUES ('161','宿州市','宿州市','','13','1');
INSERT INTO ts_network VALUES ('162','巢湖市','巢湖市','','13','1');
INSERT INTO ts_network VALUES ('163','六安市','六安市','','13','1');
INSERT INTO ts_network VALUES ('164','毫州市','毫州市','','13','1');
INSERT INTO ts_network VALUES ('165','池州市','池州市','','13','1');
INSERT INTO ts_network VALUES ('166','宣城市','宣城市','','13','1');
INSERT INTO ts_network VALUES ('167','福州市','福州市','','14','1');
INSERT INTO ts_network VALUES ('168','厦门市','厦门市','','14','1');
INSERT INTO ts_network VALUES ('169','莆田市','莆田市','','14','1');
INSERT INTO ts_network VALUES ('170','三明市','三明市','','14','1');
INSERT INTO ts_network VALUES ('171','泉州市','泉州市','','14','1');
INSERT INTO ts_network VALUES ('172','漳平市','漳平市','','14','1');
INSERT INTO ts_network VALUES ('173','南平市','南平市','','14','1');
INSERT INTO ts_network VALUES ('174','龙岩市','龙岩市','','14','1');
INSERT INTO ts_network VALUES ('175','宁德市','宁德市','','14','1');
INSERT INTO ts_network VALUES ('176','南昌市','南昌市','','15','1');
INSERT INTO ts_network VALUES ('177','景德镇市','景德镇市','','15','1');
INSERT INTO ts_network VALUES ('178','萍乡市','萍乡市','','15','1');
INSERT INTO ts_network VALUES ('179','九江市','九江市','','15','1');
INSERT INTO ts_network VALUES ('180','新余市','新余市','','15','1');
INSERT INTO ts_network VALUES ('181','鹰潭市','鹰潭市','','15','1');
INSERT INTO ts_network VALUES ('182','赣州市','赣州市','','15','1');
INSERT INTO ts_network VALUES ('183','吉安市','吉安市','','15','1');
INSERT INTO ts_network VALUES ('184','宜春市','宜春市','','15','1');
INSERT INTO ts_network VALUES ('185','抚州市','抚州市','','15','1');
INSERT INTO ts_network VALUES ('186','上饶市','上饶市','','15','1');
INSERT INTO ts_network VALUES ('187','济南市','济南市','','16','1');
INSERT INTO ts_network VALUES ('188','青岛市','青岛市','','16','1');
INSERT INTO ts_network VALUES ('189','淄博市','淄博市','','16','1');
INSERT INTO ts_network VALUES ('190','枣庄市','枣庄市','','16','1');
INSERT INTO ts_network VALUES ('191','东营市','东营市','','16','1');
INSERT INTO ts_network VALUES ('192','烟台市','烟台市','','16','1');
INSERT INTO ts_network VALUES ('193','潍坊市','潍坊市','','16','1');
INSERT INTO ts_network VALUES ('194','济宁市','济宁市','','16','1');
INSERT INTO ts_network VALUES ('195','泰安市','泰安市','','16','1');
INSERT INTO ts_network VALUES ('196','日照市','日照市','','16','1');
INSERT INTO ts_network VALUES ('197','威海市','威海市','','16','1');
INSERT INTO ts_network VALUES ('198','莱芜市','莱芜市','','16','1');
INSERT INTO ts_network VALUES ('199','临沂市','临沂市','','16','1');
INSERT INTO ts_network VALUES ('200','德州市','德州市','','16','1');
INSERT INTO ts_network VALUES ('201','聊城市','聊城市','','16','1');
INSERT INTO ts_network VALUES ('202','滨州市','滨州市','','16','1');
INSERT INTO ts_network VALUES ('203','菏泽市','菏泽市','','16','1');
INSERT INTO ts_network VALUES ('204','郑州市','郑州市','','17','1');
INSERT INTO ts_network VALUES ('205','开封市','开封市','','17','1');
INSERT INTO ts_network VALUES ('206','洛阳市','洛阳市','','17','1');
INSERT INTO ts_network VALUES ('207','平顶山','平顶山','','17','1');
INSERT INTO ts_network VALUES ('208','安阳市','安阳市','','17','1');
INSERT INTO ts_network VALUES ('209','鹤壁市','鹤壁市','','17','1');
INSERT INTO ts_network VALUES ('210','新乡市','新乡市','','17','1');
INSERT INTO ts_network VALUES ('211','焦作市','焦作市','','17','1');
INSERT INTO ts_network VALUES ('212','濮阳市','濮阳市','','17','1');
INSERT INTO ts_network VALUES ('213','许昌市','许昌市','','17','1');
INSERT INTO ts_network VALUES ('214','漯河市','漯河市','','17','1');
INSERT INTO ts_network VALUES ('215','三门峡市','三门峡市','','17','1');
INSERT INTO ts_network VALUES ('216','南阳市','南阳市','','17','1');
INSERT INTO ts_network VALUES ('217','商丘市','商丘市','','17','1');
INSERT INTO ts_network VALUES ('218','信阳市','信阳市','','17','1');
INSERT INTO ts_network VALUES ('219','周口市','周口市','','17','1');
INSERT INTO ts_network VALUES ('220','驻马店市','驻马店市','','17','1');
INSERT INTO ts_network VALUES ('221','武汉市','武汉市','','18','1');
INSERT INTO ts_network VALUES ('222','黄石市','黄石市','','18','1');
INSERT INTO ts_network VALUES ('223','十堰市','十堰市','','18','1');
INSERT INTO ts_network VALUES ('224','宜昌市','宜昌市','','18','1');
INSERT INTO ts_network VALUES ('225','襄樊市','襄樊市','','18','1');
INSERT INTO ts_network VALUES ('226','鄂州市','鄂州市','','18','1');
INSERT INTO ts_network VALUES ('227','荆门市','荆门市','','18','1');
INSERT INTO ts_network VALUES ('228','孝感市','孝感市','','18','1');
INSERT INTO ts_network VALUES ('229','荆州市','荆州市','','18','1');
INSERT INTO ts_network VALUES ('230','黄冈市','黄冈市','','18','1');
INSERT INTO ts_network VALUES ('231','咸宁市','咸宁市','','18','1');
INSERT INTO ts_network VALUES ('232','随州市','随州市','','18','1');
INSERT INTO ts_network VALUES ('233','恩施土家族苗族自治州','恩施土家族苗族自治州','','18','1');
INSERT INTO ts_network VALUES ('234','省直辖行政单位','省直辖行政单位','','18','1');
INSERT INTO ts_network VALUES ('235','株洲市','株洲市','','19','1');
INSERT INTO ts_network VALUES ('236','湘潭市','湘潭市','','19','1');
INSERT INTO ts_network VALUES ('237','衡阳市','衡阳市','','19','1');
INSERT INTO ts_network VALUES ('238','邵阳市','邵阳市','','19','1');
INSERT INTO ts_network VALUES ('239','岳阳市','岳阳市','','19','1');
INSERT INTO ts_network VALUES ('240','常德市','常德市','','19','1');
INSERT INTO ts_network VALUES ('241','张家界市','张家界市','','19','1');
INSERT INTO ts_network VALUES ('242','郴州市','郴州市','','19','1');
INSERT INTO ts_network VALUES ('243','永州市','永州市','','19','1');
INSERT INTO ts_network VALUES ('244','怀化市','怀化市','','19','1');
INSERT INTO ts_network VALUES ('245','娄底市','娄底市','','19','1');
INSERT INTO ts_network VALUES ('246','湘西土家族苗族自治州','湘西土家族苗族自治州','','19','1');
INSERT INTO ts_network VALUES ('247','广州','广州','','20','1');
INSERT INTO ts_network VALUES ('248','韶关市','韶关市','','20','1');
INSERT INTO ts_network VALUES ('249','深圳市','深证市','','20','1');
INSERT INTO ts_network VALUES ('250','珠海市','珠海市','','20','1');
INSERT INTO ts_network VALUES ('251','汕头市','汕头市','','20','1');
INSERT INTO ts_network VALUES ('252','佛山市','佛山市','','20','1');
INSERT INTO ts_network VALUES ('253','江门市','江门市','','20','1');
INSERT INTO ts_network VALUES ('254','湛江市','湛江市','','20','1');
INSERT INTO ts_network VALUES ('255','茂名市','茂名市','','20','1');
INSERT INTO ts_network VALUES ('256','肇庆市','肇庆市','','20','1');
INSERT INTO ts_network VALUES ('257','惠州市','惠州市','','20','1');
INSERT INTO ts_network VALUES ('258','梅州市','梅州市','','20','1');
INSERT INTO ts_network VALUES ('259','汕尾市','汕尾市','','20','1');
INSERT INTO ts_network VALUES ('260','河源市','河源市','','20','1');
INSERT INTO ts_network VALUES ('261','阳江市','阳江市','','20','1');
INSERT INTO ts_network VALUES ('262','清远市','清远市','','20','1');
INSERT INTO ts_network VALUES ('263','东莞市','东莞市','','20','1');
INSERT INTO ts_network VALUES ('264','中山市','中山市','','20','1');
INSERT INTO ts_network VALUES ('265','潮州市','潮州市','','20','1');
INSERT INTO ts_network VALUES ('266','揭阳市','揭阳市','','20','1');
INSERT INTO ts_network VALUES ('267','云浮市','云浮市','','20','1');
INSERT INTO ts_network VALUES ('268','南宁市','南宁市','','21','1');
INSERT INTO ts_network VALUES ('269','柳州市','柳州市','','21','1');
INSERT INTO ts_network VALUES ('270','桂林市','桂林市','','21','1');
INSERT INTO ts_network VALUES ('271','梧州市','梧州市','','21','1');
INSERT INTO ts_network VALUES ('272','北海市','北海市','','21','1');
INSERT INTO ts_network VALUES ('273','防城港市','防城港市','','21','1');
INSERT INTO ts_network VALUES ('274','钦州市','钦州市','','21','1');
INSERT INTO ts_network VALUES ('275','贵港市','贵港市','','21','1');
INSERT INTO ts_network VALUES ('276','玉林市','玉林市','','21','1');
INSERT INTO ts_network VALUES ('277','百色市','百色市','','21','1');
INSERT INTO ts_network VALUES ('278','贺州市','贺州市','','21','1');
INSERT INTO ts_network VALUES ('279','河池市','河池市','','21','1');
INSERT INTO ts_network VALUES ('280','来宾市','来宾市','','21','1');
INSERT INTO ts_network VALUES ('281','崇左市','崇左市','','21','1');
INSERT INTO ts_network VALUES ('282','海口市','海口市','','22','1');
INSERT INTO ts_network VALUES ('283','三亚市','三亚市','','22','1');
INSERT INTO ts_network VALUES ('284','省直辖县级行政单位','省直辖县级行政单位','','22','1');
INSERT INTO ts_network VALUES ('285','重庆市','重庆市','','23','1');
INSERT INTO ts_network VALUES ('286','自贡市','自贡市','','24','1');
INSERT INTO ts_network VALUES ('287','攀枝花','攀枝花','','24','1');
INSERT INTO ts_network VALUES ('288','泸州市','泸州市','','24','1');
INSERT INTO ts_network VALUES ('289','德阳市','德阳市','','24','1');
INSERT INTO ts_network VALUES ('290','绵阳市','绵羊市','','24','1');
INSERT INTO ts_network VALUES ('291','广元市','广元市','','24','1');
INSERT INTO ts_network VALUES ('292','遂宁市','遂宁市','','24','1');
INSERT INTO ts_network VALUES ('293','内江市','内江市','','24','1');
INSERT INTO ts_network VALUES ('294','南充市','南充市','','24','1');
INSERT INTO ts_network VALUES ('295','眉山市','眉山市','','24','1');
INSERT INTO ts_network VALUES ('296','宜宾市','宜宾市','','24','1');
INSERT INTO ts_network VALUES ('297','广安市','广安市','','24','1');
INSERT INTO ts_network VALUES ('298','达州市','达州市','','24','1');
INSERT INTO ts_network VALUES ('299','雅安市','雅安市','','24','1');
INSERT INTO ts_network VALUES ('300','巴中市','巴中市','','24','1');
INSERT INTO ts_network VALUES ('301','资阳市','资阳市','','24','1');
INSERT INTO ts_network VALUES ('302','阿坝藏族羌族自治州','阿坝藏族羌族自治州','','24','1');
INSERT INTO ts_network VALUES ('303','甘孜藏族自治州','甘孜藏族自治州','','24','1');
INSERT INTO ts_network VALUES ('304','凉山彝族自治州','凉山彝族自治州','','24','1');
INSERT INTO ts_network VALUES ('305','贵阳市','贵阳市','','25','1');
INSERT INTO ts_network VALUES ('306','六盘水市','六盘水市','','25','1');
INSERT INTO ts_network VALUES ('307','遵义市','遵义市','','25','1');
INSERT INTO ts_network VALUES ('308','安顺市','安顺市','','25','1');
INSERT INTO ts_network VALUES ('309','铜仁地区','铜仁地区','','25','1');
INSERT INTO ts_network VALUES ('310','黔西南布依族苗族自治州','黔西南布依族苗族自治州','','25','1');
INSERT INTO ts_network VALUES ('311','毕节地区','毕节地区','','25','1');
INSERT INTO ts_network VALUES ('312','黔东南苗族侗族自治州','黔东南苗族侗族自治州','','25','1');
INSERT INTO ts_network VALUES ('313','黔南布依族苗族自治州','黔南布依族苗族自治州','','25','1');
INSERT INTO ts_network VALUES ('314','昆明市','昆明市','','26','1');
INSERT INTO ts_network VALUES ('315','曲靖市','曲靖市','','26','1');
INSERT INTO ts_network VALUES ('316','玉溪市','玉溪市','','26','1');
INSERT INTO ts_network VALUES ('317','保山市','保山市','','26','1');
INSERT INTO ts_network VALUES ('318','邵通市','邵通市','','26','1');
INSERT INTO ts_network VALUES ('319','丽江市','丽江市','','26','1');
INSERT INTO ts_network VALUES ('320','思茅市','思茅市','','26','1');
INSERT INTO ts_network VALUES ('321','临沧市','临沧市','','26','1');
INSERT INTO ts_network VALUES ('322','楚雄彝族自治州','楚雄彝族自治州','','26','1');
INSERT INTO ts_network VALUES ('323','红河哈尼族彝族自治州','红河哈尼族彝族自治州','','26','1');
INSERT INTO ts_network VALUES ('324','文山壮族苗族自治州','文山壮族苗族自治州','','26','1');
INSERT INTO ts_network VALUES ('325','西双版纳傣族自治州','西双版纳傣族自治州','','26','1');
INSERT INTO ts_network VALUES ('326','大理白族自治州','大理白族自治州','','26','1');
INSERT INTO ts_network VALUES ('327','德宏傣族景颇族自治州','德宏傣族景颇族自治州','','26','1');
INSERT INTO ts_network VALUES ('328','怒江傈僳族自治州','怒江傈僳族自治州','','26','1');
INSERT INTO ts_network VALUES ('329','迪庆藏族自治州','迪庆藏族自治州','','26','1');
INSERT INTO ts_network VALUES ('330','拉萨','拉萨','','27','1');
INSERT INTO ts_network VALUES ('331','都昌地区','都昌地区','','27','1');
INSERT INTO ts_network VALUES ('332','山南地区','山南地区','','27','1');
INSERT INTO ts_network VALUES ('333','日喀则地区','日喀则地区','','27','1');
INSERT INTO ts_network VALUES ('334','那曲地区','那曲地区','','27','1');
INSERT INTO ts_network VALUES ('335','阿里地区','阿里地区','','27','1');
INSERT INTO ts_network VALUES ('336','林芝地区','林芝地区','','27','1');
INSERT INTO ts_network VALUES ('337','西安市','西安市','','28','1');
INSERT INTO ts_network VALUES ('338','铜川市','铜川市','','28','1');
INSERT INTO ts_network VALUES ('339','宝鸡市','宝鸡市','','28','1');
INSERT INTO ts_network VALUES ('340','咸阳市','咸阳市','','28','1');
INSERT INTO ts_network VALUES ('341','渭南市','渭南市','','28','1');
INSERT INTO ts_network VALUES ('342','延安市','延安市','','28','1');
INSERT INTO ts_network VALUES ('343','汉中市','汉中市','','28','1');
INSERT INTO ts_network VALUES ('344','榆林市','榆林市','','28','1');
INSERT INTO ts_network VALUES ('345','安康市','安康市','','28','1');
INSERT INTO ts_network VALUES ('346','商洛市','商洛市','','28','1');
INSERT INTO ts_network VALUES ('347','兰州市','兰州市','','29','1');
INSERT INTO ts_network VALUES ('348','嘉峪关市','嘉峪关市','','29','1');
INSERT INTO ts_network VALUES ('349','金昌市','金昌市','','29','1');
INSERT INTO ts_network VALUES ('350','白银市','白银市','','29','1');
INSERT INTO ts_network VALUES ('351','天水市','天水市','','29','1');
INSERT INTO ts_network VALUES ('352','武威市','武威市','','29','1');
INSERT INTO ts_network VALUES ('353','张掖市','张掖市','','29','1');
INSERT INTO ts_network VALUES ('354','平凉市','平凉市','','29','1');
INSERT INTO ts_network VALUES ('355','酒泉市','酒泉市','','29','1');
INSERT INTO ts_network VALUES ('356','庆阳市','庆阳市','','29','1');
INSERT INTO ts_network VALUES ('357','定西市','定西市','','29','1');
INSERT INTO ts_network VALUES ('358','陇南市','陇南市','','29','1');
INSERT INTO ts_network VALUES ('359','临夏回族自治州','临夏回族自治州','','29','1');
INSERT INTO ts_network VALUES ('360','甘南藏族自治州','甘南藏族自治州','','29','1');
INSERT INTO ts_network VALUES ('361','西宁市','西宁市','','30','1');
INSERT INTO ts_network VALUES ('362','海东地区','海东地区','','30','1');
INSERT INTO ts_network VALUES ('363','海北藏族自治州','海北藏族自治州','','30','1');
INSERT INTO ts_network VALUES ('364','黄南藏族自治州','黄南藏族自治州','','30','1');
INSERT INTO ts_network VALUES ('365','海南藏族自治州','海南藏族自治州','','30','1');
INSERT INTO ts_network VALUES ('366','果洛藏族自治州','果洛藏族自治州','','30','1');
INSERT INTO ts_network VALUES ('367','玉树藏族自治州','玉树藏族自治州','','30','1');
INSERT INTO ts_network VALUES ('368','海西蒙古族藏族自治州','海西蒙古族藏族自治州','','30','1');
INSERT INTO ts_network VALUES ('369','银川市','银川市','','31','1');
INSERT INTO ts_network VALUES ('370','石嘴山市','石嘴山市','','31','1');
INSERT INTO ts_network VALUES ('371','吴忠市','吴忠市','','31','1');
INSERT INTO ts_network VALUES ('372','固原市','固原市','','31','1');
INSERT INTO ts_network VALUES ('373','中卫市','中卫市','','31','1');
INSERT INTO ts_network VALUES ('374','乌鲁木齐市','乌鲁木齐市','','32','1');
INSERT INTO ts_network VALUES ('375','克拉玛依市','克拉玛依市','','32','1');
INSERT INTO ts_network VALUES ('376','吐鲁番地区','吐鲁番地区','','32','1');
INSERT INTO ts_network VALUES ('377','哈密地区','哈密地区','','32','1');
INSERT INTO ts_network VALUES ('378','昌吉回族自治州','昌吉回族自治州','','32','1');
INSERT INTO ts_network VALUES ('379','博尔塔拉蒙古自治州','博尔塔拉蒙古自治州','','32','1');
INSERT INTO ts_network VALUES ('380','巴音郭楞蒙古自治州','巴音郭楞蒙古自治州','','32','1');
INSERT INTO ts_network VALUES ('381','阿克苏地区','阿克苏地区','','32','1');
INSERT INTO ts_network VALUES ('382','克孜勒苏柯尔克孜自治州','克孜勒苏柯尔克孜自治州','','32','1');
INSERT INTO ts_network VALUES ('383','喀什地区','喀什地区','','32','1');
INSERT INTO ts_network VALUES ('384','和田地区','和田地区','','32','1');
INSERT INTO ts_network VALUES ('385','伊犁哈萨克自治州','伊犁哈萨克自治州','','32','1');
INSERT INTO ts_network VALUES ('386','塔城地区','塔城地区','','32','1');
INSERT INTO ts_network VALUES ('387','阿勒泰地区','阿勒泰地区','','32','1');
INSERT INTO ts_network VALUES ('388','省直辖行政单位','省直辖行政单位','','32','1');
INSERT INTO ts_network VALUES ('389','香港','香港','','33','1');
INSERT INTO ts_network VALUES ('390','澳门','澳门','','34','1');
INSERT INTO ts_network VALUES ('391','台北市','台北市','','35','1');
INSERT INTO ts_network VALUES ('392','高雄市','高雄市','','35','1');
INSERT INTO ts_network VALUES ('393','基隆市','基隆市','','35','1');
INSERT INTO ts_network VALUES ('394','台中市','台中市','','35','1');
INSERT INTO ts_network VALUES ('395','台南市','台南市','','35','1');
INSERT INTO ts_network VALUES ('396','新竹市','新竹市','','35','1');
INSERT INTO ts_network VALUES ('397','嘉义市','嘉义市','','35','1');

 drop table if exists ts_notify;
CREATE TABLE `ts_notify` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `type` varchar(25) NOT NULL,
  `new` tinyint(1) NOT NULL,
  `authorid` int(11) NOT NULL,
  `author` varchar(255) NOT NULL,
  `title` text NOT NULL,
  `body` text NOT NULL,
  `url` text NOT NULL,
  `cTime` int(11) NOT NULL,
  `cate` varchar(255) NOT NULL,
  `appid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

INSERT INTO ts_notify VALUES ('1','1','add_friend','2','2','fengzhiqiang','N;','a:1:{s:4:\\\"note\\\";s:4:\\\"sfds\\\";}','2','1254730616','friend','0');
INSERT INTO ts_notify VALUES ('2','2','agree_friend','1','1','管理员','N;','N;','','1254731069','friend','0');
INSERT INTO ts_notify VALUES ('3','3','add_friend','2','4','侯光成','N;','a:1:{s:4:\\\"note\\\";s:3:\\\"kkk\\\";}','4','1254732647','friend','0');
INSERT INTO ts_notify VALUES ('4','1','add_friend','2','4','侯光成','N;','a:1:{s:4:\\\"note\\\";s:6:\\\"看看\\\";}','4','1254732719','friend','0');
INSERT INTO ts_notify VALUES ('5','2','add_friend','1','4','侯光成','N;','a:1:{s:4:\\\"note\\\";s:12:\\\"加为好友\\\";}','4','1254732769','friend','0');
INSERT INTO ts_notify VALUES ('6','1','add_friend','2','3','冯志强','N;','a:1:{s:4:\\\"note\\\";s:1:\\\"d\\\";}','3','1254732904','friend','0');
INSERT INTO ts_notify VALUES ('7','4','agree_friend','0','3','冯志强','N;','N;','','1254732954','friend','0');
INSERT INTO ts_notify VALUES ('8','2','add_friend','1','3','冯志强','N;','a:1:{s:4:\\\"note\\\";s:1:\\\"s\\\";}','3','1254732981','friend','0');
INSERT INTO ts_notify VALUES ('9','4','agree_friend','0','1','管理员','N;','N;','','1254733725','friend','0');
INSERT INTO ts_notify VALUES ('10','3','agree_friend','0','1','管理员','N;','N;','','1254733747','friend','0');

 drop table if exists ts_notify_template;
CREATE TABLE `ts_notify_template` (
  `id` int(11) NOT NULL auto_increment,
  `type` varchar(255) NOT NULL,
  `type_cn` varchar(255) NOT NULL,
  `deal` varchar(255) NOT NULL,
  `title` text NOT NULL,
  `body` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

INSERT INTO ts_notify_template VALUES ('17','mini_comment','心情评论','','{actor}评论了你的心情','“{comment}”');
INSERT INTO ts_notify_template VALUES ('19','add_friend','好友请求','你已同意对方的好友请求，并将对方加为你的好友 | 你已经忽略了对方的好友请求','{actor} 请求加你为好友','“{note}”');
INSERT INTO ts_notify_template VALUES ('20','agree_friend','好友请求','你已经修改了TA的分组信息','{actor} 通过了你的好友请求，并把你加为TA的好友。','');
INSERT INTO ts_notify_template VALUES ('21','blog_comment','日志回复','','{actor} 评论了你的日志 {title}','“{comment}”');
INSERT INTO ts_notify_template VALUES ('22','comment_comment','回复的回复','','{actor}回复了你在{type}{title}的评论','“{comment}”');
INSERT INTO ts_notify_template VALUES ('23','blog_mention','日志点名通知','','{actor} 在日记 {title} 中提到了你','“{content}”');
INSERT INTO ts_notify_template VALUES ('24','vote_comment','投票评论','','{actor}评论了你的投票{title}','“{comment}”');
INSERT INTO ts_notify_template VALUES ('25','vote_in','参与投票','','{actor}参与了你的投票{title}','{opts}');
INSERT INTO ts_notify_template VALUES ('26','add_event','发起活动','','{actor} 发起了一个活动: {title}','只允许我的好友来参加。\r\n“{content}”');
INSERT INTO ts_notify_template VALUES ('27','event_comment','活动评论','','{actor}在活动 {title} 中发表了评论','“{comment}”');
INSERT INTO ts_notify_template VALUES ('28','share_comment','分享的评论','','{actor}评论了你的分享 {title}','“{comment}”');
INSERT INTO ts_notify_template VALUES ('29','share_notice','分享','','{actor}通知你Ta分享了一个{type}','');
INSERT INTO ts_notify_template VALUES ('30','event_photo_comment','活动相片评论','','{actor}评论了你{title}活动中的相片','“{comment}”');
INSERT INTO ts_notify_template VALUES ('31','photo_comment','相片评论','','{actor}评论了你的照片{title}','“{comment}”');
INSERT INTO ts_notify_template VALUES ('32','gift_send','礼品','','{user}给您送了一个礼物','{img}<br /> {sendback}<br /> <div class=\"quote\"><p><span class=\"quoteR\">{content}</span></p></div>');
INSERT INTO ts_notify_template VALUES ('33','group_reply','回复话题','','{actor}回复了你的话题','<a href=\"{SITE_URL}/Topic/topic/gid/{gid}/tid/{tid}\">{title}</a>');
INSERT INTO ts_notify_template VALUES ('34','group_topic_quote','引用话题','','{actor}引用了你的回复','<a href=\"{SITE_URL}/Topic/topic/gid/{gid}/tid/{tid}\">{title}</a>');
INSERT INTO ts_notify_template VALUES ('35','share_notice2','分享','','{actor}分享了你的一个{type}','');
INSERT INTO ts_notify_template VALUES ('36','group','创建了一个群组,邀请你加入','','{actor}邀请你加入群组','<a href=\"{SITE_URL}/Group/index/gid/{gid}\">{title}</a>');
INSERT INTO ts_notify_template VALUES ('37','group_apply','申请加入群组','','{actor}申请加入你的{title}的群组','<a href=\"{SITE_URL}/Manage/membermanage/gid/{gid}/type/apply\">同意链接</a>');
INSERT INTO ts_notify_template VALUES ('38','share_notice3','分享','','{actor}分享你了','');
INSERT INTO ts_notify_template VALUES ('39','group_create','一个新群组邀请你加入','','{actor}邀请你加入群组','<a href=\"{SITE_URL}/Group/index/gid/{gid}\">{title}</a>');

 drop table if exists ts_option;
CREATE TABLE `ts_option` (
  `name` varchar(64) NOT NULL,
  `value` text,
  `appname` varchar(30) NOT NULL default 'thinksns'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO ts_option VALUES ('site_name','ThinkSNS','thinksns');
INSERT INTO ts_option VALUES ('slogan','个性化你的社交网络','thinksns');
INSERT INTO ts_option VALUES ('site_header','ThinkSNS|网站|SNS|','thinksns');
INSERT INTO ts_option VALUES ('site_keyword','sns,think','thinksns');
INSERT INTO ts_option VALUES ('template','blue','thinksns');
INSERT INTO ts_option VALUES ('email','master@xueyou.info','thinksns');
INSERT INTO ts_option VALUES ('icp','京ICP备09050100号','thinksns');
INSERT INTO ts_option VALUES ('report_reason','黄色图片,淫秽语句,违反社区规则','thinksns');
INSERT INTO ts_option VALUES ('site_close','radio','thinksns');
INSERT INTO ts_option VALUES ('site_close_reason','被绿坝和谐了~~~','thinksns');
INSERT INTO ts_option VALUES ('allow_ips','','thinksns');
INSERT INTO ts_option VALUES ('deny_ips','','thinksns');
INSERT INTO ts_option VALUES ('privacy','a:6:{s:5:\"youke\";s:1:\"1\";s:5:\"space\";s:1:\"0\";s:4:\"wall\";s:1:\"0\";s:6:\"friend\";s:1:\"0\";s:6:\"search\";s:1:\"0\";s:4:\"foot\";s:1:\"0\";}','thinksns');
INSERT INTO ts_option VALUES ('reg_close','0','thinksns');
INSERT INTO ts_option VALUES ('reg_invite_close','0','thinksns');
INSERT INTO ts_option VALUES ('fri_tuijian','1','thinksns');
INSERT INTO ts_option VALUES ('fri_dongtai','1','thinksns');
INSERT INTO ts_option VALUES ('reg_tiaokuan','<P><FONT size=4><B><SPAN style=\\\"FONT-FAMILY: 宋体; FONT-SIZE: 16pt\\\">ThinkSNS</SPAN></B><B><SPAN style=\\\"FONT-FAMILY: 宋体; FONT-SIZE: 16pt\\\">使用协议</SPAN></B></FONT></P>\r\n<P><B><SPAN style=\\\"FONT-FAMILY: 宋体; FONT-SIZE: 12pt\\\">欢迎来到<SPAN>ThinkSNS </SPAN>！<SPAN>ThinkSNS</SPAN>是一个真人网络，提供个人空间，迷你博客，相册，群组，等服务。</SPAN></B></P>\r\n<P><SPAN style=\\\"FONT-FAMILY: 宋体; FONT-SIZE: 12pt\\\">如果你想注册成为<SPAN>ThinkSNS</SPAN>用户，请务必阅读本使用协议（以下简称<SPAN>“</SPAN>本协议<SPAN>”</SPAN>）并在注册过程中表明你同意接受本协议。<SPAN></SPAN></SPAN></P>\r\n<OL type=1>\r\n<LI style=\\\"tab-stops: list 36.0pt\\\"><SPAN style=\\\"FONT-FAMILY: 宋体; FONT-SIZE: 12pt\\\">ThinkSNS </SPAN><SPAN style=\\\"FONT-FAMILY: 宋体; FONT-SIZE: 12pt\\\">用户指在本站注册并验证注册邮件地址的用户。用户可以使用个人空间、迷你博客、相册、群组和分享等基本功能。 <SPAN></SPAN></SPAN>\r\n<LI style=\\\"tab-stops: list 36.0pt\\\"><SPAN style=\\\"FONT-FAMILY: 宋体; FONT-SIZE: 12pt\\\">一旦注册成为<SPAN>ThinkSNS</SPAN>用户，即代表你保证：（<SPAN>a</SPAN>）你提交的个人信息是真实、准确、完整的；（<SPAN>b</SPAN>）你会不断更新个人资料，以符合及时、详尽、准确的要求；（<SPAN>c</SPAN>）你在使用服务时不会违反任何适用的国家法律或法规；（<SPAN>d</SPAN>）你在使用服务时不会违反<SPAN>ThinkSNS</SPAN>一切有效的管理办法和规定。 <SPAN></SPAN></SPAN>\r\n<LI style=\\\"tab-stops: list 36.0pt\\\"><SPAN style=\\\"FONT-FAMILY: 宋体; FONT-SIZE: 12pt\\\">用户的个人资料受到<SPAN>ThinkSNS</SPAN>的保护。<SPAN>ThinkSNS</SPAN>承诺不会在未获得用户许可的情况下擅自将用户的个人资料信息出租或出售给任何第三方，但以下情况除外：（<SPAN>a</SPAN>）用户同意让第三方共享资料；（<SPAN>b</SPAN>）用户同意公开其个人资料，享受相应的产品和服务；（<SPAN>c</SPAN>）<SPAN>ThinkSNS</SPAN>需要听从法庭传票、法律命令或遵循法律程序；（<SPAN>d</SPAN>）<SPAN>ThinkSNS</SPAN>发现用户违反了本站服务条款或本站其他使用规定。更多关于隐私保护的内容请查看<SPAN>ThinkSNS</SPAN>的隐私声明。 <SPAN></SPAN></SPAN>\r\n<LI style=\\\"tab-stops: list 36.0pt\\\"><SPAN style=\\\"FONT-FAMILY: 宋体; FONT-SIZE: 12pt\\\">用户应随时根据实际情况更新个人资料。帐号相关信息不得影射党和国家领导人或者含有不雅含义，对违反者将予以删除帐号。 <SPAN></SPAN></SPAN>\r\n<LI style=\\\"tab-stops: list 36.0pt\\\"><SPAN style=\\\"FONT-FAMILY: 宋体; FONT-SIZE: 12pt\\\">用户在<SPAN>ThinkSNS</SPAN>发文或使用任何服务时，以及在自己的个人资料中，均不能对他人进行骚扰或人身攻击、包含侮辱性言辞、引起不必要的纠纷、影射党和国家领导人或含有其他不恰当的内容。如果违犯上述情形，将视情况予以警告、封禁部分权限直至删除帐号。 <SPAN></SPAN></SPAN>\r\n<LI style=\\\"tab-stops: list 36.0pt\\\"><SPAN style=\\\"FONT-FAMILY: 宋体; FONT-SIZE: 12pt\\\">用户不得使用本站的帐号从事非法商业行为或发表猥亵、色情、反党反政府言论或者其他不当言论。违者将封禁部分权限直至删除帐号。 <SPAN></SPAN></SPAN>\r\n<LI style=\\\"tab-stops: list 36.0pt\\\"><SPAN style=\\\"FONT-FAMILY: 宋体; FONT-SIZE: 12pt\\\">用户不得利用本站帐号、个人资料、迷你博客、分享、照片、群组和私信等服务进行直接或者间接商业宣传，否则将视情节轻重给予警告、封禁部分权限直至删除帐号的处罚；对于蓄意为某一商业机构进行多个商业宣传的，将视情节轻重予以警告、封禁部分权限直至删除帐号的处罚。 <SPAN></SPAN></SPAN>\r\n<LI style=\\\"tab-stops: list 36.0pt\\\"><SPAN style=\\\"FONT-FAMILY: 宋体; FONT-SIZE: 12pt\\\">用户必须保证拥有上传之照片和文字等作品的著作权或已获得合法授权，用户必须保证在本站的上传行为未侵犯任何第三方之合法权益。否则，将由用户本人承担由此带来的一切法律责任。 <SPAN></SPAN></SPAN>\r\n<LI style=\\\"tab-stops: list 36.0pt\\\"><SPAN style=\\\"FONT-FAMILY: 宋体; FONT-SIZE: 12pt\\\">用户不得手动或者使用特殊程序对本站系统进行恶意冲击。对于恶意冲击系统并可能危及系统稳定运行的用户，直接删除帐号。 <SPAN></SPAN></SPAN>\r\n<LI style=\\\"tab-stops: list 36.0pt\\\"><SPAN style=\\\"FONT-FAMILY: 宋体; FONT-SIZE: 12pt\\\">用户有权力和责任对站内出现的违反国家法律法规以及有关站规的事件进行举报。 <SPAN></SPAN></SPAN>\r\n<LI style=\\\"tab-stops: list 36.0pt\\\"><SPAN style=\\\"FONT-FAMILY: 宋体; FONT-SIZE: 12pt\\\">ThinkSNS</SPAN><SPAN style=\\\"FONT-FAMILY: 宋体; FONT-SIZE: 12pt\\\">用户帐号代表用户个人，由于用户发布消息或者其他站上交流引起的法律上的或者经济上的责任，<SPAN>ThinkSNS</SPAN>不予负担其中任何形式的责任，用户的言论文责自负。 <SPAN></SPAN></SPAN>\r\n<LI style=\\\"tab-stops: list 36.0pt\\\"><SPAN style=\\\"FONT-FAMILY: 宋体; FONT-SIZE: 12pt\\\">用户应保护好<SPAN>ThinkSNS</SPAN>帐号和密码安全，对任何人利用你的帐号和密码所进行的活动负全部责任。因<SPAN>ThinkSNS</SPAN>无法对非法或未经用户授权使用的帐号的行为作出甄别，因此<SPAN>ThinkSNS</SPAN>不承担任何责任。 <SPAN></SPAN></SPAN>\r\n<LI style=\\\"tab-stops: list 36.0pt\\\"><SPAN style=\\\"FONT-FAMILY: 宋体; FONT-SIZE: 12pt\\\">用户帐号仅代表使用者个人，不得转借、售卖或共用帐号。违者将视情节受到警告、封禁部分权限直至删除帐号的处罚。对于有意泄露帐号密码者，视同共用帐号从重处理。 <SPAN></SPAN></SPAN>\r\n<LI style=\\\"tab-stops: list 36.0pt\\\"><SPAN style=\\\"FONT-FAMILY: 宋体; FONT-SIZE: 12pt\\\">ThinkSNS </SPAN><SPAN style=\\\"FONT-FAMILY: 宋体; FONT-SIZE: 12pt\\\">有权随时修改本协议中的任何条款。任何修改自发布之时起生效。如果你在修改发布后继续使用<SPAN>ThinkSNS</SPAN>的服务，即表示你同意遵守对本协议所作出的修改。欢迎随时查看本协议，以确保了解所有相关的最新修改。如果你不同意相关修改，请你离开本站并立即停止使用本站服务。 <SPAN></SPAN></SPAN></LI></OL><SPAN style=\\\"FONT-FAMILY: 宋体; FONT-SIZE: 12pt\\\">本协议解释权归<SPAN>ThinkSNS </SPAN>，有关事项与其他声明或协议互为补充。若有冲突，以<SPAN>ThinkSNS </SPAN>公布的最新协议为准。本协议自公布之日起施行。</SPAN>','thinksns');
INSERT INTO ts_option VALUES ('reg_email','0','thinksns');
INSERT INTO ts_option VALUES ('newuser_time','11','thinksns');
INSERT INTO ts_option VALUES ('verify','a:2:{s:5:\"login\";s:1:\"1\";s:3:\"reg\";s:1:\"1\";}','thinksns');
INSERT INTO ts_option VALUES ('newuser_fri_num','3','thinksns');
INSERT INTO ts_option VALUES ('feed_privacy','a:11:{s:4:\"info\";s:1:\"1\";s:4:\"head\";s:1:\"1\";s:4:\"mini\";s:1:\"1\";s:5:\"photo\";s:1:\"1\";s:4:\"blog\";s:1:\"1\";s:7:\"c_group\";s:1:\"1\";s:7:\"j_group\";s:1:\"1\";s:10:\"add_friend\";s:1:\"1\";s:7:\"add_app\";s:1:\"1\";s:4:\"wall\";s:1:\"1\";s:7:\"comment\";s:1:\"1\";}','thinksns');
INSERT INTO ts_option VALUES ('gfw_enable','1','thinksns');
INSERT INTO ts_option VALUES ('gfw_keywords','共产党|法轮功|藏独|毛泽东|江泽民|胡锦涛|fuck|温家宝','thinksns');
INSERT INTO ts_option VALUES ('gonggao','<PRE><FONT color=#ff0066>上传作品，赢取礼品，</FONT><a href=\"http://i.thinksns.com/apps/event/index.php?s=/Index/eventDetail/id/13/uid/10001\" target=_blank target=\"_blank\"><FONT color=#ff0066>我来设计心中的TS</FONT></A></PRE>','thinksns');
INSERT INTO ts_option VALUES ('gfw_enable','1','thinksns');
INSERT INTO ts_option VALUES ('gfw_rep','信曾哥不跑调','thinksns');
INSERT INTO ts_option VALUES ('app_icon','N;','thinksns');
INSERT INTO ts_option VALUES ('gonggao_open','1','thinksns');
INSERT INTO ts_option VALUES ('reg_relation_friend','10000,10001,10006','thinksns');
INSERT INTO ts_option VALUES ('album_raws','6','photo');
INSERT INTO ts_option VALUES ('photo_raws','15','photo');
INSERT INTO ts_option VALUES ('photo_max_size','0','photo');
INSERT INTO ts_option VALUES ('max_album_num','0','photo');
INSERT INTO ts_option VALUES ('max_photo_num','0','photo');
INSERT INTO ts_option VALUES ('max_storage_size','0','photo');
INSERT INTO ts_option VALUES ('open_safemode','0','photo');
INSERT INTO ts_option VALUES ('open_photo_mark','0','photo');
INSERT INTO ts_option VALUES ('open_camera','0','photo');
INSERT INTO ts_option VALUES ('album_default_order','id DESC','photo');
INSERT INTO ts_option VALUES ('open_watermark','1','photo');
INSERT INTO ts_option VALUES ('watermark_file','','photo');
INSERT INTO ts_option VALUES ('allorder','all','blog');
INSERT INTO ts_option VALUES ('savetime','5','blog');
INSERT INTO ts_option VALUES ('limittime','','event');
INSERT INTO ts_option VALUES ('group','','event');
INSERT INTO ts_option VALUES ('score','','event');
INSERT INTO ts_option VALUES ('canCreat','0','event');
INSERT INTO ts_option VALUES ('limitsuffix','jpg,jpn,gif','event');
INSERT INTO ts_option VALUES ('limitphoto','3','event');
INSERT INTO ts_option VALUES ('membel','0','event');
INSERT INTO ts_option VALUES ('limitpage','10','event');
INSERT INTO ts_option VALUES ('reg_checkname','0','thinksns');
INSERT INTO ts_option VALUES ('reg_fuxing','安陵,安平,安期,安阳,白马,百里,柏侯,鲍俎,北宫,北郭,北门,北山,北唐,奔水,逼阳,宾牟,薄奚薄野,曹牟,曹丘,常涛,长鱼,车非,成功,成阳,乘马,叱卢,丑门,樗里,穿封,淳子,答禄,达勃,达步,达奚,淡台,邓陵,第五,地连,地伦,东方,东里,东南,东宫,东门东乡,东丹,东郭,东陵,东关,东闾,东阳,东野,东莱,豆卢,斗于,都尉,独孤端木,段干,多子,尔朱,方雷丰将,封人,封父,夫蒙,夫馀,浮丘,傅余,干已,高车,高陵,高堂,高阳,高辛,皋落,哥舒,盖楼,庚桑,梗阳,宫孙,公羊,公良,公孙,公罔,公西,公冶,公敛,公梁,公输,公上,公山,公户,公玉,公仪,公仲公坚,公伯,公祖,公乘,公晰,公族,姑布,古口,古龙,古孙,谷梁,谷浑,瓜田,关龙,鲑阳,归海,函治,韩馀,罕井,浩生,浩星,纥骨,纥奚纥于,贺拨,贺兰,贺楼,赫连,黑齿,黑肱,侯冈,呼延,壶丘,呼衍,斛律,胡非,胡母,胡毋,皇甫,皇父,兀官吉白,即墨,季瓜,季连,季孙,茄众,蒋丘,金齿,晋楚,京城,泾阳,九百,九方,睢鸠,沮渠,巨母,勘阻,渴侯,渴单,可汗,空桐,空相,昆吾,老阳,乐羊,荔菲,栎阳,梁丘,梁由,梁馀,梁垣,陵阳伶舟,冷沦,令狐,刘王,柳下,龙丘,卢妃,卢蒲,鲁步,陆费,角里,闾丘,马矢,麦丘,茅夷,弥牟,密革,密茅,墨夷,墨台,万俊,昌顿,慕容,木门,木易,南宫,南郭,南门,南荣,欧侯,欧阳,逄门,盆成,彭祖,平陵,平宁,破丑,仆固,濮阳,漆雕,奇介,綦母,綦毋,綦连,祁连,乞伏,绮里,千代,千乘,勤宿,青阳,丘丽,丘陵,屈侯,屈突,屈男,屈卢,屈同,屈门,屈引,壤四,扰龙,容成,汝嫣,萨孤,三饭,三闾,三州,桑丘,商瞿,上官,尚方,少师,少施,少室,少叔,少正,社南社北,申屠,申徒,沈犹,胜屠,石作石牛,侍其,士季,士弱,士孙,士贞,叔孙,叔先,叔促,水丘,司城,司空,司寇,司鸿,司马,司徒,司士,似和,素和,夙沙,孙阳,索阳,索卢,沓卢,太史,太叔,太阳,澹台,唐山,堂溪,陶丘,同蹄,统奚,秃发,涂钦,吐火,吐贺,吐万,吐罗,吐门,吐难,吐缶,吐浑吐奚,吐和,屯浑,脱脱,拓拨,完颜,王孙,王官,王人,微生,尾勺,温孤,温稽,闻人,屋户,巫马,吾丘,无庸,无钩,五鹿,息夫,西陵,西乞,西钥,西乡,西门,西周,西郭,西方,西野,西宫,戏阳,瑕吕,霞露,夏侯,鲜虞,鲜于,鲜阳,咸丘,相里,解枇,谢丘,新垣,辛垣,信都,信平,修鱼,徐吾,宣于,轩辕,轩丘,阏氏,延陵,罔法,铅陵,羊角,耶律,叶阳,伊祁,伊耆,猗卢,义渠,邑由,因孙,银齿,尹文,雍门,游水,由吾,右师,宥连,於陵,虞丘,盂丘,宇文,尉迟,乐羊,乐正,运奄,运期,宰父,辗迟,湛卢,章仇,仉督,长孙,长儿,真鄂,正令,执头,中央,中长,中行,中野,中英,中梁,中垒,钟离,钟吾,终黎,终葵,仲孙,仲长,周阳,周氏,周生,朱阳,诸葛,主父,颛孙,颛顼,訾辱,淄丘,子言,子人,子服子家,子桑,子叔,子车,子阳,宗伯,宗正,宗政,尊卢,昨和,左人,左丘,左师,左行,刘文,额尔,达力,蔡斯,浩赏,斛斯,夹谷,揭阳,万俟,淳于,单于,徐离','thinksns');
INSERT INTO ts_option VALUES ('limitpage','10','share');
INSERT INTO ts_option VALUES ('isDel','0','share');
INSERT INTO ts_option VALUES ('reg_danxing','艾,安,昂,敖,奥,巴,霸,白,柏,拜,班,包,保,葆,豹,鲍,暴,卑,贲,毕,闭,秘,边,鞭,彪,别,宾,邴,秉,薄,卜,布,部,才,蔡,仓,苍,操,曹,策,岑,柴,镡,昌,苌,常,昶,畅,唱,晁,巢,朝,车,陈,谌,成,承,晟,乘,程,池,迟,充,种,崇,丑,侴,初,储,楚,禇,揣,啜,春,椿,慈,次,从,丛,爨,崔,萃,寸,达,笪,大,代,戴,丹,但,啖,党,刀,德,邓,狄,翟,邸,底,弟,典,刁,丁,定,东,冬,董,豆,窦,都,堵,杜,度,端,段,敦,顿,多,朵,颚,恩,法,氾,樊,范,方,芳,防,房,费,丰,风,封,酆,冯,逢,凤,奉,俸,伏,扶,苻,服,符,福,付,傅,富,改,盖,干,甘,淦,冮,刚,皋,高,杲,郜,戈,革,合,格,盖,葛,庚,赓,耿,弓,公,宫,龚,巩,贡,勾,缑,苟,勾,辜,古,谷,固,顾,关,官,筦,管,观,贯,冠,光,广,归,妫,邽,炅,炔,贵,桂,滚,过,呙,郭,国,虢,果,哈,海,罕,撖,杭,郝,合,何,和,亻各,贺,赫,黑,亨,恒,衡,弘,闳,宏,鸿,侯,后,郈,厚,呼,轷,狐,胡,壶,虎,户,扈,花,华,滑,华,怀,槐,还,环,郇,桓,宦,皇,黄,回,惠,浑,火,霍,姬,嵇,稽,及,吉,汲,姞,戢,集,藉,籍,纪,计,季,暨,冀,加,家,嘉,郏,甲,贾,坚,菅,检,简,翦,蹇,见,监,江,将,姜,蒋,降,焦,矫,皎,敫,曒,教,接,揭,节,介,金,晋,烬,京,经,荆,井,景,敬,靖,静,酒,局,倶,琚,鞠,菊,巨,剧,隽,圈,角,开,凯,阚,康,亢,伉,柯,可,坑,孔,寇,库,蒯,郐,宽,匡,邝,旷,况,奎,隗,夔,腊,来,赖,兰,蓝,郎,劳,老,乐,雷,冷,离,黎,李,里,力,历,厉,立,励,利,郦,栗,连,廉,练,良,梁,聊,廖,列,林,临,吝,蔺,令,泠,凌,刘,留,柳,龙,隆,娄,楼,卢,庐,鲁,陆,逯,禄,路,闾,吕,律,栾,论,论,罗,洛,骆,雒,麻,马,买,麦,满,莽,毛,茆,茅,冒,枚,梅,门,蒙,孟,弥,祢,糜,米,芈,弭,宓,密,苗,缪,闵,敏,名,万,莫,墨,默,牟,母,木,沐,睦,慕,穆,那,纳,乃,佴,南,铙,倪,年,粘,念,乜,聂,宁,牛,钮,农,侬,区,欧,潘,盘,泮,庞,裴,彭,邳,皮,朴,品,平,繁,蒲,濮,浦,普,溥,柒,戚,漆,亓,齐,祁,歧,綦,乞,杞,启,千,钱,潜,强,乔,桥,谯,且,郄,钦,秦,琴,覃,勤,青,卿,清,庆,丘,邱,秋,仇,求,裘,曲,屈,麴,渠,璩,瞿,蘧,权,全,泉,阙,冉,饶,壬,任,戎,荣,容,茹,汝,阮,软,芮,瑞,洒,撒,萨,伞,桑,沙,山,闪,陕,单,善,商,赏,尚,少,召,邵,折,佘,厍,舍,申,莘,神,沈,生,绳,盛,师,施,石,时,史,士,世,是,奭,首,寿,殳,舒,疏,束,树,耍,帅,双,水,睡,顺,司,思,死,斯,四,佀,姒,松,宋,苏,宿,粟,眭,睢,隋,随,穗,孙,所,索,塔,台,邰,台,太,泰,谈,覃,谭,澹,檀,镡,汤,唐,棠,陶,腾,提,遆,题,帖,铁,通,仝,同,佟,彤,童,钭,徒,涂,屠,土,脱,完,宛,晚,万,汪,王,望,危,韦,维,蒍,隗,位,尉,温,文,闻,问,翁,瓮,邬,巫,毋,吾,吴,伍,仵,武,务,西,析,郗,息,奚,锡,习,席,袭,隰,舄,夏,先,鲜,咸,冼,洗,羡,线,相,香,襄,祥,向,相,项,肖,萧,孝,谢,渫,辛,忻,新,信,兴,星,刑,邢,行,幸,熊,修,须,顼,徐,许,旭,续,轩,禤,旋,薛,穴,雪,寻,郇,荀,押,牙,轧,鄢,燕,延,严,言,阎,颜,晏,扬,羊,阳,杨,仰,幺,要,尧,姚,铫,药,冶,业,叶,伊,衣,依,仪,宜,乙,蚁,扆,弋,义,亦,易,弈,益,裔,翼,阴,殷,银,尹,印,应,英,营,赢,灜,雍,勇,涌,幽,尤,由,游,於,欲,余,鱼,俞,馀,宇,禹,庾,玉,郁,遇,喻,裕,毓,渊,元,袁,原,圆,源,员,苑,院,乐,岳,悦,越,云,妘,郧,运,员,郓,恽,韵,载,昝,臧,迮,笮,曾,增,查,乍,翟,呰,祭,占,詹,瞻,展,战,湛,张,章,彰,仉,掌,招,召,兆,赵,肇,真,甄,镇,正,郑,政,支,执,直,植,志,郅,智,终,钟,衷,种,仲,周,朱,邾,诸,竹,竺,主,祝,专,庄,卓,禚,资,訾,子,紫,宗,邹,驺,俎,祖,左,韩','thinksns');
INSERT INTO ts_option VALUES ('defaultTime','7776000','event');
INSERT INTO ts_option VALUES ('friends_new_photo_limit','7','photo');
INSERT INTO ts_option VALUES ('max_flash_upload_num','20','photo');
INSERT INTO ts_option VALUES ('join','friend','event');
INSERT INTO ts_option VALUES ('allorder','all','event');
INSERT INTO ts_option VALUES ('smiletype','mini','blog');
INSERT INTO ts_option VALUES ('leadingnum','100','blog');
INSERT INTO ts_option VALUES ('leadingin','1','blog');
INSERT INTO ts_option VALUES ('notifyfriend','1','blog');
INSERT INTO ts_option VALUES ('fileaway','1','blog');
INSERT INTO ts_option VALUES ('fileawaypage','6','blog');
INSERT INTO ts_option VALUES ('stringcount','100','mini');
INSERT INTO ts_option VALUES ('all','1','blog');
INSERT INTO ts_option VALUES ('delete','0','blog');
INSERT INTO ts_option VALUES ('suffix','...','blog');
INSERT INTO ts_option VALUES ('titleshort','100','blog');
INSERT INTO ts_option VALUES ('limitpage','20','blog');
INSERT INTO ts_option VALUES ('all','1','mini');
INSERT INTO ts_option VALUES ('pagenum','10','mini');
INSERT INTO ts_option VALUES ('smiletype','mini','mini');
INSERT INTO ts_option VALUES ('replay','0','mini');
INSERT INTO ts_option VALUES ('fileawaypage','5','mini');
INSERT INTO ts_option VALUES ('fileaway','1','mini');
INSERT INTO ts_option VALUES ('delete','0','mini');
INSERT INTO ts_option VALUES ('email_stmp','mail.tst3.com','thinksns');
INSERT INTO ts_option VALUES ('emap_port','','thinksns');
INSERT INTO ts_option VALUES ('email_address','web@tst3.com','thinksns');
INSERT INTO ts_option VALUES ('email_password','thinksns','thinksns');
INSERT INTO ts_option VALUES ('email_subject','{SITE_NAME}给您发来的注册激活邮件。','thinksns');
INSERT INTO ts_option VALUES ('email_body','尊敬的{SEX}，您好！<p>欢迎您参与ThinkSNS 1.6版的测试。<p>请点击此链接即可激活您的帐户：<p><a href={URL}>激活{SITE_NAME}账号</a>','thinksns');
INSERT INTO ts_option VALUES ('email_port','25','thinksns');
INSERT INTO ts_option VALUES ('invite_content',' 　{SITE_NAME}真是太好玩了，我的几个好友已经在上面注册了。如果你在{SITE_NAME}死成为我的好友，你可以查看我的最新照片、日记、了解我的最新动态... \r\n <BR> \r\n <STRONG></STRONG>&nbsp; ','thinksns');
INSERT INTO ts_option VALUES ('reg_yaoqing_close','1','thinksns');
INSERT INTO ts_option VALUES ('reg_invite_close','0','thinksns');
INSERT INTO ts_option VALUES ('guest','1','thinksns');
INSERT INTO ts_option VALUES ('reg_relation_group','1,2,3,4,5','thinksns');
INSERT INTO ts_option VALUES ('home_feed','30','thinksns');
INSERT INTO ts_option VALUES ('thinksns_html_token','f5b245ead77ff0348b3bab89e1a259f9','event');
INSERT INTO ts_option VALUES ('feed_filter','user','thinksns');
INSERT INTO ts_option VALUES ('feed_filter_count','3','thinksns');
INSERT INTO ts_option VALUES ('is_im_open','1','thinksns');
INSERT INTO ts_option VALUES ('creditType','score','gift');
INSERT INTO ts_option VALUES ('creditName','积分','gift');
INSERT INTO ts_option VALUES ('adminId','1','thinksns');

 drop table if exists ts_photo;
CREATE TABLE `ts_photo` (
  `id` int(11) NOT NULL auto_increment,
  `attachId` int(11) NOT NULL,
  `albumId` int(11) NOT NULL,
  `userId` int(11) default NULL,
  `status` tinyint(2) unsigned NOT NULL default '1',
  `name` varchar(255) NOT NULL,
  `cTime` int(11) unsigned default NULL,
  `mTime` int(11) unsigned default NULL,
  `info` text,
  `commentCount` int(11) unsigned default '0',
  `readCount` int(11) unsigned default '0',
  `savepath` varchar(255) NOT NULL,
  `size` int(11) NOT NULL default '0',
  `privacy` int(1) NOT NULL default '1',
  `tags` text,
  `order` int(11) NOT NULL default '0',
  `isDel` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

INSERT INTO ts_photo VALUES ('1','1','1','2','1','1','1254731461','1254731461','','0','0','20091005/16/4ac9aec580a34.jpg','13463','1','','0','0');
INSERT INTO ts_photo VALUES ('2','5','2','4','1','DSC02463','1254733051','1254733051','','0','0','20091005/16/4ac9b4fb5121d.JPG','882738','1','','0','0');
INSERT INTO ts_photo VALUES ('3','6','2','4','1','DSC02417','1254733082','1254733082','','0','0','20091005/16/4ac9b51a38a8d.JPG','644775','1','','0','0');
INSERT INTO ts_photo VALUES ('4','7','2','4','1','DSC02418','1254733117','1254733117','','0','0','20091005/16/4ac9b53d05937.JPG','727547','1','','0','0');
INSERT INTO ts_photo VALUES ('5','8','2','4','1','DSC02419','1254733155','1254733155','','0','0','20091005/16/4ac9b5635b44d.JPG','803512','1','','0','0');
INSERT INTO ts_photo VALUES ('6','9','2','4','1','DSC02420','1254733187','1254733187','','0','0','20091005/16/4ac9b5834c688.JPG','652336','1','','0','0');
INSERT INTO ts_photo VALUES ('7','10','2','4','1','DSC02423','1254733226','1254733226','','0','0','20091005/17/4ac9b5aa377e4.JPG','752742','1','','0','0');
INSERT INTO ts_photo VALUES ('8','11','2','4','1','DSC02445','1254733256','1254733256','','0','0','20091005/17/4ac9b5c8611d0.JPG','611781','1','','0','0');
INSERT INTO ts_photo VALUES ('9','12','2','4','1','DSC02446','1254733294','1254733294','','0','0','20091005/17/4ac9b5ee53a93.JPG','795241','1','','0','0');
INSERT INTO ts_photo VALUES ('10','13','2','4','1','DSC02447','1254733333','1254733333','','0','0','20091005/17/4ac9b61549c0f.JPG','836491','1','','0','0');
INSERT INTO ts_photo VALUES ('11','14','2','4','1','DSC02448','1254733371','1254733371','','0','0','20091005/17/4ac9b63b5ab90.JPG','815370','1','','0','0');
INSERT INTO ts_photo VALUES ('12','15','2','4','1','DSC02451','1254733412','1254733412','','0','0','20091005/17/4ac9b66429849.JPG','841104','1','','0','0');
INSERT INTO ts_photo VALUES ('13','16','2','4','1','DSC02453','1254733452','1254733452','','0','0','20091005/17/4ac9b68cd97f1.JPG','813241','1','','0','0');
INSERT INTO ts_photo VALUES ('14','17','2','4','1','DSC02454','1254733494','1254733494','','0','0','20091005/17/4ac9b6b6b87a0.JPG','854853','1','','0','0');
INSERT INTO ts_photo VALUES ('15','18','2','4','1','DSC02455','1254733541','1254733541','','0','0','20091005/17/4ac9b6e5558f7.JPG','820526','1','','0','0');

 drop table if exists ts_photo_album;
CREATE TABLE `ts_photo_album` (
  `id` int(11) NOT NULL auto_increment,
  `userId` int(11) default NULL,
  `name` varchar(255) default NULL,
  `info` text,
  `cTime` int(11) unsigned default NULL,
  `mTime` int(11) unsigned default NULL,
  `coverImageId` int(11) NOT NULL,
  `coverImagePath` varchar(255) default NULL,
  `photoCount` int(11) default '0',
  `status` tinyint(2) unsigned NOT NULL default '1',
  `share` tinyint(1) NOT NULL default '0',
  `privacy` tinyint(1) NOT NULL,
  `privacy_data` text NOT NULL,
  `isDel` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `uid` (`userId`),
  KEY `cTime` (`cTime`),
  KEY `mTime` (`mTime`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

INSERT INTO ts_photo_album VALUES ('1','2','我的相册','','1254731426','1254731426','0','','1','1','0','1','','0');
INSERT INTO ts_photo_album VALUES ('2','4','我的相册','','1254732943','1254733551','15','20091005/17/4ac9b6e5558f7.JPG','14','1','0','1','','0');
INSERT INTO ts_photo_album VALUES ('3','3','我的相册','','1254734021','1254734021','0','','0','1','0','1','','0');
INSERT INTO ts_photo_album VALUES ('4','1','我的相册','','1254735543','1254735543','0','','0','1','0','1','','0');

 drop table if exists ts_photo_index;
CREATE TABLE `ts_photo_index` (
  `albumId` int(11) NOT NULL,
  `photoId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  `privacy` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`albumId`,`photoId`),
  UNIQUE KEY `album_photo` (`albumId`,`photoId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_photo_mark;
CREATE TABLE `ts_photo_mark` (
  `photoId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `userName` varchar(50) NOT NULL,
  `markedUserId` int(11) NOT NULL,
  `x` varchar(100) NOT NULL,
  `y` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_privacy;
CREATE TABLE `ts_privacy` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `privacy` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_privacy_index;
CREATE TABLE `ts_privacy_index` (
  `id` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL,
  `visitor` int(11) NOT NULL,
  `level` tinyint(1) NOT NULL default '1',
  `black` tinyint(1) NOT NULL default '0',
  `white` tinyint(1) NOT NULL default '0',
  `type` varchar(20) NOT NULL default 'space',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_report;
CREATE TABLE `ts_report` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `info` varchar(255) default NULL,
  `cTime` int(11) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `con` varchar(255) NOT NULL,
  `appid` int(11) NOT NULL,
  `recordId` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_saveemail;
CREATE TABLE `ts_saveemail` (
  `id` mediumint(10) unsigned NOT NULL auto_increment,
  `toemail` varchar(255) NOT NULL,
  `title` text NOT NULL,
  `content` text,
  `uid` int(11) NOT NULL,
  `username` varchar(36) NOT NULL,
  `senderemail` varchar(255) default NULL,
  `status` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_share;
CREATE TABLE `ts_share` (
  `id` int(11) NOT NULL auto_increment,
  `typeId` int(11) NOT NULL,
  `toUid` int(11) NOT NULL,
  `toUserName` varchar(50) default NULL,
  `fromUid` int(11) default NULL,
  `fromUserName` varchar(50) default NULL,
  `aimId` int(11) default NULL,
  `url` varchar(255) default NULL,
  `title` text,
  `info` text NOT NULL,
  `data` text NOT NULL,
  `cTime` int(11) NOT NULL,
  `purview` tinyint(4) default '0',
  `viewNum` int(11) default '0',
  `comNum` int(11) default '0',
  `isDel` tinyint(1) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_share_set;
CREATE TABLE `ts_share_set` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `typeIds` text,
  `uids` text,
  `passive` tinyint(1) default '0',
  `uid_type` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_share_type;
CREATE TABLE `ts_share_type` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(20) NOT NULL,
  `alias` varchar(20) NOT NULL,
  `sort` tinyint(4) default NULL,
  `state` tinyint(4) default '0',
  `temp_list` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

INSERT INTO ts_share_type VALUES ('1','网址','url','2','0','<div class=\"lh30\"><a href=\'{url}\' target=\'_blank\'>{title}</a></div>\r\n\r\n<div class=\"lh30\"><a href=\'{url}\' target=\'_blank\'>{url}</a></div>');
INSERT INTO ts_share_type VALUES ('2','视频','video','1','0','<div class=\"playbutton lh30\" id=\"{id}\" rel=\"{url}\" onclick=\"playflash(this);\"><img class=\"hand\" src=\"{SITE_URL}/apps/share/Tpl/default/Public/images/video_img.gif\" style=\"cursor:pointer\"/></div> <p id=\"flash_video_{id}\"></p> \r\n\r\n<a class=\"video-close-link\" id=\"video_close_{id}\" href=\"javascript:void(0)\" onClick=\"video_close(this)\" rel=\"{id}\" style=\"display:none\">收起</a>');
INSERT INTO ts_share_type VALUES ('3','音乐','music','4','0','<img class=\"hand\" src=\"{SITE_URL}/apps/share/Tpl/default/Public/images/music.gif\" alt=\"音乐\" onclick=\"javascript:playmusic(\'{url}\', this, \'{id}\');\" /> <p id=\"flash_mp3_{id}\"></p> <a class=\"video-close-link\" id=\"mp3_close_{id}\" href=\"javascript:void(0)\" onClick=\"mp3_close(this)\" rel=\"{id}\" style=\"display:none\">收起</a>');
INSERT INTO ts_share_type VALUES ('4','flash','flash','5','0','<div class=\"playbutton\" id=\"{id}\" rel=\"{url}\" onclick=\"playflash(this);\"><img class=\"hand\" src=\"{SITE_URL}/apps/share/Tpl/default/Public/images/video_img.gif\" /></div> \r\n\r\n<p id=\"flash_video_{id}\"></p> <a class=\"video-close-link\" id=\"video_close_{id}\" href=\"javascript:void(0)\" onClick=\"video_close(this)\" rel=\"{id}\" style=\"display:none\">收起</a>');
INSERT INTO ts_share_type VALUES ('5','日志','blog','6','0','<div class=\"lh30 fB\"><a href=\'{SITE_URL}/apps/Share/index.php?s=/Index/content/id/{id}\'>{title}</a></div> \r\n<div class=\"lh20\">来自:<a href=\'{SITE_URL}/index.php?s=/space/{uid}\'>{name}</a></div>\r\n<div class=\"lh20 cGray2\">{intro}</div>');
INSERT INTO ts_share_type VALUES ('6','相册','album','13','0','<div style=\"clear: both;padding:5px 0;\">\r\n<div class=\"left\" style=\"width:125px; padding-top:10px;\">\r\n   <span>\r\n      <a href=\"{SITE_URL}/apps/photo/index.php?s=/Index/album/id/{aimId}/uid/{userId}\">\r\n	     <img src=\"{cover}\" />\r\n	  </a> \r\n   </span>\r\n</div>\r\n<div class=\"left\" style=\"width:400px; padding-top:10px; line-height:25px;\">\r\n   <div>相册： \r\n      <a href=\"{SITE_URL}/apps/photo/index.php?s=/Index/album/id/{aimId}/uid/{userId}\">{name}</a>\r\n   </div>\r\n   <div>来自： <a href=\"{SITE_URL}/index.php?s=/space/{userId}\">{username}</a></div>\r\n   <div>{info}</div>\r\n</div>\r\n<div style=\"clear: both;\"></div>\r\n</div>');
INSERT INTO ts_share_type VALUES ('7','相片','photo','12','0','<div style=\"clear: both;padding:5px 0;\">\r\n<div class=\"left\" style=\"width:125px; padding-top:10px;\">\r\n   <span>\r\n      <a href=\"{SITE_URL}/apps/photo/index.php?s=/Index/photo/id/{aimId}/aid/{albumId}/uid/{userId}/type/mAll\">\r\n	     <img src=\"{SITE_URL}/thumb.php?w=120&h=100&url={photo}\" />\r\n	  </a> \r\n   </span>\r\n</div>\r\n<div class=\"left\" style=\"width:400px; padding-top:10px; line-height:25px;\">\r\n   <div>相片： \r\n      <a href=\"{SITE_URL}/apps/photo/index.php?s=/Index/photo/id/{aimId}/aid/{albumId}/uid/{userId}/type/mAll\">{name}</a>\r\n   </div>\r\n   <div>相册： \r\n      <a href=\"{SITE_URL}/apps/photo/index.php?s=/Index/album/id/{albumId}/uid/{userId}\">{albumName}</a>\r\n   </div>\r\n   <div>来自： <a href=\"{SITE_URL}/index.php?s=/space/{userId}\">{username}</a></div>\r\n   <div>{info}</div>\r\n</div>\r\n<div style=\"clear: both;\"></div>\r\n</div>');
INSERT INTO ts_share_type VALUES ('8','群组','group','11','0','<div class=\"clear: both;padding:5px 0;display:table;\"><div class=\"left\" style=\"width:70px; padding-top:10px;\"><span class=\"headpic50\"><a href=\"{SITE_URL}/apps/group/index.php?s=/Group/index/gid/{aimId}\"><img src=\"{SITE_URL}/thumb.php?w=100&h=100&url={logo}\" /></a> </span>\r\n</div>\r\n<div class=\"left\" style=\"width:480px; padding-top:10px;\"><div><a href=\"{SITE_URL}/apps/group/index.php?s=/Group/index/gid/{aimId}\">{name}</a></div>\r\n\r\n<div class=\"lh20\">{catagory}</div></div></div>\r\n<div class=\"lh20\">现有 {membercount} 名成员</div>');
INSERT INTO ts_share_type VALUES ('9','话题','topic','10','0','<div class=\"lh30 fB\"><a href=\"{SITE_URL}/apps/group/index.php?s=/Topic/topic/gid/{gid}/tid/{aimId}\">{title}</a></div>\r\n\r\n<div class=\"lh20\"><a href=\'{SITE_URL}/index.php?s=/space/{uid}\'>{name}</a></div>\r\n<div class=\"lh20\">群组: <a href=\"{SITE_URL}/apps/group/index.php?s=/Group/index/gid/{gid}\">{groupName}</a></div>\r\n<div class=\"lh20 cGray2\">{intro}</div>');
INSERT INTO ts_share_type VALUES ('10','用户','user','9','0','<div class=\"clear: both;padding:5px 0;display:table;\"><div class=\"left\" style=\"width:70px; padding-top:10px;\"><span class=\"headpic50\"><a href=\"{SITE_URL}/index.php?s=/space/{uid}\"><img src=\"{userface}\" /></a> </span>\r\n</div>\r\n<div class=\"left\" style=\"width:480px; padding-top:10px;\"><div><a href=\"{SITE_URL}/index.php?s=/space/{uid}\"><strong>{username}</strong></a></div>\r\n\r\n<div class=\"lh20\">{mini}</div></div></div>');
INSERT INTO ts_share_type VALUES ('12','活动','event','8','0','<div class=\"lh30\"><a href=\"{url}\">{title}</a> 发起人:<a href=\'{SITE_URL}/index.php?s=/space/{uid}\'>{name}</a></div>');
INSERT INTO ts_share_type VALUES ('13','投票','vote','7','0','<div class=\"lh30\"><a href=\'{SITE_URL}/apps/vote/index.php?s=/Index/pollDetail/id/{aimId}\' target=\'_blank\'>{title}</a></div>\r\n\r\n<div class=\"lh30\">来自:<a href=\'{SITE_URL}/index.php?s=/space/{uid}\'>{name}</a></div>\r\n');
INSERT INTO ts_share_type VALUES ('14','图片','picture','3','0','<div class=\"lh20\"><a href=\'{SITE_URL}/apps/Share/index.php?s=/Index/content/id/{id}\'><img src=\'{SITE_URL}/thumb.php?w=120&h=100&t=f&url={url}\' /></a></div>');

 drop table if exists ts_site;
CREATE TABLE `ts_site` (
  `name` varchar(255) default NULL,
  `url` varchar(255) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO ts_site VALUES ('ThinkSNS','http://www.thinksns.com');

 drop table if exists ts_smile;
CREATE TABLE `ts_smile` (
  `id` int(3) NOT NULL auto_increment,
  `type` varchar(10) default NULL,
  `emotion` varchar(10) default NULL,
  `filename` varchar(20) NOT NULL,
  `title` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=158 DEFAULT CHARSET=utf8;

INSERT INTO ts_smile VALUES ('7','mini','/拥抱','hug.gif','拥抱');
INSERT INTO ts_smile VALUES ('8','mini','/示爱','kiss.gif','示爱');
INSERT INTO ts_smile VALUES ('9','mini','/呲牙','lol.gif','呲牙');
INSERT INTO ts_smile VALUES ('10','mini','/可爱','loveliness.gif','可爱');
INSERT INTO ts_smile VALUES ('11','mini','/折磨','mad.gif','折磨');
INSERT INTO ts_smile VALUES ('13','mini','/难过','sad.gif','难过');
INSERT INTO ts_smile VALUES ('18','mini','/流汗','sweat.gif','流汗');
INSERT INTO ts_smile VALUES ('100','mini','/憨笑','biggrin.gif','憨笑');
INSERT INTO ts_smile VALUES ('102','mini','/大哭','cry.gif','大哭');
INSERT INTO ts_smile VALUES ('103','mini','/惊恐','funk.gif','惊恐');
INSERT INTO ts_smile VALUES ('104','mini','/握手','handshake.gif','握手');
INSERT INTO ts_smile VALUES ('105','mini','/发怒','huffy.gif','发怒');
INSERT INTO ts_smile VALUES ('107','mini','/惊讶','shocked.gif','惊讶');
INSERT INTO ts_smile VALUES ('108','mini','/害羞','shy.gif','害羞');
INSERT INTO ts_smile VALUES ('109','mini','/微笑','smile.gif','微笑');
INSERT INTO ts_smile VALUES ('112','mini','/偷笑','titter.gif','偷笑');
INSERT INTO ts_smile VALUES ('113','mini','/调皮','tongue.gif','调皮');
INSERT INTO ts_smile VALUES ('114','mini','/胜利','victory.gif','胜利');
INSERT INTO ts_smile VALUES ('133','123','12312','chang.gif','表情0');
INSERT INTO ts_smile VALUES ('134','123','dddd','hou.gif','表情1');
INSERT INTO ts_smile VALUES ('135','taobao','01','613145192005.gif','表情0');
INSERT INTO ts_smile VALUES ('136','taobao','02','613154192005.gif','表情1');
INSERT INTO ts_smile VALUES ('137','taobao','03','61316192005.gif','表情2');
INSERT INTO ts_smile VALUES ('138','taobao','04','613167192005.gif','表情3');
INSERT INTO ts_smile VALUES ('139','taobao','05','613176192005.gif','表情4');
INSERT INTO ts_smile VALUES ('140','taobao','06','613184192005.gif','表情5');
INSERT INTO ts_smile VALUES ('141','taobao','07','613185192005.gif','表情6');
INSERT INTO ts_smile VALUES ('142','taobao','08','613187192005.gif','表情7');
INSERT INTO ts_smile VALUES ('143','taobao','09','613206192005.gif','表情8');
INSERT INTO ts_smile VALUES ('144','taobao','10','613207192005.gif','表情9');
INSERT INTO ts_smile VALUES ('145','taobao','11','613226192005.gif','表情10');
INSERT INTO ts_smile VALUES ('146','taobao','12','613304192005.gif','表情11');
INSERT INTO ts_smile VALUES ('147','taobao','13','613324192005.gif','表情12');
INSERT INTO ts_smile VALUES ('148','taobao','14','613336192005.gif','表情13');
INSERT INTO ts_smile VALUES ('149','taobao','15','613354192005.gif','表情14');
INSERT INTO ts_smile VALUES ('150','taobao','16','613374192005.gif','表情15');
INSERT INTO ts_smile VALUES ('151','taobao','17','613376192005.gif','表情16');
INSERT INTO ts_smile VALUES ('152','taobao','18','613396192005.gif','表情17');
INSERT INTO ts_smile VALUES ('153','taobao','19','613424192005.gif','表情18');
INSERT INTO ts_smile VALUES ('154','taobao','20','613454192005.gif','表情19');
INSERT INTO ts_smile VALUES ('155','taobao','21','613474192005.gif','表情20');
INSERT INTO ts_smile VALUES ('156','taobao','22','613475192005.gif','表情21');
INSERT INTO ts_smile VALUES ('157','taobao','23','613495192005.gif','表情22');

 drop table if exists ts_space;
CREATE TABLE `ts_space` (
  `value` varchar(255) NOT NULL,
  `variable` varchar(30) NOT NULL,
  `uid` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `appname` varchar(30) NOT NULL,
  KEY `appname` (`appname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO ts_space VALUES ('1','count','2','','photo');
INSERT INTO ts_space VALUES ('1','count','4','','mini');
INSERT INTO ts_space VALUES ('14','count','4','','photo');

 drop table if exists ts_system_group;
CREATE TABLE `ts_system_group` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(150) NOT NULL,
  `showname` varchar(150) NOT NULL,
  `icon` varchar(150) NOT NULL,
  `type` varchar(150) NOT NULL,
  `description` varchar(250) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

INSERT INTO ts_system_group VALUES ('1','系统管理员','ts官方','admin.png','admin','系统管理员');
INSERT INTO ts_system_group VALUES ('2','信息管理员','信息管理员','admin2.gif','admin','信息管理员');
INSERT INTO ts_system_group VALUES ('3','合作伙伴','合作伙伴','hzhb.png','web','合作伙伴');
INSERT INTO ts_system_group VALUES ('4','TS Builder','TS Builder','Builder.png','web','TSBuilder');
INSERT INTO ts_system_group VALUES ('5','热心会员','热心会员','hotvip.png','web','热心会员');
INSERT INTO ts_system_group VALUES ('6','网站管理员','官方成员','','admin','网站管理员');

 drop table if exists ts_system_node;
CREATE TABLE `ts_system_node` (
  `id` int(11) NOT NULL auto_increment,
  `pid` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `title` varchar(150) NOT NULL,
  `containaction` longtext NOT NULL,
  `description` varchar(255) NOT NULL,
  `ordernum` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `state` tinyint(3) NOT NULL,
  `type` varchar(100) default '',
  `selected` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=88 DEFAULT CHARSET=utf8;

INSERT INTO ts_system_node VALUES ('1','0','initial','首页','','后台菜单首页','0','1','1','admin','1');
INSERT INTO ts_system_node VALUES ('2','0','user','用户','','用户管理','0','1','1','admin','0');
INSERT INTO ts_system_node VALUES ('3','0','system','系统','','系统','0','1','1','admin','0');
INSERT INTO ts_system_node VALUES ('4','0','application','应用','','应用','0','1','1','admin','0');
INSERT INTO ts_system_node VALUES ('5','0','expert','高级','','高级配置','0','1','1','admin','0');
INSERT INTO ts_system_node VALUES ('7','2','User','用户管理','','test','0','2','1','admin','0');
INSERT INTO ts_system_node VALUES ('8','7','index','用户列表','a:4:{i:0;a:2:{s:5:\"title\";s:12:\"用户列表\";s:4:\"name\";s:5:\"index\";}i:1;a:2:{s:5:\"title\";s:12:\"推荐用户\";s:4:\"name\";s:7:\"commend\";}i:2;a:2:{s:5:\"title\";s:6:\"修改\";s:4:\"name\";s:4:\"edit\";}i:3;a:2:{s:5:\"title\";s:12:\"修改操作\";s:4:\"name\";s:6:\"doedit\";}}','用户列表','0','3','1','admin','0');
INSERT INTO ts_system_node VALUES ('9','3','System','系统管理','','系统管理','0','2','1','admin','0');
INSERT INTO ts_system_node VALUES ('10','9','index','站点设置','','站点设置','0','3','1','admin','0');
INSERT INTO ts_system_node VALUES ('11','9','privacy','隐私设置','','隐私设置','0','3','1','admin','0');
INSERT INTO ts_system_node VALUES ('12','9','reg','注册配置','','注册配置','0','3','1','admin','0');
INSERT INTO ts_system_node VALUES ('14','9','feed','动态','','动态','0','3','1','admin','0');
INSERT INTO ts_system_node VALUES ('17','9','ad_list','广告管理','','广告管理','0','3','1','admin','0');
INSERT INTO ts_system_node VALUES ('18','9','links_list','友情链接','','友情链接','0','3','1','admin','0');
INSERT INTO ts_system_node VALUES ('20','9','auditing','审核设置','','审核设置','0','3','1','admin','0');
INSERT INTO ts_system_node VALUES ('21','9','gonggao','公告设置','','公告设置','0','3','1','admin','0');
INSERT INTO ts_system_node VALUES ('22','9','report','举报管理','','举报管理','0','3','1','admin','0');
INSERT INTO ts_system_node VALUES ('23','2','Popedom','权限管理','','权限管理','0','2','1','admin','0');
INSERT INTO ts_system_node VALUES ('24','23','node','节点管理','','节点管理','0','3','1','admin','0');
INSERT INTO ts_system_node VALUES ('25','23','group','用户组管理','','用户组管理','0','3','1','admin','0');
INSERT INTO ts_system_node VALUES ('26','4','AppManage','应用管理','','应用管理','0','2','1','admin','0');
INSERT INTO ts_system_node VALUES ('27','4','Apps','应用配置','','各类应用配置','0','2','1','admin','0');
INSERT INTO ts_system_node VALUES ('28','5','Templet','模板管理','','模板管理','0','2','1','admin','0');
INSERT INTO ts_system_node VALUES ('29','28','notify','通知模板','','通知模板','0','3','1','admin','0');
INSERT INTO ts_system_node VALUES ('30','28','feed','动态模板','','动态模板','0','3','1','admin','0');
INSERT INTO ts_system_node VALUES ('31','1','Home','首页管理','','首页管理','0','2','1','admin','0');
INSERT INTO ts_system_node VALUES ('32','31','index','首页','a:1:{i:0;a:2:{s:5:\"title\";s:6:\"首页\";s:4:\"name\";s:5:\"index\";}}','首页','0','3','1','admin','0');
INSERT INTO ts_system_node VALUES ('37','5','Expert','高级配置','','高级配置','0','2','1','admin','0');
INSERT INTO ts_system_node VALUES ('38','37','wordfilter','词汇过滤','','词汇过滤','0','3','1','admin','0');
INSERT INTO ts_system_node VALUES ('39','26','index','所有的应用','','所有的应用','0','3','1','admin','0');
INSERT INTO ts_system_node VALUES ('40','26','defapp','默认的应用','','默认的应用','0','3','1','admin','0');
INSERT INTO ts_system_node VALUES ('41','26','choice','可选的应用','','可选的应用','0','3','1','admin','0');
INSERT INTO ts_system_node VALUES ('42','26','close','关闭的应用','','关闭的应用','0','3','1','admin','0');
INSERT INTO ts_system_node VALUES ('43','26','add','添加应用','','添加应用','0','3','1','admin','0');
INSERT INTO ts_system_node VALUES ('44','23','commision','权限管理','','权限管理','0','3','1','admin','0');
INSERT INTO ts_system_node VALUES ('45','9','email','邮件管理','','对激活邮件的管理','0','3','1','admin','0');
INSERT INTO ts_system_node VALUES ('46','9','Invite','邀请设置','','邀请设置','0','3','1','admin','0');
INSERT INTO ts_system_node VALUES ('47','3','info','信息发布管理','','信息发布管理','0','2','1','admin','0');
INSERT INTO ts_system_node VALUES ('48','47','index','文章列表','','文章列表','0','3','1','admin','0');
INSERT INTO ts_system_node VALUES ('49','47','addCategory','添加文章分类','','添加文章分类','0','3','1','admin','0');
INSERT INTO ts_system_node VALUES ('50','47','addContent','添加文章','','添加文章','0','3','1','admin','0');
INSERT INTO ts_system_node VALUES ('51','9','friend','好友管理','','好友管理','0','3','1','admin','0');
INSERT INTO ts_system_node VALUES ('54','0','THINKSNS','官方默认','','官方默认','0','1','1','guest','0');
INSERT INTO ts_system_node VALUES ('55','0','blog','博客APP','','博客APP','0','1','1','guest','0');
INSERT INTO ts_system_node VALUES ('56','0','photo','相册APP','','','0','1','1','guest','0');
INSERT INTO ts_system_node VALUES ('57','54','public','公共模块','','公共模块','0','2','1','guest','0');
INSERT INTO ts_system_node VALUES ('58','54','index','首页','','首页','0','2','1','guest','0');
INSERT INTO ts_system_node VALUES ('59','58','login','登陆','a:2:{i:0;a:2:{s:5:\"title\";s:6:\"登陆\";s:4:\"name\";s:5:\"login\";}i:1;a:2:{s:5:\"title\";s:12:\"登陆操作\";s:4:\"name\";s:5:\"login\";}}','登陆','0','3','1','guest','0');
INSERT INTO ts_system_node VALUES ('60','58','index','首页','a:1:{i:0;a:2:{s:5:\"title\";s:6:\"首页\";s:4:\"name\";s:5:\"index\";}}','首页','0','3','1','guest','0');
INSERT INTO ts_system_node VALUES ('61','58','getPass','获取密码','a:1:{i:0;a:2:{s:5:\"title\";s:12:\"获取密码\";s:4:\"name\";s:7:\"getPass\";}}','获取密码','0','3','1','guest','0');
INSERT INTO ts_system_node VALUES ('62','58','resetPass','复改密码','a:2:{i:0;a:2:{s:5:\"title\";s:12:\"复改密码\";s:4:\"name\";s:9:\"resetPass\";}i:1;a:2:{s:5:\"title\";s:18:\"重置密码操作\";s:4:\"name\";s:11:\"doResetPass\";}}','复改密码','0','3','1','guest','0');
INSERT INTO ts_system_node VALUES ('63','58','reg','注册','a:2:{i:0;a:2:{s:5:\"title\";s:6:\"注册\";s:4:\"name\";s:3:\"reg\";}i:1;a:2:{s:5:\"title\";s:12:\"注册操作\";s:4:\"name\";s:5:\"doReg\";}}','注册','0','3','1','guest','0');
INSERT INTO ts_system_node VALUES ('64','0','blog','日志','','日志','0','1','1','apps','0');
INSERT INTO ts_system_node VALUES ('66','0','share','分享','','分享','0','1','1','apps','0');
INSERT INTO ts_system_node VALUES ('68','0','photo','相册','','','0','1','1','apps','0');
INSERT INTO ts_system_node VALUES ('77','64','index','首页','a:1:{i:0;a:2:{s:5:\"title\";s:6:\"首页\";s:4:\"name\";s:5:\"index\";}}','首页','0','2','1','apps','0');
INSERT INTO ts_system_node VALUES ('78','66','index','首页','a:1:{i:0;a:2:{s:5:\"title\";s:6:\"首页\";s:4:\"name\";s:5:\"index\";}}','首页','0','2','1','apps','0');
INSERT INTO ts_system_node VALUES ('79','68','index','首页','a:1:{i:0;a:2:{s:5:\"title\";s:6:\"首页\";s:4:\"name\";s:5:\"index\";}}','首页','0','2','1','apps','0');
INSERT INTO ts_system_node VALUES ('80','37','cnzz','cnzz站长统计','a:1:{i:0;a:2:{s:5:\"title\";s:16:\"cnzz站长统计\";s:4:\"name\";s:4:\"cnzz\";}}','cnzz站长统计','0','3','1','admin','0');
INSERT INTO ts_system_node VALUES ('82','9','scores','积分规则','a:1:{i:0;a:2:{s:5:\"title\";s:12:\"积分规则\";s:4:\"name\";s:6:\"scores\";}}','积分规则','0','3','1','admin','0');
INSERT INTO ts_system_node VALUES ('83','37','backup','数据备份','a:1:{i:0;a:2:{s:5:\"title\";s:12:\"数据备份\";s:4:\"name\";s:6:\"backup\";}}','数据备份','0','3','1','admin','0');
INSERT INTO ts_system_node VALUES ('84','23','rank','用户等级管理','a:1:{i:0;a:2:{s:5:\"title\";s:18:\"用户等级管理\";s:4:\"name\";s:4:\"rank\";}}','用户等级管理','0','3','1','admin','0');
INSERT INTO ts_system_node VALUES ('85','7','msg','消息群发','a:1:{i:0;a:2:{s:5:\"title\";s:12:\"消息群发\";s:4:\"name\";s:3:\"msg\";}}','消息群发','0','3','1','admin','0');
INSERT INTO ts_system_node VALUES ('86','7','userscore','批量用户积分','a:1:{i:0;a:2:{s:5:\"title\";s:18:\"批量用户积分\";s:4:\"name\";s:9:\"userscore\";}}','批量用户积分','0','3','1','admin','0');
INSERT INTO ts_system_node VALUES ('87','9','ico','表情管理','a:1:{i:0;a:2:{s:5:\"title\";s:12:\"表情管理\";s:4:\"name\";s:3:\"ico\";}}','表情管理','0','3','1','admin','0');

 drop table if exists ts_system_node_copy;
CREATE TABLE `ts_system_node_copy` (
  `id` int(11) NOT NULL auto_increment,
  `pid` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `title` varchar(150) NOT NULL,
  `containaction` longtext NOT NULL,
  `description` varchar(255) NOT NULL,
  `ordernum` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `state` tinyint(3) NOT NULL,
  `type` varchar(100) default '',
  `selected` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=84 DEFAULT CHARSET=utf8;

INSERT INTO ts_system_node_copy VALUES ('1','0','initial','首页','','后台菜单首页','0','1','1','admin','1');
INSERT INTO ts_system_node_copy VALUES ('2','0','user','用户','','用户管理','0','1','1','admin','0');
INSERT INTO ts_system_node_copy VALUES ('3','0','system','系统','','系统','0','1','1','admin','0');
INSERT INTO ts_system_node_copy VALUES ('4','0','application','应用','','应用','0','1','1','admin','0');
INSERT INTO ts_system_node_copy VALUES ('5','0','expert','高级','','高级配置','0','1','1','admin','0');
INSERT INTO ts_system_node_copy VALUES ('7','2','User','用户管理','','test','0','2','1','admin','0');
INSERT INTO ts_system_node_copy VALUES ('8','7','index','用户列表','a:4:{i:0;a:2:{s:5:\"title\";s:12:\"用户列表\";s:4:\"name\";s:5:\"index\";}i:1;a:2:{s:5:\"title\";s:12:\"推荐用户\";s:4:\"name\";s:7:\"commend\";}i:2;a:2:{s:5:\"title\";s:6:\"修改\";s:4:\"name\";s:4:\"edit\";}i:3;a:2:{s:5:\"title\";s:12:\"修改操作\";s:4:\"name\";s:6:\"doedit\";}}','用户列表','0','3','1','admin','0');
INSERT INTO ts_system_node_copy VALUES ('9','3','System','系统管理','','系统管理','0','2','1','admin','0');
INSERT INTO ts_system_node_copy VALUES ('10','9','index','站点设置','','站点设置','0','3','1','admin','0');
INSERT INTO ts_system_node_copy VALUES ('11','9','privacy','隐私设置','','隐私设置','0','3','1','admin','0');
INSERT INTO ts_system_node_copy VALUES ('12','9','reg','注册配置','','注册配置','0','3','1','admin','0');
INSERT INTO ts_system_node_copy VALUES ('14','9','feed','动态','','动态','0','3','1','admin','0');
INSERT INTO ts_system_node_copy VALUES ('15','9','feed','通知','','通知','0','3','1','admin','0');
INSERT INTO ts_system_node_copy VALUES ('17','9','ad_list','广告管理','','广告管理','0','3','1','admin','0');
INSERT INTO ts_system_node_copy VALUES ('18','9','links_list','友情链接','','友情链接','0','3','1','admin','0');
INSERT INTO ts_system_node_copy VALUES ('19','9','config','表情','','表情','0','3','1','admin','0');
INSERT INTO ts_system_node_copy VALUES ('20','9','auditing','审核设置','','审核设置','0','3','1','admin','0');
INSERT INTO ts_system_node_copy VALUES ('21','9','gonggao','公告设置','','公告设置','0','3','1','admin','0');
INSERT INTO ts_system_node_copy VALUES ('22','9','report','举报管理','','举报管理','0','3','1','admin','0');
INSERT INTO ts_system_node_copy VALUES ('23','2','Popedom','权限管理','','权限管理','0','2','1','admin','0');
INSERT INTO ts_system_node_copy VALUES ('24','23','node','节点管理','','节点管理','0','3','1','admin','0');
INSERT INTO ts_system_node_copy VALUES ('25','23','group','用户组管理','','用户组管理','0','3','1','admin','0');
INSERT INTO ts_system_node_copy VALUES ('26','4','AppManage','应用管理','','应用管理','0','2','1','admin','0');
INSERT INTO ts_system_node_copy VALUES ('27','4','Apps','各类应用','','各类应用','0','2','1','admin','0');
INSERT INTO ts_system_node_copy VALUES ('28','5','Templet','模板管理','','模板管理','0','2','1','admin','0');
INSERT INTO ts_system_node_copy VALUES ('29','28','notify','通知模板','','通知模板','0','3','1','admin','0');
INSERT INTO ts_system_node_copy VALUES ('30','28','feed','动态模板','','动态模板','0','3','1','admin','0');
INSERT INTO ts_system_node_copy VALUES ('31','1','Home','首页管理','','首页管理','0','2','1','admin','0');
INSERT INTO ts_system_node_copy VALUES ('32','31','index','列表示例页面','a:1:{i:0;a:2:{s:5:\"title\";s:18:\"列表示例页面\";s:4:\"name\";s:5:\"index\";}}','列表示例页面','0','3','1','admin','0');
INSERT INTO ts_system_node_copy VALUES ('37','5','Expert','高级配置','','高级配置','0','2','1','admin','0');
INSERT INTO ts_system_node_copy VALUES ('38','37','wordfilter','词汇过滤','','词汇过滤','0','3','1','admin','0');
INSERT INTO ts_system_node_copy VALUES ('39','26','index','所有的应用','','所有的应用','0','3','1','admin','0');
INSERT INTO ts_system_node_copy VALUES ('40','26','defapp','默认的应用','','默认的应用','0','3','1','admin','0');
INSERT INTO ts_system_node_copy VALUES ('41','26','choice','可选的应用','','可选的应用','0','3','1','admin','0');
INSERT INTO ts_system_node_copy VALUES ('42','26','close','关闭的应用','','关闭的应用','0','3','1','admin','0');
INSERT INTO ts_system_node_copy VALUES ('43','26','add','添加应用','','添加应用','0','3','1','admin','0');
INSERT INTO ts_system_node_copy VALUES ('44','23','commision','权限管理','','权限管理','0','3','1','admin','0');
INSERT INTO ts_system_node_copy VALUES ('45','9','email','邮件管理','','对激活邮件的管理','0','3','1','admin','0');
INSERT INTO ts_system_node_copy VALUES ('46','9','Invite','邀请设置','','邀请设置','0','3','1','admin','0');
INSERT INTO ts_system_node_copy VALUES ('47','3','info','信息发布管理','','信息发布管理','0','2','1','admin','0');
INSERT INTO ts_system_node_copy VALUES ('48','47','index','文章列表','','文章列表','0','3','1','admin','0');
INSERT INTO ts_system_node_copy VALUES ('49','47','addCategory','添加文章分类','','添加文章分类','0','3','1','admin','0');
INSERT INTO ts_system_node_copy VALUES ('50','47','addContent','添加文章','','添加文章','0','3','1','admin','0');
INSERT INTO ts_system_node_copy VALUES ('51','9','friend','好友管理','','好友管理','0','3','1','admin','0');
INSERT INTO ts_system_node_copy VALUES ('54','0','THINKSNS','官方默认','','官方默认','0','1','1','guest','0');
INSERT INTO ts_system_node_copy VALUES ('55','0','blog','博客APP','','博客APP','0','1','1','guest','0');
INSERT INTO ts_system_node_copy VALUES ('56','0','photo','相册APP','','','0','1','1','guest','0');
INSERT INTO ts_system_node_copy VALUES ('57','54','public','公共模块','','公共模块','0','2','1','guest','0');
INSERT INTO ts_system_node_copy VALUES ('58','54','index','首页','','首页','0','2','1','guest','0');
INSERT INTO ts_system_node_copy VALUES ('59','58','login','登陆','a:2:{i:0;a:2:{s:5:\"title\";s:6:\"登陆\";s:4:\"name\";s:5:\"login\";}i:1;a:2:{s:5:\"title\";s:12:\"登陆操作\";s:4:\"name\";s:5:\"login\";}}','登陆','0','3','1','guest','0');
INSERT INTO ts_system_node_copy VALUES ('60','58','index','首页','a:1:{i:0;a:2:{s:5:\"title\";s:6:\"首页\";s:4:\"name\";s:5:\"index\";}}','首页','0','3','1','guest','0');
INSERT INTO ts_system_node_copy VALUES ('61','58','getPass','获取密码','a:1:{i:0;a:2:{s:5:\"title\";s:12:\"获取密码\";s:4:\"name\";s:7:\"getPass\";}}','获取密码','0','3','1','guest','0');
INSERT INTO ts_system_node_copy VALUES ('62','58','resetPass','复改密码','a:2:{i:0;a:2:{s:5:\"title\";s:12:\"复改密码\";s:4:\"name\";s:9:\"resetPass\";}i:1;a:2:{s:5:\"title\";s:18:\"重置密码操作\";s:4:\"name\";s:11:\"doResetPass\";}}','复改密码','0','3','1','guest','0');
INSERT INTO ts_system_node_copy VALUES ('63','58','reg','注册','a:2:{i:0;a:2:{s:5:\"title\";s:6:\"注册\";s:4:\"name\";s:3:\"reg\";}i:1;a:2:{s:5:\"title\";s:12:\"注册操作\";s:4:\"name\";s:5:\"doReg\";}}','注册','0','3','1','guest','0');
INSERT INTO ts_system_node_copy VALUES ('64','0','blog','日志','','日志','0','1','1','apps','0');
INSERT INTO ts_system_node_copy VALUES ('66','0','share','分享','','分享','0','1','1','apps','0');
INSERT INTO ts_system_node_copy VALUES ('68','0','photo','相册','','','0','1','1','apps','0');
INSERT INTO ts_system_node_copy VALUES ('77','64','index','首页','a:1:{i:0;a:2:{s:5:\"title\";s:6:\"首页\";s:4:\"name\";s:5:\"index\";}}','首页','0','2','1','apps','0');
INSERT INTO ts_system_node_copy VALUES ('78','66','index','首页','a:1:{i:0;a:2:{s:5:\"title\";s:6:\"首页\";s:4:\"name\";s:5:\"index\";}}','首页','0','2','1','apps','0');
INSERT INTO ts_system_node_copy VALUES ('79','68','index','首页','a:1:{i:0;a:2:{s:5:\"title\";s:6:\"首页\";s:4:\"name\";s:5:\"index\";}}','首页','0','2','1','apps','0');
INSERT INTO ts_system_node_copy VALUES ('80','23','rank','用户等级管理','a:1:{i:0;a:2:{s:5:\"title\";s:18:\"用户等级管理\";s:4:\"name\";s:4:\"rank\";}}','用户等级管理','0','3','1','admin','0');
INSERT INTO ts_system_node_copy VALUES ('81','7','msg','消息群发','a:1:{i:0;a:2:{s:5:\"title\";s:12:\"消息群发\";s:4:\"name\";s:3:\"msg\";}}','消息群发','0','3','1','admin','0');
INSERT INTO ts_system_node_copy VALUES ('83','9','scores','积分规则','a:1:{i:0;a:2:{s:5:\"title\";s:12:\"积分规则\";s:4:\"name\";s:6:\"scores\";}}','积分规则','0','3','1','admin','0');

 drop table if exists ts_system_popedom;
CREATE TABLE `ts_system_popedom` (
  `id` int(11) NOT NULL auto_increment,
  `groupid` int(11) NOT NULL,
  `menuid` int(11) NOT NULL,
  `modelid` int(11) NOT NULL,
  `type` varchar(100) NOT NULL,
  `arraction` longtext NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=174 DEFAULT CHARSET=utf8;

INSERT INTO ts_system_popedom VALUES ('145','1','1','31','admin','a:4:{i:0;s:2:\"32\";i:1;s:2:\"33\";i:2;s:2:\"34\";i:3;s:2:\"35\";}');
INSERT INTO ts_system_popedom VALUES ('146','1','2','7','admin','a:1:{i:0;s:1:\"8\";}');
INSERT INTO ts_system_popedom VALUES ('147','1','2','23','admin','a:3:{i:0;s:2:\"24\";i:1;s:2:\"25\";i:2;s:2:\"44\";}');
INSERT INTO ts_system_popedom VALUES ('148','1','3','9','admin','a:15:{i:0;s:2:\"10\";i:1;s:2:\"11\";i:2;s:2:\"12\";i:3;s:2:\"13\";i:4;s:2:\"15\";i:5;s:2:\"16\";i:6;s:2:\"17\";i:7;s:2:\"18\";i:8;s:2:\"19\";i:9;s:2:\"20\";i:10;s:2:\"21\";i:11;s:2:\"22\";i:12;s:2:\"45\";i:13;s:2:\"46\";i:14;s:2:\"51\";}');
INSERT INTO ts_system_popedom VALUES ('149','1','3','47','admin','a:3:{i:0;s:2:\"48\";i:1;s:2:\"49\";i:2;s:2:\"50\";}');
INSERT INTO ts_system_popedom VALUES ('150','1','4','26','admin','a:5:{i:0;s:2:\"39\";i:1;s:2:\"40\";i:2;s:2:\"41\";i:3;s:2:\"42\";i:4;s:2:\"43\";}');
INSERT INTO ts_system_popedom VALUES ('151','1','5','28','admin','a:2:{i:0;s:2:\"29\";i:1;s:2:\"30\";}');
INSERT INTO ts_system_popedom VALUES ('152','1','5','37','admin','a:1:{i:0;s:2:\"38\";}');
INSERT INTO ts_system_popedom VALUES ('160','1','0','64','apps','a:1:{i:0;s:2:\"77\";}');
INSERT INTO ts_system_popedom VALUES ('161','1','0','66','apps','a:1:{i:0;s:2:\"78\";}');
INSERT INTO ts_system_popedom VALUES ('162','1','0','68','apps','a:1:{i:0;s:2:\"79\";}');
INSERT INTO ts_system_popedom VALUES ('168','2','1','31','admin','a:4:{i:0;s:2:\"32\";i:1;s:2:\"33\";i:2;s:2:\"34\";i:3;s:2:\"35\";}');
INSERT INTO ts_system_popedom VALUES ('169','2','2','7','admin','a:1:{i:0;s:1:\"8\";}');
INSERT INTO ts_system_popedom VALUES ('173','6','3','47','admin','a:3:{i:0;s:2:\"48\";i:1;s:2:\"49\";i:2;s:2:\"50\";}');
INSERT INTO ts_system_popedom VALUES ('172','6','3','9','admin','a:14:{i:0;s:2:\"10\";i:1;s:2:\"11\";i:2;s:2:\"12\";i:3;s:2:\"14\";i:4;s:2:\"17\";i:5;s:2:\"18\";i:6;s:2:\"20\";i:7;s:2:\"21\";i:8;s:2:\"22\";i:9;s:2:\"45\";i:10;s:2:\"46\";i:11;s:2:\"51\";i:12;s:2:\"82\";i:13;s:2:\"87\";}');

 drop table if exists ts_system_user_rank;
CREATE TABLE `ts_system_user_rank` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `rulemin` text NOT NULL,
  `rulemax` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

INSERT INTO ts_system_user_rank VALUES ('25','1级用户','1.gif','a:2:{s:5:\"score\";i:0;s:10:\"experience\";i:0;}','a:2:{s:5:\"score\";i:0;s:10:\"experience\";i:200;}');
INSERT INTO ts_system_user_rank VALUES ('26','2级用户','2.gif','a:2:{s:5:\"score\";i:0;s:10:\"experience\";i:200;}','a:2:{s:5:\"score\";i:0;s:10:\"experience\";i:400;}');
INSERT INTO ts_system_user_rank VALUES ('27','3级用户','3.gif','a:2:{s:5:\"score\";i:0;s:10:\"experience\";i:400;}','a:2:{s:5:\"score\";i:0;s:10:\"experience\";i:1000;}');
INSERT INTO ts_system_user_rank VALUES ('28','4级用户','4.gif','a:2:{s:5:\"score\";i:0;s:10:\"experience\";i:1000;}','a:2:{s:5:\"score\";i:0;s:10:\"experience\";i:1500;}');
INSERT INTO ts_system_user_rank VALUES ('29','5级用户','5.gif','a:2:{s:5:\"score\";i:0;s:10:\"experience\";i:1500;}','a:2:{s:5:\"score\";i:0;s:10:\"experience\";i:300000;}');

 drop table if exists ts_user;
CREATE TABLE `ts_user` (
  `id` int(11) NOT NULL auto_increment,
  `email` varchar(255) NOT NULL,
  `passwd` varchar(255) NOT NULL,
  `name` varchar(255) default NULL,
  `handle` varchar(255) default NULL,
  `sex` varchar(10) default NULL,
  `birthday` varchar(255) default NULL,
  `blood_type` varchar(5) default NULL,
  `current_province` varchar(255) default NULL,
  `current_city` varchar(255) default NULL,
  `current_area` varchar(255) default NULL,
  `admin_level` varchar(255) default '0',
  `commend` tinyint(1) NOT NULL,
  `active` tinyint(1) NOT NULL default '0',
  `cTime` int(11) NOT NULL,
  `identity` tinyint(1) NOT NULL default '1',
  `score` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

INSERT INTO ts_user VALUES ('1','master@xueyou.de','3441eda41a25bfac58e4ef784d41efd1','管理员','','1','','','2','42','','1','0','1','1254696637','1','1000');
INSERT INTO ts_user VALUES ('2','fzqmail@hotmail.com','bcf253157368702449282a38edba1dc0','fengzhiqiang','','1','','','28','337','','0','0','0','1254730560','1','0');
INSERT INTO ts_user VALUES ('3','fzqmail@163.com','bcf253157368702449282a38edba1dc0','冯志强','','1','','','28','337','','4','0','1','1254731898','1','0');
INSERT INTO ts_user VALUES ('4','meiinging@163.com','bf2b00caa0cf57a7d71b7ec0fffdf321','侯光成','','1','','','28','337','','4','0','1','1254732262','1','0');

 drop table if exists ts_user_app;
CREATE TABLE `ts_user_app` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `appid` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_user_attach;
CREATE TABLE `ts_user_attach` (
  `id` int(11) NOT NULL auto_increment,
  `userId` int(11) default NULL,
  `appId` int(11) default NULL,
  `attachId` int(11) default NULL,
  `title` varchar(50) default NULL,
  `info` text,
  `cTime` int(11) unsigned default NULL,
  `mTime` int(11) unsigned default NULL,
  `tags` text,
  `commentCount` int(11) unsigned default '0',
  `readCount` int(11) unsigned default '0',
  `digCount` int(11) unsigned default '0',
  `attachPath` varchar(255) NOT NULL,
  `attachSize` varchar(20) default NULL,
  `attachType` varchar(20) default NULL,
  `status` tinyint(2) unsigned NOT NULL default '1',
  `private` tinyint(2) default '0',
  PRIMARY KEY  (`id`),
  KEY `userId` (`userId`),
  KEY `cTime` (`cTime`),
  KEY `mTime` (`mTime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_user_gift;
CREATE TABLE `ts_user_gift` (
  `id` int(11) NOT NULL auto_increment,
  `fromUserId` int(11) NOT NULL,
  `fromUserName` varchar(255) NOT NULL,
  `toUserId` int(11) NOT NULL,
  `giftPrice` int(11) NOT NULL,
  `giftImg` varchar(255) NOT NULL,
  `sendInfo` text NOT NULL,
  `sendWay` tinyint(1) NOT NULL,
  `cTime` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_user_info;
CREATE TABLE `ts_user_info` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `info` text NOT NULL,
  `intro` text NOT NULL,
  `contact` text NOT NULL,
  `education` text NOT NULL,
  `career` text NOT NULL,
  `credit` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

INSERT INTO ts_user_info VALUES ('1','1','','','','','','a:2:{s:5:\"score\";s:1:\"4\";s:10:\"experience\";s:2:\"20\";}');
INSERT INTO ts_user_info VALUES ('2','2','a:3:{s:8:\"birthday\";a:4:{i:0;s:1:\"0\";i:1;i:0;i:2;s:8:\"1980-1-1\";i:3;N;}s:10:\"ts_areaval\";a:4:{i:0;s:1:\"0\";i:1;i:0;i:2;s:6:\"28,337\";i:3;a:2:{i:0;s:2:\"28\";i:1;s:3:\"337\";}}s:3:\"sex\";a:4:{i:0;s:1:\"0\";i:1;i:0;i:2;s:3:\"男\";i:3;N;}}','','','','','a:2:{s:5:\"score\";s:1:\"3\";s:10:\"experience\";s:1:\"5\";}');
INSERT INTO ts_user_info VALUES ('3','3','a:3:{s:8:\"birthday\";a:4:{i:0;s:1:\"0\";i:1;i:0;i:2;s:10:\"1983-12-17\";i:3;N;}s:10:\"ts_areaval\";a:4:{i:0;s:1:\"0\";i:1;i:0;i:2;s:6:\"28,337\";i:3;a:2:{i:0;s:2:\"28\";i:1;s:3:\"337\";}}s:3:\"sex\";a:4:{i:0;s:1:\"0\";i:1;i:0;i:2;s:3:\"男\";i:3;N;}}','','','','','a:2:{s:5:\"score\";s:1:\"6\";s:10:\"experience\";s:2:\"11\";}');
INSERT INTO ts_user_info VALUES ('4','4','a:3:{s:8:\"birthday\";a:4:{i:0;s:1:\"1\";i:1;i:0;i:2;s:9:\"1983-3-26\";i:3;N;}s:10:\"ts_areaval\";a:4:{i:0;s:1:\"1\";i:1;i:0;i:2;s:6:\"28,337\";i:3;a:2:{i:0;s:2:\"28\";i:1;s:3:\"337\";}}s:3:\"sex\";a:4:{i:0;s:1:\"1\";i:1;i:0;i:2;s:3:\"男\";i:3;N;}}','','','','','a:2:{s:5:\"score\";s:1:\"3\";s:10:\"experience\";s:2:\"15\";}');

 drop table if exists ts_user_online;
CREATE TABLE `ts_user_online` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `uname` varchar(255) NOT NULL,
  `mini` varchar(255) default NULL,
  `activeTime` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

INSERT INTO ts_user_online VALUES ('1','1','管理员','','1254735648');
INSERT INTO ts_user_online VALUES ('2','2','fengzhiqiang','','1254731486');
INSERT INTO ts_user_online VALUES ('3','4','侯光成','','1254734408');
INSERT INTO ts_user_online VALUES ('4','3','冯志强','','1254734137');

 drop table if exists ts_user_score;
CREATE TABLE `ts_user_score` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `info` varchar(255) NOT NULL,
  `action` varchar(50) NOT NULL,
  `type` varchar(20) NOT NULL default 'score',
  `number` int(3) NOT NULL,
  `cTime` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;

INSERT INTO ts_user_score VALUES ('1','1','用户登录增加了1经验','user_login','experience','1','1254696713');
INSERT INTO ts_user_score VALUES ('2','1','空间被访问增加了1积分','user_visited','score','1','1254730601');
INSERT INTO ts_user_score VALUES ('3','1','空间被访问增加了1经验','user_visited','experience','1','1254730601');
INSERT INTO ts_user_score VALUES ('4','2','访问他人空间增加了2经验','visit_space','experience','2','1254730601');
INSERT INTO ts_user_score VALUES ('5','1','用户登录增加了1经验','user_login','experience','1','1254730989');
INSERT INTO ts_user_score VALUES ('6','3','空间被访问增加了1积分','user_visited','score','1','1254732621');
INSERT INTO ts_user_score VALUES ('7','3','空间被访问增加了1经验','user_visited','experience','1','1254732621');
INSERT INTO ts_user_score VALUES ('8','4','访问他人空间增加了2经验','visit_space','experience','2','1254732621');
INSERT INTO ts_user_score VALUES ('9','1','空间被访问增加了1积分','user_visited','score','1','1254732698');
INSERT INTO ts_user_score VALUES ('10','1','空间被访问增加了1经验','user_visited','experience','1','1254732698');
INSERT INTO ts_user_score VALUES ('11','4','访问他人空间增加了2经验','visit_space','experience','2','1254732698');
INSERT INTO ts_user_score VALUES ('12','3','空间被访问增加了1积分','user_visited','score','1','1254732739');
INSERT INTO ts_user_score VALUES ('13','3','空间被访问增加了1经验','user_visited','experience','1','1254732739');
INSERT INTO ts_user_score VALUES ('14','4','访问他人空间增加了2经验','visit_space','experience','2','1254732739');
INSERT INTO ts_user_score VALUES ('15','2','空间被访问增加了1积分','user_visited','score','1','1254732751');
INSERT INTO ts_user_score VALUES ('16','2','空间被访问增加了1经验','user_visited','experience','1','1254732751');
INSERT INTO ts_user_score VALUES ('17','4','访问他人空间增加了2经验','visit_space','experience','2','1254732751');
INSERT INTO ts_user_score VALUES ('18','4','发布心情增加了1积分','add_mini','score','1','1254732832');
INSERT INTO ts_user_score VALUES ('19','4','发布心情增加了2经验','add_mini','experience','2','1254732832');
INSERT INTO ts_user_score VALUES ('20','1','空间被访问增加了1积分','user_visited','score','1','1254732891');
INSERT INTO ts_user_score VALUES ('21','1','空间被访问增加了1经验','user_visited','experience','1','1254732891');
INSERT INTO ts_user_score VALUES ('22','3','访问他人空间增加了2经验','visit_space','experience','2','1254732891');
INSERT INTO ts_user_score VALUES ('23','2','空间被访问增加了1积分','user_visited','score','1','1254732971');
INSERT INTO ts_user_score VALUES ('24','2','空间被访问增加了1经验','user_visited','experience','1','1254732971');
INSERT INTO ts_user_score VALUES ('25','3','访问他人空间增加了2经验','visit_space','experience','2','1254732971');
INSERT INTO ts_user_score VALUES ('26','4','空间被访问增加了1积分','user_visited','score','1','1254733659');
INSERT INTO ts_user_score VALUES ('27','4','空间被访问增加了1经验','user_visited','experience','1','1254733659');
INSERT INTO ts_user_score VALUES ('28','1','访问他人空间增加了2经验','visit_space','experience','2','1254733659');
INSERT INTO ts_user_score VALUES ('29','3','空间被访问增加了1积分','user_visited','score','1','1254733940');
INSERT INTO ts_user_score VALUES ('30','3','空间被访问增加了1经验','user_visited','experience','1','1254733940');
INSERT INTO ts_user_score VALUES ('31','1','访问他人空间增加了2经验','visit_space','experience','2','1254733940');
INSERT INTO ts_user_score VALUES ('32','2','空间被访问增加了1积分','user_visited','score','1','1254734027');
INSERT INTO ts_user_score VALUES ('33','2','空间被访问增加了1经验','user_visited','experience','1','1254734027');
INSERT INTO ts_user_score VALUES ('34','1','访问他人空间增加了2经验','visit_space','experience','2','1254734027');
INSERT INTO ts_user_score VALUES ('35','3','空间被访问增加了1积分','user_visited','score','1','1254734077');
INSERT INTO ts_user_score VALUES ('36','3','空间被访问增加了1经验','user_visited','experience','1','1254734077');
INSERT INTO ts_user_score VALUES ('37','1','访问他人空间增加了2经验','visit_space','experience','2','1254734077');
INSERT INTO ts_user_score VALUES ('38','3','用户登录增加了1经验','user_login','experience','1','1254734095');
INSERT INTO ts_user_score VALUES ('39','1','空间被访问增加了1积分','user_visited','score','1','1254734146');
INSERT INTO ts_user_score VALUES ('40','1','空间被访问增加了1经验','user_visited','experience','1','1254734146');
INSERT INTO ts_user_score VALUES ('41','4','访问他人空间增加了2经验','visit_space','experience','2','1254734146');
INSERT INTO ts_user_score VALUES ('42','4','用户登录增加了1经验','user_login','experience','1','1254734338');
INSERT INTO ts_user_score VALUES ('43','3','空间被访问增加了1积分','user_visited','score','1','1254735296');
INSERT INTO ts_user_score VALUES ('44','3','空间被访问增加了1经验','user_visited','experience','1','1254735296');
INSERT INTO ts_user_score VALUES ('45','1','访问他人空间增加了2经验','visit_space','experience','2','1254735296');
INSERT INTO ts_user_score VALUES ('46','3','空间被访问增加了1积分','user_visited','score','1','1254735510');
INSERT INTO ts_user_score VALUES ('47','3','空间被访问增加了1经验','user_visited','experience','1','1254735510');
INSERT INTO ts_user_score VALUES ('48','1','访问他人空间增加了2经验','visit_space','experience','2','1254735510');
INSERT INTO ts_user_score VALUES ('49','4','空间被访问增加了1积分','user_visited','score','1','1254735531');
INSERT INTO ts_user_score VALUES ('50','4','空间被访问增加了1经验','user_visited','experience','1','1254735531');
INSERT INTO ts_user_score VALUES ('51','1','访问他人空间增加了2经验','visit_space','experience','2','1254735531');

 drop table if exists ts_user_search;
CREATE TABLE `ts_user_search` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `extra1` varchar(255) default NULL,
  `extra2` varchar(255) default NULL,
  `extra3` varchar(255) default NULL,
  `extra4` varchar(255) default NULL,
  `extra5` varchar(255) default NULL,
  `privacy` int(1) default '0',
  `home` int(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

INSERT INTO ts_user_search VALUES ('1','2','birthday','1980-1-1','','','','','','0','0');
INSERT INTO ts_user_search VALUES ('2','2','ts_areaval','28,337','28','337','','','','0','0');
INSERT INTO ts_user_search VALUES ('3','2','sex','1','','','','','','0','0');
INSERT INTO ts_user_search VALUES ('4','3','birthday','1983-12-17','','','','','','0','0');
INSERT INTO ts_user_search VALUES ('5','3','ts_areaval','28,337','28','337','','','','0','0');
INSERT INTO ts_user_search VALUES ('6','3','sex','1','','','','','','0','0');
INSERT INTO ts_user_search VALUES ('7','4','birthday','1983-3-26','','','','','','1','0');
INSERT INTO ts_user_search VALUES ('8','4','ts_areaval','28,337','28','337','','','','1','0');
INSERT INTO ts_user_search VALUES ('9','4','sex','1','','','','','','1','0');

 drop table if exists ts_visitor;
CREATE TABLE `ts_visitor` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `visitId` int(11) NOT NULL,
  `cTime` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

INSERT INTO ts_visitor VALUES ('1','3','管理员','1','1254735510');
INSERT INTO ts_visitor VALUES ('2','2','管理员','1','1254734027');
INSERT INTO ts_visitor VALUES ('3','1','侯光成','4','1254734145');
INSERT INTO ts_visitor VALUES ('4','4','管理员','1','1254735531');

 drop table if exists ts_vote;
CREATE TABLE `ts_vote` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `title` text NOT NULL,
  `explain` text NOT NULL,
  `type` tinyint(4) NOT NULL,
  `glimit` tinyint(4) NOT NULL default '0',
  `deadline` int(11) NOT NULL,
  `onlyfriend` tinyint(4) NOT NULL,
  `cTime` int(11) NOT NULL,
  `vote_num` int(11) NOT NULL default '0',
  `commentCount` int(11) NOT NULL default '0',
  `feedId` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_vote_comment;
CREATE TABLE `ts_vote_comment` (
  `id` int(11) NOT NULL auto_increment,
  `vote_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `cTime` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_vote_config;
CREATE TABLE `ts_vote_config` (
  `variable` char(20) NOT NULL,
  `value` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_vote_opt;
CREATE TABLE `ts_vote_opt` (
  `id` int(11) NOT NULL auto_increment,
  `vote_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `num` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_vote_user;
CREATE TABLE `ts_vote_user` (
  `id` int(11) NOT NULL auto_increment,
  `vote_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `opts` text NOT NULL,
  `cTime` int(11) NOT NULL,
  `feedId` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_wall;
CREATE TABLE `ts_wall` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `fromUserId` int(11) NOT NULL,
  `fromUserName` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `cTime` int(11) NOT NULL,
  `replyWallId` int(11) NOT NULL default '0',
  `privacy` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 drop table if exists ts_work_search;
CREATE TABLE `ts_work_search` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `company` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `begin` int(11) NOT NULL,
  `end` int(11) NOT NULL,
  `privacy` tinyint(1) NOT NULL default '0',
  `home` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


