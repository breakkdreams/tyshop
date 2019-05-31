<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_func('global');
pc_base::load_app_class('foreground');

class index extends foreground {
	function __construct() {

		$this->get_db = pc_base::load_model('get_model');
		$this->member_db = pc_base::load_model('member_model');
		$this->members_db = pc_base::load_model('member_model');
		$this->member_detail_db = pc_base::load_model('member_detail_model');
		$this->sso_members_db = pc_base::load_model('sso_members_model');
		$this->module_db = pc_base::load_model('module_model');

		$this->_userid = param::get_cookie('_userid');

	}


//=========================== 验证 START
	
	/**
	 * 公共_手机号码_是否重复验证
	 */
	public function public_checkmobile_ajax()
	{
		$userid = $_GET['userid'];
		$mobile = $_GET['mobile'];
		if ($this->member_db->get_one(['mobile'=>$mobile])) {
			echo '0';
		}else{
			echo '1';
		}

	}


//=========================== 验证 END



//=========================== 操作 START

	/**
	 * 会员首页
	 */
	public function init()
	{
		$_userid = $this->_userid;
		$memberinfo = $this->member_db->get_one(['userid'=>$_userid]);

		include template('zymember', 'init');

	}

	/**
	 * 店铺首页
	 */
	public function shop_init()
	{
		$_userid = $this->_userid;
		$memberinfo = $this->member_db->get_one(['userid'=>$_userid]);

		include template('zymember', 'shop_init');
	}

	/**
	 * 店铺_个人资料
	 */
	public function shop_personal_data()
	{
		$_userid = $this->_userid;
		$memberinfo = $this->member_db->get_one(['userid'=>$_userid]);

		include template('zymember', 'shop_personal_data');
	}

	/**
	 * 会员登录页
	 */
	public function login()
	{
		$_userid = $this->_userid;
		include template('zymember', 'login');
	}

	/**
	 * 会员注册页
	 */
	public function register()
	{
		$_userid = $this->_userid;
		include template('zymember', 'register');
	}

	/**
	 * 个人资料
	 */
	public function personal_data()
	{
		$_userid = $this->_userid;
		include template('zymember', 'personal_data');
	}

	/**
	 * 帐号与安全
	 */
	public function security()
	{
		$_userid = $this->_userid;
		include template('zymember', 'security');
	}

	/**
	 * 帐号与安全-密码修改
	 */
	public function psd_edit()
	{
		$_userid = $this->_userid;
		$progress = $_GET['progress'];
		switch ($progress) {
			case 1:			//密码找回_手机验证
				include template('zymember', 'psd_edit');
				break;
			
			case 2:			//密码找回_设置密码
				include template('zymember', 'psd_edit2');
				break;
			
			default:
				include template('zymember', 'psd_edit');
				break;
		}
	}

	/**
	 * 密码找回
	 */
	public function psd_back()
	{
		$progress = $_GET['progress'];
		switch ($progress) {
			case 1:			//密码找回_手机验证
				include template('zymember', 'psd_back');
				break;
			
			case 2:			//密码找回_设置密码
				include template('zymember', 'psd_back2');
				break;
			
			case 3:			//密码找回_设置密码
				include template('zymember', 'psd_back3');
				break;
			
			default:
				include template('zymember', 'psd_back');
				break;
		}	
	}

	/**
	 * 帐号与安全-交易密码修改_不记得
	 */
	public function paypsd_edit()
	{
		$_userid = $this->_userid;
		$progress = $_GET['progress'];
		switch ($progress) {
			case 1:			//密码找回_手机验证
				include template('zymember', 'paypsd_edit');
				break;
			
			case 2:			//密码找回_设置密码
				include template('zymember', 'paypsd_edit2');
				break;
			
			default:
				include template('zymember', 'paypsd_edit');
				break;
		}
	}

	/**
	 * 帐号与安全-交易密码修改_记得
	 */
	public function paypsd_edit_jd()
	{
		$_userid = $this->_userid;
		include template('zymember', 'paypsd_edit_jd');
	}


	/**
	 * 绑定手机号码
	 */
	public function binding_mobile()
	{
		$_userid = $this->_userid;
		include template('zymember', 'binding_mobile');
	}


	/**
	 * 修改手机号码
	 */
	public function update_mobile()
	{
		$_userid = $this->_userid;
		include template('zymember', 'update_mobile');
	}


