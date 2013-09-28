-- phpMyAdmin SQL Dump
-- version 3.3.7
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2013 年 04 月 29 日 07:31
-- 服务器版本: 5.0.90
-- PHP 版本: 5.2.14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `98qing`
--

-- --------------------------------------------------------

--
-- 表的结构 `xg_admin`
--

CREATE TABLE IF NOT EXISTS `xg_admin` (
  `id` smallint(6) unsigned NOT NULL auto_increment,
  `name` varchar(50) NOT NULL,
  `pwd` char(32) NOT NULL,
  `count` smallint(6) NOT NULL,
  `ok` varchar(50) NOT NULL,
  `del` bigint(1) NOT NULL,
  `ip` varchar(40) NOT NULL,
  `email` varchar(40) NOT NULL,
  `logintime` int(11) NOT NULL,
  `lastlogintime` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `xg_admin`
--

INSERT INTO `xg_admin` (`id`, `name`, `pwd`, `count`, `ok`, `del`, `ip`, `email`, `logintime`, `lastlogintime`) VALUES
(2, 'admin', '0306c2a05a511f70fcd9746b1c3f98b7', 1, '', 0, '127.0.0.1', 'admin@xgcms.com', 1367220571, 1367208208);

-- --------------------------------------------------------

--
-- 表的结构 `xg_ads`
--

CREATE TABLE IF NOT EXISTS `xg_ads` (
  `adsid` smallint(4) unsigned NOT NULL auto_increment,
  `adsname` varchar(50) NOT NULL,
  `adscontent` text NOT NULL,
  PRIMARY KEY  (`adsid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- 转存表中的数据 `xg_ads`
--

INSERT INTO `xg_ads` (`adsid`, `adsname`, `adscontent`) VALUES
(10, '96090', '<a href="http://www.98qing.com/Pay/Upgrade"><img src="./Public/ads/95090.gif"  /></a>');

-- --------------------------------------------------------

--
-- 表的结构 `xg_album`
--

CREATE TABLE IF NOT EXISTS `xg_album` (
  `albumid` mediumint(8) unsigned NOT NULL auto_increment,
  `albumname` varchar(50) NOT NULL default '',
  `catid` smallint(6) unsigned NOT NULL default '0',
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `username` varchar(15) NOT NULL default '',
  `updatetime` int(10) unsigned NOT NULL default '0',
  `picprice` float unsigned NOT NULL default '0',
  `pic` varchar(100) NOT NULL default '',
  `width` int(4) NOT NULL,
  `height` int(4) NOT NULL,
  `picflag` tinyint(1) NOT NULL default '0',
  `password` varchar(10) NOT NULL default '',
  `favtimes` mediumint(8) unsigned NOT NULL,
  `sharetimes` mediumint(8) unsigned NOT NULL,
  `status` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`albumid`),
  KEY `uid` (`uid`,`updatetime`),
  KEY `updatetime` (`updatetime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `xg_album`
--


-- --------------------------------------------------------

--
-- 表的结构 `xg_announce`
--

CREATE TABLE IF NOT EXISTS `xg_announce` (
  `aid` smallint(4) unsigned NOT NULL auto_increment,
  `title` char(80) NOT NULL,
  `content` text NOT NULL,
  `starttime` date NOT NULL default '0000-00-00',
  `endtime` date NOT NULL default '0000-00-00',
  `addtime` int(10) unsigned NOT NULL default '0',
  `hits` smallint(5) unsigned NOT NULL default '0',
  `style` char(15) NOT NULL,
  PRIMARY KEY  (`aid`),
  KEY `siteid` (`endtime`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- 转存表中的数据 `xg_announce`
--


-- --------------------------------------------------------

--
-- 表的结构 `xg_attachment`
--

CREATE TABLE IF NOT EXISTS `xg_attachment` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `model` varchar(15) NOT NULL,
  `image` varchar(200) NOT NULL,
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `create_time` int(10) unsigned NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `xg_attachment`
--


-- --------------------------------------------------------

--
-- 表的结构 `xg_charge`
--

CREATE TABLE IF NOT EXISTS `xg_charge` (
  `id` int(6) NOT NULL auto_increment,
  `uid` int(10) NOT NULL,
  `username` varchar(20) NOT NULL default '',
  `item` varchar(255) NOT NULL default '',
  `num` decimal(10,2) NOT NULL default '0.00',
  `account` enum('0','1','2','3') NOT NULL default '0',
  `time` int(10) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `xg_charge`
--


-- --------------------------------------------------------

--
-- 表的结构 `xg_collect`
--

CREATE TABLE IF NOT EXISTS `xg_collect` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `be_collect` int(8) NOT NULL,
  `time` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `xg_collect`
--


-- --------------------------------------------------------

--
-- 表的结构 `xg_diary`
--

CREATE TABLE IF NOT EXISTS `xg_diary` (
  `id` int(11) NOT NULL auto_increment,
  `uid` mediumint(8) NOT NULL default '0',
  `username` char(20) NOT NULL,
  `cateid` int(4) NOT NULL default '0',
  `diarytitle` varchar(100) NOT NULL default '',
  `thedate` int(10) NOT NULL,
  `theweather` tinyint(2) NOT NULL default '1',
  `themood` tinyint(3) NOT NULL default '1',
  `pic` varchar(255) default NULL,
  `diarytxt` text,
  `diarybg` tinyint(2) default '1',
  `stat` tinyint(1) NOT NULL default '0',
  `viewtimes` int(11) NOT NULL default '0',
  `good` int(11) NOT NULL default '0',
  `bad` int(11) NOT NULL default '0',
  `recommends` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`),
  KEY `stat` (`stat`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `xg_diary`
--


-- --------------------------------------------------------

--
-- 表的结构 `xg_links`
--

CREATE TABLE IF NOT EXISTS `xg_links` (
  `id` int(2) NOT NULL auto_increment,
  `url` varchar(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `logo` varchar(100) NOT NULL,
  `rank` int(6) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `xg_links`
--


-- --------------------------------------------------------

--
-- 表的结构 `xg_member`
--

CREATE TABLE IF NOT EXISTS `xg_member` (
  `uid` int(8) NOT NULL auto_increment,
  `username` char(20) NOT NULL,
  `password` char(40) NOT NULL,
  `email` char(32) NOT NULL,
  `group` tinyint(2) NOT NULL,
  `reg_time` int(10) NOT NULL,
  `login_time` int(10) NOT NULL,
  `login_count` int(4) NOT NULL,
  `reg_ip` char(20) NOT NULL,
  `login_ip` char(20) NOT NULL,
  `upgrade_time` int(10) NOT NULL,
  `upgrade_end` int(10) NOT NULL,
  `credit` int(10) NOT NULL default '0',
  `money` decimal(6,2) NOT NULL default '0.00',
  `islock` tinyint(1) NOT NULL default '0',
  `old_uid` int(10) NOT NULL,
  PRIMARY KEY  (`uid`),
  UNIQUE KEY `username` (`username`),
  KEY `group` (`group`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `xg_member`
--


-- --------------------------------------------------------

--
-- 表的结构 `xg_member_detail`
--

CREATE TABLE IF NOT EXISTS `xg_member_detail` (
  `uid` int(8) NOT NULL,
  `nickname` char(20) NOT NULL,
  `avatar` int(6) NOT NULL default '0',
  `gender` mediumint(2) NOT NULL,
  `province` mediumint(2) NOT NULL,
  `city` mediumint(4) NOT NULL,
  `birthday` int(10) NOT NULL,
  `age` mediumint(2) NOT NULL,
  `height` mediumint(3) NOT NULL,
  `figure` mediumint(3) NOT NULL,
  `star` mediumint(2) NOT NULL,
  `blood` mediumint(2) NOT NULL,
  `character` char(20) NOT NULL,
  `superiority` char(20) NOT NULL,
  `interest` char(20) NOT NULL,
  `drinking` tinyint(1) NOT NULL,
  `smoke` tinyint(1) NOT NULL,
  `edu` mediumint(2) NOT NULL,
  `job` mediumint(2) NOT NULL,
  `income` mediumint(5) NOT NULL,
  `house` mediumint(2) NOT NULL,
  `marry` mediumint(2) NOT NULL,
  `datingfor` char(10) NOT NULL,
  `concept` char(20) NOT NULL,
  `favplace` char(20) NOT NULL,
  `aboutme` text NOT NULL,
  `qq` int(10) NOT NULL,
  `tel` varchar(12) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `msn` char(30) NOT NULL,
  `status` mediumint(2) NOT NULL default '1',
  `hits` int(6) NOT NULL default '0',
  `updatetime` int(10) NOT NULL,
  PRIMARY KEY  (`uid`),
  KEY `city` (`city`),
  KEY `age` (`uid`,`gender`,`age`),
  KEY `hits` (`hits`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `xg_member_detail`
--


-- --------------------------------------------------------

--
-- 表的结构 `xg_member_group`
--

CREATE TABLE IF NOT EXISTS `xg_member_group` (
  `groupid` tinyint(3) unsigned NOT NULL auto_increment,
  `name` char(20) NOT NULL,
  `issystem` tinyint(1) unsigned NOT NULL default '0',
  `allowmessage` smallint(5) unsigned NOT NULL default '0',
  `allowvisit` tinyint(1) unsigned NOT NULL default '0',
  `allowpost` tinyint(1) unsigned NOT NULL default '0',
  `allowsearch` tinyint(1) unsigned NOT NULL default '0',
  `allowupgrade` tinyint(1) unsigned NOT NULL default '1',
  `price_y` decimal(8,2) unsigned NOT NULL default '0.00',
  `price_m` decimal(8,2) unsigned NOT NULL default '0.00',
  `price_d` decimal(8,2) unsigned NOT NULL default '0.00',
  `description` mediumtext NOT NULL,
  `listorder` tinyint(3) unsigned NOT NULL default '0',
  `disabled` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`groupid`),
  KEY `disabled` (`disabled`),
  KEY `listorder` (`listorder`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- 转存表中的数据 `xg_member_group`
--

INSERT INTO `xg_member_group` (`groupid`, `name`, `issystem`, `allowmessage`, `allowvisit`, `allowpost`, `allowsearch`, `allowupgrade`, `price_y`, `price_m`, `price_d`, `description`, `listorder`, `disabled`) VALUES
(1, '管理员', 1, 9999, 1, 1, 1, 1, 0.00, 0.00, 0.00, '', 0, 0),
(2, '禁用', 1, 0, 0, 0, 0, 1, 0.00, 0.00, 0.00, '', 10, 0),
(3, '游客', 1, 0, 1, 0, 0, 1, 0.00, 0.00, 0.00, '', 0, 0),
(4, '待邮件验证', 1, 0, 1, 0, 0, 1, 0.00, 0.00, 0.00, '', 0, 0),
(5, '待审核', 1, 0, 1, 0, 0, 1, 0.00, 0.00, 0.00, '', 0, 0),
(6, '普通会员', 1, 50, 1, 1, 1, 1, 0.00, 0.00, 0.00, '', 0, 0),
(7, 'VIP会员', 0, 1000, 1, 1, 1, 1, 280.00, 150.00, 450.00, '', 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `xg_member_require`
--

CREATE TABLE IF NOT EXISTS `xg_member_require` (
  `uid` int(8) NOT NULL,
  `rq_marry` varchar(30) NOT NULL,
  `rq_age` varchar(30) NOT NULL,
  `rq_area` varchar(30) NOT NULL,
  `rq_edu` varchar(30) NOT NULL,
  `rq_job` varchar(30) NOT NULL,
  `rq_income` varchar(30) NOT NULL,
  `rq_height` varchar(30) NOT NULL,
  `rq_figure` varchar(30) NOT NULL,
  `rq_other` varchar(255) NOT NULL,
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `xg_member_require`
--


-- --------------------------------------------------------

--
-- 表的结构 `xg_menu`
--

CREATE TABLE IF NOT EXISTS `xg_menu` (
  `id` int(4) NOT NULL auto_increment,
  `url` varchar(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `parentid` int(6) NOT NULL default '0',
  `rank` int(6) NOT NULL,
  `type` int(2) NOT NULL,
  `target` varchar(20) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- 转存表中的数据 `xg_menu`
--


-- --------------------------------------------------------

--
-- 表的结构 `xg_message`
--

CREATE TABLE IF NOT EXISTS `xg_message` (
  `id` int(6) NOT NULL auto_increment,
  `from` int(10) NOT NULL,
  `to` int(10) NOT NULL,
  `m_title` char(100) NOT NULL,
  `m_content` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `time` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `to` (`to`),
  KEY `from` (`from`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `xg_message`
--


-- --------------------------------------------------------

--
-- 表的结构 `xg_order`
--

CREATE TABLE IF NOT EXISTS `xg_order` (
  `id` int(6) NOT NULL auto_increment,
  `orderid` char(30) NOT NULL,
  `uid` int(11) NOT NULL,
  `type` varchar(20) NOT NULL,
  `payment` varchar(20) NOT NULL,
  `amount` decimal(8,2) NOT NULL default '0.00',
  `remark` tinytext NOT NULL,
  `time` int(10) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `xg_order`
--

