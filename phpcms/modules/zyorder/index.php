<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_func('global');
pc_base::load_sys_class('form', '', 0);
pc_base::load_sys_class('format', '', 0);

class index{
	function __construct() {
		$this->get_db = pc_base::load_model('get_model');
		$this->order_db = pc_base::load_model('zy_order_model');
		$this->logistics_db = pc_base::load_model('zy_logistics_model');
		$this->evaluate_set_db = pc_base::load_model('zy_evaluate_set_model');
		$this->evaluate_db = pc_base::load_model('zy_evaluate_model');
		
	}
    /**
	* api
	*/

	/**
	* 获取订单列表
	*/
	public function order_list(){
		include template('zyorder', 'order_list');		
	}
	
	
	public function order_list_shop(){
		$_storeid = $_GET['storeid'];
		$pageindex = $_GET['pageindex'];
		$pagesize = $_GET['pagesize'];
		if($pageindex == null){
			$pageindex = 1;
		}
		if($pagesize == null){
			$pagesize = 10;
		}
		if($_userid==null){
			exit($this->show_error("no userid"));
		}
		$where = 'storeid='.$_storeid;
		if($_GET['status']==1){
			$where.= ' AND status=1';
		}else if($_GET['status']==2){
			$where.= ' AND status=2';
		}else if($_GET['status']==3){
			$where.= ' AND status=3';
		}else if($_GET['status']==4){
			$where.= ' AND status=4';
		}else{
			$where.= ' AND 1';
		}
		$where.=' AND status<6';
		$order = 'id DESC';
		$orders=$this->order_db->listinfo($where,$order,$page,$pagesize); //读取数据库里的字段
		$orderss = $this->order_db->select(1);
		$totalcount = count($orderss);
		//查询商品信息
		$totalpage = ceil($totalcount/$pagesize);
		$data = [
			"status"=> 'success',
			"code"=>1,
			"message"=>'操作成功',
			"data"=>$orders,
			"pages"=>$pages,
			"pagesize"=>$pagesize,
			"pageindex"=>$pageindex,
			"totalpage"=>$totalpage,
			"totalcount"=>$totalcount
		];
		echo json_encode($data);
	}
	/**
	* 订单中心_订单详情
	*/
	public function order_info(){
		//用户id和用户名
		$_userid = $_GET['userid'];
		$id = $_GET['id'];
		if($_userid==null){
			exit($this->show_error("no userid"));
		}
		if($id == null){
			exit($this->show_error("no id"));
		}
		if($this->check_uid($id,$_userid)){
			$order = $this->order_db->get_one(array('id'=>$_GET['id'],'userid'=>$_userid));
			exit($this->show_success($order));
		}else{
			exit($this->show_error(""));
		}	
	}
	
	/**
	* 提醒发货
	*/
	public function order_txfh(){
		$id = $_POST['id'];
		$_userid = $_POST['userid'];
		if($_userid ==null){
			echo $this->show_error("no userid");
			exit();
		}
		if($id == null){
			exit($this->show_error("no id"));
		}
		if($this->check_uid_status($id,$_userid,2)){	
			exit($this->show_success(""));
		}else{	
			exit($this->show_status_error());
		}
	}
	
