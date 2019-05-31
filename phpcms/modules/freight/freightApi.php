<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin', 'admin', 0);
pc_base::load_sys_class('format', '', 0);
pc_base::load_sys_class('form', '', 0);
pc_base::load_app_func('global');

class freightApi {

	function __construct() {
		$this->get_db = pc_base::load_model('get_model');
        $this->freight = pc_base::load_model('freight_model');
        $this->goods_freight = pc_base::load_model('goods_freight_model');
        $this->shipping_way = pc_base::load_model('shipping_way_model');

	}

    /**
     * SERVER:添加商品和运费关联表
     *
     * @param goodsid 商品id
     * @param template 运费模板id
     * @return true/false
     */
    public function add_goods_freight() {
	    $goodsid = $_POST['goodsid'];//商品id
        $templateid = $_POST['templateid'];//运费模板id
        $data=[
            'goodsid'=>$goodsid,
            'templateid'=>$templateid,
        ];
        if(empty($goodsid) || empty($templateid)){
            $result = [
                'status'=>'error',
                'code'=>300,
                'message'=>'参数有误',
            ];
            exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
        }
        $results = $this->goods_freight->insert($data);

        $result = [
            'status'=>'success',
            'code'=>200,
            'message'=>'成功',
        ];

        exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
    }


    /**
     * API:商品详情页获取运费
     *
     * @param goodsid 商品id
     * @param province 地址str(省)
     * @param weight 重量
     * @param volume 体积
     * @param count 数量(详情页传1)
     * @return 运费
     */
    public function getfreightinfo() {
        $templateid = $_POST['tid'];//商品id
        $province = $_POST['province'];//地址str(省)
        $weight = $_POST['weight'];//重量
        $volume = $_POST['volume'];//体积
        $count = $_POST['count'];//数量(详情页传1)

        $yunfei = 0;
        if($province == null || $province == ''){
            $province = '全国';
        }

        $template = $this->freight->get_one(array('template_id'=>$templateid));
        if($template == null){
            return 0;
        }
        $shipping_way=$this->shipping_way->select(array('template_id'=>$templateid));
        if($template['price_way'] == 1){
            //按件
            foreach ($shipping_way as $info){
                if(strpos($info['area_name'],$province) !==false){
                    //包含了
                    if($info['first_num'] >= $count){
                        $yunfei = $info['first_fee'];
                    }else{
                        $cut = $count - $info['first_num'];
                        $continue_num = $info['continue_num'];
                        $result = ceil($cut/$continue_num);
                        $yunfei = $info['first_fee'] + $info['continue_fee']*$result;
                    }
                    break;
                }else{
                    if($info['first_num'] >= $count){
                        $yunfei = $info['first_fee'];
                    }else{
                        $cut = $count - $info['first_num'];
                        $continue_num = $info['continue_num'];
                        $result = ceil($cut/$continue_num);
                        $yunfei = $info['first_fee'] + $info['continue_fee']*$result;
                    }
                }
            }
        }elseif ($template['price_way'] == 2){
            $total = $weight*$count;
            //按重量
            foreach ($shipping_way as $info){
                if(strpos($info['area_name'],$province) !==false){
                    //包含了
                    if($info['first_num'] >= $total){
                        $yunfei = $info['first_fee'];
                    }else{
                        $cut = $total - $info['first_num'];
                        $continue_num = $info['continue_num'];
                        $result = ceil($cut/$continue_num);
                        $yunfei = $info['first_fee'] + $info['continue_fee']*$result;
                    }
                    break;
                }else{
                    if($info['first_num'] >= $total){
                        $yunfei = $info['first_fee'];
                    }else{
                        $cut = $total - $info['first_num'];
                        $continue_num = $info['continue_num'];
                        $result = ceil($cut/$continue_num);
                        $yunfei = $info['first_fee'] + $info['continue_fee']*$result;
                    }
                }
            }
        }else{
            //按体积
            $total = $volume*$count;
            //按重量
            foreach ($shipping_way as $info){
                if(strpos($info['area_name'],$province) !==false){
                    //包含了
                    if($info['first_num'] >= $total){
                        $yunfei = $info['first_fee'];
                    }else{
                        $cut = $total - $info['first_num'];
                        $continue_num = $info['continue_num'];
                        $result = ceil($cut/$continue_num);
                        $yunfei = $info['first_fee'] + $info['continue_fee']*$result;
                    }
                    break;
                }else{
                    if($info['first_num'] >= $total){
                        $yunfei = $info['first_fee'];
                    }else{
                        $cut = $total - $info['first_num'];
                        $continue_num = $info['continue_num'];
                        $result = ceil($cut/$continue_num);
                        $yunfei = $info['first_fee'] + $info['continue_fee']*$result;
                    }
                }
            }
        }


        $results = [
            'status'=>'success',
            'code'=>200,
            'message'=>'成功',
            'data'=>$yunfei
        ];

        exit(json_encode($results,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
    }
}
?>












