
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
-- 表的结构 `zy_member_audit`
--

CREATE TABLE IF NOT EXISTS `zy_member_audit` (
  `id` int(180) unsigned NOT NULL AUTO_INCREMENT,
  `userid` mediumint(10) unsigned NOT NULL COMMENT '用户id',
  `addtime` char(11) NOT NULL COMMENT '添加时间',
  `audit` tinyint(1) unsigned NOT NULL COMMENT '状态描述：1提交待审核、2通过、3驳回',
  `audit_no` varchar(255) NOT NULL COMMENT '驳回理由',
  `shopname` varchar(255) NOT NULL COMMENT '店铺_名称',
  `store_logo` varchar(255) NOT NULL COMMENT '店铺_logo',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户审核记录' AUTO_INCREMENT=1 ;