	public function order_stats_info(){
	    if($_GET['d']=='0'){
			$beginYesterday=mktime(0,0,0,date('m'),date('d')-1,date('Y'));
     	    $endYesterday=mktime(0,0,0,date('m'),date('d'),date('Y'))-1;
			$sql = " addtime >= ".$beginYesterday." AND addtime <= ".$endYesterday;
			$census = [
		   'date'=>'Yesterday'
		];
		}else if($_GET['d']=='1'){
			$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
    	    $endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
			$sql = " addtime >= ".$beginToday." AND addtime <= ".$endToday;
			$census = [
		   'date'=>'Today'
		];
		}else{
			exit($this->show_error('no params'));
		}
		$arr = [];
		$orders = $this->order_db->select($sql);
		foreach($orders as $order){
			array_push($arr,$order['userid']);
		}
		$count = array_count_values($arr);
	
		foreach($count as $k => $v){
			$census['tj'][] = [
			    'userid'=>$k,
				'count'=>$v
			];
		}
		echo $this->show_success($census);
		
	}
	
	
	public function order_stats(){
		$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
    	$endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
    	$beginYesterday=mktime(0,0,0,date('m'),date('d')-1,date('Y'));
     	$endYesterday=mktime(0,0,0,date('m'),date('d'),date('Y'))-1;
		$year = date('Y',time());
		$stats = [];
		$sql = " addtime >= ".$beginToday." AND addtime <= ".$endToday;
		$order = $this->order_db->select($sql);
		$stats[]=[
		    	"today".$i=>count($order)
		];
		$sql = " addtime >= ".$beginYesterday." AND addtime <= ".$endYesterday;
		$order = $this->order_db->select($sql);
		$stats[]=[
		    	"yesterday".$i=>count($order)
		];
		for($i = 1;$i<=12;$i++){
			$arr = $this->mFristAndLast($year,$i);
			$sql = " addtime >= ".$arr['firstday']." AND addtime <= ".$arr['lastday'];
		    $order = $this->order_db->select($sql);
		    $stats[]=[
		    	"month".$i=>count($order)
		    ];
		}
		echo json_encode($stats);
	}

	function mFristAndLast($y = "", $m = ""){
		if ($y == "") $y = date("Y");
		if ($m == "") $m = date("m");
		$m = sprintf("%02d", intval($m));
		$y = str_pad(intval($y), 4, "0", STR_PAD_RIGHT);

		$m>12 || $m<1 ? $m=1 : $m=$m;
		$firstday = strtotime($y . $m . "01000000");
		$firstdaystr = date("Y-m-01", $firstday);
		$lastday = strtotime(date('Y-m-d 23:59:59', strtotime("$firstdaystr +1 month -1 day")));
      
		return array(
			"firstday" => $firstday,
			"lastday" => $lastday
		);
	}


	
	
   
	/**
	* 订单中心_待支付_取消订单
	*/
	public function order_cancel(){	

		$id = $_POST['id'];
		$_userid = $_POST['userid'];
		if($_userid ==null){
			echo $this->show_error("no userid");
			exit();
		}
		if($id == null){
			exit($this->show_error("no id"));
		}
		if($this->check_uid_status($id,$_userid,2)||$this->check_uid_status($id,$_userid,1)){	
			$result = $this->order_db->update(array('status'=>7),array('id'=>$id));
			exit($this->show_success($result));
		}else{	
			exit($this->show_status_error());
		}
	}
    //删除订单
	public function order_delete(){	
		$id = $_POST['id'];
		$_userid = $_POST['userid'];
		if($_userid==null){
			echo $this->show_error("no userid");
			exit();
		}
		if($id == null){
			exit($this->show_error("no id"));
		}
		if($this->check_uid_status($id,$_userid,5)){	
			$result = $this->order_db->delete(array('id'=>$id));
			echo $this->show_success($result);
		}else{	
			echo $this->show_error("");
		}
	}

    //判断当前订单是否与userid 相关联，订单状态
	public function check_uid_status($id,$_userid,$status){	
		if($_userid!=null){	
			$order = $this->order_db->get_one(array('id'=>$id));
			if($order['status'] != $status||$order['userid']!= $_userid){
				return false;
			}
			return true;
		}	
	}
	
	public function show_status_error(){
		$data = [
			"status"=>'error',
			"code"=>1003,
			"message"=>'userid和订单id不匹配或者当前状态错误',
			"data"=>''
		];
		return json_encode($data);
	}


	
	/**
	* 订单id与userid 是否相关
	*/
	public function check_uid($id,$_userid){	
		if($_userid!=null){	
			$order = $this->order_db->get_one(array('id'=>$id));
			if($order['userid'] != $_userid){	
				$data = [
					"status"=>'error',
					"code"=>1002,
					"message"=>'userid和订单id不匹配',
					"data"=>''
				];
				exit(json_encode($data));
			}
			return true;
		}

	}
    /**
	* 判断userid是否为空
	*/
	public function is_userid($userid){
		if($userid==null){
			$data = [
				"status"=>'error',
				"code"=>1001,
				"message"=>'userid为空',
				"data"=>''
			];
			exit(json_encode($data));
		}
	}

