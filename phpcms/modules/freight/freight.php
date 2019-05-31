<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);
class freight extends admin {
	function __construct() {
		parent::__construct();
		$this->freight = pc_base::load_model('freight_model');
		$this->shipping_way = pc_base::load_model('shipping_way_model');
		$this->region = pc_base::load_model('region_model');
		$this->large_area = pc_base::load_model('large_area_model');
	}

	public function freightlist() {
		$where = ' status = 1 ';
 		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$infos = $this->freight ->listinfo($where,$order = '',$page, $pages = '10');
		$pages = $this->freight->pages;

		for ($i=0; $i < sizeof($infos) ; $i++) { 
			$shippingway = $this->shipping_way->get_one(array('template_id'=>$infos[$i]['template_id']));
			$infos[$i]['first_num'] = $shippingway['first_num'];
			$infos[$i]['continue_num'] = $shippingway['continue_num'];
			$infos[$i]['first_fee'] = $shippingway['first_fee'];
			$infos[$i]['continue_fee'] = $shippingway['continue_fee'];
			//省市区
            $province = $this->region->get_one(array('region_id'=>$infos[$i]['province']));
            $infos[$i]['province'] = $province['region_name'];
            $city = $this->region->get_one(array('region_id'=>$infos[$i]['city']));
            $infos[$i]['city'] = $city['region_name'];
            $district = $this->region->get_one(array('region_id'=>$infos[$i]['district']));
            $infos[$i]['district'] = $district['region_name'];
		}
		$str = $_REQUEST['pc_hash'];

		$big_menu = array("javascript:window.location.href='?m=freight&c=freight&a=addpage&pc_hash=$str'", "添加");
		include $this->admin_tpl('freight_list');
	}

	//添加页面
	public function addpage() {
		//城市
		$country = $this->region->get_one(array('parent_id'=>0));
		include $this->admin_tpl('freight_add');
   }

    //详情页面
    public function infopage() {
	    $template_id = $_GET['template_id'];
        if($template_id){
            $shipping_way=$this->shipping_way->select(array('template_id'=>$template_id));
            $template = $this->freight->get_one(array('template_id'=>$template_id));
        }
        include $this->admin_tpl('freight_edit');
    }

   //获取省市区
	public function regionlist() {
		$parent = $_GET['parent'];
		//三级联动
		$regionlist = $this->region->select(array('parent_id'=>$parent));
		exit(json_encode($regionlist,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
   }
    //获取区域
	public function getarea() {
		$area = $this->large_area->select('');
		exit(json_encode($area,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
   }

	//添加
 	public function add() {
        if(trim($_POST['template_name'])==''){
            showmessage(L('请填写模板名称'),'');
            die;
        }
        if(trim($_POST['country'])==0||trim($_POST['province'])==0||trim($_POST['city'])==0||trim($_POST['area'])==0){
            showmessage(L('请选择商品所在地区'),'');
            die;
        }

        foreach ($_POST['default'] as &$default){
            $default_first_num=trim($default['first_num']);
            $default_first_fee=trim($default['first_fee']);
            $default_continue_num=trim($default['continue_num']);
            $default_continue_fee=trim($default['continue_fee']);
            if($default_first_num==0||$default_first_num==''||!is_numeric($default_first_num)){
                showmessage(L('商品件数必须为大于0的数字'),'');
                die;
            }
            if($default_first_fee==0||$default_first_fee==''||!is_numeric($default_first_fee)){
                showmessage(L('商品首费价格必须为大于0的数字'),'');
                die;
            }
            if($default_continue_num==0||$default_continue_num==''||!is_numeric($default_continue_num)){
                showmessage(L('商品续件数必须为大于0的数字'),'');
                die;
            }
            if($default_continue_fee==0||$default_continue_fee==''||!is_numeric($default_continue_fee)){
                showmessage(L('商品续费价格必须为大于0的数字'),'');
                die;
            }
            $default[area_name]="全国";
            $default[area_id]=1;
            $default[is_default]=1;
            $default[create_date]=time();
            $default[create_by]=$_SESSION['userid'];
        }

        foreach ($_POST['other'] as &$others){
            if($others[area_id]==0||$others[area_id]==''){
                showmessage(L('请选择商品配送区域'),'');
                die;
            }
            if($others[first_num]==0||$others[first_num]==''||!is_numeric($others[first_num])){
                showmessage(L('商品首费价格必须为大于0的数字'),'');
                die;
            }
            if($others[first_fee]==0||$others[first_fee]==''||!is_numeric($others[first_fee])){
                showmessage(L('商品首费价格必须为大于0的数字'),'');
                die;
            }
            if($others[continue_num]==0||$others[continue_num]==''||!is_numeric($others[continue_num])){
                showmessage(L('商品续件数必须为大于0的数字'),'');
                die;
            }
            if($others[continue_fee]==0||$others[continue_fee]==''||!is_numeric($others[continue_fee])){
                showmessage(L('商品续费价格必须为大于0的数字'),'');
                die;
            }
            $others[is_default]=0;
            $others[create_date]=time();
            $others[create_by]=$_SESSION['userid'];
        }

        $data=[
            'template_name'=>$_POST['template_name'],//模板名称
            'country'=>$_POST['country'],//国家
            'province'=>$_POST['province'],//省
            'city'=>$_POST['city'],//市
            'district'=>$_POST['area'],//区
            'is_free'=>$_POST['is_free'],////是否包邮(0.不包邮 1.包邮)
            'price_way'=>$_POST['price_way'],//计价方式(1.按件 2.按重量 3.按体积)
            'shipping_way'=>'0',//快递
            'shop_id'=>$_SESSION['userid'],//商铺id
            'create_date'=>time(),//时间
            'create_by'=>$_SESSION['userid'],
        ];
        $way_id = $this->freight->insert($data,true);
        if($way_id){
            foreach ($_POST['default'] as &$default){
                $default[template_id]=$way_id;
                $default_res=$this->shipping_way->insert($default);
            }
            foreach ($_POST['other'] as &$other){
                $other[template_id]=$way_id;
                $other_res=$this->shipping_way->insert($other);
            }
            if(!$default_res&&!$other_res){
                showmessage(L('运送方式添加失败'),'');
            }else{
                showmessage(L('操作成功'),'?m=freight&c=freight&a=freightlist');
            }
        }else{
            showmessage(L('添加失败'),'');
        }
	}

	/**
	 * 删除
	 */
	public function delete() {
  		if((!isset($_GET['template_id']) || empty($_GET['template_id'])) && (!isset($_POST['template_id']) || empty($_POST['template_id']))) {
			showmessage(L('illegal_parameters'), HTTP_REFERER);
		} else {
            $template_id = intval($_GET['template_id']);
            if($template_id < 1) return false;
            //删除友情链接
            $result = $this->freight->update(array('status'=>0),array('template_id'=>$template_id));
            if($result){
                showmessage(L('operation_success'),'?m=freight&c=freight&a=freightlist');
            }else {
                showmessage(L("operation_failure"),'?m=freight&c=freight&a=freightlist');
            }
			showmessage(L('operation_success'), HTTP_REFERER);
		}
	}
	
}
?>