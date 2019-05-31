<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin', 'admin', 0);
pc_base::load_sys_class('format', '', 0);
pc_base::load_sys_class('form', '', 0);
pc_base::load_app_func('global');
pc_base::load_app_func('KdApiSearch');
class zyexpress extends admin {
	function __construct() {
		parent::__construct();
		$this->express_db = pc_base::load_model('zyexpress_model');
	}

	/**
	 * 设置快递接口文件
	 */
	public function kd_manage(){
		$EXinfo = pc_base::load_config('zysystem');
		if(isset($_POST['dosubmit'])){
			$info['EBusinessID']=$_POST['EBusinessID'];
			$info['AppKey']=$_POST['AppKey'];
			$info['ReqURL']=$_POST['ReqURL'];
			$this->set_config($info,'zysystem');	 //保存进config文件
			showmessage(L('operation_success'), HTTP_REFERER);
		}else{
			include $this->admin_tpl('kd_manage');
		}
	}
	/**
	 * 设置快递公司
	 */
	public function kd_code(){
		
		if(isset($_POST['dosubmit'])){
			
		}else{
			
			$page = empty($_GET['page'])?1:intval($_GET['page']);
			$info = $this->express_db->listinfo('','id asc',$page);
			$pages = $this->express_db->pages;
			$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=zyexpress&c=zyexpress&a=add\', title: \'添加快递公司\', width:\'500\', height:\'300\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加快递公司');
			include $this->admin_tpl('kd_code');
		}
	}
	
	/**
	 * 查询快递单号
	 */
	 
	public function kd_search(){
		$EXinfo = pc_base::load_config('zysystem');
		if(isset($_POST['dosubmit'])){
			$orderInfo['ShipperCode']=$_POST['ShipperCode'];
			$orderInfo['LogisticCode']=$_POST['LogisticCode'];
			$orderInfo['OrderCode']=empty($_POST['OrderCode'])?'':intval($_POST['OrderCode']);
			//print_r($orderInfo);
			//print_r($EXinfo);
			$data = getOrderTracesByJson($EXinfo, $orderInfo['ShipperCode'],$orderInfo["LogisticCode"],$orderInfo['OrderCode']);
			$msg = json_decode($data,ture);
			
			$code=$this->express_db->get_one(array('code'=>$msg['ShipperCode']));
			$msg['company']=$code['company'];
			
			/* 
			物 流 状 态 ：
			0-无 轨 迹
			1-已揽收
			2-在途中
			3-签收 */
			if($msg['State']=='0'){
				$msg['state']='无轨迹';
			}else if($msg['State']=='1'){
				$msg['state']='已揽收';
			}else if($msg['State']=='2'){
				$msg['state']='在途中';
			}else if($msg['State']=='3'){
				$msg['state']='签收';
			}
			
			/* echo "<pre>";
			var_dump($msg);
			echo "</pre>"; */
			
			
		}
		$info = $this->express_db->select('');
		include $this->admin_tpl('kd_search');
		
	}
	
	/**
	 * 设置config文件
	 * @param $config 配属信息
	 * @param $filename 要配置的文件名称
	 */
	function set_config($config, $filename="zysystem") {
		$configfile = CACHE_PATH.'configs'.DIRECTORY_SEPARATOR.$filename.'.php';
		if(!is_writable($configfile)) showmessage('Please chmod '.$configfile.' to 0777 !');
		$pattern = $replacement = array();
		foreach($config as $k=>$v) {
			if(in_array($k,array('EBusinessID','AppKey','ReqURL'))) {
				$v = trim($v);
				$configs[$k] = $v;
				$pattern[$k] = "/'".$k."'\s*=>\s*([']?)[^']*([']?)(\s*),/is";
	        	$replacement[$k] = "'".$k."' => \${1}".$v."\${2}\${3},";					
			}
		}
		$str = file_get_contents($configfile);
		$str = preg_replace($pattern, $replacement, $str);
		return pc_base::load_config('system','lock_ex') ? file_put_contents($configfile, $str, LOCK_EX) : file_put_contents($configfile, $str);		
	}
	

	/*
	 * 添加二维码信息
	 * */
	 public function add()
	{
		if(isset($_POST['dosubmit'])){
			$data['company'] = $_POST['company'];
            $data['code'] = $_POST['code'];

			if($this->express_db->insert($data)){
				showmessage('操作成功','index.php?m=zyexpress&c=zyexpress&a=adds');
			}else{
				showmessage('操作失败','index.php?m=zyexpress&c=zyexpress&a=adds');
			}
		}else{
			include $this->admin_tpl('kd_code_add');
		}
	}
    public function adds(){
        showmessage('已操作','','','add');
    }

	/*
	 * 编辑二维码信息
	 * */
	public function edit()
	{
		if(isset($_POST['dosubmit'])){
            $data['company'] = $_POST['company'];
            $data['code'] = $_POST['code'];
            
			if($this->express_db->update($data,array('id'=>$_POST['id']))){
				showmessage('操作成功','index.php?m=zyexpress&c=zyexpress&a=edits');
			}else{
				showmessage('操作失败','index.php?m=zyexpress&c=zyexpress&a=edits');
			}
		}else{
           
			$info = $this->express_db->get_one(array('id'=>$_GET['id']));
			include $this->admin_tpl('kd_code_edit');
		}
	}

	public function edits(){
		showmessage('已操作','','','edit');
	}
	

 	/**
	  *	删除
	  */ 
	public function del(){
		//删除单个
		$id=intval($_GET['id']);
		if($id){
			$result=$this->express_db->delete(array('id'=>$id));
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
				$result=$this->express_db->delete(array('id'=>$pid));
			}	
			showmessage(L('operation_success'),HTTP_REFERER);
		}
		//都没有选择删除什么
		if( empty($_POST['id'])){
			showmessage('请选择要删除的记录',HTTP_REFERER);
		}		
	}




}