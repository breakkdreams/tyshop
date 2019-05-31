<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_func('global');
pc_base::load_sys_class('form', '', 0);
pc_base::load_sys_class('format', '', 0);
class api {
	function __construct() {
		$this->db = pc_base::load_model('zylinkage_model');
	}
	

	/**
	 * 显示菜单
	 */
	public function listall(){
		$id = $_GET['id'] ? $_GET['id'] : 0;
		$where = 'parentid='.$id.' AND linkageid!=1 AND isshow=1';
		$info=$this->db->select($where,'`linkageid`,`name`');

		$result = [
			'status'=>'success',
			'code'=>200,
			'message'=>'操作成功',
			'data'=>$info
		];
		exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
	}

}
?>