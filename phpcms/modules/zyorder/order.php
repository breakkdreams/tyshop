<?php

defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin', 'admin', 0);	//加载应用类方法
pc_base::load_sys_class('form', 0, 0);
pc_base::load_app_func('global');

class order extends admin {
	/**
	*构造函数，初始化
	*/
	public function __construct()
	{
		
		//开启session会话
		session_start();
		//初始化父级的构造函数
		parent::__construct();
		//会员主表
		$this->members_db = pc_base::load_model('members_model');
		$this->member_db = pc_base::load_model('member_model');
		//会员附表
		$this->member_detail_db = pc_base::load_model('member_detail_model');
		$this->shop_db = pc_base::load_model('shop_model');
		//订单中心
		$this->order_db = pc_base::load_model('zy_order_model');
		$this->evaluate_set_db = pc_base::load_model('zy_evaluate_set_model');
	    $this->logistics_company_db = pc_base::load_model('zy_logistics_company_model');
        $this->zyconfig_db = pc_base::load_model('zyconfig_model');
		$this->module_db = pc_base::load_model('module_model');
		$this->config = $this->zyconfig_db->get_one('','url');
		$this->ordergoods_db = pc_base::load_model('zy_order_goods_model');
		//引入卓远网络公共函数库
		//require_once 'zywl/functions/global.func.php';
        $this->express_db = pc_base::load_model('zyexpress_model');
	}

/**
* 菜单===========================================
*/
//订单管理
//	--订单管理
//	--订单管理_商品信息
//	--订单管理_购买者信息
//	--订单管理_物流信息
//	--订单管理_订单发货
//	--订单管理_删除
//	--物流管理
//	--订单管理_添加物流
//	--订单管理_删除
//	--设置关键词
//	--清除缓存
		



/**
* 订单管理===========================================
*/
	
	
    
	
	
	/**
	* 订单管理
	*/
	public function order_list(){		
		$where = '1';
		if($_GET['type']){
			if($_GET['q']){
				if($_GET['type'] == 1){
					$where .= " and ordersn ='".$_GET['q']."'";
				}
				if($_GET['type'] == 2){
					$where .= " and userid ='".$_GET['q']."'";
				}
				if($_GET['type'] == 3){
					$where .= " and username ='".$_GET['q']."'";
				}
				if($_GET['type'] == 4){
					$where .= " and mobile ='".$_GET['q']."'";
				}
			}
		}
		if($_GET['status']){
			if($_GET['status'] == 1){
				$where .= " and status=1";
			}else if($_GET['status'] == 2){
				$where .= " and status=2";
			}else if($_GET['status'] == 3){
				$where .= " and status=3";
			}else if($_GET['status'] == 4){
				$where .= " and status=4";
			}else if($_GET['status'] == 5){
				$where .= " and status=5";
			}
		}
		if($_GET['start_addtime']){
			$where .= " and addtime >= '".strtotime($_GET['start_addtime'])."'";
		}
		if($_GET['end_addtime']){
			$where .= " and addtime <= '".strtotime($_GET['end_addtime'])."'";
		}

		$order = 'id DESC';
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$info=$this->order_db->listinfo($where,$order,$page,20); //读取数据库里的字段
		$pages = $this->order_db->pages;  //分页
		include $this->admin_tpl('order_manage'); //和模板对应上
	}
	

	/**
	* 订单管理_删除
	*/
	public function order_manage_del(){
		//删除单个
		$id=intval($_GET['id']);
		if($id){
			$result=$this->order_db->delete(array('id'=>$id));
			if($result)
			{
				showmessage(L('operation_success'),HTTP_REFERER);
			}else {
				showmessage(L("operation_failure"),HTTP_REFERER);
			}
		}

		//批量删除；
		if(is_array($_POST['id'])){
			foreach($_POST['id'] as $pid) {
				$result=$this->order_db->delete(array('id'=>$pid));
			}
			showmessage(L('operation_success'),HTTP_REFERER);
		}

		//都没有选择删除什么
		if( empty($_POST['id'])){
			showmessage('请选择要删除的信息',HTTP_REFERER);
		}
	}
	


	/**
	* 订单管理_用户信息
	*/
	public function order_manage_userinfo(){
		$show_header = false;		//去掉最上面的线条		
		$id = $_GET['id'];
		$info = $this->order_db->get_one(array('id' =>$id));
		include $this->admin_tpl('order_manage_userinfo');  //和模板对应上
		
	}
	
	
	
