-- phpMyAdmin SQL Dump
-- version 4.1.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2018-02-10 07:11:41
-- 服务器版本： 5.6.17
-- PHP Version: 5.5.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `zy_zyaddr`
--
-- 表的结构 `zy_zyaddr`   地址
--

CREATE TABLE IF NOT EXISTS `zy_zyaddr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL COMMENT '用户ID',
  `name` varchar(255) NOT NULL COMMENT '收件人姓名',
  `phone` varchar(255) NOT NULL COMMENT '手机号码',
  `province` varchar(100) NOT NULL COMMENT '省',
  `city` varchar(100) NOT NULL COMMENT '市',
  `district` varchar(100) NOT NULL COMMENT '区',
  `address` varchar(255) NOT NULL COMMENT '详细地址',
  `default` SMALLINT(3) NOT NULL COMMENT '是否默认，1默认 0 非默认',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

