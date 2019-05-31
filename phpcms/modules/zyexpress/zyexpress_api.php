<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_func('global');
pc_base::load_sys_class('form', '', 0);
pc_base::load_sys_class('format', '', 0);
pc_base::load_app_func('KdApiSearch');

class zyexpress_api{
	function __construct() {
		$this->express_db = pc_base::load_model('zyexpress_model');
		
	}




	/**
	 * 查询物流信息
	 * @status [状态] 200操作成功/-101请完善后台物流配置信息/-104参数不能为空
	 * @param  [type] $ShipperCode [*数据组]
	 * @param  [type] $LogisticCode [*翻页数据]
	 */
	public function kd_logistics(){
		$EXinfo = pc_base::load_config('zysystem');
		if(!$EXinfo['EBusinessID'] || !$EXinfo['AppKey'] || !$EXinfo['ReqURL']){
			//==================	操作失败-验证 START
				$this->_return_status(-101);
			//==================	操作失败-验证 END
		}


		$orderInfo['ShipperCode']=$_POST['ShipperCode'];
		$orderInfo['LogisticCode']=$_POST['LogisticCode'];
		if(!$orderInfo['ShipperCode'] || !$orderInfo['LogisticCode']){
			//==================	操作失败-验证 START
				$this->_return_status(-104);
			//==================	操作失败-验证 END
		}

		$data = getOrderTracesByJson($EXinfo, $orderInfo['ShipperCode'],$orderInfo["LogisticCode"]);
		
		$msg = json_decode($data,ture);

		/* 
		物 流 状 态 ：
		0-无 轨 迹
		1-已揽收
		2-在途中
		3-签收 */
		if($msg['State']=='0'){
			$msg['State']='无轨迹';
		}else if($msg['State']=='1'){
			$msg['State']='已揽收';
		}else if($msg['State']=='2'){
			$msg['State']='在途中';
		}else if($msg['State']=='3'){
			$msg['State']='签收';
		}

		//==================	操作成功-返回数据 START
			$this->_return_status(200,$msg);
		//==================	操作成功-返回数据 END

	}



	/**
	 * 查询快递公司信息
	 * @status [状态] 200操作成功
	 * @param  [type] $company [物流公司，非必填；不填写出来全部]
	 */
	public function kd_company(){
		$company = $_POST['company'];
		$where = $company ? array('company'=>$company) : '';
		if($company){
			$info = $this->express_db->select($where,'`company`,`code`');
		}else{
			$info = $this->express_db->select($where,'`company`,`code`');
		}



		//==================	操作成功-返回数据 START
			$this->_return_status(200,$info);
		//==================	操作成功-返回数据 END
	}





	/*
	 * 私有返回状态_返回状态
	 * @status [状态] 200操作成功/-100状态码不能为空，操作失败/-101请完善后台物流配置信息/-102帐号已锁定,无法登录/-103请先登录
	 * @param  [type] $status [*状态]
	 * @param  [type] $data [*数据组]
	 * @param  [type] $page [*翻页数据]
	 */
	private function _return_status($status,$data,$pages) 
	{
		$status = $status;	//状态
		$data = $data;	//成功：返回数据组
		$pages = $pages;	//成功：返回数据组
		$data = $data;	//成功：返回数据组
		//==================	操作失败-验证 START
			switch ($status) {
				case 200:	//操作成功
					$result = [
						'status'=>'success',
						'code'=>200,
						'message'=>'操作成功',
					];
					if($data){
						$result['data']=$data;
					}
					if($pages){
						$result['page']=$pages;
					}
					exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
					break;
				
				case -101:	//请完善后台物流配置信息
					$result = [
						'status'=>'error',
						'code'=>-101,
						'message'=>'请完善后台物流配置信息',
					];
					exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
					break;
				
				case -102:	//帐号已锁定,无法登录
					$result = [
						'status'=>'error',
						'code'=>-102,
						'message'=>'帐号已锁定,无法登录',
					];
					exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
					break;
				
				case -103:	//请先登录
					$result = [
						'status'=>'error',
						'code'=>-103,
						'message'=>'请先登录',
					];
					exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
					break;
				
				case -104:	//参数不能为空
					$result = [
						'status'=>'error',
						'code'=>-104,
						'message'=>'参数不能为空',
					];
					exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
					break;
				
				default:
					$result = [
						'status'=>'error',
						'code'=>-100,
						'message'=>'操作失败',	//帐号已锁定,无法登录
					];
					exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
					break;
			}
		//==================	操作失败-验证 END
	}



}
?>