	/**
	 * 微信公众号_一键登录
	 */
	public function public_wechat_login()
	{
		$_userid = param::get_cookie('_userid');
		$forward = $_GET['forward'] ? urldecode($_GET['forward']) : urldecode(APP_PATH.'index.php?m=member&c=index');
		$wechatUrl = urlencode(APP_PATH."index.php?m=zymember&c=index&a=public_wechat_api&forward=".$forward);
		$wechat_appid = pc_base::load_config('zysystem', 'wechat_appid');

		if(!$_userid){
			if( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ){
				$url= 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$wechat_appid.'&redirect_uri='.$wechatUrl.'&response_type=code&scope=snsapi_userinfo&state=2#wechat_redirect';
				header("Location: ".$url."");
			}else{
				$url = 'index.php?m=zymember&c=index&a=login';
				header("Location: ".$url."");
			}
		}else{
			$url = 'index.php?m=zymember&c=index';
			header("Location: ".$url."");
		}

	}


	/**
	 * 微信电脑_扫码登录
	 */
	public function public_wechatpc_login()
	{
		
	}
	
	/**
	 * 微信登录、注册转换接口
	 */
	public function public_wechat_api()
	{
		//微信密钥
		$this->_wechat_appid = pc_base::load_config('zysystem', 'wechat_appid');	//微信PE appid
		$this->_wechat_appsecret = pc_base::load_config('zysystem', 'wechat_appsecret');	//微信PE appsecret
		$this->_wechatpc_appid = pc_base::load_config('zysystem', 'wechatpc_appid');	//微信PC appid
		$this->_wechatpc_appsecret = pc_base::load_config('zysystem', 'wechatpc_appsecret');	//微信PC appsecret
		$this->_wechat_kaifang = pc_base::load_config('zysystem', 'wechat_kaifang');	//是否开启微信开放平台（0开启、1未开启）
		$this->_wechat_off = pc_base::load_config('zysystem', 'wechat_off');	//是否开启微信登录（0开启、1未开启）

		//验证微信接口是否打开
		if($this->_wechat_off==1){
			showmessage('请开启微信登录模式,填写配置');
		}

		
		//==================	获取微信信息-微信用户信息 START
			//换成自己的接口信息
			$code = $_GET['code'];
			$state = $_GET['state'];		//1、电脑还是公众号（1：电脑2：公众号）
			$arrr = explode(",", $state);
			$forward = $_GET['forward'];	//下面要跳转的链接
			$port=$arrr[0];		//1、电脑还是公众号（1：电脑2：公众号）
			//$pid=$arrr[1];

			//判断是电脑还是微信公众号过来的,然后赋值给appid和appsecret
			switch ($port) {
				case 1:
					$appid = $this->_wechatpc_appid;
					$appsecret = $this->_wechatpc_appsecret;
					break;
				
				case 2:
					$appid = $this->_wechat_appid;
					$appsecret = $this->_wechat_appsecret;
					break;
				
				default:
					showmessage('操作错误');
					break;
			}

			//通过code换取网页授权access_token
			$token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$appsecret.'&code='.$code.'&grant_type=authorization_code';
			$token = json_decode(file_get_contents($token_url));
			if(isset($token->errcode)){
				showmessage('<br/><h2>错误信息：</h2>'.$token->errmsg);
			}

			//刷新access_token
			$access_token_url = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid='.$appid.'&grant_type=refresh_token&refresh_token='.$token->refresh_token;
			$access_token = json_decode(file_get_contents($access_token_url));
			if(isset($access_token->errmsg)){
				showmessage('<br/><h2>错误信息：</h2>'.$access_token->errmsg);
			}

			//拉去用户信息
			$user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token->access_token.'&openid='.$access_token->openid.'&lang=zh_CN';
			$user_info = json_decode(file_get_contents($user_info_url));
			if (isset($user_info->errcode)) {
				showmessage('<br/><h2>错误信息：</h2>'.$user_info->errmsg);
			}

			//微信用户的信息
			$rs =  json_decode(json_encode($user_info),true);//转换成数组
			
		//==================	获取微信信息-微信用户信息 END

		
		//==================	新建微信登录临时表，判断是否已经有手机号码 START
		//根据微信号来查用户信息。看是否存在这个用户（判断是否开启了微信开放平台进行电脑微信进行绑定）
        //如果不存在，就把当前用户的信息,进行注册，在登录
		//如果存在，那就直接登录
		if($this->_wechat_kaifang==1){	//关闭
			$memberinfo = $this->member_db->get_one(array('wechatpe_openid'=>$rs['openid']));
		}else{	//开启
			$memberinfo = $this->member_db->get_one(array('wechat_unionid'=>$rs['unionid']));
		}

		if ($memberinfo) {	//登陆
			$cookietime = SYS_TIME + 7200;	//系统时间+两个小时
			$phpcms_auth = sys_auth($memberinfo['userid']."\t".$memberinfo['password'], 'ENCODE', get_auth_key('login'));
			param::set_cookie('auth', $phpcms_auth, $cookietime);
			param::set_cookie('_userid', $memberinfo['userid'], $cookietime);
			param::set_cookie('_username', $memberinfo['username'], $cookietime);
			param::set_cookie('_nickname', $memberinfo['nickname'], $cookietime);
			param::set_cookie('_groupid', $memberinfo['groupid'], $cookietime);
			param::set_cookie('cookietime', $_cookietime, $cookietime);

			$url = $forward;
			header("Location: ".$url."");
		}else{			//注册

			//==================	操作成功-注册数据 START
				$rs['sex'] = $rs['sex']==1 ? '男' : '女';
				$temporary_name = str_replace(' ','',$rs['nickname']);
				if(empty($temporary_name)){
					$rs['nickname']='???';
				}

				//获取会员基本设置的配置
				$member_setting = $this->module_db->get_one(array('module'=>'member'), 'setting');
				$member_setting = string2array($member_setting['setting']);
				$password = 123456;
				$mobile = '';

				$userinfo = array();
				//用户基本信息
				$userinfo['username'] = create_randomstr(8);
				$userinfo['password'] = $password;
				$userinfo['encrypt'] = create_randomstr(6);
				$userinfo['regdate'] = time();
				$userinfo['regip'] = ip();
				$userinfo['email'] = time().'@300c.cn';
				$userinfo['groupid'] = 2;
				$userinfo['amount'] = 0;
				$userinfo['point'] = $member_setting['defualtpoint'];
				$userinfo['modelid'] = 10;
				//$userinfo['islock'] = $_POST['info']['islock']==1 ? 0 : 1;
				//$userinfo['vip'] = $_POST['info']['vip']==1 ? 1 : 0;
				//$userinfo['overduedate'] = strtotime($_POST['info']['overduedate']);
				$userinfo['mobile'] = $mobile;

				$userinfo['headimgurl'] = $rs['headimgurl'];
				$userinfo['sex'] = $rs['sex'];
				$userinfo['nickname'] = $rs['nickname'];

				//记录微信信息
				$userinfo['wechat_unionid'] = $rs['unionid'];
				$userinfo['wechat_name'] = $rs['nickname'];
				$userinfo['wechat_headimg'] = $rs['headimgurl'];
				$userinfo['wechat_sex'] = $rs['sex'];
				if($port==1){
					$userinfo['wechatpc_openid'] = $rs['openid'];
				}else{
					$userinfo['wechatpe_openid'] = $rs['openid'];
				}
				//记录微信信息

				
				//传入phpsso为明文密码，加密后存入phpcms_v9
				$password = $userinfo['password'];
				$userinfo['password'] = password($userinfo['password'], $userinfo['encrypt']);


				//主表
				$userid=$this->member_db->insert($userinfo,true);
				$this->member_db->update(array('phpssouid'=>$userid),'userid='.$userid);
				
				//sso表
				$sso_members_db = pc_base::load_model('sso_members_model');
				$data_member_sso = array(
					'username'=>$userinfo['username'],
					'password'=>$userinfo['password'],
					'random'=>$userinfo['encrypt'],
					'email'=>$userinfo['email'],
					'regdate'=>$userinfo['regdate'],
					'lastdate'=>$userinfo['regdate'],
					'regip'=>$userinfo['regip'],
					'lastip'=>$userinfo['lastip'],
					'appname'=>'phpcmsv9',
					'type'=>'app',
				);	
				$sso_members_db->insert($data_member_sso);
				
				//附表
				$data_member_detail = array(
					'userid'=>$userid,
				);	
				$this->member_detail_db->insert($data_member_detail);
				//==================	操作成功-注册数据 END			

			//登陆
			$memberinfo = $this->member_db->get_one(array('userid'=>$userid));
			
			$cookietime = SYS_TIME + 7200;	//系统时间+两个小时
			$phpcms_auth = sys_auth($memberinfo['userid']."\t".$memberinfo['password'], 'ENCODE', get_auth_key('login'));
			param::set_cookie('auth', $phpcms_auth, $cookietime);
			param::set_cookie('_userid', $memberinfo['userid'], $cookietime);
			param::set_cookie('_username', $memberinfo['username'], $cookietime);
			param::set_cookie('_nickname', $memberinfo['nickname'], $cookietime);
			param::set_cookie('_groupid', $memberinfo['groupid'], $cookietime);
			param::set_cookie('cookietime', $_cookietime, $cookietime);
			
			$url = $forward;
			header("Location: ".$url."");
		}

		//==================	新建微信登录临时表，判断是否已经有手机号码 END
		
	}


//=========================== 操作 END
	
	

	/**
	 * 收藏列表
	 */
	public function collect()
	{
		$_userid = $this->_userid;
		include template('zymember', 'collect');
	}
	

	/**
	 * 足迹列表
	 */
	public function footprint()
	{
		include template('zymember', 'footprint');
	}



}
?>
