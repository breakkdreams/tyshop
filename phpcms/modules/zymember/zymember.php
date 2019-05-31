<?php

defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin', 'admin', 0);	//加载应用类方法
pc_base::load_sys_class('form', 0, 0);
pc_base::load_app_func('global');

class zymember extends admin {
	/**
	*构造函数，初始化
	*/
	public function __construct()
	{
		//开启session会话
		session_start();
		//初始化父级的构造函数
		parent::__construct();
		//引入数据表
		$this->get_db = pc_base::load_model('get_model');
		$this->member_db = pc_base::load_model('member_model');
		//会员附表
		$this->member_detail_db = pc_base::load_model('member_detail_model');
		//会员组表
		$this->member_group_db = pc_base::load_model('member_group_model');
		//会员sso表
		$this->sso_members_db = pc_base::load_model('sso_members_model');
		//会员审核记录表
		$this->member_audit_db = pc_base::load_model('member_audit_model');
		//配置模块表
		$this->zyconfig_db = pc_base::load_model('zyconfig_model');
		$this->module_db = pc_base::load_model('module_model');

        //$this->tables_member = zy_member;   //数据表名
        $this->tables_member = zy_member;   //数据表名_会员主表
        $this->tables_detail = zy_member_detail;   //数据表名_会员附表
        $this->tables_sso = zy_sso_members;   //数据表名_会员sso表
        $this->tables_group = zy_member_group;   //数据表名_会员组表
        $this->db_name = pc_base::load_config('database', 'default')['database'];	//数据库		
	}



//===========================配置模块-配置管理-会员配置（别人需要的） START
	/**
	 * 会员配置-列表
	 */
	public function zyconfig()
	{
		$big_menu = array
		(
			'javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=zymember&c=zymember&a=configadd\', title:\'添加配置\', width:\'700\', height:\'220\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function()	{window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加配置'
		);
		$where = ['item_name'=>'zymember'];
		$order = 'id DESC';
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$info=$this->zyconfig_db->listinfo($where,$order,$page,20);
		$pages = $this->zyconfig_db->pages;

		include $this->admin_tpl('zyconfig');
	}




