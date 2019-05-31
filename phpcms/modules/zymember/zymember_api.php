<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_func('global');
pc_base::load_sys_class('form', '', 0);
pc_base::load_sys_class('format', '', 0);

class zymember_api{
	function __construct() {
		
		$this->get_db = pc_base::load_model('get_model');	
		$this->members_db = pc_base::load_model('members_model');
		//会员附表
		$this->member_detail_db = pc_base::load_model('member_detail_model');
		//会员组表
		$this->member_group_db = pc_base::load_model('member_group_model');

	}


	/**
	 * 资金模块_用于用户提现
	 * @param  [type] $userid [*用户id]
	 * @return [json]         [数据组]
	 */
	public function zyfunds_withdrawal($userid)
	{
		$userid = $_GET['userid'];
		if(!$userid){
			$result = [
				'status'=>'error',
				'code'=>-1,
				'message'=>'请输入用户id',
				
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
		}

		$memberinfo = $this->members_db->get_one(['userid'=>$userid]);
		if(!$memberinfo){
			$result = [
				'status'=>'error',
				'code'=>-2,
				'message'=>'用户不存在',
				
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
		}

		$result = [
			'status'=>'success',
			'code'=>200,
			'message'=>'操作成功',
			'data'=>[
				'userid'=>$memberinfo['userid'],
				'username'=>$memberinfo['username'],
				'nickname'=>$memberinfo['nickname'],
				'phone'=>$memberinfo['mobile'],
				'cash'=>$memberinfo['amount'],
				'trade_pass'=>$memberinfo['trade_password'],
				'trade_encrypt'=>$memberinfo['trade_encrypt'],
			]
		];
		exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
	}


	/**
	 * 公共模块_获取会员组信息
	 * @return [json]         [数据组]
	 */
	public function pub_membergroup()
	{

		$member_group = $this->member_group_db->select();
		foreach ($member_group as $key => $value) {
			$member_groups[$key]['groupid'] = $value['groupid'];
			$member_groups[$key]['name'] = $value['name'];
		}
		$result = [
			'status'=>'success',
			'code'=>200,
			'message'=>'操作成功',
			'data'=>$member_groups
		];
		exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
	}



	/**
	 * 公共模块_会员详细信息
	 * @param  [type] $userid [*用户id]
	 * @param  [type] $field [需要查询的字段，已逗号隔开]
	 * @param  [type] $type [*类型：1web端、2APP端]
	 * @return [json]         [数据组]
	 */
	public function pub_memberinfo($userid=NULL,$field)
	{
		if($_POST['type']){
			$field = $_POST['field'] ? $_POST['field'] : '';
			$userid = $_POST['userid'];
			$type = $_POST['type'] ? $_POST['type'] : 1;	//类型：1web端、2APP端
		}else{
			$field = $_GET['field'] ? $_GET['field'] : '';
			$userid = $_GET['userid'];
			$type = $_GET['type'] ? $_GET['type'] : 1;	//类型：1web端、2APP端
		}
		$userid = $type==1 ? param::get_cookie('_userid') : $userid;

		
		//==================	操作失败-验证 START
		if(!$userid){
			$result = [
				'status'=>'error',
				'code'=>-1,
				'message'=>'请输入用户id',
				
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
		}

		$memberinfo = $this->members_db->get_one(['userid'=>$userid]);
		if(!$memberinfo){
			$result = [
				'status'=>'error',
				'code'=>-2,
				'message'=>'用户不存在',
				
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
		}
		//==================	操作失败-验证 END

		//==================	操作成功-查询数据 START
		//如果有字段数组传值过来，那么就只显示传值过来的字段值(利用逗号进行打散操作)
		//如果没有字段数组传值过来，那么就显示当前用户的全部信息
		if($field){
			$field = explode(",", $field);		//打散成数组，到时候进行重新组装
			foreach ($field as $key => $value) {
				$data[$value] = $memberinfo[$value];
			}
		}else{
			$data = $memberinfo;
		}
		//加上域名
		if($data['headimgurl']=='statics/images/member/nophoto.gif'){
			$data['headimgurl'] = APP_PATH.'statics/images/member/nophoto.gif';
		}		
		$result = [
			'status'=>'success',
			'code'=>200,
			'message'=>'操作成功',
			'data'=>$data
		];
		exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
		//==================	操作成功-查询数据 END
	}



	/**
	 * 公共模块_增加余额
	 * @param  [type] $userid [*用户id]
	 * @param  [type] $amount [*金额]
	 * @param  [type] $describe [*描述]
	 * @param  [type] $module [*所属模块]
	 * @param  [type] $type [*类型：1web端、2APP端]
	 * @return [json]         [数据组]
	 */
	public function pub_increaseamount($userid=NULL,$amount=0,$describe,$module)
	{
		//$userid = $_GET['userid'];
		$describe = $_GET['describe'];
		$module = $_GET['module'];
		$amount = $_GET['amount'];
		$type = $_GET['type'] ? $_GET['type'] : 1;	//类型：1web端、2APP端
		$userid = $type==1 ? param::get_cookie('_userid') : $_GET['userid'];

		//==================	操作失败-验证 START
		if(!$userid){
			$result = [
				'status'=>'error',
				'code'=>-1,
				'message'=>'请输入用户id',
				
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
		}

		$memberinfo = $this->members_db->get_one(['userid'=>$userid]);
		if(!$memberinfo){
			$result = [
				'status'=>'error',
				'code'=>-2,
				'message'=>'用户不存在',
				
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
		}
		if(!$describe || !$module){
			$result = [
				'status'=>'error',
				'code'=>-4,
				'message'=>'描述、所属模块内容不能为空',
				
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
		}
		//==================	操作失败-验证 END

		//==================	操作成功-更新数据 START
		$this->members_db->update(['amount'=>'+='.$amount],['userid'=>$userid]);
		$result = [
			'status'=>'success',
			'code'=>200,
			'message'=>'操作成功',
			
		];
		exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
		//==================	操作成功-更新数据 END
	}


	/**
	 * 公共模块_减少余额
	 * @param  [type] $userid [*用户id]
	 * @param  [int] $amount [*金额]
	 * @param  [type] $describe [*描述]
	 * @param  [type] $module [*所属模块]
	 * @param  [type] $type [*类型：1web端、2APP端]
	 * @return [json]         [数据组]
	 */
	public function pub_reduceamount($userid=NULL,$amount=0,$describe,$module)
	{
		//$userid = $_GET['userid'];
		$describe = $_GET['describe'];
		$module = $_GET['module'];
		$amount = $_GET['amount'];
		$type = $_GET['type'] ? $_GET['type'] : 1;	//类型：1web端、2APP端
		$userid = $type==1 ? param::get_cookie('_userid') : $_GET['userid'];

		//==================	操作失败-验证 START
		if(!$userid){
			$result = [
				'status'=>'error',
				'code'=>-1,
				'message'=>'请输入用户id',
				
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
		}

		$memberinfo = $this->members_db->get_one(['userid'=>$userid]);
		if(!$memberinfo){
			$result = [
				'status'=>'error',
				'code'=>-2,
				'message'=>'用户不存在',
				
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
		}
		if($memberinfo['amount']<$amount){
			$result = [
				'status'=>'error',
				'code'=>-3,
				'message'=>'余额不足',
				
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
		}
		if(!$describe || !$module){
			$result = [
				'status'=>'error',
				'code'=>-4,
				'message'=>'描述、所属模块内容不能为空',
				
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
		}
		//==================	操作失败-验证 END

		//==================	操作成功-更新数据 START
		$this->members_db->update(['amount'=>'-='.$amount],['userid'=>$userid]);
		$result = [
			'status'=>'success',
			'code'=>200,
			'message'=>'操作成功',
			
		];
		exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
		//==================	操作成功-更新数据 END
	}




	/**
	 * 商品模块_获取用户昵称
	 * @param  [type] $ids [*用户id，已逗号的形式传值]
	 * @return [json]         [数据组]
	 */
	public function zyshop_nickname($ids)
	{
		$useridstr = $_POST['ids'];
		if(!$useridstr){
			$result = [
				'status'=>'error',
				'code'=>-1,
				'message'=>'用户id不能为空',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
		}

		$where='userid in ('.$useridstr.')';
		$sql='SELECT `userid`,`shopname`,`groupid`as`group`,`proprietary` FROM phpcms_member WHERE '.$where;
		$infos = $this->get_db->multi_listinfo($sql);

		foreach ($infos as $key => $value) {
			$infos[$key]['group'] = $value['group']==2 ? 0 : 1;
			$infos[$key]['proprietary'] = $value['proprietary']==1 ? 1 : 0;
		}


		$result = [
			'status'=>'success',
			'code'=>200,
			'message'=>'操作成功',
			'data'=>$infos,
		];
		exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
	}


	/**
	 * 通讯模块_获取会员组的全部会员
	 * @param  [type] $groupid [*会员组id，默认是0=全部会员]
	 * @return [json]         [数据组]
	 */
	public function zymessagesys_group($groupid)
	{
		$groupid = $_POST['groupid'] ? $_POST['groupid'] : 0;
		if($groupid==0){
			$memberinfo = $this->members_db->select('','`userid`,`username`,`nickname`,`mobile`,`groupid`');
		}else{
			$memberinfo = $this->members_db->select(['groupid'=>$groupid],'`userid`,`username`,`nickname`,`mobile`,`groupid`');
		}


		//==================	操作成功-更新数据 START

			$result = [
				'status'=>'success',
				'code'=>200,
				'message'=>'操作成功',
				'data'=>$memberinfo,
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));

		//==================	操作成功-更新数据 END

	}


	/**
	 * 订单模块_验证支付密码是否正确
	 * @param  [type] $userid [*用户id]
	 * @param  [type] $pay_password [*支付密码]
	 * @return [json]         [数据组]
	 */
	public function zyorder_offpaypas()
	{
		$userid = $_POST['userid'];	//用户id
		$pay_password = $_POST['pay_password'];	//支付密码



		//==================	操作失败-验证 START
			if(!$userid || !$pay_password){
				$result = [
					'status'=>'error',
					'code'=>-1,
					'message'=>'用户id、交易密码不能为空',
				];
				exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
			}

			$memberinfo = $this->members_db->get_one(['userid'=>$userid]);
			if(!$memberinfo){
				$result = [
					'status'=>'error',
					'code'=>-2,
					'message'=>'用户不存在',
					
				];
				exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
			}

			//请先设置支付密码
			if(!$memberinfo['trade_password']) {
				$result = [
					'status'=>'error',
					'code'=>-3,
					'message'=>'请先设置交易密码',
				];
				exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
			}

			//密码错误
			if($memberinfo['trade_password'] != password($pay_password, $memberinfo['trade_encrypt'])) {
				$result = [
					'status'=>'error',
					'code'=>-4,
					'message'=>'密码错误',
				];
				exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
			}

		//==================	操作成功-返回数据 START

			$result = [
				'status'=>'success',
				'code'=>200,
				'message'=>'操作成功'
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));

		//==================	操作成功-返回数据 END

	}





}