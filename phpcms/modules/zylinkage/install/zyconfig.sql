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
-- 表的结构 `zy_zyconfig`
--
CREATE TABLE IF NOT EXISTS `zy_zyconfig` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `config_name` varchar(255) NOT NULL COMMENT '配置名称',
  `model_name` varchar(255) NOT NULL COMMENT '所需模块',
  `item_name` varchar(255) NOT NULL COMMENT '模块项目名',
  `url` varchar(255) NOT NULL COMMENT '地址',
  `api_url` varchar(255) NOT NULL COMMENT 'API地址',
  `explain` text NOT NULL COMMENT '说明',
  `api_explain` text NOT NULL COMMENT 'api说明',
  `key` VARCHAR(255) NOT NULL COMMENT '配置表的关键字',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

DELETE FROM zy_zyconfig WHERE `item_name`='zylinkage';
INSERT INTO `zy_zyconfig` (`config_name`, `model_name`, `item_name`, `url`, `api_url`, `explain`, `api_explain`, `key`) VALUES
('获取地区', 'zylinkage 联动菜单', 'zylinkage', 'http://pub.300c.cn/index.php?m=zylinkage&c=api&a=listall', '域名/index.php?m=zylinkage&c=api&a=listall', '一、应用模块：联动菜单      配置来源：地址模块\r\n\r\n二、用途：地址模块——显示列表\r\n\r\n三、提供参数：\r\n\r\n 1）请求参数说明：\r\n     id :地区id \r\n\r\n 2）返回格式： json\r\n\r\n 3）请求方式： http  get', '一、请求参数：\r\n\r\n\r\n 1）请求参数说明：\r\n     id :地区id \r\n\r\n 2）返回格式： json\r\n\r\n 3）请求方式： http  get\r\n\r\n\r\n\r\n\r\n二、返回信息 :\r\n\r\n\r\n\r\n  返回格式：{\r\n    "status": "success",\r\n    "code": 200,\r\n    "message": "获取成功",\r\n    "data": [\r\n        {\r\n            "linkageid": "13",\r\n            "name": "钓鱼岛",\r\n        }\r\n    ]\r\n}\r\n\r\n\r\n\r\n\r\n\r\n三、返回字段解释：\r\n\r\n\r\n\r\n  status: 操作成功/操作失败\r\n\r\n  code: 操作状态\r\n\r\n  message: 提示信息\r\n\r\n  data: [ ] 数据组\r\n\r\n     data.linkageid: 地区id\r\n\r\n     data.name: 地区名称\r\n\r\n\r\n\r\n\r\n\r\n\r\n四、状态信息说明：\r\n\r\n\r\n\r\n 200：操作成功\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n五、实例代码：\r\n\r\n\r\n\r\n<script type="javascript/text">\r\n\r\n$.ajax({\r\n\r\n  url:''域名/index.php?m=zylinkage&c=api&a=listall'',\r\n\r\n  data:{id:1,userid:1},\r\n\r\n  dataType:''json'',\r\n\r\n  type:''post'',\r\n\r\n  success:function(res){\r\n\r\n {\r\n    "status": "success",\r\n    "code": 200,\r\n    "message": "获取成功",\r\n    "data": [\r\n        {\r\n            "linkageid": "13",\r\n            "name": "钓鱼岛",\r\n        }\r\n    ]\r\n}\r\n\r\n}\r\n\r\n  },\r\n\r\n});\r\n\r\n</script>', 'zylinkage1');