	/**
	* 订单管理_商品信息
	*/
	public function order_manage_shopinfo(){
    
		$info = $this->ordergoods_db->select(array('order_id'=>$_GET['id']));
		include $this->admin_tpl('order_manage_shopinfo');  //和模板对应上

	}

    /**
     * 订单管理_修改价格页面
     */
    public function order_edit_price(){
        $id = $_GET['id'];
        $info = $this->ordergoods_db->select(array('order_id'=>$id));
        $orderinfo = $this->order_db->get_one(array('id' =>$id));
        include $this->admin_tpl('order_edit_price');  //和模板对应上

    }
    /**
     * 订单管理_修改价格操作
     */
    public function update_price(){
        $orderid = $_POST['orderid'];
        $freeship = $_POST['freeship'];
        $orderinfo = $this->order_db->get_one(array('id' =>$orderid));
        $updateprice = 0;
        if($orderinfo['status'] == 1){
            $ordergoods = $this->ordergoods_db->select(array('order_id'=>$orderid));
            for ($i = 0; $i < sizeof($ordergoods); $i++) {
                $order_goods_id = $ordergoods[$i]['id'];
                $p = 'price_'.$order_goods_id;
                $goods_price = $_POST[$p];
                $result = $this->ordergoods_db->update(array('goods_price' => $goods_price), array('id' => $order_goods_id));

                $updateprice += $goods_price;
            }
            $updateprice += $freeship;
            $result = $this->order_db->update(array('totalprice' => $updateprice, 'freeship' => $freeship), array('id' => $orderid));
            showmessage(L('update_success'), HTTP_REFERER);
        }else{
            showmessage(L("operation_failure"),HTTP_REFERER);
        }
    }

    /**
     * 订单管理_修改收货地址
     */
    public function order_update_address(){

        if($_POST['dosubmit']){
            $id = $_POST['id'];
            $lx_name = $_POST['lx_name'];
            $lx_mobile = $_POST['lx_mobile'];
            $province = $_POST['province'];
            $city = $_POST['city'];
            $area = $_POST['area'];
            $address = $_POST['address'];
            $result = $this->order_db->update(array('lx_mobile' => $lx_mobile, 'lx_name' => $lx_name, 'province' => $province, 'city' => $city, 'area' => $area, 'address' => $address), array('id' => $id));
            if($result){
                showmessage(L('update_success'), HTTP_REFERER);
            }else{
                showmessage(L("operation_failure"),HTTP_REFERER);
            }
        }else{
            $orderid = $_GET['id'];
            $info = $this->order_db->get_one(array('id' =>$orderid));
            include $this->admin_tpl('order_update_address');  //和模板对应上
        }
    }

    /**
     * 卖家关闭订单
     */
    public function closeorder(){
        $orderid = $_POST['id'];
        $order = $this->order_db->get_one(array('id' => $orderid));
        if ($order['status'] == 1) {
            $result = $this->order_db->update(array('status' => 11, 'prestatus' => $order['status']), array('id' => $orderid));
            if ($result) {
                $data = [
                    "status" => 'success',
                    "code" => 200,
                    "message" => '关闭成功',
                ];
                exit(json_encode($data));
            } else {
                $data = [
                    "status" => 'error',
                    "code" => 300,
                    "message" => '关闭失败',
                ];
                exit(json_encode($data));
            }
        } else {
            $data = [
                "status" => 'error',
                "code" => 300,
                "message" => '该状态不支持关闭',
            ];
            exit(json_encode($data));
        }
    }




    /**
	* 订单管理_订单发货_添加物流
	*/
	public function order_manage_ddfh(){	
		
		if($_POST['dosubmit']){
			$wuliu = $this->express_db->get_one(array('code'=>$_POST['shippercode']));
			$info = $this->order_db->update(array('shipper_name' =>$wuliu['company'],'shipper_code' =>$wuliu['code'],'logistics_order' =>$_POST['logistics_order'],'fhtime'=>time(),'status'=>'3'),array('id'=>$_POST['id']));
			//发送模板消息‘订单发货通知’	start
			
			/**
			 * 微信模板消息_订单发货通知
			 * @param  [type] $touser [用户openid]
			 * @param  [type] $url 	[跳转的链接地址]
			 * @param  [type] $name    [收货人姓名]
			 * @param  [type] $mobile  [收货人手机号]
			 * @param  [type] $kd_gs  [快递公司]
			 * @param  [type] $kd_order  [快递单号]
			 * @param  [type] $order_sn  [订单号]
			 * @return [type]         [description]
			 */
			 //发送模板消息‘订单支付状态通知’	end
			
			if($info){
				showmessage(L('update_success'), HTTP_REFERER);
			}else{
				showmessage(L("operation_failure"),HTTP_REFERER);
			}		
		}else{
			$orderid = $_GET['id'];
			$info = $this->express_db->get_one(array('id' =>$orderid));
			$infok = $this->express_db->select();
			include $this->admin_tpl('order_manage_ddfh');  //和模板对应上
		}
	}
	