	/*
	 * 会员配置-添加
	 * */
	public function configadd()
	{

		if($_POST['dosubmit'])
		{
			if(empty($_POST['config_name']))
			{
				showmessage('请输入项目名',HTTP_REFERER);
			}
			$zyconfig_num = $this->zyconfig_db->count(['item_name'=>'zymember'])+1;
			$car=array
			(
				'config_name'=>$_POST['config_name'],
				'model_name'=>$_POST['model_name'],
				'url'=>$_POST['url'],
				'item_name'=>'zymember',
				'key'=>'zymember'.$zyconfig_num,
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


//===========================配置模块-配置管理-会员配置（别人需要的） END




//===========================会员模块 START
	
	/**
	 * 会员管理_会员管理-列表
	 */
	public function manage()
	{
		//条件
		$where= 'proprietary!=1';
		if($_GET['type']){
			if($_GET['q']){
				if($_GET['type'] == 1){
					$where .= " and username like '%".$_GET['q']."%' ";
				}
				if($_GET['type'] == 2){
					$where .= " and userid ='".$_GET['q']."'";
				}
				if($_GET['type'] == 3){
					$where .= " and name like '%".$_GET['q']."%' ";
				}
				if($_GET['type'] == 4){
					$where .= " and mobile like '%".$_GET['q']."%' ";
				}
				if($_GET['type'] == 5){
					$where .= " and nickname like '%".$_GET['q']."%' ";
				}
			}
		}
		//正常/禁用
		if($_GET['islock']){
			if($_GET['islock'] == 1){
				$where .= " and islock = 0 ";
			}
			if($_GET['islock'] == 2){
				$where .= " and islock = 1";
			}
		}
		//会员组
		if($_GET['groupid']){
			$where .= " and groupid = ".$_GET['groupid'];
		}


		if($_GET['start_addtime']){
			$where .= " and regdate >= '".strtotime($_GET['start_addtime'])."'";
		}
		if($_GET['end_addtime']){
			$where .= " and regdate <= '".strtotime($_GET['end_addtime'])."'";
		}
		$order = 'userid DESC';
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$info=$this->member_db->listinfo($where,$order,$page); //读取数据库里的字段
		$pages = $this->member_db->pages;  //分页


		//var_dump($where);



		//添加会员
		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=zymember&c=zymember&a=manage_add\', title:\'添加会员\', width:\'700\', height:\'500\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加会员');


		//会员组
		$member_group = $this->member_group_db->select();

		include $this->admin_tpl('manage');
	}

	/**
	 * 会员管理_会员管理-添加
	 */
	public function manage_add()
	{

		if($_POST['dosubmit']){

			$userinfo = array();
			//用户基本信息
			$userinfo['username'] = create_randomstr(8);
			$userinfo['password'] = $_POST['info']['password'];
			$userinfo['encrypt'] = create_randomstr(6);
			$userinfo['nickname'] = $_POST['info']['nickname'];
			$userinfo['regdate'] = time();
			$userinfo['regip'] = ip();
			$userinfo['email'] = $_POST['info']['email'];
			$userinfo['groupid'] = $_POST['info']['groupid'];
			$userinfo['amount'] = $_POST['info']['amount'];
			$userinfo['point'] = $_POST['info']['point'];
			$userinfo['modelid'] = 10;
			//$userinfo['islock'] = $_POST['info']['islock']==1 ? 0 : 1;
			$userinfo['vip'] = $_POST['info']['vip']==1 ? 1 : 0;
			$userinfo['overduedate'] = strtotime($_POST['info']['overduedate']);
			$userinfo['mobile'] = $_POST['info']['mobile'];

			
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
			showmessage(L('operation_success'), '?m=member&c=member&a=user_manage', '', 'add');
			
		}else{
			$show_header = $show_scroll = true;
			$siteid = get_siteid();

			//会员组缓存
			$group_cache = getcache('grouplist', 'member');
			foreach($group_cache as $_key=>$_value) {
				$grouplist[$_key] = $_value['name'];
			}

			include $this->admin_tpl('manage_add');
		}
	}	

	/**
	 * 会员管理_会员管理-删除
	 */
	public function manage_del()
	{
		//删除单个
		$id=intval($_GET['id']);
		if($id){
			$result=$this->member_db->delete(array('userid'=>$id));
			$this->member_detail_db->delete(array('userid'=>$id));
			$this->sso_members_db->delete(array('uid'=>$id));
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
				//$result=$this->lottery_num_db->delete(array('id'=>$pid));
			}
			showmessage(L('operation_success'),HTTP_REFERER);
		}

		//都没有选择删除什么
		if( empty($_POST['id'])){
			showmessage('请选择要删除的用户',HTTP_REFERER);
		}
	}
	

	/**
	 * 会员管理_会员管理-查看
	 */
	public function manage_view()
	{
		$show_header = false;		//去掉最上面的线条
		
		$member = $this->member_db->get_one('userid='.$_GET['userid']);
		//会员组
		$member_group = $this->member_group_db->select();

		include $this->admin_tpl('manage_view');
	}

	/**
	 * 会员管理_会员管理-修改
	 */
	public function manage_edit()
	{
		
		$show_header = false;		//去掉最上面的线条
		if($_POST['dosubmit']){
			//主表信息字段
			$basicinfo = array();
			$basicinfo['userid'] = $_POST['info']['userid'];
			$basicinfo['nickname'] = $_POST['info']['nickname'];
			$basicinfo['email'] = $_POST['info']['email'];
			$basicinfo['point'] = $_POST['info']['point'];
			$basicinfo['amount'] = $_POST['info']['amount'];
			$basicinfo['mobile'] = $_POST['info']['mobile'];
			$basicinfo['groupid'] = $_POST['info']['groupid'];
			$basicinfo['vip'] = $_POST['info']['vip'];
			$basicinfo['overduedate'] = $_POST['info']['overduedate'];

			$basicinfo['shopname'] = $_POST['info']['shopname'];

			//附表信息字段
			$basicinfos = array();
			$basicinfos['birthday'] = $_POST['infos']['birthday'];

			$userinfo = $this->member_db->get_one('userid='.$basicinfo['userid']);
			
			//如果密码不为空，修改用户密码。
			if(isset($_POST['info']['password']) && !empty($_POST['info']['password'])) {
				$pswd = password($_POST['info']['password'], $userinfo['encrypt']);
				
				$this->member_db->update(array('password'=>$pswd,'encrypt'=>$userinfo['encrypt']), array('userid'=>$basicinfo['userid']));
				$this->sso_members_db->update(array('password'=>$pswd,'random'=>$userinfo['encrypt']), array('uid'=>$userinfo['phpssouid']));
			}
			
			//删除用户头像
			if(!empty($_POST['delavatar'])) {
				$this->member_db->update(array('headimgurl'=>'statics/images/member/nophoto.gif'),'userid='.$basicinfo['userid']);
			}
			//删除店铺logo
			if(!empty($_POST['store_logo'])) {
				$this->member_db->update(array('store_logo'=>''),'userid='.$basicinfo['userid']);
			}
			
			$data =array(
				'nickname'=>$basicinfo['nickname'],
				'email'=>$basicinfo['email'],
				'point'=>$basicinfo['point'],
				//'amount'=>$basicinfo['amount'],
				'mobile'=>$basicinfo['mobile'],
				'groupid'=>$basicinfo['groupid'],
				'vip'=>$basicinfo['vip'],
				'overduedate'=>strtotime($basicinfo['overduedate']),

				'shopname'=>$basicinfo['shopname'],
			);
			$this->member_db->update($data,'userid='.$basicinfo['userid']);
			$this->member_detail_db->update($basicinfos,'userid='.$basicinfo['userid']);
			$this->sso_members_db->update(array('email'=>$basicinfo['email']), array('uid'=>$userinfo['phpssouid']));

			showmessage('操作成功', '?m=zymember&c=zymember&a=manage', '', 'edit');
		}else{

			$member = $this->member_db->get_one('userid='.$_GET['userid']);
			$member_detail = $this->member_detail_db->get_one('userid='.$_GET['userid']);
			//会员组
			$member_group = $this->member_group_db->select();
	
			$form_overdudate = form::date('info[overduedate]', date('Y-m-d H:i:s',$member['overduedate']), 1);

			include $this->admin_tpl('manage_edit');
		}
	}


	/**
	 * 会员管理_会员管理-审核通过/审核驳回按钮
	 */
	public function store_audit()
	{
		if($_GET['store_audit']==2){
			$userid = $_GET['userid'];
			//通过
			$this->member_db->update(['store_audit'=>2,'groupid'=>18],['userid'=>$userid]);

			//插入用户审核记录
			$this->addaudit_record($userid,2);
			showmessage(L('operation_success'),HTTP_REFERER);
		}elseif ($_POST['store_audit']==3) {
			//驳回、驳回理由
			$store_audit_no = $_POST['store_audit_no'];
			$userid = $_POST['userid'];
			$this->member_db->update(['store_audit'=>3,'store_audit_no'=>$store_audit_no],['userid'=>$userid]);

			//插入用户审核记录
			$this->addaudit_record($userid,3,$store_audit_no);
			showmessage('操作成功', '?m=zymember&c=zymember&a=manage', '', 'edit');
		}else{
			$show_header = false;		//去掉最上面的线条
			include $this->admin_tpl('store_audit');
		}	
	}

	/**
	 * [addaudit_record 插入用户审核记录]
	 * @param  [type] $userid   [用户id]
	 * @param  [type] $status   [审核状态：1待审核、2通过、3驳回]
	 * @param  [type] $audit_no [驳回理由]
	 * @return [type]           [description]
	 */
	function addaudit_record($userid,$status,$audit_no){
		$member_audit = $this->member_audit_db->get_one(['userid'=>$userid]);
		$data= [
			'userid'=>$userid,
			'addtime'=>time(),
			'shopname'=>$member_audit['shopname'],
			'store_logo'=>$member_audit['store_logo'],
			'audit'=>$status,
			'audit_no'=>$audit_no,
		];
		$this->member_audit_db->insert($data);
	}

	/**
	 * 会员管理_会员管理-店铺申请信息
	 */
	public function store_info()
	{
		$show_header = false;		//去掉最上面的线条
		
		$member = $this->member_db->get_one('userid='.$_GET['userid']);

		include $this->admin_tpl('store_info');
	}


	/**
	 * 会员管理_会员管理-开启/关闭禁用按钮
	 */
	public function member_islock()
	{
		if($_GET['islock']==1){
			//禁用
			$this->member_db->update(['islock'=>1],['userid'=>$_GET['userid']]);
		}elseif ($_GET['islock']==0) {
			//启用
			$this->member_db->update(['islock'=>0],['userid'=>$_GET['userid']]);
		}
		showmessage(L('operation_success'),HTTP_REFERER);
	}


	/**
	 * 会员管理_会员管理-店铺开启/关闭禁用按钮
	 */
	public function store_islock()
	{
		if($_GET['islock']==1){
			//禁用
			$this->member_db->update(['store_islock'=>1],['userid'=>$_GET['userid']]);
		}elseif ($_GET['islock']==0) {
			//启用
			$this->member_db->update(['store_islock'=>0],['userid'=>$_GET['userid']]);
		}
		showmessage(L('operation_success'),HTTP_REFERER);
	}

	/**
	 * 会员模型管理_会员模型管理-列表
	 */
	public function model()
	{
        $show_header = false;		//去掉最上面的线条

        $this->db = pc_base::load_model('sitemodel_model');
        $member_model_list = $this->db->listinfo(array('type'=>2, 'siteid'=>$this->get_siteid()), 'sort', $page, 10);
        include $this->admin_tpl('model');
	}


	/**
	 * 会员模型管理_会员模型管理-清空操作
	 */
	public function model_clear()
	{
		$modelid = $_GET['modelid'];
        $this->db = pc_base::load_model('member_model');
        switch ($modelid) {
            case 1:
                $sql  = "TRUNCATE TABLE zy_member";
                $aa = $this->db->query($sql);
                break;
            case 2:
                $sql  = "TRUNCATE TABLE zy_member_group";
                $aa = $this->db->query($sql);
                break;
            case 3:
                $sql  = "TRUNCATE TABLE zy_member_detail";
                $aa = $this->db->query($sql);
                break;
            case 4:
                $sql  = "TRUNCATE TABLE zy_sso_members";
                $aa = $this->db->query($sql);
                break;
            default:
                showmessage('操作失败', HTTP_REFERER);
                break;
        }
        showmessage("操作成功", HTTP_REFERER);
	}

	/**
	 * 会员模型管理_会员模型管理-字段管理
	 */
	public function model_field()
	{
		$modelid = $_GET['modelid'];
        $big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=zymember&c=zymember&a=model_field_add&id='.$modelid.'\', title:\'添加模型字段\', width:\'550\', height:\'350\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加模型字段');

        switch ($modelid) {
            case 1:
                $title = '会员主表';
                $sql = "select * from information_schema.columns where table_schema ='$this->db_name' and table_name = '$this->tables_member'";
                break;
            case 2:
                $title = '会员组表';
                $sql = "select * from information_schema.columns where table_schema ='$this->db_name' and table_name = '$this->tables_group'";
                break;
            case 3:
                $title = '会员附表';
                $sql = "select * from information_schema.columns where table_schema ='$this->db_name' and table_name = '$this->tables_detail'";
                break;
            case 4:
                $title = '会员sso表';
                $sql = "select * from information_schema.columns where table_schema ='$this->db_name' and table_name = '$this->tables_sso'";
                break;
            default:
                showmessage('操作失败', HTTP_REFERER);
                break;
        }
        
        //列出某表的所有结构
        $sql1 = $this->member_db->query($sql);

        include $this->admin_tpl('model_field');
	}

