<?php
// +------------------------------------------------------------
// | 卓远网络PHPCMS在线商城1.0
// +------------------------------------------------------------
// | 卓远网络：叶洋洋 QQ:1327262511 http://www.300c.cn/
// +------------------------------------------------------------
// | 欢迎加入卓远网络-Team，和卓远一起，精通PHPCMS
// +------------------------------------------------------------
// | 版本号：201600811
// +------------------------------------------------------------


  
	if(!function_exists('dump'))
	{
		function dump($val, $isexit = true)
		{
			@header("Content-type: text/html; charset=UTF-8");
			echo '<pre>'; var_dump($val); echo '</pre>';
			if ($isexit) exit;
		}
	}  
  
    /**
     * 分类选择
     * @param string $file 栏目缓存文件名
     * @param intval/array $catid 别选中的ID，多选是可以是数组
     * @param string $str 属性
     * @param string $default_option 默认选项
     * @param intval $modelid 按所属模型筛选
     * @param intval $type 栏目类型
     * @param intval $onlysub 只可选择子栏目
     * @param intval $siteid 如果设置了siteid 那么则按照siteid取
     */
    function select_category($file = '',$catid = 0, $str = '', $default_option = '', $modelid = 0, $type = -1, $onlysub = 0,$siteid = 0) {
        $tree = pc_base::load_sys_class('tree');
        if(!$siteid) $siteid = param::get_cookie('siteid');
        if (!$file) {
            $file = 'category_content_'.$siteid;
        }
        $result = getcache($file,'zyshop');
        $string = '<select '.$str.'>';
        if($default_option) $string .= "<option value='0'>$default_option</option>";
        if (is_array($result)) {
            foreach($result as $r) {
                if($siteid != $r['siteid'] || ($type >= 0 && $r['type'] != $type)) continue;
                $r['selected'] = '';
                if(is_array($catid)) {
                    $r['selected'] = in_array($r['catid'], $catid) ? 'selected' : '';
                } elseif(is_numeric($catid)) {
                    $r['selected'] = $catid==$r['catid'] ? 'selected' : '';
                }
                $r['html_disabled'] = "0";
                if (!empty($onlysub) && $r['child'] != 0) {
                    $r['html_disabled'] = "1";
                }
                $categorys[$r['catid']] = $r;
                if($modelid && $r['modelid']!= $modelid ) unset($categorys[$r['catid']]);
            }
        }
        $str  = "<option value='\$catid' \$selected>\$spacer \$catname</option>;";
        $str2 = "<optgroup label='\$spacer \$catname'></optgroup>";

        $tree->init($categorys);
        $string .= $tree->get_tree_category(0, $str, $str2);
            
        $string .= '</select>';
        return $string;
    }
    
    function get_unit()
    {
        return array(
            '1'=>'件',
            '2'=>'袋', 
            '3'=>'斤', 
            '4'=>'个', 
            '5'=>'瓶',        
        );    
    }

	//订单状态 0确认订单 1未付款 2已付款未发货 3发货中 4已完成
    function get_status($status = false)
    {
        $arr = array(
            0=>'未发货', 
            1=>'发货中', 
            2=>'已完成',        
        );    
		if($status === false) return $arr;
		else return $arr[intval($status)];
    }

	//计费方式 'self','fixed','weight','number'
    function get_shipping_mode($mode = '')
    {
        $arr = array(
            'self'=>'上门取货',       
            'fixed'=>'固定费用',       
            'weight'=>'按重量计算',       
            'number'=>'按商品件数计算',       
        );    
		if($mode == '') return $arr;
		else return $arr[trim($mode)];
    }
    
    function select_unit($str, $id='')
    {
        $units = get_unit();
        $id = !$id? '': intval($id);
        $select = '<select '.$str.'>';
        foreach($units as $key=>$val){
            if($id && $id == $key){
                $select .= '<option value="'.$key.'" selected="selected">'.$val.'</option>';
            }else{
                $select .= '<option value="'.$key.'">'.$val.'</option>';
            } 
        }   
        $select .= '</select>' ;
        return $select;
    }
    function getProductPic($pids)
    {
      $ids = explode(',', $pids);
      $string = '';
      if(is_array($ids)){
          foreach($ids as $pid){
              $productmodel_db = pc_base::load_model('zyshop_product_model');
              $product_pic = $productmodel_db->get_one(array('pid'=>$pid), 'pic');
              if($product_pic['pic']){
                  $string .= '<img src="'. $product_pic['pic'].'" width="30px" /> &nbsp;';
              }
          }
      }
      return !empty($string)? $string: '<img src="'.IMG_PATH.'admin_img/bfqicon1.jpg" width="30px" />'; 
    }
    
    /**
     * 生成流水号
     */
    function create_transaction_code()
	{
        mt_srand((double )microtime() * 1000000 );
        return date("YmdHis" ).str_pad( mt_rand( 1, 99999 ), 5, "0", STR_PAD_LEFT );
    }

    /**
     * 商品编号
     */
    function productNo($id)
	{
        return 'PN' . str_pad($id, 5, 0, STR_PAD_LEFT);
    }

    /**
     * 商品编号解码
     */
	function productNoDecode($str)
	{
		if(substr($str, 0, 2) == 'PN')
		{
			return intval(substr($str, 2));
		}
        return $str;
    }
   
	/**
	 * 
	 * 取二维数组中一个字段
	 * @param array $arr
	 * @param str $keyField
	 * @param str $valueField
	 * @param str $preKeyField 当$keyField有值，索引前加字符
	 */
	function array_to($arr, $keyField='', $valueField='', $preKeyField='' )
	{
		if(empty($arr)) return $arr;

		$result = array();
		foreach($arr as $k=>$v)
		{
			if( is_object($v) )
			{
				if($keyField)
					$result[$preKeyField . $v->$keyField] = $valueField ? $v->$valueField : $v;
				else
					$result[$k] = $valueField ? $v->$valueField : $v;
			}
			elseif( is_array($v) )
			{
				if($keyField)
					$result[$preKeyField . $v[$keyField]] = $valueField ? $v[$valueField] : $v;
				else
					$result[$k] = $valueField ? $v[$valueField] : $v;
			}
		}
		return $result;
	}
	
	/**
	 * 
	 * 去掉数组中的重复ID并排序
	 * @param $arr 数组
	 * @param $id 排序的字段
	 */
	function array_order($arr,$id){
			$con=count($arr);
			
			for ($i = 0; $i <$con; $i++) {
				$pid[$i]=$arr[$i][$id];
			
				}
			
				$p=	array_flip($pid);
				$pp=array_flip($p);
				$c=0;
				for ($j = 0; $j <$con; $j++){
					if ($pp[$j]){
						$pc[$c]=$pp[$j];
						$c++;
						}
					
					}
			return $pc;
		
		}
		
		
		
		