		/**
	* 订单管理_查看物流信息
	*/
	public function order_manage_wlxx(){	

			$id = $_GET['id'];
		    $order =  $this->order_db->get_one(array('id'=>$id));
		    $KdApi = pc_base::load_app_class('KdApiSearch');
			$KdApi = new KdApiSearch();
	
			$logisticResult=$KdApi->getOrderTracesByJson($order['shipper_code'],$order['logistics_order']);
			$logisticResult = json_decode($logisticResult,true);
			include $this->admin_tpl('order_manage_wlxx');  //和模板对应上
	
	}

   
    /**
	* 物流管理
	*/
	public function logistics_company(){
		$where = '1';
		$name=isset($_GET['name'])?$_GET['name']:'';


		if(!empty($name)){
			$where .= " and name like '%$name%'  ";
		}

		$order = 'id ASC';
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		
    
		$info=$this->logistics_company_db->listinfo($where,$order,$page); //读取数据库里的字段 第四个参数 为每页多少条信息
		$pages = $this->logistics_company_db->pages;  //分页

		include $this->admin_tpl('order_logistics');  //和模板对应上
	}
/**
	* 物流管理_删除
	*/
	public function order_logistics_del(){
		//删除单个
		$id=intval($_GET['id']);
		if($id){
			$result=$this->logistics_company_db->delete(array('id'=>$id));
			if($result)
			{
				showmessage(L('operation_success'),HTTP_REFERER);
			}else {
				showmessage(L("operation_failure"),HTTP_REFERER);
			}
		}

		//批量删除；
		if(is_array($_POST['id'])){
			foreach($_POST['id'] as $pid) {
				$result=$this->zyorder_kuaidi_db->delete(array('id'=>$pid));
			}
			showmessage(L('operation_success'),HTTP_REFERER);
		}

		//都没有选择删除什么
		if( empty($_POST['id'])){
			showmessage('请选择要删除的信息',HTTP_REFERER);
		}
	}
	/**
	* 物流管理_添加
	*/
	public function order_logistics_add(){
		if($_POST['dosubmit']){
			$info = $this->logistics_company_db->insert(array('name' =>trim($_POST['name']),'value' =>trim($_POST['value'])));

			if($info){
				showmessage('添加成功', HTTP_REFERER);
			}else{
				showmessage('添加失败', HTTP_REFERER);
			}		
		}else{
			include $this->admin_tpl('order_logistics_add');  //和模板对应上
		}
	}

	
	//评价
	public function evaluate_set(){
			$big_menu = array

		(

		    	'javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=zymessagesys&c=messagesys&a=configadd\', title:\'添加配置\', width:\'700\', height:\'200\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function()	{window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加配置'

		);

		$where = '1';
		$name=isset($_GET['name'])?$_GET['name']:'';


		if(!empty($name)){
			$where .= " and name like '%$name%'  ";
		}

		$order = 'id ASC';
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		
    
		$info=$this->evaluate_set_db->listinfo($where,$order,$page); //读取数据库里的字段 第四个参数 为每页多少条信息
		$pages = $this->evaluate_set_db->pages;  //分页

		include $this->admin_tpl('evaluate_set');  //和模板对应上
	}

    public function evaluate_set_add(){

	    if($_POST){
	    	$name = $_POST['name'];
	    	$value = $_POST['value'];
	    	$data = [
                 "name"=>$name,
                 "value"=>$value
	    	];
            $result  = $this->evaluate_set_db->insert($data);
            if($result){
				showmessage('添加成功', HTTP_REFERER);
			}else{
				showmessage('添加失败', HTTP_REFERER);
			}	
	    }else{
		include $this->admin_tpl('evaluate_set_add');  //和模板对应上
	    }
	}
	