    /**
     * 会员模型管理_会员模型管理-字段管理-删除
     */
	public function model_field_del()
	{
        $modelid = $_GET['modelid'];
        $zd = $_GET['ziduan'];

        switch ($modelid) {
            case 1:
		        $sql =	"alter table `$this->tables_member` DROP COLUMN `$zd`" ;	 //删除字段
                break;
            case 2:
		        $sql =	"alter table `$this->tables_group` DROP COLUMN `$zd`" ;	 //删除字段
                break;
            case 3:
		        $sql =	"alter table `$this->tables_detail` DROP COLUMN `$zd`" ;	 //删除字段
                break;
            case 4:
		        $sql =	"alter table `$this->tables_sso` DROP COLUMN `$zd`" ;	 //删除字段
                break;
            default:
                showmessage('操作失败', HTTP_REFERER);
                break;
        }

        $info =	$this->member_db->query($sql);

        if($info){
            showmessage("删除成功", HTTP_REFERER ,'edit');
        }else{
            showmessage("删除失败");

        }

    }


    /**
     * 会员模型管理_会员模型管理-字段管理-添加
     */
	public function model_field_add()
	{
        if($_POST['dosubmit']){
            $modelid = $_POST['id'];
            $name =	$_POST["name"];
            if($_POST["type"]==1){
                $type=VARCHAR;
            }
            if($_POST["type"]==2){
                $type =TEXT;
            }

            if($_POST["type"]==3){
                $type =INT;
            }


            $lang =$_POST["lang"];
            $zhushi =$_POST["zhushi"];
            $null =$_POST["null"];
            if($_POST['status']==1){
                $kong = "NULL DEFAULT '$null' ";
            }

            if($_POST['status']==2){
                $kong = 'NOT NULL';
            }

	        switch ($modelid) {
	            case 1:
		            $sql = "ALTER TABLE `$this->tables_member` ADD `$name` $type($lang) CHARACTER SET utf8 COLLATE utf8_general_ci $kong COMMENT '$zhushi' ";
	                break;
	            case 2:
		            $sql = "ALTER TABLE `$this->tables_group` ADD `$name` $type($lang) CHARACTER SET utf8 COLLATE utf8_general_ci $kong COMMENT '$zhushi' ";
	                break;
	            case 3:
		            $sql = "ALTER TABLE `$this->tables_detail` ADD `$name` $type($lang) CHARACTER SET utf8 COLLATE utf8_general_ci $kong COMMENT '$zhushi' ";
	                break;
	            case 4:
		            $sql = "ALTER TABLE `$this->tables_sso` ADD `$name` $type($lang) CHARACTER SET utf8 COLLATE utf8_general_ci $kong COMMENT '$zhushi' ";
	                break;
	            default:
	                showmessage('操作失败', HTTP_REFERER);
	                break;
	        }


            //增加某表的结构
            $info =	$this->member_db->query($sql);

            if($info){
                showmessage("添加成功", HTTP_REFERER, '', 'add');
            }else{
                showmessage("添加失败");
            }

        }else{
            $modelid = $_GET['id'];
            switch ($modelid) {
                case 1:
                    $title = '会员主表';
                    break;
                case 2:
                    $title = '会员组表';
                    break;
                case 3:
                    $title = '会员附表';
                    break;
                case 4:
                    $title = '会员sso表';
                    break;
                default:
                    showmessage('操作失败', HTTP_REFERER);
                    break;
            }
            include $this->admin_tpl('model_field_add');
        }
    }

    /**
     * 会员模型管理_会员模型管理-字段管理-修改
     */
    public function model_field_edit(){
    	$modelid = $_GET['modelid'];
        $zd = $_GET['ziduan'];

        if($_POST['dosubmit']){
			$id =	$_POST["id"];
            $name = $_POST["name"];
            $modelid = $_POST["modelid"];
            if($_POST["type"]==1){
                $type=VARCHAR;
            }
            if($_POST["type"]==2){
                $type =TEXT;
            }

            if($_POST["type"]==3){
                $type =INT;
            }


            $lang =$_POST["lang"];
            $zhushi =$_POST["zhushi"];
            $null = $_POST["null"];
            if($_POST['status']==1){
                $kong = "NOT null DEFAULT '$null'";
            }

            if($_POST['status']==2){
                $kong = 'NOT NULL';
            }

	        switch ($modelid) {
	            case 1:
	                $sql = " ALTER TABLE `$this->tables_member` CHANGE `$id` `$name` $type($lang) CHARACTER SET utf8 COLLATE utf8_general_ci $kong COMMENT '$zhushi' ";
	                break;
	            case 2:
	                $sql = " ALTER TABLE `$this->tables_group` CHANGE `$id` `$name` $type($lang) CHARACTER SET utf8 COLLATE utf8_general_ci $kong COMMENT '$zhushi' ";
	                break;
	            case 3:
	                $sql = " ALTER TABLE `$this->tables_detail` CHANGE `$id` `$name` $type($lang) CHARACTER SET utf8 COLLATE utf8_general_ci $kong COMMENT '$zhushi' ";
	                break;
	            case 4:
	                $sql = " ALTER TABLE `$this->tables_sso` CHANGE `$id` `$name` $type($lang) CHARACTER SET utf8 COLLATE utf8_general_ci $kong COMMENT '$zhushi' ";
	                break;
	            default:
	                showmessage('操作失败', HTTP_REFERER);
	                break;
	        }


            $info =	$this->member_db->query($sql);	 //修改字段属性
            if($info){
                showmessage("修改成功", HTTP_REFERER, '','edit');
            }else{
                showmessage("修改失败");
            }

        }else{
	        switch ($modelid) {
	            case 1:
	            $sql = "select * from information_schema.columns where table_schema ='$this->db_name' and table_name = '$this->tables_member' and COLUMN_NAME= '$zd' ";
	                break;
	            case 2:
	                $sql = "select * from information_schema.columns where table_schema ='$this->db_name' and table_name = '$this->tables_group' and COLUMN_NAME= '$zd' ";
	                break;
	            case 3:
	                $sql = "select * from information_schema.columns where table_schema ='$this->db_name' and table_name = '$this->tables_detail' and COLUMN_NAME= '$zd' ";
	                break;
	            case 4:
	                $sql = "select * from information_schema.columns where table_schema ='$this->db_name' and table_name = '$this->tables_sso' and COLUMN_NAME= '$zd' ";
	                break;
	            default:
	                showmessage('操作失败', HTTP_REFERER);
	                break;
	        }
            //查找某数据库中的某表的某个字段的结构
            $sql1 = $this->member_db->query($sql);
            foreach($sql1 as $info){
            }
        }
        include $this->admin_tpl('model_field_edit');
    }


	/**
	 * 会员模型管理_会员基本设置-列表
	 */
	public function config()
	{

        $member_setting = $this->module_db->get_one(array('module'=>'member'), 'setting');
        $member_setting = string2array($member_setting['setting']);

		//这里是获取ZY系统配置的信息
		$setconfig = pc_base::load_config('zysystem');	
		extract($setconfig);

        include $this->admin_tpl('config');
	}

	/**
	 * 会员模型管理_会员基本设置-修改
	 */
	public function config_edit()
	{
        $member_setting = array2string($_POST['info']);
        $this->module_db->update(array('module'=>'member', 'setting'=>$member_setting), array('module'=>'member'));
        showmessage(L('operation_success'), HTTP_REFERER);
	}

