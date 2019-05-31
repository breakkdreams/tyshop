<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);
class chatlog extends admin {
	function __construct() {
		parent::__construct();
		$this->chatlog = pc_base::load_model('chatlog_model');
        $this->member_db = pc_base::load_model('member_model');
	}

	public function chatloglist() {
        $createtime = $_POST['createtime'];
	    $shopid = $_SESSION['userid'];;//客服id
        $fname = $_POST['fromuserid'];
        $tname = $_POST['touserid'];

        if(!empty($fname)){
            $member = $this->member_db->get_one(array('username'=>$fname));
            $fid = $member['userid'];
        }
        if(!empty($tname)){
            $member = $this->member_db->get_one(array('username'=>$tname));
            $tid = $member['userid'];
        }

        $where = '1 ';
        if(!empty($fid) && empty($tid)){
            $where .= 'and fromuserid = '.$fid;
        }elseif(empty($fid) && !empty($tid)){
            $where .= 'and touserid = '.$tid;
        }elseif (!empty($fid) && !empty($tid)){
            $where .= 'and ((touserid = '.$tid.' and fromuserid = '.$fid.') or (touserid = '.$fid.' and fromuserid = '.$tid.'))';
        }
        $start = strtotime($createtime.' 00:00:00');
        $end = strtotime($createtime.' 23:59:59');
        if(!empty($createtime)){
            $where .= ' and create_date > '.$start.' and  create_date < '.$end.' ';
        }

        $page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
        $infos = $this->chatlog ->listinfo($where,$order = 'create_date desc',$page, $pages = '10');
        $pages = $this->chatlog->pages;

        for ($i=0;$i<sizeof($infos);$i++){
            $memberinfo = $this->member_db->get_one(array('userid'=>$infos[$i]['fromuserid']));
            $infos[$i]['fromusername'] = $memberinfo['username'];
            $memberinfo1 = $this->member_db->get_one(array('userid'=>$infos[$i]['touserid']));
            $infos[$i]['tousername'] = $memberinfo1['username'];
        }

		include $this->admin_tpl('chatlog_list');
	}


	
}
?>