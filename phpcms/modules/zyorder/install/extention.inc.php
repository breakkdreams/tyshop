<?php
defined('IN_PHPCMS') or exit('Access Denied');
defined('INSTALL') or exit('Access Denied');
$parentid = $menu_db->insert(array('name'=>'ordermodule', 'parentid'=>0, 'm'=>'fkyd', 'c'=>'fkyd', 'a'=>'init', 'data'=>'', 'listorder'=>0, 'display'=>'1'), true);

$pid = $menu_db->insert(
	array(
		'name'=>'order', //菜单名称
		'parentid'=>$parentid, //添加到后台的主菜单里
		'm'=>'zyorder', //模块
		'c'=>'order', //文件
		'a'=>'init',//方法
		'data'=>'', //附加参数
		'listorder'=>0, //菜单排序
		'display'=>'1'), //显示菜单 1是显示 0是隐藏
		true //插入菜单之后，是否返回id
	);

$menu_db->insert(
	array(
		'name'=>'order_list', //菜单名称
		'parentid'=>$pid, //添加到后台的主菜单里
		'm'=>'zyorder', //模块
		'c'=>'order', //文件
		'a'=>'order_list',//方法
		'data'=>'', //附加参数
		'listorder'=>0, //菜单排序
		'display'=>'1'), //显示菜单 1是显示 0是隐藏
		true //插入菜单之后，是否返回id
	);
//	$pid = $menu_db->insert(
//	array(
//		'name'=>'logistics', //菜单名称
//		'parentid'=>$parentid, //添加到后台的主菜单里
//		'm'=>'zyorder', //模块
//		'c'=>'order', //文件
//		'a'=>'logistics',//方法
//		'data'=>'', //附加参数
//		'listorder'=>0, //菜单排序
//		'display'=>'1'), //显示菜单 1是显示 0是隐藏
//		true //插入菜单之后，是否返回id
//	);
//	$menu_db->insert(
//	array(
//		'name'=>'logistics_company', //菜单名称
//		'parentid'=>$pid, //添加到后台的主菜单里
//		'm'=>'zyorder', //模块
//		'c'=>'order', //文件
//		'a'=>'logistics_company',//方法
//		'data'=>'', //附加参数
//		'listorder'=>0, //菜单排序
//		'display'=>'1'), //显示菜单 1是显示 0是隐藏
//		true //插入菜单之后，是否返回id
//	);
$language = array
(
    'ordermodule'=>'订单模板',
	'logistics'=>'物流管理',
	'logistics_company'=>'物流配置',
	'order'=>'订单管理',
	'order_list'=>'订单管理列表',
);
?>
