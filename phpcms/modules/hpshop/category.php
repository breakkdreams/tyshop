<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin', 'admin', 0);
pc_base::load_sys_class('format', '', 0);
pc_base::load_sys_class('form', '', 0);
pc_base::load_app_func('global');

class category extends admin {
	function __construct() {
		parent::__construct();
        //分类栏目表
        $this->goodscat_db = pc_base::load_model('goodscat_model');
	}

    /**
     * 分类列表
     */
	public function categorylist() {
        $where=' 1 ';
        $infos = $this->goodscat_db->select($where,'*','',$order = 'sort ASC,id ASC');
        $infos = catetree($infos);
        //添加栏目
        $big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=hpshop&c=category&a=catadd\', title:\'添加商品分类\', width:\'800\', height:\'500\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加商品分类');
		include $this->admin_tpl('categorylist');
	}

    /**
     * 添加分类栏目
     */
    public function catadd(){

        if($_POST['dosubmit']){
            $data=[
                'pid'=>$_POST['pid'],
                'cate_name'=>$_POST['cname'],
                'cate_img'=>$_POST['thumb'],
                'isshow'=>$_POST['status'],
                'description'=>$_POST['desc'],
            ];

            $results=$this->goodscat_db->insert($data);

            showmessage(L('添加栏目成功'), '?m=hpshop&c=category&a=catadds');

        }else{
            $where=' 1 ';
            $info = $this->goodscat_db->select($where,'*','',$order = 'id ASC, sort ASC');
            $info = catetree($info);
            $upload_allowext = 'jpg|jpeg|gif|png|bmp';
            $isselectimage = '1';
            $images_width = '';
            $images_height = '';
            $watermark = '0';
            $authkey = upload_key("1,$upload_allowext,$isselectimage,$images_width,$images_height,$watermark");
            include $this->admin_tpl('categoryadd');
        }
    }

    /**
     * 添加中间跳转
     */
    public function catadds(){
        showmessage(L('operation_success'), '', '', 'add');
    }

    /**
     * 商品分类_删除
     */
    public function catdel(){
        //删除单个
        $id=intval($_GET['id']);
        pdel($id);
        showmessage('删除成功',HTTP_REFERER);
    }

    /**
     * 编辑商品分类
     */
    public function catedit(){

        if($_POST['dosubmit']){
            $id=$_POST['id'];
            $data=[
                'pid'=>$_POST['pid'],
                'cate_name'=>$_POST['cname'],
                'cate_img'=>$_POST['thumb'],
                'isshow'=>$_POST['status'],
                'description'=>$_POST['desc'],
            ];

            $results=$this->goodscat_db->update($data,array('id'=>$id));
            showmessage(L('修改商品分类信息成功'), '?m=hpshop&c=category&a=catedits');

        }else{
            $where = '1';
            $info = $this->goodscat_db->get_one(array('id'=>$_GET['id']));
            $infos = $this->goodscat_db->select($where,'*','',$order = 'id ASC, sort ASC');
            $infos = catetree($infos);
            $upload_allowext = 'jpg|jpeg|gif|png|bmp';
            $isselectimage = '1';
            $images_width = '';
            $images_height = '';
            $watermark = '0';
            $authkey = upload_key("1,$upload_allowext,$isselectimage,$images_width,$images_height,$watermark");
            include $this->admin_tpl('categoryedit');
        }
    }

    /**
     * 添加中间跳转
     */
    public function catedits(){
        showmessage(L('operation_success'), '', '', 'edit');
    }

    /**
     * 分类栏目排序
     */
    public function catlistorder() {
        if(isset($_POST['listorders'])) {
            foreach($_POST['listorders'] as $id => $listorder) {
                $this->goodscat_db->update(array('sort'=>$listorder),array('id'=>$id));
            }
            showmessage(L('operation_success'),'?m=hpshop&c=category&a=categorylist');
        } else {
            showmessage(L('operation_failure'),'?m=hpshop&c=category&a=categorylist');
        }
    }
	
}
?>