	public function evaluate_set_del(){
		if($_POST){
			foreach($_POST['id'] as $id){
		        $this->evaluate_set_db->delete(array('id'=>$id));
			}
			showmessage("删除成功",HTTP_REFERER);
		}
	}

	public function config()
	{
		$big_menu = array
		(
			'javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=zyorder&c=order&a=configadd\', title:\'添加配置\', width:\'700\', height:\'200\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function()	{window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加配置'
		);
		
		$where = ['item_name'=>'zyorder'];
		$order = 'id DESC';
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$info=$this->zyconfig_db->listinfo($where,$order,$page,20);
		$pages = $this->zyconfig_db->pages;

		include $this->admin_tpl('zyconfig');
	}
	
	/*
	 * 添加配置
	 * */
	public function configadd()
	{

		if($_POST['dosubmit'])
		{
			if(empty($_POST['config_name']))
			{
				showmessage('请输入项目名',HTTP_REFERER);
			}
			$zyconfig_num = $this->zyconfig_db->count(['item_name'=>'zyorder']);
			$car=array
			(
				'config_name'=>$_POST['config_name'],
				'model_name'=>$_POST['model_name'],
				'url'=>$_POST['url'],
				'item_name'=>'zyorder',
				'key'=>'zyorder'.($zyconfig_num+1),
			);

			$this->zyconfig_db->insert($car); //修改
			showmessage(L('operation_success'), '', '', 'add');
		}
		else
		{
			$into=$this->module_db->select();
			include $this->admin_tpl('zyconfigadd');
		}
	}
 
   
	/**
	 * 编辑配置界面
	 * @return [type] [description]
	 */
	public function configedit()
	{
		if(isset($_POST['dosubmit']))
		{
			$car=array
			(
				'url'=>$_POST['url'],
				'model_name'=>$_POST['model_name'],
			);
			$this->zyconfig_db->update($car, array('id'=>$_POST['id'])); //修改
			showmessage('操作完成','','','edit');
		}
		else
		{
			if(!$_GET['id'])
			{
				showmessage('id不能为空',HTTP_REFERER);
			}
			$into=$this->module_db->select();
			$info =$this->zyconfig_db->get_one(array('id'=>$_GET['id']));
			include $this->admin_tpl('zyconfigshow');
		}
	}

	/**
	 * 编辑文档界面
	 * @return [type] [description]
	 */
	public function configeditD()
	{
		if(isset($_POST['dosubmit']))
		{
			$car=array
			(
				'api_url'=>$_POST['api_url'],
				'explain'=>$_POST['explain'],
				'api_explain'=>$_POST['api_explain'],
			);
			$this->zyconfig_db->update($car, array('id'=>$_GET['id'])); //修改
			showmessage('操作完成','','','show');
		}
		else
		{
			if(!$_GET['id'])
			{
				showmessage('id不能为空',HTTP_REFERER);
			}
			$info =$this->zyconfig_db->get_one(array('id'=>$_GET['id']));
			include $this->admin_tpl('zyconfigdoc');
		}
	}

	/**
	 * 删除配置
	 * @return [type] [description]
	 */
	public function configdel()
	{
		if(intval($_GET['id']))
		{
			$result=$this->zyconfig_db->delete(array('id'=>$_GET['id']));
			if($result)
			{
				showmessage(L('operation_success'),HTTP_REFERER);
			}else {
				showmessage(L("operation_failure"),HTTP_REFERER);
			}
		}

		//批量删除；
		if(is_array($_POST['id']))
		{
			foreach($_POST['id'] as $id) {
				$result=$this->zyconfig_db->delete(array('id'=>$id));
			}
			showmessage(L('operation_success'),HTTP_REFERER);
		}

		//都没有选择删除什么
		if(empty($_POST['id'])) {
			showmessage('请选择要删除的记录',HTTP_REFERER);
		}
	}



