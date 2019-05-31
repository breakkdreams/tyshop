<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_func('global');
pc_base::load_sys_class('form', '', 0);
pc_base::load_sys_class('format', '', 0);
require_once 'classes/phpqrcode/QRcode.class.php';
class index{
	function __construct() {
		$this->get_db = pc_base::load_model('get_model');
		
	}

	
}
?>