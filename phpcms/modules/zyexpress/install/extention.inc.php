<?php
// +------------------------------------------------------------
// | zyexpress
// +------------------------------------------------------------
// | 卓远网络：CY QQ:185017580 http://www.300c.cn/
// +------------------------------------------------------------
// | 欢迎加入卓远网络-Team，和卓远一起，精通PHPCMS
// +------------------------------------------------------------
// | 版本号：20190125
// +------------------------------------------------------------
defined('IN_PHPCMS') or exit('Access Denied');
defined('INSTALL') or exit('Access Denied');

/**
 * 添加父级菜单:后台添加一个卓远商城菜单
 */

//先判断有没有卓远网络的大菜单
$zywldb = $menu_db->get_one(array('name'=>'ordermodule','parentid'=>'0'));
if($zywldb){
	$parentid =$zywldb['id'];
}else{
	$parentid = $menu_db->insert(
	array(
		'name'=>'ordermodule',
		'parentid'=>'0',
		'm'=>'zyorder',
		'c'=>'order',
		'a'=>'init',
		'data'=>'',
		'listorder'=>9,
		'display'=>'1'
		),
	true
    );
}

/**
 * 添加菜单:充值提现管理
 */
$pid = $menu_db->insert(
	array(
		'name'=>'zyexpress', //菜单名称
		'parentid'=>$parentid, //添加到后台的主菜单里
		'm'=>'zyexpress', //模块
		'c'=>'zyexpress', //文件
		'a'=>'init',//方法
		'data'=>'', //附加参数
		'listorder'=>0, //菜单排序
		'display'=>'1'), //显示菜单 1是显示 0是隐藏
		true //插入菜单之后，是否返回id
	);

/**
 * 添加子菜单  账号管理
 */
$menu_db->insert(
	array(
		'name'=>'kd_manage', //菜单名称
		'parentid'=>$pid, //添加到积分商城。
		'm'=>'zyexpress', //模块
		'c'=>'zyexpress',//文件 
		'a'=>'kd_manage', //方法
		'data'=>'', //附加参数
		'listorder'=>0, //菜单排序
		'display'=>'1' //显示菜单 1是显示 0是隐藏
		)
	);
/**
 * 添加子菜单  账号管理
 */
$menu_db->insert(
	array(
		'name'=>'kd_search', //菜单名称
		'parentid'=>$pid, //添加到积分商城。
		'm'=>'zyexpress', //模块
		'c'=>'zyexpress',//文件 
		'a'=>'kd_search', //方法
		'data'=>'', //附加参数
		'listorder'=>0, //菜单排序
		'display'=>'1' //显示菜单 1是显示 0是隐藏
		)
	);
/**
 * 添加子菜单  账号管理
 */
$menu_db->insert(
	array(
		'name'=>'kd_code', //菜单名称
		'parentid'=>$pid, //添加到积分商城。
		'm'=>'zyexpress', //模块
		'c'=>'zyexpress',//文件 
		'a'=>'kd_code', //方法
		'data'=>'', //附加参数
		'listorder'=>0, //菜单排序
		'display'=>'1' //显示菜单 1是显示 0是隐藏
		)
	);
/**
 * 菜单名称翻译
 */
$language = array(
	'ordermodule'=>'订单系统',
	'zyexpress'=>'快递摸块',
	'kd_manage'=>'快递配置',
	'kd_search'=>'快递查询',
	'kd_code'=>'快递公司管理',
);

?>