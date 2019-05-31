<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin', 'admin', 0);
pc_base::load_sys_class('format', '', 0);
pc_base::load_sys_class('form', '', 0);
pc_base::load_app_func('global');

class zyaddr extends admin {
	function __construct() {
		parent::__construct();
		$this->get_db = pc_base::load_model('get_model');
		$this->zyconfig_db = pc_base::load_model('zyconfig_model');
		$this->zyaddr_db = pc_base::load_model('zyaddr_model');
		$this->module_db = pc_base::load_model('module_model');
	}

	public function init(){
		$where = '';
		if(!empty($_GET['type'])){
			if($_GET['type']==1){
				$where = "`name` like '%{$_GET['q']}%'";
			}elseif($_GET['type']==2){
				$where = "`phone` like '%{$_GET['q']}%'";
			}elseif($_GET['type']==3){
				$where = "`province` like '%{$_GET['q']}%'";
			}elseif($_GET['type']==4){
				$where = "`city` like '%{$_GET['q']}%'";
			}elseif($_GET['type']==5){
				$where = "`district` like '%{$_GET['q']}%'";
			}elseif($_GET['type']=6){
				$where = "`address` like '%{$_GET['q']}%'";
			}
		}
		
		$page = empty($_GET['page'])?1:intval($_GET['page']);
		$info = $this->zyaddr_db->listinfo($where,'id desc',$page,10);
		$pages = $this->zyaddr_db->pages;
		include $this->admin_tpl('index');
	}

	public function edit(){
		if($_POST['dosubmit']){
			$map = array('id'=>$_POST['id']);
			$data = array(
				"userid" => $_POST['userid'],
				"name" => $_POST['name'],
				"province" => $_POST['province'],
				"city" => $_POST['city'],
				"district" => $_POST['district'],
				"address" => $_POST['address'],
				"default" => $_POST['default']
			);

			if($this->zyaddr_db->update($data,$map)){
				showmessage("操作成功","?m=zyaddr&c=zyaddr&a=edits");
			}else{
				showmessage("操作失败","?m=zyaddr&c=zyaddr&a=edits");
			}
		}else{
			$info = $this->zyaddr_db->get_one(array('id'=>$_GET['id']));
			include $this->admin_tpl('edit');
		}
	}

	public function edits(){
		showmessage("操作",'','',"edit");
	}

	public function del(){
		if(intval($_GET['id']))
		{
			$result=$this->zyaddr_db->delete(array('id'=>$_GET['id']));
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
				$result=$this->zyaddr_db->delete(array('id'=>$id));
			}
			showmessage(L('operation_success'),HTTP_REFERER);
		}

		//都没有选择删除什么
		if(empty($_POST['id'])) {
			showmessage('请选择要删除的记录',HTTP_REFERER);
		}
	}

	/*
	 * 配置模块
	 * */
	public function zyconfig()
	{
		$big_menu = array
		(
			'javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=zyaddr&c=zyaddr&a=configadd\', title:\'添加配置\', width:\'700\', height:\'200\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function()	{window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加配置'
		);
		$order = 'id DESC';
		$where = array("item_name"=>'zyaddr');
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
			$num = $this->zyconfig_db->count(array('item_name'=>"zyaddr"))+1;
			$car=array
			(
				'config_name'=>$_POST['config_name'],
				'model_name'=>$_POST['model_name'],
				'item_name'=>"zyaddr",
				"key"=>"zyaddr".$num,
				'url'=>$_POST['url'],
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
}









