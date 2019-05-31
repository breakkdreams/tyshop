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
--

CREATE TABLE IF NOT EXISTS `zy_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shopid` int(11) NOT NULL COMMENT '店铺ID',
  `template_id` int(11) NOT NULL COMMENT '运费模板id',
  `goods_name` varchar(255) NOT NULL COMMENT '商品名称',
  `summary` text NOT NULL COMMENT '商品简述',
  `thumb` varchar(100) NOT NULL COMMENT '商品主图',
  `album` text NOT NULL COMMENT '商品相册',
  `content` text NOT NULL COMMENT '商品内容信息',
  `market_price` decimal(10,2) NOT NULL COMMENT '市场价',
  `shop_price` decimal(10,2) NOT NULL COMMENT '本店价',
  `score_price` decimal(10,2) NOT NULL COMMENT '积分价',
  `on_sale` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否上架 1：上架 2：下架',
  `stock` int(11) NOT NULL DEFAULT '999' COMMENT '库存',
  `salesnum` int(11) NOT NULL DEFAULT '0' COMMENT '销量',
  `catid` mediumint(9) NOT NULL COMMENT '所属栏目',
  `brand_id` mediumint(9) NOT NULL DEFAULT '0' COMMENT '所属品牌',
  `type_id` mediumint(9) NOT NULL DEFAULT '0' COMMENT '所属类型',
  `isok` tinyint(1) NOT NULL DEFAULT '1' COMMENT '商品审核（1.正常 2.待审核 3.退稿）',
  `isspec` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否有规格数据（1有 0无）',
  `volume` VARCHAR(50) NOT NULL DEFAULT '0' COMMENT '体积(单位:m³)',
  `weight` VARCHAR(50) NOT NULL DEFAULT '0' COMMENT '重量(单位:克)',
  `promotion_price` DECIMAL(10,2) NOT NULL COMMENT '促销价格'
  `starttime` int(20) NOT NULL COMMENT '开始时间',
  `endtime` int(20) NOT NULL COMMENT '结束时间',
  `person_number` int(20) NOT NULL COMMENT '成团人数',
  `waiting_time` VARCHAR(20) NOT NULL COMMENT '等待成团时间(小时)',
  `group_price` DECIMAL(10,2) NOT NULL COMMENT '团购价',
  `goods_type` int(11) NOT NULL DEFAULT '0' COMMENT '商品类型(0,普通商品 1.促销商品 2.团购商品)',
  `addtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `zy_goodstype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(30) NOT NULL COMMENT '类型名称',
  `type_content` text NOT NULL COMMENT '属性信息',
  `tally` int(11) NOT NULL COMMENT '计数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `zy_goodsattr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goodstypeid` int(11) NOT NULL COMMENT '所属商品类型id',
  `attrname` varchar(255) NOT NULL COMMENT '属性名称',
  `attrval` text NOT NULL COMMENT '属性值',
  `isshow` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否显示（0不显示 1显示）',
  `sort` int(11) NOT NULL DEFAULT '500' COMMENT '排序',
  `attrtype` tinyint(4) NOT NULL DEFAULT '0' COMMENT '属性类型（0输入框 1单选）',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `zy_goods_specs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shopid` int(11) NOT NULL COMMENT '店铺id',
  `goodsid` int(11) NOT NULL COMMENT '商品id',
  `specid` varchar(255) NOT NULL COMMENT '组合',
  `specids` varchar(255) NOT NULL COMMENT '组合参数',
  `makerprice` decimal(11,2) NOT NULL COMMENT '市场价',
  `specprice` decimal(11,2) NOT NULL COMMENT '本店价',
  `specstock` int(11) NOT NULL COMMENT '库存',
  `salenum` int(11) NOT NULL COMMENT '销量',
  `status` TINYINT(1) NOT NULL COMMENT '是否启用（1启用 0禁用）',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `zy_goods_attr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shopid` int(11) NOT NULL COMMENT '店铺id',
  `goodsid` int(11) NOT NULL COMMENT '商品id',
  `attrid` int(11) NOT NULL COMMENT '关联属性id',
  `val` varchar(255) NOT NULL COMMENT '属性值',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `zy_goods_sh` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL COMMENT '用户ID',
  `searchHistory` text NOT NULL COMMENT '搜索历史',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `zy_goodscarts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `ischeck` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否选中（1选中 0未选中）',
  `goodsid` int(11) NOT NULL DEFAULT '0' COMMENT '商品ID',
  `goodsspecid` varchar(200) NOT NULL DEFAULT '0' COMMENT '商品规格',
  `cartnum` int(11) NOT NULL DEFAULT '0' COMMENT '购买数量',
  PRIMARY KEY (`id`),
  KEY `userId` (`userid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `zy_group_buy_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) COMMENT '用户ID',
  `orderid` int(11) COMMENT '订单id',
  `goodsid` int(11) COMMENT '商品ID',
  `waitingtime` VARCHAR(20) NOT NULL DEFAULT '0' COMMENT '等待时间',
  `addtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
