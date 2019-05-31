<?php

class foreground {
	
	public function __construct() {

		$this->db = pc_base::load_model('member_model');
		//ajax验证信息不需要登录
		if(substr(ROUTE_A, 0, 7) != 'public_') {
			self::check_member();
		}
	}
	
	/**
	 * 判断用户是否已经登陆
	 */
	final public function check_member() {
		$phpcms_auth = param::get_cookie('auth');
		if(ROUTE_M =='zymember' && ROUTE_C =='index' && in_array(ROUTE_A, array('login', 'register','psd_back'))) {
			if ($phpcms_auth) {
				showmessage('登录成功', 'index.php?m=zymember&c=index');
			} else {
				return true;
			}
		} else {
			//判断是否存在auth cookie
			if ($phpcms_auth) {
				$auth_key = $auth_key = get_auth_key('login');
				list($userid, $password) = explode("\t", sys_auth($phpcms_auth, 'DECODE', $auth_key));
				$userid = intval($userid);
				//验证用户，获取用户信息
				$this->memberinfo = $this->db->get_one(array('userid'=>$userid));
				if($this->memberinfo['islock']) exit('<h1>Bad Request!</h1>');
				//获取用户模型信息
				$this->db->set_model($this->memberinfo['modelid']);

				$this->_member_modelinfo = $this->db->get_one(array('userid'=>$userid));
				$this->_member_modelinfo = $this->_member_modelinfo ? $this->_member_modelinfo : array();
				$this->db->set_model();
				if(is_array($this->memberinfo)) {
					$this->memberinfo = array_merge($this->memberinfo, $this->_member_modelinfo);
				}
				
				if($this->memberinfo && $this->memberinfo['password'] === $password) {
					
					if (!defined('SITEID')) {
					   define('SITEID', $this->memberinfo['siteid']);
					}
					
					if($this->memberinfo['groupid'] == 1) {
						param::set_cookie('auth', '');
						param::set_cookie('_userid', '');
						param::set_cookie('_username', '');
						param::set_cookie('_groupid', '');
						showmessage(L('userid_banned_by_administrator', '', 'member'), 'index.php?m=zymember&c=index&a=login');
					} elseif($this->memberinfo['groupid'] == 7) {
						param::set_cookie('auth', '');
						param::set_cookie('_userid', '');
						param::set_cookie('_groupid', '');
						
						//设置当前登录待验证账号COOKIE，为重发邮件所用
						param::set_cookie('_regusername', $this->memberinfo['username']);
						param::set_cookie('_reguserid', $this->memberinfo['userid']);
						param::set_cookie('_reguseruid', $this->memberinfo['phpssouid']);
						
						param::set_cookie('email', $this->memberinfo['email']);
						showmessage(L('need_emial_authentication', '', 'member'), 'index.php?m=zymember&c=index&a=register&t=2');
					}
				} else {
					param::set_cookie('auth', '');
					param::set_cookie('_userid', '');
					param::set_cookie('_username', '');
					param::set_cookie('_groupid', '');
				}
				unset($userid, $password, $phpcms_auth, $auth_key);
			} else {
				/*$appid = pc_base::load_config('zysystem', 'wechat_appid');
				$forward= isset($_GET['forward']) ?  urlencode($_GET['forward']) : urlencode(get_url());
				
				if( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ){
					$get_url = APP_PAHT.'index.php?m=zymember&c=index&a=public_wechat_api&forward='.$_GET['forward'];
					$forward = urlencode($get_url);
					$url= 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri='.$forward.'&response_type=code&scope=snsapi_userinfo&state=2#wechat_redirect';
					header("Location: ".$url.""); 
				}else{
					showmessage('您的会话已过期，请重新登录。', 'index.php?m=zymember&c=index&a=login&forward='.$forward);
				}*/
				$forward= isset($_GET['forward']) ?  urlencode($_GET['forward']) : urlencode(get_url());
				showmessage('您的会话已过期，请重新登录。', 'index.php?m=zymember&c=index&a=login&forward='.$forward);
			}
		}
	}

	
}