/**
 * 生成支付按钮
 * @param $data 按钮数据
 * @param $attr 按钮属性 如样式等
 * @param $ishow 是否显示描述
 */
function mk_pay_btn($data,$attr='class="otherpay"',$ishow='0') {
	$pay_type = '';
	if(is_array($data)){
		foreach ($data as $v) {
			$pay_type .= '<li id="zhifubao" '.$attr.'>';
			$pay_type .='<input name="pay_type" value="'.$v['pay_id'].'" class="hide" type="radio"> <span class="u-icon i-radio"></span><p class="aposition">'.$v['name'].'</p>'.'<spam class="u-icon i-pay-zfbapp"></span>';
			$pay_type .=$ishow ? '<span class="payment-desc">'.$v['pay_desc'].'</span>' :'';
			$pay_type .= '</li>';
		}
	}
	return $pay_type;
}	






/**
 * PC端_生成支付按钮
 * @param $data 按钮数据
 * @param $attr 按钮属性 如样式等
 * @param $ishow 是否显示描述
 */
function mk_pay_btnPC($data,$attr='class="zfxzxm"',$ishow='0') {
	$pay_type = '';
	if(is_array($data)){
		foreach ($data as $v) {
			$pay_type .= '<label><div '.$attr.'>';
			$pay_type .='<input name="pay_type" id="zhifubao" value="'.$v['pay_id'].'" class="hide" type="radio"> <span class="zfbxx">'.$v['name'].'</span>';
			$pay_type .=$ishow ? '<span class="payment-desc">'.$v['pay_desc'].'</span>' :'';
			$pay_type .= '</div></label>';
		}
	}
	return $pay_type;
}	






function get_code($button_attr = '')
	{
		if (strtoupper($this->config['gateway_method']) == 'POST') $str = '<form action="' . $this->config['gateway_url'] . '" method="POST" target="_blank">';
		else $str = '<form action="' . $this->config['gateway_url'] . '" method="GET" target="_blank">';
		$prepare_data = $this->getpreparedata();
		foreach ($prepare_data as $key => $value) $str .= '<input type="hidden" name="' . $key . '" value="' . $value . '" />';
		$str .= '<input type="submit" ' . $button_attr . ' />';
		$str .= '</form>';
		return $str;
	}
?>
