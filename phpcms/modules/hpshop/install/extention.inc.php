<?php
// +------------------------------------------------------------
// | distribution
// +------------------------------------------------------------
// | 卓远网络：CY QQ:185017580 http://www.300c.cn/
// +------------------------------------------------------------
// | 欢迎加入卓远网络-Team，和卓远一起，精通PHPCMS
// +------------------------------------------------------------
// | 版本号：20180208
// +------------------------------------------------------------
defined('IN_PHPCMS') or exit('Access Denied');
defined('INSTALL') or exit('Access Denied');

/**
 * 添加父级菜单:后台添加一个卓远商城菜单
 */



//先判断有没有卓远网络的大菜单
$zywldb = $menu_db->get_one(array('name'=>'hpshop','parentid'=>'0'));
if($zywldb){
	$parentid =$zywldb['id'];
}else{
	$parentid = $menu_db->insert(
	array(
		'name'=>'hpshop',
		'parentid'=>'0',
		'm'=>'hpshop',
		'c'=>'goods',
		'a'=>'init',
		'data'=>'',
		'listorder'=>9,
		'display'=>'1'
		),
	true
    );
}

/**
 * 添加菜单:订单管理
 */
$pid = $menu_db->insert(
	array(
		'name'=>'goodsmanage', //菜单名称
		'parentid'=>$parentid, //添加到后台的主菜单里
		'm'=>'hpshop', //模块
		'c'=>'goods', //文件
		'a'=>'init',//方法
		'data'=>'', //附加参数
		'listorder'=>0, //菜单排序
		'display'=>'1'), //显示菜单 1是显示 0是隐藏
		true //插入菜单之后，是否返回id
	);

/**
 * 添加子菜单  商品列表
 */
$menu_db->insert(
	array(
		'name'=>'goodstype', //菜单名称
		'parentid'=>$pid, //添加到积分商城。
		'm'=>'hpshop', //模块
		'c'=>'goods',//文件 
		'a'=>'goodstype', //方法
		'data'=>'', //附加参数
		'listorder'=>1, //菜单排序
		'display'=>'1' //显示菜单 1是显示 0是隐藏
		)
	);

$menu_db->insert(
    array(
        'name'=>'goodslist', //菜单名称
        'parentid'=>$pid, //添加到积分商城。
        'm'=>'hpshop', //模块
        'c'=>'goods',//文件
        'a'=>'goodslist', //方法
        'data'=>'', //附加参数
        'listorder'=>1, //菜单排序
        'display'=>'1' //显示菜单 1是显示 0是隐藏
    )
);

//$menu_db->insert(
//    array(
//        'name'=>'promotion', //菜单名称
//        'parentid'=>$pid, //添加到积分商城。
//        'm'=>'hpshop', //模块
//        'c'=>'goods',//文件
//        'a'=>'promotionlist', //方法
//        'data'=>'', //附加参数
//        'listorder'=>1, //菜单排序
//        'display'=>'1' //显示菜单 1是显示 0是隐藏
//    )
//);
//
//$menu_db->insert(
//    array(
//        'name'=>'groupbuy', //菜单名称
//        'parentid'=>$pid, //添加到积分商城。
//        'm'=>'hpshop', //模块
//        'c'=>'goods',//文件
//        'a'=>'groupbuylist', //方法
//        'data'=>'', //附加参数
//        'listorder'=>1, //菜单排序
//        'display'=>'1' //显示菜单 1是显示 0是隐藏
//    )
//);


/**
 * 添加菜单:分类管理
 */
$pids = $menu_db->insert(
	array(
		'name'=>'categorymanage', //菜单名称
		'parentid'=>$parentid, //添加到后台的主菜单里
		'm'=>'hpshop', //模块
		'c'=>'category', //文件
		'a'=>'init',//方法
		'data'=>'', //附加参数
		'listorder'=>1, //菜单排序
		'display'=>'1'), //显示菜单 1是显示 0是隐藏
		true //插入菜单之后，是否返回id
	);


/**
 * 添加子菜单  栏目分类
 */
$menu_db->insert(
	array(
		'name'=>'goodscategory', //菜单名称
		'parentid'=>$pids, //添加到积分商城。
		'm'=>'hpshop', //模块
		'c'=>'category',//文件
		'a'=>'categorylist', //方法
		'data'=>'', //附加参数
		'listorder'=>0, //菜单排序
		'display'=>'1' //显示菜单 1是显示 0是隐藏
		)
	);



/**
 * 菜单名称翻译
 */
$language = array(
	'hpshop'=>'商品管理',
	'goodsmanage'=>'商品管理',
	'goodslist'=>'商品列表',
	'goodsbrand'=>'商品品牌',
	'goodsposition'=>'推荐位管理',
	'goodsverify'=>'商品审核',
	'categorymanage'=>'分类管理',
	'goodscategory'=>'商品分类',
	'goodstype'=>'商品类型',
	'zyconfig'=>'配置管理',
	'zyconfigss'=>'商品配置',
    'promotion'=>'促销管理',
    'groupbuy'=>'团购管理',
);

?>