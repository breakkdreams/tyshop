<?php
defined('IN_PHPCMS') or exit('Access Denied');
defined('INSTALL') or exit('Access Denied');


/**
 * 添加父级菜单:后台添加一个卓远商城菜单
 */
//先判断有没有卓远网络的大菜单
$zywldb = $menu_db->get_one(array('name'=>'zyaddrsys','parentid'=>'0'));
if($zywldb){
	$parentid =$zywldb['id'];
}else{
	$parentid = $menu_db->insert(
	array(
		'name'=>'zyaddrsys',
		'parentid'=>'0',
		'm'=>'zyaddr',
		'c'=>'zyaddr',
		'a'=>'init',
		'data'=>'',
		'listorder'=>8,
		'display'=>'1'
		),
	true
    );
}

/**
 * 添加菜单:地址配置
 */
$pid = $menu_db->insert(
	array(
		'name'=>'zyaddrconfig', //菜单名称
		'parentid'=>$parentid, //添加到后台的主菜单里
		'm'=>'zylinkage', //模块
		'c'=>'zylinkage', //文件
		'a'=>'init',//方法
		'data'=>'', //附加参数
		'listorder'=>0, //菜单排序
		'display'=>'1'), //显示菜单 1是显示 0是隐藏
		true //插入菜单之后，是否返回id
	);

/**
 * 添加子菜单:联动菜单
 */
$userid = $menu_db->insert(
	array(
		'name'=>'zylinkage', //菜单名称
		'parentid'=>$pid, //添加到积分商城。
		'm'=>'zylinkage', //模块
		'c'=>'zylinkage',//文件 
		'a'=>'init', //方法
		'data'=>'', //附加参数
		'listorder'=>1, //菜单排序
		'display'=>'1' //显示菜单 1是显示 0是隐藏
		),true//插入菜单之后，是否返回id
	);

	
/**
 * 添加子菜单:配置模块
 */
$zywldbs = $menu_db->get_one(array('name'=>'zyconfigmenu','parentid'=>'0'));
if($zywldbs){
	$parentids =$zywldbs['id'];
}else{
	$parentids = $menu_db->insert(
		array(
			'name'=>'zyconfigmenu',
			'parentid'=>'0',
			'm'=>'zyconfig',
			'c'=>'config',
			'a'=>'init',
			'data'=>'',
			'listorder'=>9,
			'display'=>'1'
		),
		true
	);
}

/**
 * 添加菜单:配置管理
 */
$zywl = $menu_db->get_one(array('name'=>'zyconfig','m'=>'pubconfig','c'=>'pubconfig','a'=>'init'));
if($zywl){
	$sid =$zywl['id'];
}else{
	$sid = $menu_db->insert(
		array(
			'name'=>'zyconfig',
			'parentid'=>$parentids,
			'm'=>'pubconfig',
			'c'=>'pubconfig',
			'a'=>'init',
			'data'=>'',
			'listorder'=>0,
			'display'=>'1'
		),
		true
	);
}

/**
 * 添加子菜单:联动菜单配置
 */
$sids = $menu_db->insert(
	array(
		'name'=>'zyaddr_linkage', //菜单名称
		'parentid'=>$sid, //添加到积分商城。
		'm'=>'zylinkage', //模块
		'c'=>'zylinkage',//文件
		'a'=>'zyconfig', //方法
		'data'=>'', //附加参数
		'listorder'=>1, //菜单排序
		'display'=>'1' //显示菜单 1是显示 0是隐藏
	),true//插入菜单之后，是否返回id
);

/**
 * 菜单名称翻译
 */	
$language = array(
	'zyaddrsys'=>'地址管理',
	'zyaddrconfig'=>'地址配置',
	'zylinkage' =>'联动菜单',
	'zyconfigmenu'=>'配置模块',
	'zyconfig'=>'配置管理',
	'zyaddr_linkage'=>'联动菜单配置',
);


?>