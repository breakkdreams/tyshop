-- phpMyAdmin SQL Dump
-- version 4.1.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2019-03-23 09:37:44
-- 服务器版本： 5.6.17
-- PHP Version: 5.5.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `zypub`
--

-- --------------------------------------------------------

--
-- 表的结构 `zy_evaluate`
--

CREATE TABLE IF NOT EXISTS `zy_evaluate` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` int(11) NOT NULL,
  `content` text CHARACTER SET utf8 NOT NULL,
  `addtime` int(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=36 ;

--
-- 转存表中的数据 `zy_evaluate`
--

INSERT INTO `zy_evaluate` (`id`, `orderid`, `content`, `addtime`) VALUES
(35, 34, '{"zonghepingfen":"2","jibensuzhi":"3","yirongzhuozhuang":"4"}', 1549958039);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