	/**
	* 订单中心_支付
	*/
	public function order_pay(){
		$id = $_POST['id'];
		if($this->order_check($id)){
			$result =  $this->order_db->update(array('status'=>2),array('id'=>$id));
			$this->echo_result($result);
		}else{
			$this->echo_result(0);
		}
	}
	public function show_success($data){
		$data= json_encode($data);
		$data = [
			"status"=> 'success',
			"code"=>1,
			"message"=>'操作成功',
			"data"=>$data
		];
		return json_encode($data);
	}
	public function show_error($data){
		$data = [
			"status"=> 'error',
			"code"=>0,
			"message"=>'操作失败',
			"data"=>json_encode($data)
		];
		return json_encode($data);
	}

	
	/**
	* 订单管理_快递鸟ajax传值
	*/
	public function kuaidi_ajx() {
		$id = $_GET['id'];
		$_userid = $_GET['userid'];
		if($this->check_uid_status($id,$_userid,3)){
			$KdApi = pc_base::load_app_class('KdApiSearch');
			$KdApi = new KdApiSearch();
			$order = $this->order_db->get_one(array('id'=>$id,'userid'=>$_userid));
			$logisticResult=$KdApi->getOrderTracesByJson($order['shipper_code'],$order['logistics_order']);
			echo $logisticResult;
		}
	}

	public function kuaidi_ajx2(){
		$shipper_code = $_GET['shipper_code'];
		$logistics_order = $_GET['logistics_order'];
		$KdApi = pc_base::load_app_class('KdApiSearch');
		$KdApi = new KdApiSearch();
		$logisticResult=$KdApi->getOrderTracesByJson($shipper_code,$logistics_order);
		echo $logisticResult;
	}




	/**
	* 添加订单
	*/
	public function addorder(){
		$_userid = $_POST['userid'];
		if($_userid==null){
			echo $this->show_error("no userid");
			exit();
		}
		$shopid = $_POST['shopid'];  //商品id
		$ordersn = time()+mt_rand(100,999);
		$buycarid = $_POST['buycarid']; //购物车id
		$province = $_POST['province'];  //收货地址省 
		$city = $_POST['city'];//收货地址市
		$area = $_POST['area'];//收货地址区
		$address = $_POST['address']; //详细地址
		$lx_mobile = $_POST['lx_mobile']; //联系电话
		$lx_name = $_POST['lx_name']; //联系人
		$lx_code = $_POST['lx_code']; //联系邮编
		$totalprice = $_POST['totalprice'];  //总价
		$usernote = $_POST['usernote']; //备注
		$addtime = time();//生成下单时间
		$data = [
			"userid"=>$_userid,
			"shopid"=>$shopid,
			"ordersn"=>$ordersn,
			"buycarid"=>$buycarid,
			"province"=>$province,
			"city"=>$city,
			"area"=>$area,
			"status"=>1,
			"address"=>$address,
			"lx_mobile"=>$lx_mobile,
			"lx_name"=>$lx_name,
			"lx_code"=>$lx_code,
			"totalprice"=>$totalprice,
			"usernote"=>$usernote,
			"addtime"=>$addtime,
		];
		
		foreach($data as $v){
			if($v==null){
				$re = ['code'=>0];
				echo json_encode($re);
				return ; 
			}

		}
		$result = $this->order_db->insert($data);
		exit($this->show_success($result));
	}
	/**
	* 订单中心_订单发货
	*/
	public function order_ddfh(){
		$shippercode = $_POST['shippercode'];
		$logistics_order = $_POST['logistics_order'];
		$id = $_POST['id'];
		$_userid = $_POST['userid'];
		if($this->check_uid_status($id,$_userid,2));
		$wuliu = $this->logistics_company_db->get_one(array('value'=>$shippercode)); //获取物流
		$info = $this->order_db->update(array('shipper_name' =>$wuliu['name'],'shipper_code' =>$wuliu['value'],'logistics_order' =>$logistics_order,'fhtime'=>time(),'status'=>'3'),array('id'=>$id));
		if($info){
			exit($this->show_success(""));
		}else{
			exit($this->show_error(""));
		}
	}
    //确认收货
	public function order_qrsh(){
		$_userid = $_POST['userid'];
		$id = $_POST['id'];
		if($_userid == null){
			exit($this->show_error("no userid"));
		}
		if($id == null){
			exit($this->show_error("no id"));
		}
		if($this->check_uid_status($id,$_userid,3)){
			$result = $this->order_db->update(array('status'=>4),array('id'=>$id));
			exit($this->show_success($result));
		}else{
			exit($this->show_error(""));
		}
	}

