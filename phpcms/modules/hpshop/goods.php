<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin', 'admin', 0);
pc_base::load_sys_class('format', '', 0);
pc_base::load_sys_class('form', '', 0);
pc_base::load_app_func('global');

class goods extends admin {
	function __construct() {
		parent::__construct();
        //分类栏目表
        $this->goodscat_db = pc_base::load_model('goodscat_model');
        //商品表
        $this->goods_db = pc_base::load_model('goods_model');
        //商品类型表
        $this->goodstype_db = pc_base::load_model('goodstype_model');
        //商品类型属性表
        $this->goodsattr_db = pc_base::load_model('goodsattr_model');
        //商品规格组合表
        $this->goods_specs_db = pc_base::load_model('goods_specs_model');
        //商品属性表
        $this->goods_attr_db = pc_base::load_model('goods_attr_model');
        $this->member_db = pc_base::load_model('member_model');
        $this->get_db = pc_base::load_model('get_model');
        //运费模板
        $this->freight = pc_base::load_model('freight_model');
        //促销商品
        $this->goods_promotion = pc_base::load_model('goods_promotion_model');
	}

    /**
     * 商品列表
     */
    public function goodslist(){

        $where = ' isok = 1 ';

        if($_GET['type']){
            if($_GET['q']){
                if($_GET['type'] == 1){
                    $where .= " and id =".$_GET['q'];
                }
                elseif($_GET['type'] == 2){
                    $where .= " and goods_name like '%".$_GET['q']."%' ";
                }
            }
        }

        if($_GET['status']){
            $where .= " and on_sale = ".$_GET['status'];
        }

        if($_GET['start_addtime']){
            $start_addtime=$_GET['start_addtime'];
            $where .= " and addtime >= '".strtotime($_GET['start_addtime'])."'";
        }
        if($_GET['end_addtime']){
            $end_addtime=$_GET['end_addtime'];
            $where .= " and addtime <= '".strtotime($_GET['end_addtime'])."'";
        }
        $page = $_GET['page'] ? $_GET['page'] : '1';
        $infos = $this->goods_db->listinfo($where, $order = 'addtime DESC,id DESC', $page, $pagesize = 20);
        $pages = $this->goods_db->pages;

        $cinfo = $this->goodscat_db->select('1','id,cate_name,pid','',$order = 'sort ASC,id ASC');
        $cinfo = getcatinfo($cinfo,1);
        $tinfo = $this->goodstype_db->select('1','id,type_name','',$order = 'id ASC');
        $tinfo = getcatinfo($tinfo,3);
        //添加商品$_GET['pc_hash']
        $big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=hpshop&c=goods&a=goodsadd\', title:\'添加商品\', width:\'1200\', height:\'700\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加商品');
        include $this->admin_tpl('goodslist');
    }


    /**
     * 添加商品
     */
    public function goodsadd(){

        if($_POST['dosubmit']){
            if($_POST['goodsimg_url']){
                $goodsimg=[];
                $count=count($_POST['goodsimg_url']);
                for ($i=0; $i <$count ; $i++) {
                    $goodsimg[]=[
                        'url'=>$_POST['goodsimg_url'][$i],
                        'alt'=>$_POST['goodsimg_alt'][$i],
                    ];
                }
            }

            $goodsimg = array2string($goodsimg);
            $data=[
                'shopid' => $_POST['shopid'],
                'goods_name'=>$_POST['gname'],
                'summary'=>$_POST['summary'],
                'thumb'=>$_POST['thumb'],
                'album'=>$goodsimg,
                'content'=>$_POST['content'],
                'on_sale'=>$_POST['status'],
                'market_price'=>$_POST['mprice'],
                'shop_price'=>$_POST['sprice'],
                'score_price'=>$_POST['scoreprice'],
                'stock'=>$_POST['stock'],
                'catid' => $_POST['cid'],
                'brand_id' => $_POST['bid'],
                'type_id' => $_POST['tid'],
                'volume' => $_POST['volume'],
                'weight' => $_POST['weight'],
                'template_id' => $_POST['template_id'],
                'addtime'=>time(),
            ];

            $results=$this->goods_db->insert($data,true);


            if(isset($_POST['goodsspec'])){
                $stock = 0;
                $len = count($_POST['goodsspec']);
                $sql= "insert into zy_goods_specs ( `shopid`, `goodsid`, `specid`, `specids`, `makerprice`, `specprice`, `specstock`, `status`) values";
                for($i=0;$i<$len;$i++){
                    $sql.="(1, ".$results.", '".$_POST['goodsspec'][$i]['key']."', '".$_POST['goodsspec'][$i]['val']."', '', '".$_POST['goodsspec'][$i]['bprice']."', '".$_POST['goodsspec'][$i]['stock']."', '".$_POST['goodsspec'][$i]['open']."'),";
                    $stock += $_POST['goodsspec'][$i]['stock'];
                };

                $this->goods_db->update(['stock'=>$stock,'isspec'=>1],['id'=>$results]);
                $sql = substr($sql,0,strlen($sql)-1);
                $this->goods_specs_db->query($sql);
            }

            if(isset($_POST['goods_attr'])){

                $sqls= "insert into phpcms_goods_attr ( `shopid`, `goodsid`, `attrid`, `val`) values";
                foreach ($_POST['goods_attr'] as $k => $v) {
                    $sqls.="(1, ".$results.", ".$k.", '".$v."'),";
                }
                $sqls = substr($sqls,0,strlen($sqls)-1);
                $this->goods_attr_db->query($sqls);
            }

            showmessage(L('添加商品成功'), '?m=hpshop&c=goods&a=goodsadds');

        }else{
            $cinfo = $this->goodscat_db->select('1','id,cate_name,pid','',$order = 'sort ASC,id ASC');
            $cinfo = catetree($cinfo);
            $tinfo = $this->goodstype_db->select('1','id,type_name','',$order = 'id ASC');

            $minfo = $this->member_db->select(' groupid = 18 ');

            $fnfos = $this->freight ->select();


            $upload_allowext = 'jpg|jpeg|gif|png|bmp';
            $isselectimage = '1';
            $images_width = '';
            $images_height = '';
            $watermark = '0';
            $authkey = upload_key("1,$upload_allowext,$isselectimage,$images_width,$images_height,$watermark");

            $upload_number = '10';
            $upload_allowext = 'gif|jpg|jpeg|png|bmp';
            $isselectimage = '0';
            $authkeys = upload_key("$upload_number,$upload_allowext,$isselectimage");

            $allowuploadnum = '10';
            $alowuploadexts = '';
            $allowbrowser = 1;
            $authkeyss = upload_key("$allowuploadnum,$alowuploadexts,$allowbrowser");

            include $this->admin_tpl('goodsadd');
        }
    }

    /**
     * 添加中间跳转
     */
    public function goodsadds(){
        showmessage(L('operation_success'), '', '', 'add');
    }

    /**
     * 商品_删除
     */
    public function goodsdel(){
        //删除单个
        $id=intval($_GET['id']);
        if($id){
            $result=$this->goods_db->delete(array('id'=>$id));
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
                $result=$this->goods_db->delete(array('id'=>$pid));
            }
            showmessage(L('operation_success'),HTTP_REFERER);
        }

        //都没有选择删除什么
        if( empty($_POST['id'])){
            showmessage('请选择要删除的记录',HTTP_REFERER);
        }
    }

    /**
     * 编辑商品
     */
    public function goodsedit(){

        if($_POST['dosubmit']){
            $id=$_POST['id'];
            if($_POST['goodsimg_url']){
                $goodsimg=[];
                $count=count($_POST['goodsimg_url']);
                for ($i=0; $i <$count ; $i++) {
                    $goodsimg[]=[
                        'url'=>$_POST['goodsimg_url'][$i],
                        'alt'=>$_POST['goodsimg_alt'][$i],
                    ];
                }
            }

            $goodsimg = array2string($goodsimg);
            $data=[
                'shopid' => $_POST['shopid'],
                'goods_name'=>$_POST['gname'],
                'summary'=>$_POST['summary'],
                'thumb'=>$_POST['thumb'],
                'album'=>$goodsimg,
                'content'=>$_POST['content'],
                'on_sale'=>$_POST['status'],
                'market_price'=>$_POST['mprice'],
                'shop_price'=>$_POST['sprice'],
                'score_price'=>$_POST['scoreprice'],
                'catid' => $_POST['cid'],
                'brand_id' => $_POST['bid'],
                'type_id' => $_POST['tid'],
                'stock'=>$_POST['stock'],
                'volume' => $_POST['volume'],
                'weight' => $_POST['weight'],
                'template_id' => $_POST['template_id'],
            ];

            $results=$this->goods_db->update($data,array('id'=>$id));

            if(isset($_POST['goodsspecs'])){
                $stock = 0;
                $sql= "replace into phpcms_goods_specs (`id`, `shopid`, `goodsid`, `specid`, `specids`, `makerprice`, `specprice`, `specstock`, `status`) values";

                foreach ($_POST['goodsspecs'] as $k => $v) {
                    $sql.="(".$k.", 1, ".$id.", '".$v['key']."', '".$v['val']."', '', '".$v['bprice']."', '".$v['stock']."', '".$v['open']."'),";
                    $stock += $v['stock'];
                }
                $this->goods_db->update(['stock'=>$stock],['id'=>$id]);
                $sql = substr($sql,0,strlen($sql)-1);
                $this->goods_specs_db->query($sql);
            }

            if(isset($_POST['goods_attrs'])){

                $sqls= "replace into phpcms_goods_attr (`id`, `shopid`, `goodsid`, `attrid`, `val`) values";
                foreach ($_POST['goods_attrs'] as $k => $v) {
                    $sqls.="(".$k.", 1, ".$id.", ".$v['aid'].", '".$v['val']."'),";
                }
                $sqls = substr($sqls,0,strlen($sqls)-1);
                $this->goods_attr_db->query($sqls);
            }

            if(isset($_POST['goodsspec'])){
                $this->goods_specs_db->delete(['goodsid'=>$id]);
                $stock = 0;
                $len = count($_POST['goodsspec']);
                $sql= "insert into phpcms_goods_specs ( `shopid`, `goodsid`, `specid`, `specids`, `makerprice`, `specprice`, `specstock`, `status`) values";
                for($i=0;$i<$len;$i++){
                    $sql.="(1, ".$id.", '".$_POST['goodsspec'][$i]['key']."', '".$_POST['goodsspec'][$i]['val']."', '', '".$_POST['goodsspec'][$i]['bprice']."', '".$_POST['goodsspec'][$i]['stock']."', '".$_POST['goodsspec'][$i]['open']."'),";
                    $stock += $_POST['goodsspec'][$i]['stock'];
                };
                $this->goods_db->update(['stock'=>$stock,'isspec'=>1],['id'=>$id]);
                $sql = substr($sql,0,strlen($sql)-1);
                $this->goods_specs_db->query($sql);
            }

            if( !isset($_POST['goodsspec']) && !isset($_POST['goodsspecs']) ){
                $this->goods_db->update(['isspec'=>0],['id'=>$id]);
            }

            if(isset($_POST['goods_attr'])){
                $this->goods_attr_db->delete(['goodsid'=>$id]);
                $sqls= "insert into phpcms_goods_attr ( `shopid`, `goodsid`, `attrid`, `val`) values";
                foreach ($_POST['goods_attr'] as $k => $v) {
                    $sqls.="(1, ".$id.", ".$k.", '".$v."'),";
                }
                $sqls = substr($sqls,0,strlen($sqls)-1);
                $this->goods_attr_db->query($sqls);

            }

            showmessage(L('修改商品信息成功'), '?m=hpshop&c=goods&a=goodsedits');

        }else{
            $info = $this->goods_db->get_one(array('id'=>$_GET['id']));

            $gattr = $this->goodsattr_db->select(array('goodstypeid'=>$info['type_id'],'attrtype'=>1));

            $where= 'a.attrid = b.id and a.goodsid = '.$_GET['id'];
            $sql = 'SELECT a.id, a.val, a.attrid, b.attrname FROM phpcms_goods_attr a,phpcms_goodsattr b WHERE '.$where.' ORDER BY a.id ASC';
            $page = intval($_GET['page']);
            $gattrs = $this->get_db->multi_listinfo($sql,$page,999999);
            if($info['isspec'] == 1){
                $gspec = $this->goods_specs_db->select(array('goodsid'=>$_GET['id']));
            }

            $alinfo = string2array($info['album']);
            $cinfo = $this->goodscat_db->select('1','id,cate_name,pid','',$order = 'sort ASC,id ASC');
            $cinfo = catetree($cinfo);
            $tinfo = $this->goodstype_db->select('1','id,type_name','',$order = 'id ASC');

            $minfo = $this->member_db->select(' groupid = 18 ');

            $fnfos = $this->freight ->select();

            $upload_allowext = 'jpg|jpeg|gif|png|bmp';
            $isselectimage = '1';
            $images_width = '';
            $images_height = '';
            $watermark = '0';
            $authkey = upload_key("1,$upload_allowext,$isselectimage,$images_width,$images_height,$watermark");

            $upload_number = '10';
            $upload_allowext = 'gif|jpg|jpeg|png|bmp';
            $isselectimage = '0';
            $authkeys = upload_key("$upload_number,$upload_allowext,$isselectimage");

            $allowuploadnum = '10';
            $alowuploadexts = '';
            $allowbrowser = 1;
            $authkeyss = upload_key("$allowuploadnum,$alowuploadexts,$allowbrowser");
            include $this->admin_tpl('goodsedit');
        }
    }

    /**
     * 添加中间跳转
     */
    public function goodsedits(){
        showmessage(L('operation_success'), '', '', 'edit');
    }


    /**
     * 商品类型
     */
    public function goodstype(){

        $where = ' 1 ';
        if($_GET['q']){
            $where .= " and type_name like '%".$_GET['q']."%' ";
        }

        $page = $_GET['page'] ? $_GET['page'] : '1';
        $infos = $this->goodstype_db->listinfo($where, $order = 'id ASC', $page, $pagesize = 20);
        $pages = $this->goodstype_db->pages;

        $big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=hpshop&c=goods&a=typeadd\', title:\'添加商品类型\', width:\'800\', height:\'500\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加商品类型');

        include $this->admin_tpl('goodstype');
    }


    /**
     * 添加商品类型
     */
    public function typeadd(){

        if($_POST['dosubmit']){
            // dump($_POST,true);
            $data = [
                'type_name'=>$_POST['tname'],
            ];

            $id = $this->goodstype_db->insert($data,true);

//            $datas = [
//                'goodstypeid' => $id,
//                'attrname' => '颜色',
//                'attrval' => '红,黄,蓝',
//                'isshow' => 0,
//                'attrtype' => 1,
//                'sort' => 0
//            ];
//            $this->goodsattr_db->insert($datas);

            showmessage(L('添加商品类型成功'), '?m=hpshop&c=goods&a=typeadds');

        }else{

            include $this->admin_tpl('typeadd');
        }
    }

    /**
     * 添加中间跳转
     */
    public function typeadds(){
        showmessage(L('operation_success'), '', '', 'add');
    }

    /**
     * 编辑商品分类
     */
    public function typeedit(){

        if($_POST['dosubmit']){
            $id=$_POST['id'];
            $data=[
                'type_name'=>$_POST['tname'],
            ];

            $results=$this->goodstype_db->update($data,array('id'=>$id));

            showmessage(L('修改商品分类信息成功'), '?m=hpshop&c=goods&a=typeedits');

        }else{
            $info = $this->goodstype_db->get_one(array('id'=>$_GET['id']));
            include $this->admin_tpl('typeedit');
        }
    }


    /**
     * 添加中间跳转
     */
    public function typeedits(){
        showmessage(L('operation_success'), '', '', 'edit');
    }



    /**
     * 商品类型_删除
     */
    public function typedel(){
        //删除单个
        $id=intval($_GET['id']);
        if($id){
            $num = $this->goods_db->count(array('type_id'=>$id));
            if($num > 0){
                showmessage(L('该属性有商品正在使用，无法删除'),HTTP_REFERER);
            }else{
                $result=$this->goodstype_db->delete(array('id'=>$id));
                if ($result) {
                    showmessage(L('operation_success'),HTTP_REFERER);
                }else {
                    showmessage(L("operation_failure"),HTTP_REFERER);
                }
            }

        }

        //批量删除；
        if(is_array($_POST['id'])){
            foreach($_POST['id'] as $pid) {
                $result=$this->goodstype_db->delete(array('id'=>$pid));
            }
            showmessage(L('operation_success'),HTTP_REFERER);
        }

        //都没有选择删除什么
        if( empty($_POST['id'])){
            showmessage('请选择要删除的记录',HTTP_REFERER);
        }
    }

    /**
     * 商品类型属性列表
     */
    public function typeattr(){
        $tinfo = $this->goodstype_db->get_one(array('id'=>$_GET['id']));
        $infos = $this->goodsattr_db->select(array('goodstypeid'=>$_GET['id']),'*','','sort ASC');

        $big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=hpshop&c=goods&a=typeattradd&id='.$_GET['id'].'\', title:\'添加新属性\', width:\'800\', height:\'500\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加新属性');

        include $this->admin_tpl('typeattr');
    }


    /**
     * 添加属性
     */
    public function typeattradd(){

        if($_POST['dosubmit']){

            if($_POST['value']){
                $value=str_replace('，', ',', $_POST['value']);
            }else{
                $value='';
            }

            $data = [
                'goodstypeid' => $_POST['id'],
                'attrname' => $_POST['aname'],
                'attrval' => $value,
                'isshow' => $_POST['show'],
                'attrtype' => $_POST['status'],
            ];
            $this->goodsattr_db->insert($data);

            showmessage(L('添加属性成功'), '?m=hpshop&c=goods&a=typeattradds');

        }else{

            include $this->admin_tpl('typeattradd');
        }
    }

    /**
     * 添加中间跳转
     */
    public function typeattradds(){
        showmessage(L('operation_success'), '', '', 'add');
    }


    /**
     * 编辑属性
     */
    public function typeattredit(){

        if($_POST['dosubmit']){
            if($_POST['value']){
                $value=str_replace('，', ',', $_POST['value']);
            }else{
                $value='';
            }

            $data = [
                'attrname' => $_POST['aname'],
                'attrval' => $value,
                'isshow' => $_POST['show'],
                'attrtype' => $_POST['status'],
            ];
            $this->goodsattr_db->update($data,array('id'=>$_POST['id']));

            showmessage(L('修改属性信息成功'), '?m=hpshop&c=goods&a=typeattredits');

        }else{
            $info = $this->goodsattr_db->get_one(array('id'=>$_GET['id']));

            include $this->admin_tpl('typeattredit');
        }
    }


    /**
     * 添加中间跳转
     */
    public function typeattredits(){
        showmessage(L('operation_success'), '', '', 'edit');
    }

    /**
     * 商品属性_删除
     */
    public function typeattrdel(){

        //删除单个
        $id=intval($_GET['id']);
        if($id){
            $result=$this->goodsattr_db->delete(array('id'=>$id));
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
                $result=$this->goodsattr_db->delete(array('id'=>$pid));
            }
            showmessage(L('operation_success'),HTTP_REFERER);
        }

        //都没有选择删除什么
        if( empty($_POST['id'])){
            showmessage('请选择要删除的记录',HTTP_REFERER);
        }
    }

    /**
     * 属性排序
     */
    public function goodsattrlistorder() {
        // dump($_POST,true);
        if(isset($_POST['listorders'])) {
            foreach($_POST['listorders'] as $id => $listorder) {
                $this->goodsattr_db->update(array('sort'=>$listorder),array('id'=>$id));
            }
            showmessage(L('operation_success'),HTTP_REFERER);
        } else {
            showmessage(L('operation_failure'),HTTP_REFERER);
        }
    }

    /**
     * 获取属性信息
     * @param
     */
    public function getattr() {
        $id=$_POST['tid'];
        $info = $this->goodsattr_db->select(array('goodstypeid'=>$id,'attrtype'=>1,'isshow'=>1),'attrval,attrname','','sort ASC');
        $infos = $this->goodsattr_db->select(array('goodstypeid'=>$id,'attrtype'=>0,'isshow'=>1),'*','','sort DESC,id DESC');
        $num = count($info);
        $ninfo = $this->newattr($info,$num);
        $data = [
            'attr' => $infos,
            'spec' => $ninfo,
            'specname' => $info
        ];
        echo json_encode($data,JSON_UNESCAPED_UNICODE);
    }
    /**
     * 获取属性搭配信息
     */
    public function newattr($arr,$num,$time=0,$data=[]) {
        if($num == 0){
            return '0';
        }
        $sarr = explode(',',$arr[$time]['attrval']);
        if( empty($data) ){
            foreach ($sarr as $k => $v) {

                $data[] = [
                    $time => $v,
                    'keys' => $k+1,
                    'vals' => $v
                ];
            }
        }else{
            $narr = [];
            foreach ($data as $ks => $vs) {

                foreach ($sarr as $k => $v) {
                    $lsarr = $data;
                    $lsarr[$ks][$time] = $v;
                    $lsarr[$ks]['keys'] .= '-'.($k+1);
                    $lsarr[$ks]['vals'] .= ','.$v;
                    $narr[] = $lsarr[$ks];
                }
            }
            $data = $narr;
        }
        $num--;
        $time++;
        if ( $num > 0 ) {
            $data = $this->newattr($arr,$num,$time,$data);
        }
        return $data;
    }


    /**
     * 促销商品列表
     */
    public function promotionlist(){
        $where = '1 ';
        $page = $_GET['page'] ? $_GET['page'] : '1';
        $infos = $this->goods_promotion->listinfo($where, $order = '', $page, $pagesize = 20);

        for ($i=0;$i<sizeof($infos);$i++){
            $godos = $this->goods_db->get_one(array('id'=>$infos[$i]['goodsid']));
            $infos[$i]['goods_name'] = $godos['goods_name'];
            $infos[$i]['thumb'] = $godos['thumb'];
            $infos[$i]['shop_price'] = $godos['shop_price'];
        }
        $pages = $this->goods_promotion->pages;

        $big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=hpshop&c=goods&a=promotionadd\', title:\'添加促销商品\', width:\'800\', height:\'600\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加促销商品');
        include $this->admin_tpl('goodspromotionlist');
    }


    /**
     * 添加促销商品
     */
    public function promotionadd(){
        if($_POST['dosubmit']){
            $data=[
                'starttime' => $_POST['start_time'],
                'endtime'=>$_POST['end_time'],
                'goodsid'=>$_POST['goodsid'],
                'promotion_price'=>$_POST['promotion_price'],
                'addtime'=>time(),
            ];
            $results=$this->goods_promotion->insert($data,true);
            showmessage(L('添加促销商品成功'), '?m=hpshop&c=goods&a=goodsadds');
        }else{
            $list = $this->goods_promotion->select();
            $ids = '';
            foreach ($list as $pinfo){
                if(!empty($ids)){
                    $ids.=',';
                }
                $ids.=$pinfo['goodsid'];
            }
            $where = 'isok = 1';
            if(!empty($ids)){
                $where .= ' and id not in('.$ids.') ';
            }

            $info = $this->goods_db->select($where);
            include $this->admin_tpl('goodspromotionadd');
        }
    }

    /**
     * 编辑促销商品
     */
    public function promotionedit(){
        if($_POST['dosubmit']){
            $id = $_GET['id'];
            $goods_type = $_POST['goods_type'];
            if($goods_type == 0){
                $data=[
                    'goods_type'=>$goods_type,
                ];
            }elseif ($goods_type == 1){
                $data=[
                    'starttime' => strtotime($_POST['starttime']),
                    'endtime'=>strtotime($_POST['endtime']),
                    'promotion_price'=>$_POST['promotion_price'],
                    'goods_type'=>$goods_type,
                ];
            }elseif ($goods_type == 2){
                $data=[
                    'starttime' => strtotime($_POST['starttime']),
                    'endtime'=>strtotime($_POST['endtime']),
                    'waiting_time'=>$_POST['waiting_time'],
                    'goods_type'=>$goods_type,
                    'person_number'=>$_POST['person_number'],
                    'group_price'=>$_POST['group_price'],
                ];
            }
            $results=$this->goods_db->update($data,array('id'=>$id));
            showmessage(L('修改促销商品信息成功'), '?m=hpshop&c=goods&a=goodsedits');
        }else{
            $id = $_GET['id'];
            $info = $this->goods_db->get_one(array('id'=>$id));
            include $this->admin_tpl('goodspromotionedit');
        }
    }

    /**
     * 促销商品_删除
     */
    public function goodspromotiondel(){
        //删除单个
        $id=intval($_GET['id']);
        if($id){
            $result=$this->goods_promotion->delete(array('id'=>$id));
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
                $result=$this->goods_promotion->delete(array('id'=>$pid));
            }
            showmessage(L('operation_success'),HTTP_REFERER);
        }
        //都没有选择删除什么
        if( empty($_POST['id'])){
            showmessage('请选择要删除的记录',HTTP_REFERER);
        }
    }






}
?>