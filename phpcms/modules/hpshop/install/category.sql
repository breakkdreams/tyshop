-- phpMyAdmin SQL Dump
-- version 4.0.3
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2018 年 05 月 11 日 15:50
-- 服务器版本: 5.5.25
-- PHP 版本: 5.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `300c`
--

-- --------------------------------------------------------

--
-- 表的结构 `zy_goods`
CREATE TABLE IF NOT EXISTS `zy_goodscat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cate_name` varchar(30) NOT NULL COMMENT '商品分类名称',
  `cate_img` varchar(100) NOT NULL COMMENT '栏目图片',
  `isshow` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:显示 2：隐藏',
  `description` varchar(255) NOT NULL COMMENT '描述',
  `sort` smallint(6) NOT NULL DEFAULT '500' COMMENT '排序',
  `pid` smallint(6) NOT NULL DEFAULT '0' COMMENT '上级ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;