	/**
	 * 会员模型管理_会员基本设置-接口配置修改
	 */
	public function config_edit_sys()
	{
		set_config($_POST['setconfig'],'zysystem');	 //保存进config文件
		showmessage(L('update_success'), HTTP_REFERER, '', 'view');
	}


	/**
	 * 会员模型管理_会员组管理-列表
	 */
	public function group()
	{
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$member_group_list = $this->member_group_db->listinfo('', 'sort ASC,groupid ASC', $page, 15);

		//TODO 此处循环中执行sql，会严重影响效率，稍后考虑在memebr_group表中加入会员数字段和统计会员总数功能解决。
		foreach ($member_group_list as $k=>$v) {
			$membernum = $this->member_db->count(array('groupid'=>$v['groupid']));
			$member_group_list[$k]['membernum'] = $membernum;
		}
		$pages = $this->member_group_db->pages;
		
		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=zymember&c=zymember&a=group_add\', title:\'添加会员组\', width:\'600\', height:\'400\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', L('member_group_add'));

        include $this->admin_tpl('group');
	}

	/**
	 * 会员模型管理_会员组管理-添加
	 */
	public function group_add()
	{
		if(isset($_POST['dosubmit'])) {
			$info = array();
			if(!$this->group_checkname($_POST['info']['name'])){
				showmessage('1');
			}
			$info = $_POST['info'];
			$info['allowpost'] = $info['allowpost'] ? 1 : 0;
			$info['allowupgrade'] = $info['allowupgrade'] ? 1 : 0;
			$info['allowpostverify'] = $info['allowpostverify'] ? 1 : 0;
			$info['allowsendmessage'] = $info['allowsendmessage'] ? 1 : 0;
			$info['allowattachment'] = $info['allowattachment'] ? 1 : 0;
			$info['allowsearch'] = $info['allowsearch'] ? 1 : 0;
			$info['allowvisit'] = $info['allowvisit'] ? 1 : 0;
			
			$this->member_group_db->insert($info);
			if($this->member_group_db->insert_id()){
				$this->group_updatecache();
				showmessage('操作成功','?m=zymember&c=zymember&a=group', '', 'add');
			}
		} else {
			$show_header = $show_scroll = true;
			include $this->admin_tpl('group_add');
		}
	}

	/**
	 * 会员模型管理_会员组管理-删除
	 */
	public function group_del()
	{
		$groupidarr = isset($_POST['groupid']) ? $_POST['groupid'] : showmessage('非法参数', HTTP_REFERER);
		$where = to_sqls($groupidarr, '', 'groupid');
		if ($this->member_group_db->delete($where)) {
			$this->group_updatecache();
			showmessage('操作成功', HTTP_REFERER);
		} else {
			showmessage('操作失败', HTTP_REFERER);
		}
	}

	/**
	 * 会员模型管理_会员组管理-修改
	 */
	public function group_edit() 
	{
		if(isset($_POST['dosubmit'])) {
			$info = array();
			$info = $_POST['info'];

			$info['allowpost'] = isset($info['allowpost']) ? 1 : 0;
			$info['allowupgrade'] = isset($info['allowupgrade']) ? 1 : 0;
			$info['allowpostverify'] = isset($info['allowpostverify']) ? 1 : 0;
			$info['allowsendmessage'] = isset($info['allowsendmessage']) ? 1 : 0;
			$info['allowattachment'] = isset($info['allowattachment']) ? 1 : 0;
			$info['allowsearch'] = isset($info['allowsearch']) ? 1 : 0;
			$info['allowvisit'] = isset($info['allowvisit']) ? 1 : 0;
			
			$this->member_group_db->update($info, array('groupid'=>$info['groupid']));
			
			$this->group_updatecache();
			showmessage(L('operation_success'), '?m=zymember&c=zymember&a=group_edit', '', 'edit');
		} else {					
			$show_header = $show_scroll = true;
			$groupid = isset($_GET['groupid']) ? $_GET['groupid'] : showmessage(L('illegal_parameters'), HTTP_REFERER);
			
			$groupinfo = $this->member_group_db->get_one(array('groupid'=>$groupid));
			include $this->admin_tpl('group_edit');		
		}
	}
	
	/**
	 * 会员模型管理_会员组管理-排序
	 */
	public function group_sort() 
	{		
		if(isset($_POST['sort'])) {
			foreach($_POST['sort'] as $k=>$v) {
				$this->member_group_db->update(array('sort'=>$v), array('groupid'=>$k));
			}
			
			$this->group_updatecache();
			showmessage(L('operation_success'), HTTP_REFERER);
		} else {
			showmessage(L('operation_failure'), HTTP_REFERER);
		}
	}
	

	/**
	 * 会员模型管理_会员组管理-检查用户名是否合法
	 * @param string $name
	 */
	private function group_checkname($name = NULL) 
	{
		if(empty($name)) return false;
		if ($this->member_group_db->get_one(array('name'=>$name),'groupid')){
			return false;
		}
		return true;
	}


	/**
	 * 会员模型管理_会员组管理-更新会员组列表缓存
	 */
	private function group_updatecache()
	{
		$grouplist = $this->member_group_db->listinfo('', '', 1, 1000, 'groupid');
		setcache('grouplist', $grouplist);
	}


	/**
	 * 会员模型管理_会员组管理-添加-验证会员组名称
	 */
	public function group_checkname_ajax()
	{
		$name = isset($_GET['name']) && trim($_GET['name']) ? trim($_GET['name']) : exit(0);
		$name = iconv('utf-8', CHARSET, $name);
		
		if ($this->member_group_db->get_one(array('name'=>$name),'groupid')){
			exit('0');
		} else {
			exit('1');
		}
	}



