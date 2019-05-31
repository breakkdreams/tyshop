DROP TABLE IF EXISTS `zy_chatlog`;
CREATE TABLE `zy_chatlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键字段',
  `fromuserid` int(11) DEFAULT '0' COMMENT '发送的用户id',
  `touserid` int(11) DEFAULT '0' COMMENT '接受的用户id',
  `content` varchar(255) DEFAULT NULL COMMENT '内容',
  `create_date` int(11) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='聊天记录';