	//评价
	public function evaluate(){
		include template('zyorder', 'evaluate');
		/*$_userid = $_POST['userid'];
		$id = $_POST['id'];
		$content = $_POST['content'];
		$star = $_POST['star']; 
		$anony = $_POST['anony'];
		if($_userid==null){
			exit($this->show_error(""));
		}
		if($id==null){
			exit($this->show_error(""));
		}
		if($this->check_uid_status($id,$_userid,4)){
			$evaluate_set = $this->evaluate_set_db->select(1);
			$setarr = [];
			foreach($evaluate_set as $v){
				array_push($setarr,$v['value']);
			}

			foreach($_POST as $k=>$v){
				if(in_array($k,$setarr)){
					$star[$k] = $v;
				}
			}

			$result = $this->uploadimg($_FILES,$_userid);

			$data =[
				'orderid'=>$_POST['id'],
				'content'=>$content,
				'star'=>$star,
				'userid'=>$_userid,
				'img'=>$result,
				'addtime'=>time()
			];
			$evaluateid = $this->evaluate_db->insert($data,true);
			$result = $this->order_db->update(array('evaluateid'=>$evaluateid,'status'=>5),array('id'=>$_POST['id']));
			exit($this->show_success($result));
		}else{
			exit($this->show_error(""));
		}*/
	}

	function uploadimg($files,$_userid){

		$basepath = str_replace( '\\' , '/',dirname(dirname(dirname(dirname(__FILE__)))));

		$savePath = $basepath.'/uploadfile/order/'.$_userid.'/'.date('Ymd',time());

		if(!file_exists($savePath)){
			mkdir($savePath,0777,true);
		}
		$result=[];
		foreach ($files as $key => $val) {
            $imgName = time().rand(1000, 9999);//随机数
            $file_dir = $savePath . "/" . $imgName . ".jpg";
            if (move_uploaded_file($val["tmp_name"], $file_dir)) {
            	$result['src']= APP_PATH.'uploadfile/zyshop/'.$_userid.'/'.date('Ymd',time()).'/'. $imgName . ".jpg";
            } else {
            	$info = [
            		'code'=>0,
            		'msg' =>'Error',
            		'data' => ''
            	];
            	$info = json_encode($info,JSON_UNESCAPED_UNICODE);
            	$info = stripslashes($info);
            	return $info;
            }
        }
        $info = [
        	'code'=>1,
        	'msg' =>'OK',
        	'data' => $result
        ];
        $info = json_encode($info,JSON_UNESCAPED_UNICODE);
        $info = stripslashes($info);
        return $info;

    }

    /**
	* 页面
	*/

    /**
	* 订单管理
	*/
	public function order_ym(){
		include template('zyorder','m_order');
	}

    public function order_info_ym(){
		include template('zyorder','m_order_info');
	}

	public function evaluate_ym(){
		include template('zyorder', 'evaluate');
	}
	

	public function demo(){
		include template('zyorder', 'demo');
	}
	
	
	/**
	* 订单中心_交易完成_物流信息
	*/
	public function order_wlxx(){	
		include template('zyorder', 'm_order_logistics');
	}

	
	/**
	* 订单中心_待支付
	*/
	public function shop_pay(){	
		include template('zyorder', 'shop_pay');
	}

	

}
?>