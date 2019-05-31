
-- phpMyAdmin SQL Dump
-- version 4.5.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2019-01-17 05:25:20
-- 服务器版本： 5.7.11
-- PHP Version: 5.6.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `phpcmsv9`
--

-- --------------------------------------------------------

--
-- 表的结构 `zy_member`
--

CREATE TABLE IF NOT EXISTS `zy_member` (
  `userid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `phpssouid` mediumint(8) unsigned NOT NULL,
  `username` char(20) NOT NULL DEFAULT '',
  `password` char(32) NOT NULL DEFAULT '',
  `encrypt` char(6) NOT NULL,
  `nickname` varbinary(120) NOT NULL,
  `regdate` int(10) unsigned NOT NULL DEFAULT '0',
  `lastdate` int(10) unsigned NOT NULL DEFAULT '0',
  `regip` char(15) NOT NULL DEFAULT '',
  `lastip` char(15) NOT NULL DEFAULT '',
  `loginnum` smallint(5) unsigned NOT NULL DEFAULT '0',
  `email` char(32) NOT NULL DEFAULT '',
  `groupid` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `areaid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `amount` decimal(8,2) unsigned NOT NULL DEFAULT '0.00',
  `point` smallint(5) unsigned NOT NULL DEFAULT '0',
  `modelid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `message` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `islock` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `vip` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `overduedate` int(10) unsigned NOT NULL DEFAULT '0',
  `siteid` smallint(5) unsigned NOT NULL DEFAULT '1',
  `connectid` char(40) NOT NULL DEFAULT '',
  `from` char(10) NOT NULL DEFAULT '',
  `mobile` char(11) NOT NULL DEFAULT '',
  `trade_password` char(32) NOT NULL COMMENT '交易密码',
  `trade_encrypt` char(8) NOT NULL COMMENT '交易密码_加密字段',
  `headimgurl` varchar(255) NOT NULL DEFAULT 'statics/images/member/nophoto.gif' COMMENT '头像',
  `sex` char(10) NOT NULL DEFAULT '保密' COMMENT '男、女、保密',
  `wechat_unionid` varchar(100) NOT NULL COMMENT '微信登录唯一ID',
  `wechat_name` varbinary(120) NOT NULL COMMENT '微信昵称',
  `wechatpc_openid` varchar(255) NOT NULL COMMENT '微信电脑openid',
  `wechatpe_openid` varchar(255) NOT NULL COMMENT '微信公众号openid',
  `wechatapp_openid` varchar(255) NOT NULL COMMENT '微信APP_openid',
  `wechatxcx_openid` varchar(255) NOT NULL COMMENT '微信小程序_openid',
  `wechat_headimg` varchar(255) NOT NULL COMMENT '微信头像',
  `wechat_sex` char(10) NOT NULL COMMENT '微信性别',
  `shopname` varchar(255) NOT NULL COMMENT '店铺_名称',
  `proprietary` tinyint(1) unsigned NOT NULL COMMENT '店铺_是否自营：0否、1是',
  `store_audit` tinyint(1) unsigned NOT NULL COMMENT '店铺_审核：1待审核、2通过、3驳回、0无操作',
  `store_logo` varchar(255) NOT NULL COMMENT '店铺_logo',
  `store_audit_no` varchar(255) NOT NULL COMMENT '店铺_审核驳回理由',
  `store_islock` tinyint(1) unsigned DEFAULT '0' COMMENT '店铺锁定：0开、1关',
  PRIMARY KEY (`userid`),
  UNIQUE KEY `username` (`username`),
  KEY `email` (`email`(20)),
  KEY `phpssouid` (`phpssouid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;


-- 
-- 出入数据
-- 
INSERT INTO `zy_member` (`userid`, `phpssouid`, `username`, `password`, `encrypt`, `nickname`, `regdate`, `lastdate`, `regip`, `lastip`, `loginnum`, `email`, `groupid`, `areaid`, `amount`, `point`, `modelid`, `message`, `islock`, `vip`, `overduedate`, `siteid`, `connectid`, `from`, `mobile`, `trade_password`, `trade_encrypt`, `headimgurl`, `sex`, `wechat_unionid`, `wechat_name`, `wechatpc_openid`, `wechatpe_openid`, `wechat_headimg`, `wechat_sex`, `shopname`, `proprietary`) VALUES
(1, 1, 'ldFuFM2f', 'ab50224008d1968857a51f003003e1dd', 'd4QH7t', '系统店铺', 1553058634, 1554348667, '192.168.1.47', '192.168.1.4', 21, 'zhuoyuan@300c.cn', 18, 0, '0.00', 0, 10, 0, 0, 0, 0, 1, '', '', '15067614058', 'ab50224008d1968857a51f003003e1dd', 'd4QH7t', 'statics/images/member/nophoto.gif', '保密', '', '', '', '', '', '', '系统自营店铺', 1);