//===========================会员模块 END




	/**
	 * 用户管理
	 */
	public function user_manage(){

		//调用两张数据表
		$where= 'member_types=1';
		if($_GET['type']){
			if($_GET['q']){
				if($_GET['type'] == 1){
					$where .= " and username like '%".$_GET['q']."%' ";
				}
				if($_GET['type'] == 2){
					$where .= " and userid ='".$_GET['q']."'";
				}
				if($_GET['type'] == 3){
					$where .= " and name like '%".$_GET['q']."%' ";
				}
				if($_GET['type'] == 4){
					$where .= " and mobile like '%".$_GET['q']."%' ";
				}
				if($_GET['type'] == 5){
					$where .= " and nickname like '%".$_GET['q']."%' ";
				}
			}
		}
		//省/市/区
		if($_GET['province']){
			$where .= " and province ='".$_GET['province']."'";
		}
		if($_GET['city']){
			$where .= " and city ='".$_GET['city']."'";
		}
		if($_GET['districe']){
			$where .= " and districe ='".$_GET['districe']."'";
		}
		//正常/禁用
		if($_GET['disable']){
			if($_GET['disable'] == 1){
				$where .= " and disable = 1 ";
			}
			if($_GET['disable'] == 2){
				$where .= " and disable = 2";
			}
		}
		//已认证/未认证
		if($_GET['certification']){
			$where .= " and certification = ".$_GET['certification'];
		}
		//申请产品分类
		if($_GET['product_types']){
			$where .= " and product_types ='".$_GET['product_types']."'";
		}

		//日期
		if($_GET['time']){
			if($_GET['time']==1){
				$start_addtime = strtotime(date("Y-m-d 00:00:00"));
				$end_addtime = strtotime(date("Y-m-d 23:59:59"));
			}
			if($_GET['time']==2){
				$start_addtime = strtotime(date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),1,date("Y"))));	//本月月初
				$end_addtime = strtotime(date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("t"),date("Y"))));	//本月月末
			}
			$where .= " and regdate >= '".$start_addtime."'";
			$where .= " and regdate <= '".$end_addtime."'";

		}


		/*if($_GET['start_addtime']){
			$where .= " and regdate >= '".strtotime($_GET['start_addtime'])."'";
		}
		if($_GET['end_addtime']){
			$where .= " and regdate <= '".strtotime($_GET['end_addtime'])."'";
		}*/
		$order = 'userid DESC';
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$info=$this->member_db->listinfo($where,$order,$page); //读取数据库里的字段
		$pages = $this->member_db->pages;  //分页


		//用户数
		$userNum =count($this->member_db->select('member_types=1')); 
		//今日新增
		$jr_timeStart = strtotime(date("Y-m-d 00:00:00"));;
		$jr_timeEnd = strtotime(date("Y-m-d 23:59:59"));
		$jr_timeWhere = 'member_types=1';
		$jr_timeWhere .= " and regdate >= '".$jr_timeStart."'";
		$jr_timeWhere .= " and regdate <= '".$jr_timeEnd."'";
		$jr_userNum =count($this->member_db->select($jr_timeWhere)); 
		//本月新增
		$by_timeStart = strtotime(date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),1,date("Y"))));	//本月月初
		$by_timeEnd = strtotime(date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("t"),date("Y"))));	//本月月末
		$by_timeWhere = 'member_types=1';
		$by_timeWhere .= " and regdate >= '".$by_timeStart."'";
		$by_timeWhere .= " and regdate <= '".$by_timeEnd."'";
		$by_userNum =count($this->member_db->select($by_timeWhere)); 

		//var_dump($where);



		//添加会员
		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=zymember&c=member&a=user_manage_add\', title:\'添加用户\', width:\'700\', height:\'500\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加用户');


			$province_arr = $this->linkage_db->select('parentid=0 and linkageid!=1');

			/* 删除 */
			/*============ 等地址联动做好，这里就删除 ===========*/
			/*$province_arr = [
				0=>[
					'id'=>1,
					'name'=>"周边旅游",
					'level'=>1,
					'parent'=>0,
					'children'=>[11,12],
				],
				1=>[
					'id'=>2,
					'name'=>"出境旅游",
					'level'=>1,
					'parent'=>0,
					'children'=>[21,22],
				],
			];


			$province_json = '[{"id": 1,"name": "周边旅游","level": 1,"parent": 0,"children": [2,3]}]';
			$city_json = '[{"id": 2,"name": "广东","level": 2,"parent": 1,"children": [4,5,6]},{"id": 3,"name": "上海","level": 2,"parent": 1,"children": []}]';
			$districe_json = '[{"id": 4,"name": "广州","level": 3,"parent": 2,"children": []},{"id": 5,"name": "潮州","level": 3,"parent": 2,"children": []},{"id": 6,"name": "沙巴","level": 3,"parent": 2,"children": []}]';*/
			/* 删除 */

		include $this->admin_tpl('user_manage');
	}

	/**
	* 用户管理_添加
	*/
	public function user_manage_add(){

		if($_POST['dosubmit']){


			$userinfo = array();
			//用户基本信息
			$userinfo['username'] = create_randomstr(8);
			$userinfo['password'] = $_POST['password'];
			$userinfo['encrypt'] = create_randomstr(6);
			$userinfo['nickname'] = $_POST['nickname'];
			$userinfo['regdate'] = time();
			$userinfo['regip'] = ip();
			$userinfo['email'] = time().'300c.cn';
			$userinfo['mobile'] = $_POST['mobile'];
			$userinfo['groupid'] = 2;
			$userinfo['modelid'] = 10;
			$userinfo['islock'] = $_POST['disable']==1 ? 0 : 1;
			$userinfo['province'] = $_POST['province'] ? $_POST['province'] : exit(0);
			$userinfo['city'] = $_POST['city'];
			$userinfo['districe'] = $_POST['districe'];
			$userinfo['certification'] = $_POST['certification'];
			$userinfo['disable'] = $_POST['disable'];
			$userinfo['name'] = $_POST['name'];
			$userinfo['idcard'] = $_POST['idcard'];

			$userinfo['professional'] = $_POST['professional'];
			$userinfo['product_types'] = $_POST['product_types'];
			$userinfo['member_types'] = 1;
			$userinfo['memberinfo_types'] = $_POST['enterprise'];
			$userinfo['enterprise'] = $_POST['enterprise'];
			$userinfo['mclass'] = '0';

			//公司信息
			if($userinfo['enterprise']==2){
				$userinfo['company'] = $_POST['company'];
				$userinfo['oper_name'] = $_POST['oper_name'];
				$userinfo['artners_name'] = $_POST['artners_name'];
				$userinfo['credit_code'] = $_POST['credit_code'];
			}


			
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

			showmessage(L('operation_success'), '?m=member&c=member&a=user_manage', '', 'add');
			
		}else{
			$province_arr = $this->linkage_db->select('parentid=0 and linkageid!=1');

			/* 删除 */
			/*============ 等地址联动做好，这里就删除 ===========*/
			/*$province_arr = [
				0=>[
					'id'=>1,
					'name'=>"周边旅游",
					'level'=>1,
					'parent'=>0,
					'children'=>[11,12],
				],
				1=>[
					'id'=>2,
					'name'=>"出境旅游",
					'level'=>1,
					'parent'=>0,
					'children'=>[21,22],
				],
			];


			$province_json = '[{"id": 1,"name": "周边旅游","level": 1,"parent": 0,"children": [2,3]}]';
			$city_json = '[{"id": 2,"name": "广东","level": 2,"parent": 1,"children": [4,5,6]},{"id": 3,"name": "上海","level": 2,"parent": 1,"children": []}]';
			$districe_json = '[{"id": 4,"name": "广州","level": 3,"parent": 2,"children": []},{"id": 5,"name": "潮州","level": 3,"parent": 2,"children": []},{"id": 6,"name": "沙巴","level": 3,"parent": 2,"children": []}]';*/
			/* 删除 */

			include $this->admin_tpl('user_manage_add');
		}
	}	



	/**
	 * 代理商管理
	 */
	public function agent_manage(){
		//调用两张数据表
		$where= 'member_types=2';

		if($_GET['type']){
			if($_GET['q']){
				if($_GET['type'] == 1){
					$where .= " and username like '%".$_GET['q']."%' ";
				}
				if($_GET['type'] == 2){
					$where .= " and userid ='".$_GET['q']."'";
				}
				if($_GET['type'] == 3){
					$where .= " and name like '%".$_GET['q']."%' ";
				}
				if($_GET['type'] == 4){
					$where .= " and mobile like '%".$_GET['q']."%' ";
				}
				if($_GET['type'] == 5){
					$where .= " and nickname like '%".$_GET['q']."%' ";
				}
			}
		}
		//省/市/区
		if($_GET['province']){
			$where .= " and province ='".$_GET['province']."'";
		}
		if($_GET['city']){
			$where .= " and city ='".$_GET['city']."'";
		}
		if($_GET['districe']){
			$where .= " and districe ='".$_GET['districe']."'";
		}
		//正常/禁用
		if($_GET['disable']){
			if($_GET['disable'] == 1){
				$where .= " and disable = 1 ";
			}
			if($_GET['disable'] == 2){
				$where .= " and disable = 2";
			}
		}
		//已认证/未认证
		if($_GET['certification']){
			$where .= " and certification = ".$_GET['certification'];
		}
		//申请产品分类
		if($_GET['product_types']){
			$where .= " and product_types ='".$_GET['product_types']."'";
		}
		//等级
		if($_GET['memberinfo_types']){
			$where .= " and memberinfo_types ='".$_GET['memberinfo_types']."'";
		}


		$order = 'userid DESC';
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$info=$this->member_db->listinfo($where,$order,$page); //读取数据库里的字段
		$pages = $this->member_db->pages;  //分页


		//添加会员
		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=zymember&c=member&a=agent_manage_add\', title:\'添加代理商\', width:\'700\', height:\'500\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加代理商');

			$province_arr = $this->linkage_db->select('parentid=0 and linkageid!=1');

			/* 删除 */
			/*============ 等地址联动做好，这里就删除 ===========*/
			/*$province_arr = [
				0=>[
					'id'=>1,
					'name'=>"周边旅游",
					'level'=>1,
					'parent'=>0,
					'children'=>[11,12],
				],
				1=>[
					'id'=>2,
					'name'=>"出境旅游",
					'level'=>1,
					'parent'=>0,
					'children'=>[21,22],
				],
			];


			$province_json = '[{"id": 1,"name": "周边旅游","level": 1,"parent": 0,"children": [2,3]}]';
			$city_json = '[{"id": 2,"name": "广东","level": 2,"parent": 1,"children": [4,5,6]},{"id": 3,"name": "上海","level": 2,"parent": 1,"children": []}]';
			$districe_json = '[{"id": 4,"name": "广州","level": 3,"parent": 2,"children": []},{"id": 5,"name": "潮州","level": 3,"parent": 2,"children": []},{"id": 6,"name": "沙巴","level": 3,"parent": 2,"children": []}]';*/
			/* 删除 */

			$loanstype = $this->zyconfig_loanstype->select('parentid!=0','*','','listorder ASC,id DESC');


		include $this->admin_tpl('agent_manage');
	}

	


	/**
	* 代理商管理_添加
	*/
	public function agent_manage_add(){

		if($_POST['dosubmit']){


			$userinfo = array();
			//用户基本信息
			$userinfo['username'] = create_randomstr(8);
			$userinfo['password'] = $_POST['password'];
			$userinfo['encrypt'] = create_randomstr(6);
			$userinfo['nickname'] = $_POST['nickname'];
			$userinfo['regdate'] = time();
			$userinfo['regip'] = ip();
			$userinfo['email'] = time().'300c.cn';
			$userinfo['mobile'] = $_POST['mobile'];
			$userinfo['groupid'] = 2;
			$userinfo['modelid'] = 10;
			$userinfo['islock'] = $_POST['disable']==1 ? 0 : 1;
			$userinfo['province'] = $_POST['province'] ? $_POST['province'] : exit(0);
			$userinfo['city'] = $_POST['city'];
			$userinfo['districe'] = $_POST['districe'];
			$userinfo['certification'] = $_POST['certification'];
			$userinfo['disable'] = $_POST['disable'];
			$userinfo['name'] = $_POST['name'];
			$userinfo['idcard'] = $_POST['idcard'];

			$userinfo['professional'] = $_POST['professional'];
			$userinfo['product_types'] = $_POST['product_types2'];
			$userinfo['memberinfo_types'] = $_POST['memberinfo_types'];
			$userinfo['member_types'] = 2;
			$userinfo['memberinfo_types'] = $_POST['memberinfo_types'];
			$userinfo['enterprise'] = $_POST['enterprise'];

			if($_POST['memberinfo_types']==21){
				$userinfo['mclass']=3;
			}elseif($_POST['memberinfo_types']==22){
				$userinfo['mclass']=2;
			}elseif($_POST['memberinfo_types']==23){
				$userinfo['mclass']=1;
			}

			//公司信息
			if($userinfo['enterprise']==2){
				$userinfo['company'] = $_POST['company'];
				$userinfo['oper_name'] = $_POST['oper_name'];
				$userinfo['artners_name'] = $_POST['artners_name'];
				$userinfo['credit_code'] = $_POST['credit_code'];
			}


			
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

			showmessage(L('operation_success'), '?m=member&c=member&a=agent_manage', '', 'add');
			
		}else{
			$province_arr = $this->linkage_db->select('parentid=0 and linkageid!=1');

			/* 删除 */
			/*============ 等地址联动做好，这里就删除 ===========*/
			/*$province_arr = [
				0=>[
					'id'=>1,
					'name'=>"周边旅游",
					'level'=>1,
					'parent'=>0,
					'children'=>[11,12],
				],
				1=>[
					'id'=>2,
					'name'=>"出境旅游",
					'level'=>1,
					'parent'=>0,
					'children'=>[21,22],
				],
			];


			$province_json = '[{"id": 1,"name": "周边旅游","level": 1,"parent": 0,"children": [2,3]}]';
			$city_json = '[{"id": 2,"name": "广东","level": 2,"parent": 1,"children": [4,5,6]},{"id": 3,"name": "上海","level": 2,"parent": 1,"children": []}]';
			$districe_json = '[{"id": 4,"name": "广州","level": 3,"parent": 2,"children": []},{"id": 5,"name": "潮州","level": 3,"parent": 2,"children": []},{"id": 6,"name": "沙巴","level": 3,"parent": 2,"children": []}]';*/
			/* 删除 */

			//菜单
			$result = $this->zyconfig_loanstype->select('parentid=0','*','','listorder ASC,id DESC');

			include $this->admin_tpl('agent_manage_add');
		}
	}	





	/**
	 * 产品经理管理
	 */
	public function manager_manage(){
		
		//调用两张数据表
		$where= 'member_types=3';

		if($_GET['type']){
			if($_GET['q']){
				if($_GET['type'] == 1){
					$where .= " and username like '%".$_GET['q']."%' ";
				}
				if($_GET['type'] == 2){
					$where .= " and userid ='".$_GET['q']."'";
				}
				if($_GET['type'] == 3){
					$where .= " and name like '%".$_GET['q']."%' ";
				}
				if($_GET['type'] == 4){
					$where .= " and mobile like '%".$_GET['q']."%' ";
				}
				if($_GET['type'] == 5){
					$where .= " and nickname like '%".$_GET['q']."%' ";
				}
			}
		}
		//省/市/区
		if($_GET['province']){
			$where .= " and province ='".$_GET['province']."'";
		}
		if($_GET['city']){
			$where .= " and city ='".$_GET['city']."'";
		}
		if($_GET['districe']){
			$where .= " and districe ='".$_GET['districe']."'";
		}
		//正常/禁用
		if($_GET['disable']){
			if($_GET['disable'] == 1){
				$where .= " and disable = 1 ";
			}
			if($_GET['disable'] == 2){
				$where .= " and disable = 2";
			}
		}
		//等级
		if($_GET['memberinfo_types']){
			if($_GET['memberinfo_types'] == 11){
				$where .= " and memberinfo_types = 11 ";
			}
			if($_GET['memberinfo_types'] == 12){
				$where .= " and memberinfo_types = 12";
			}
			if($_GET['memberinfo_types'] == 13){
				$where .= " and memberinfo_types = 13";
			}
		}
		//申请产品分类
		if($_GET['product_types']){
			$where .= " and product_types ='".$_GET['product_types']."'";
		}

		$order = 'userid DESC';
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$info=$this->member_db->listinfo($where,$order,$page); //读取数据库里的字段
		$pages = $this->member_db->pages;  //分页


		//添加会员
		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=zymember&c=member&a=manager_manage_add\', title:\'添加经理\', width:\'700\', height:\'500\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加经理');




			$province_arr = $this->linkage_db->select('parentid=0 and linkageid!=1');

			/* 删除 */
			/*============ 等地址联动做好，这里就删除 ===========*/
			/*$province_arr = [
				0=>[
					'id'=>1,
					'name'=>"周边旅游",
					'level'=>1,
					'parent'=>0,
					'children'=>[11,12],
				],
				1=>[
					'id'=>2,
					'name'=>"出境旅游",
					'level'=>1,
					'parent'=>0,
					'children'=>[21,22],
				],
			];


			$province_json = '[{"id": 1,"name": "周边旅游","level": 1,"parent": 0,"children": [2,3]}]';
			$city_json = '[{"id": 2,"name": "广东","level": 2,"parent": 1,"children": [4,5,6]},{"id": 3,"name": "上海","level": 2,"parent": 1,"children": []}]';
			$districe_json = '[{"id": 4,"name": "广州","level": 3,"parent": 2,"children": []},{"id": 5,"name": "潮州","level": 3,"parent": 2,"children": []},{"id": 6,"name": "沙巴","level": 3,"parent": 2,"children": []}]';*/
			/* 删除 */


		include $this->admin_tpl('manager_manage');
	}




	/**
	* 用户管理_添加
	*/
	public function manager_manage_add(){

		if($_POST['dosubmit']){


			$userinfo = array();
			//用户基本信息
			$userinfo['username'] = create_randomstr(8);
			$userinfo['password'] = $_POST['password'];
			$userinfo['encrypt'] = create_randomstr(6);
			$userinfo['nickname'] = $_POST['nickname'];
			$userinfo['regdate'] = time();
			$userinfo['regip'] = ip();
			$userinfo['email'] = time().'300c.cn';
			$userinfo['mobile'] = $_POST['mobile'];
			$userinfo['groupid'] = 2;
			$userinfo['modelid'] = 10;
			$userinfo['islock'] = $_POST['disable']==1 ? 0 : 1;
			$userinfo['province'] = $_POST['province'] ? $_POST['province'] : exit(0);
			$userinfo['city'] = $_POST['city'];
			$userinfo['districe'] = $_POST['districe'];
			$userinfo['certification'] = 1;
			$userinfo['disable'] = $_POST['disable'];
			$userinfo['name'] = $_POST['name'];
			$userinfo['idcard'] = $_POST['idcard'];

			$userinfo['professional'] = $_POST['professional'];
			$userinfo['product_types'] = $_POST['product_types'];
			$userinfo['member_types'] = 3;
			$userinfo['memberinfo_types'] = $_POST['memberinfo_types'] ? $_POST['memberinfo_types'] : 13;
			$userinfo['enterprise'] = $_POST['enterprise'];


			if($userinfo['memberinfo_types']==11){
				$userinfo['memberinfo_types']=11;
			}elseif($userinfo['memberinfo_types']==12){
				$userinfo['memberinfo_types']=12;
			}elseif($userinfo['memberinfo_types']==13){
				$userinfo['memberinfo_types']=13;
			}

			//公司信息
			/*if($userinfo['enterprise']==2){
				$userinfo['company'] = $_POST['company'];
				$userinfo['oper_name'] = $_POST['oper_name'];
				$userinfo['artners_name'] = $_POST['artners_name'];
				$userinfo['credit_code'] = $_POST['credit_code'];
			}*/


			
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

			showmessage(L('operation_success'), '?m=member&c=member&a=user_manage', '', 'add');
			
		}else{
			$province_arr = $this->linkage_db->select('parentid=0 and linkageid!=1');

			/* 删除 */
			/*============ 等地址联动做好，这里就删除 ===========*/
			/*$province_arr = [
				0=>[
					'id'=>1,
					'name'=>"周边旅游",
					'level'=>1,
					'parent'=>0,
					'children'=>[11,12],
				],
				1=>[
					'id'=>2,
					'name'=>"出境旅游",
					'level'=>1,
					'parent'=>0,
					'children'=>[21,22],
				],
			];


			$province_json = '[{"id": 1,"name": "周边旅游","level": 1,"parent": 0,"children": [2,3]}]';
			$city_json = '[{"id": 2,"name": "广东","level": 2,"parent": 1,"children": [4,5,6]},{"id": 3,"name": "上海","level": 2,"parent": 1,"children": []}]';
			$districe_json = '[{"id": 4,"name": "广州","level": 3,"parent": 2,"children": []},{"id": 5,"name": "潮州","level": 3,"parent": 2,"children": []},{"id": 6,"name": "沙巴","level": 3,"parent": 2,"children": []}]';*/
			/* 删除 */

			include $this->admin_tpl('manager_manage_add');
		}
	}	




	/**
	 * [member_disable 开启/关闭禁用按钮]
	 * @return [type] [description]
	 */
	public function member_disable(){
		if($_GET['disable']==1){
			//禁用
			$this->member_db->update(['islock'=>1,'disable'=>2],['userid'=>$_GET['userid']]);
		}elseif ($_GET['disable']==2) {
			//启用
			$this->member_db->update(['islock'=>0,'disable'=>1],['userid'=>$_GET['userid']]);
		}
		showmessage(L('operation_success'),HTTP_REFERER);
	}



	/**
	* 用户管理_查看
	*/
	public function member_manage_view(){
		$show_header = false;		//去掉最上面的线条
		

		$member = $this->member_db->get_one('userid='.$_GET['userid']);
		$bankinfo = $this->bankcard_db->select('userid='.$_GET['userid']);

		//用户身份证照片
		$member['idcard_img'] = json_decode($member['idcard_img']);

		if($_GET['type']==1){
			include $this->admin_tpl('user_manage_view');
		}elseif($_GET['type']==2){
			include $this->admin_tpl('agent_manage_view');
		}elseif($_GET['type']==3){
			include $this->admin_tpl('manager_manage_view');
		}
	}



	/**
	* 用户管理_删除
	*/
	public function member_manage_del(){
		//删除单个
		$id=intval($_GET['id']);
		if($id){
			$result=$this->member_db->delete(array('userid'=>$id));
			$this->member_detail_db->delete(array('userid'=>$id));
			$this->sso_members_db->delete(array('uid'=>$id));
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
				//$result=$this->lottery_num_db->delete(array('id'=>$pid));
			}
			showmessage(L('operation_success'),HTTP_REFERER);
		}

		//都没有选择删除什么
		if( empty($_POST['id'])){
			showmessage('请选择要删除的用户',HTTP_REFERER);
		}
	}





	/**
	* php正则验证密码规则
	* 只允许 数字、字母、下划线
	* 最短6位、最长16位
	*/
	function ispassword($str) {
		if (preg_match('/^[_0-9a-z]{6,16}$/i',$str)){
			return true;
		}else {
			return false;
		}
	}


	/**
	 * [import_manager 导出产品经理]
	 * @return [type] [description]
	 */
	public function import_manager(){

		pc_base::load_app_func('global','zyexcel');
		$ceshi_db  = pc_base::load_model('hospitals_model');	//需要增加的表
		
if (! empty ( $_FILES ['file_stu'] ['name'] ))
 
		$tmp_file = $_FILES ['file_stu'] ['tmp_name'];
	    $file_types = explode ( ".", $_FILES ['file_stu'] ['name'] );
	    $file_type = $file_types [count ( $file_types ) - 1];
	     /*判别是不是.xls文件，判别是不是excel文件*/

	     if (strtolower ( $file_type ) != "xls" && strtolower ( $file_type ) != "xlsx"){
	         showmessage('不是excel文件，请重新选择文件',HTTP_REFERER);
	     }
	 
		 $basepath = str_replace( '\\' , '/' , realpath(dirname(__FILE__).'/../../../'));
		
	    /*设置上传路径*/
	    $savePath = $basepath.'/uploadfile/excel/';

	    //如果文件夹不存在，那么就新建文件夹
		if(!file_exists($savePath)){
			mkdir($savePath);
		}


	    /*以时间来命名上传的文件*/
		
	     $str = date ( 'Ymdhis' ); 
	     $file_name = $str . "." . $file_type;
	
/*echo '<pre>';
var_dump($tmp_file);
var_dump($savePath.$file_name);
echo '<pre>';
exit;	
*/

     /*是否上传成功*/
     if (! copy ( $tmp_file, $savePath.$file_name )) 
      {
          showmessage('上传失败',HTTP_REFERER);
      }
		$filename =$savePath.$file_name;
		$res = excel_import($filename);
		
		
/*echo '<pre>';
var_dump($res);
echo '<pre>';
exit;*/
	

		$exist = array();
		$exist_num = 0;
		//写入数据库--
		foreach ($res as $v) {
			
			//=================如果账号已存在，那么就跳过这一条,并且记录一下
			if($this->member_db->get_one(['mobile'=>$v[3]])){
				$exist[$exist_num]=$v[3];
				$exist_num++;
				continue;
			}

			//=================发送短信写在这里
			

			//===================下面是插入数据操作
			/* 增加会员的数据组 */
			$userinfo = array();
			//用户基本信息
			$userinfo['username'] = create_randomstr(8);
			$userinfo['password'] = $v[4];
			$userinfo['encrypt'] = create_randomstr(6);
			$userinfo['nickname'] = $v[0];
			$userinfo['regdate'] = time();
			$userinfo['regip'] = ip();
			$userinfo['email'] = time().'300c.cn';
			$userinfo['mobile'] = $v[3];
			$userinfo['groupid'] = 2;
			$userinfo['modelid'] = 10;
			$userinfo['islock'] = $v[10]==1 ? 0 : 1;
			$userinfo['province'] = $v[6] ? $v[6] : exit(0);
			$userinfo['city'] = $v[7];
			$userinfo['districe'] = $v[8];
			$userinfo['certification'] = 1;
			$userinfo['disable'] = $v[10];
			$userinfo['name'] = $v[1];
			$userinfo['idcard'] = $v[2];

			//$userinfo['professional'] = $v[3];
			$userinfo['product_types'] = $v[9];
			$userinfo['member_types'] = 3;
			$userinfo['memberinfo_types'] = $v[5] ? $v[5] : 13;
			$userinfo['enterprise'] = $v['enterprise'];


			if($userinfo['memberinfo_types']==11){
				$userinfo['memberinfo_types']=11;
			}elseif($userinfo['memberinfo_types']==12){
				$userinfo['memberinfo_types']=12;
			}elseif($userinfo['memberinfo_types']==13){
				$userinfo['memberinfo_types']=13;
			}

			//公司信息
			/*if($userinfo['enterprise']==2){
				$userinfo['company'] = $_POST['company'];
				$userinfo['oper_name'] = $_POST['oper_name'];
				$userinfo['artners_name'] = $_POST['artners_name'];
				$userinfo['credit_code'] = $_POST['credit_code'];
			}*/


			
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
			/* 增加会员的数据组 */


		}


		
		if ($exist){
			echo '<h1>已存在用户列表</h1>';
			echo '<pre>';
			var_dump($exist);
			echo '<pre>';
			exit;
		}else{
			showmessage('导入成功',HTTP_REFERER);
		}

	}



	/**
	 * [export_member 导出会员信息]
	 * @return [type] [description]
	 */
	public function export_member(){
		pc_base::load_app_func('global','zyexcel');

		$where = 'member_types=1';


		//会员资料所有导出，主表
		$member_array = $this->member_db->select($where,'`userid`,`username`,`nickname`,`name`,`province`,`city`,`districe`,`regdate`,`professional`,`product_types`,`certification`,`disable`,`idcard`,`enterprise`,`company`,`oper_name`,`artners_name`,`credit_code`');
		for ($i=0; $i < count($member_array); $i++) { 
			$member_array[$i]['address']=$member_array[$i]['province'].','.$member_array[$i]['city'];
			$member_array[$i]['regdate']=date('Y-m-d h:i:s',$member_array[$i]['regdate']);

			if ($member_array[$i]['certification']==1) {
				$member_array[$i]['certification'] = '已认证';
			}else{
				$member_array[$i]['certification'] = '未认证';
			}
			if ($member_array[$i]['disable']==1) {
				$member_array[$i]['disable'] = '正常';
			}else{
				$member_array[$i]['disable'] = '禁用';
			}
			if ($member_array[$i]['enterprise']==1) {
				$member_array[$i]['userid'] = $member_array[$i]['userid'].'(个人)';
			}else{
				$member_array[$i]['userid'] = $member_array[$i]['userid'].'(企业)';
			}
		}
		
		
	//封装所有的会员资料到一个数组#all_member，主表加附表
 	 $all_member = $member_array;
  /* foreach ($member_array as $member){
	  	$all_member[] = get_memberinfo_all($member['userid']);
	  
	  }*/
	 /*echo "<pre>";
      			  var_dump($member_array);
     			   echo'</pre>';
      			  exit;*/
	  
	  //设置不要导出的字段，并且过滤掉
	  $no_exeport_fields = array('province','city','districe','enterprise');
      //$no_exeport_fields = array();			  
	  
	  foreach ($all_member as $k=>$member){
		  foreach($member as $field=>$value){
			if(in_array($field,$no_exeport_fields)){
				//删除过滤掉得字段
				
				unset($all_member[$k][$field]);
				}  
		  	}
		  
		  }
	
		 //字段转向翻译
		 $field_en_array=array();
		 $field_en_array['userid']='用户编号';
		 $field_en_array['username']='用户账号';
		 $field_en_array['nickname']='昵称';
		 $field_en_array['name']='真实姓名';
		 $field_en_array['idcard']='身份证';
		 $field_en_array['product_types']='产品分类';
		 $field_en_array['regdate']='注册时间';
		 $field_en_array['address']='地区';
		 $field_en_array['certification']='是否认证';
		 $field_en_array['disable']='是否禁用';
		 $field_en_array['enterprise']='个人/企业';
		 $field_en_array['company']='单位公司';
		 $field_en_array['credit_code']='纳税识别号';
		 $field_en_array['oper_name']='法人';
		 $field_en_array['artners_name']='股东';
		 
		 
		 	/*$model_field_array = $this->db_model_field->listinfo();
			
	
				echo "<pre>";
      			  var_dump($model_field_array);
     			   echo'</pre>';
      			  exit;*/


		 
		export_to_excel($all_member,'用户数据导出', $field_en_array);


		showmessage('导出成功',HTTP_REFERER);
	}



	/**
	 * [certification_ok 认证审核]
	 * @return [type] [description]
	 */
	public function certification_ok(){
		$_userid = $_GET['userid'];
		$disable = $_GET['disable'];
		$types = $_GET['types'];

		if ($disable==1) {
			$this->member_db->update(['certification'=>$disable,'memberinfo_types'=>$types],['userid'=>$_userid]); //读取数据库里的字段
		}else{
			$this->member_db->update(['certification'=>$disable,'enterprise'=>1],['userid'=>$_userid]); //读取数据库里的字段
			$this->member_db->update(['company'=>'','oper_name'=>'','artners_name'=>'','credit_code'=>'','idcard'=>'','name'=>''],['userid'=>$_userid]); //企业
			$this->member_db->update(['idcard'=>'','name'=>''],['userid'=>$_userid]); //个人
		}


		showmessage('操作成功',HTTP_REFERER);
	}



	/**
	* 用户管理_查看
	*/
	public function member_team(){
		$big_menu = array('javascript:window.history.back(-1);', '返回');

		$userid = $_GET['userid'];
		$member = $this->member_db->get_one('userid='.$_GET['userid']);
		$province_arr = $this->linkage_db->select('parentid=0 and linkageid!=1');

		pc_base::load_app_func('global','distribution');
		$did=getchilrenid($userid);
		$did=array_unique($did);
		$count=count($did);
		$did=implode(',', $did);	
		if(empty($did)){
			$did=0;
		}	
		$where= 'userid in ('.$did.')';

		include $this->admin_tpl('member_team');
	}




}
?>