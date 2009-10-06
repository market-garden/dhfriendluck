/*
MySQL Data Transfer
Source Host: localhost
Source Database: lsi
Target Host: localhost
Target Database: lsi
Date: 2009-7-3 14:55:14
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for ts_vote
-- ----------------------------
DROP TABLE IF EXISTS `ts_vote`;
CREATE TABLE `ts_vote` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `title` text NOT NULL,
  `explain` text NOT NULL,
  `type` tinyint(4) NOT NULL,
  `glimit` tinyint(4) NOT NULL default '0',
  `deadline` int(11) NOT NULL,
  `onlyfriend` tinyint(4) NOT NULL,
  `cTime` int(11) NOT NULL,
  `vote_num` int(11) NOT NULL default '0',
  `comm_num` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ts_vote_comment
-- ----------------------------
DROP TABLE IF EXISTS `ts_vote_comment`;
CREATE TABLE `ts_vote_comment` (
  `id` int(11) NOT NULL auto_increment,
  `vote_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `cTime` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ts_vote_opt
-- ----------------------------
DROP TABLE IF EXISTS `ts_vote_opt`;
CREATE TABLE `ts_vote_opt` (
  `id` int(11) NOT NULL auto_increment,
  `vote_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `num` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=74 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ts_vote_user
-- ----------------------------
DROP TABLE IF EXISTS `ts_vote_user`;
CREATE TABLE `ts_vote_user` (
  `id` int(11) NOT NULL auto_increment,
  `vote_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `opts` text NOT NULL,
  `cTime` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

