
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
-- 表的结构 `zy_member_footprint`
--

CREATE TABLE IF NOT EXISTS `zy_member_footprint` (
  `id` smallint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `pid` smallint(8) unsigned NOT NULL COMMENT '商品id',
  `catid` smallint(8) unsigned NOT NULL COMMENT '商品catid',
  `url` varchar(255) NOT NULL COMMENT '商品url',
  `thumb` varchar(255) NOT NULL COMMENT '商品图片',
  `title` char(80) NOT NULL COMMENT '商品标题',
  `price` decimal(8,2) NOT NULL COMMENT '商品价格',
  `userid` smallint(8) unsigned NOT NULL COMMENT '足迹用户',
  `addtime` char(11) NOT NULL COMMENT '添加时间',
  `footprint_time` char(11) NOT NULL COMMENT '日期(2018-02-24 01:00:00)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='会员管理_足迹' AUTO_INCREMENT=1 ;