	/**
     * 同意/拒绝退款
     */
	public function agree_refuse_tk (){
		$id = $_POST['id'];
		$isagree = $_POST['isagree'];//1.同意 2.拒绝
		if ( !$id ) {
			$result = [
				'status' => 'error',
				'code' => -1,
				'message' => '缺少必要参数！',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		}
		//查询订单
		$order =  $this->order_db->get_one(array('id'=>$id));
		//判断是否退款申请 = 7
		if($order['status'] == 7){
			if($isagree == 1){
				$result = $this->order_db->update(array('status'=>8,'shstatus'=>1),array('id'=>$id));
				 if($result){
					//返回用户余额与积分
					$member =  $this->member_db->get_one(array('userid'=>$order['userid']));
					//获取用户余额,积分
					$membermoney = $member['amount'];
					$memberscore = $member['scoremoney'];
					$ordermoney = $order['totalprice'];
					$orderscore = $order['scoreprice'];

					$totalmoney = $membermoney+$ordermoney;
					$totalscore = $memberscore+$orderscore;
					$this->member_db->update(array('scoremoney'=>$totalscore,'amount'=>$totalmoney),array('userid'=>$order['userid']));
					$result = [
						'status' => 'error',
						'code' => 1,
						'message' => '操作成功',
					];
					exit(json_encode($result,JSON_UNESCAPED_UNICODE));
				 }else{
					$result = [
						'status' => 'error',
						'code' => -1,
						'message' => '操作失败',
					];
					exit(json_encode($result,JSON_UNESCAPED_UNICODE));
				}
			}elseif($isagree == 2){
				$result = $this->order_db->update(array('status'=>$order['prestatus'],'shstatus'=>2),array('id'=>$id));
			}
		}else{
			$result = [
				'status' => 'error',
				'code' => -1,
				'message' => '未申请退款',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		}
	}


	/**
     * 同意/拒绝申请的退货
     */
	public function agree_refuse_apply_th (){
		$id = $_POST['id'];
		$isagree = $_POST['isagree'];//1.同意 2.拒绝
		if ( !$id ) {
			$result = [
				'status' => 'error',
				'code' => -1,
				'message' => '缺少必要参数！',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		}
		//查询订单
		$order =  $this->order_db->get_one(array('id'=>$id));
		//判断是否退款申请 = 10
		if($order['status'] == 10){
			if($isagree == 1){
				$result = $this->order_db->update(array('status'=>10,'shstatus'=>2),array('id'=>$id));
				 if($result){
					$result = [
						'status' => 'error',
						'code' => 1,
						'message' => '操作成功',
					];
					exit(json_encode($result,JSON_UNESCAPED_UNICODE));
				 }else{
					$result = [
						'status' => 'error',
						'code' => -1,
						'message' => '操作失败',
					];
					exit(json_encode($result,JSON_UNESCAPED_UNICODE));
				}
			}elseif($isagree == 2){
				$result = $this->order_db->update(array('status'=>$order['prestatus'],'shstatus'=>5),array('id'=>$id));
			}
		}else{
			$result = [
				'status' => 'error',
				'code' => -1,
				'message' => '未申请退货',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		}
	}

	/**
     * 同意/拒绝退货
     */
	public function agree_refuse_th (){
		$id = $_POST['id'];
		$isagree = $_POST['isagree'];//1.同意 2.拒绝
		if ( !$id ) {
			$result = [
				'status' => 'error',
				'code' => -1,
				'message' => '缺少必要参数！',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		}
		//查询订单
		$order =  $this->order_db->get_one(array('id'=>$id));
		//判断是否退款申请 = 12
		if($order['status'] == 10){
			if($isagree == 1){
				$result = $this->order_db->update(array('status'=>13,'shstatus'=>1),array('id'=>$id));
				 if($result){
					//返回用户余额与积分
					$member =  $this->member_db->get_one(array('userid'=>$order['userid']));
					//获取用户余额,积分
					$membermoney = $member['amount'];
					$memberscore = $member['scoremoney'];
					$ordermoney = $order['totalprice'];
					$orderscore = $order['scoreprice'];

					$totalmoney = $membermoney+$ordermoney;
					$totalscore = $memberscore+$orderscore;
					$this->member_db->update(array('scoremoney'=>$totalscore,'amount'=>$totalmoney),array('userid'=>$order['userid']));
					$result = [
						'status' => 'error',
						'code' => 1,
						'message' => '操作成功',
					];
					exit(json_encode($result,JSON_UNESCAPED_UNICODE));
				 }else{
					$result = [
						'status' => 'error',
						'code' => -1,
						'message' => '操作失败',
					];
					exit(json_encode($result,JSON_UNESCAPED_UNICODE));
				}
			}elseif($isagree == 2){
				$result = $this->order_db->update(array('status'=>$order['prestatus'],'shstatus'=>5),array('id'=>$id));
			}
		}else{
			$result = [
				'status' => 'error',
				'code' => -1,
				'message' => '未申请退货',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		}
	}


}
?>