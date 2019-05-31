<?php
defined('IN_PHPCMS') or exit('Access Denied');
defined('INSTALL') or exit('Access Denied');


/**
 * 添加父级菜单:后台添加一个卓远商城菜单
 */
//先判断有没有卓远网络的大菜单
$zywldb = $menu_db->get_one(array('name'=>'zymembermenu','parentid'=>'0'));
if($zywldb){
	$parentid =$zywldb['id'];
}else{
	$parentid = $menu_db->insert(
	array(
		'name'=>'zymembermenu', 
		'parentid'=>'0', 
		'm'=>'zymember', 
		'c'=>'zymember', 
		'a'=>'init', 
		'data'=>'', 
		'listorder'=>9,   
		'display'=>'1'
		),
	true
    );
}

/**
 * 添加菜单:会员管理
 */
$pid = $menu_db->insert(
	array(
		'name'=>'zymember_manage', //菜单名称
		'parentid'=>$parentid, //添加到后台的主菜单里
		'm'=>'zymember', //模块
		'c'=>'zymember', //文件
		'a'=>'zymember_manage',//方法
		'data'=>'', //附加参数
		'listorder'=>0, //菜单排序
		'display'=>'1'), //显示菜单 1是显示 0是隐藏
		true //插入菜单之后，是否返回id
	);

/**
 * 添加子菜单:会员管理
 */
$userid = $menu_db->insert(
	array(
		'name'=>'zymember_manage_menu', //菜单名称
		'parentid'=>$pid, //添加到积分商城。
		'm'=>'zymember', //模块
		'c'=>'zymember',//文件 
		'a'=>'manage', //方法
		'data'=>'', //附加参数
		'listorder'=>1, //菜单排序
		'display'=>'1' //显示菜单 1是显示 0是隐藏
		),true//插入菜单之后，是否返回id
	);

/**
 * 添加菜单:会员模型管理
 */
$pids = $menu_db->insert(
	array(
		'name'=>'zymember_model', //菜单名称
		'parentid'=>$parentid, //添加到后台的主菜单里
		'm'=>'zymember', //模块
		'c'=>'zymember', //文件
		'a'=>'zymember_model',//方法
		'data'=>'', //附加参数
		'listorder'=>0, //菜单排序
		'display'=>'1'), //显示菜单 1是显示 0是隐藏
	true //插入菜单之后，是否返回id
);

/**
 * 添加子菜单:会员模型管理
 */
$userids = $menu_db->insert(
	array(
		'name'=>'zymember_model_manage', //菜单名称
		'parentid'=>$pids, //添加到积分商城。
		'm'=>'zymember', //模块
		'c'=>'zymember',//文件
		'a'=>'model', //方法
		'data'=>'', //附加参数
		'listorder'=>1, //菜单排序
		'display'=>'1' //显示菜单 1是显示 0是隐藏
	),true//插入菜单之后，是否返回id
);



/**
 * 添加子菜单:会员基本设置
 */
$agentid = $menu_db->insert(
	array(
		'name'=>'member_config', //菜单名称
		'parentid'=>$pids, //添加到积分商城。
		'm'=>'zymember', //模块
		'c'=>'zymember',//文件 
		'a'=>'config', //方法
		'data'=>'', //附加参数
		'listorder'=>2, //菜单排序
		'display'=>'1' //显示菜单 1是显示 0是隐藏
		),true//插入菜单之后，是否返回id
	);
		
/**
 * 添加子菜单:会员组管理
 */
$managerid = $menu_db->insert(
	array(
		'name'=>'member_group2', //菜单名称
		'parentid'=>$pids, //添加到积分商城。
		'm'=>'zymember', //模块
		'c'=>'zymember',//文件 
		'a'=>'group', //方法
		'data'=>'', //附加参数
		'listorder'=>3, //菜单排序
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
 * 添加子菜单:通讯配置
 */
$sids = $menu_db->insert(
	array(
		'name'=>'zymember_configs', //菜单名称
		'parentid'=>$sid, //添加到积分商城。
		'm'=>'zymember', //模块
		'c'=>'zymember',//文件
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
	'zymembermenu'=>'会员系统',
	'zymember_manage'=>'会员管理',
	'zymember_manage_menu' =>'会员管理',
	'zymember_model' =>'会员模型管理',
	'zymember_model_manage' =>'会员模型管理',
	'member_config'=>'会员基本设置',
	'member_group2'=>'会员组管理',
	'zyconfigmenu'=>'配置模块',
	'zyconfig'=>'配置管理',
	'zymember_configs'=>'会员配置',
);


?>