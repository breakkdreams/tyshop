<?php

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
     * 生成流水号
     */
    function create_transaction_code()
	{
        mt_srand((double )microtime() * 1000000 );
        return date("YmdHis" ).str_pad( mt_rand( 1, 99999 ), 5, "0", STR_PAD_LEFT );
    }

    //分级数组排序
    function catetree($cateRes){
        return sorts($cateRes);
    }

    function sorts($cateRes,$pid=0,$level=0){

        static $arr=array();
        foreach ($cateRes as $k => $v) {
            if($v['pid']==$pid){
                $v['level']=$level;
                $arr[]=$v;
                sorts($cateRes,$v['id'],$level+1);
            }
        }

        return $arr;
    }


   //获取子栏目id

    function childrenids($cateid){
        $goodscat_db = pc_base::load_model('goodscat_model');
        $data = $goodscat_db->select('1','id,pid','',$order = 'id ASC, sort ASC');
        return _childrenids($data,$cateid);
    }

    function _childrenids($data,$cateid){
        static $arr=array();
        foreach ($data as $k => $v) {
            if($v['pid']==$cateid){
                $arr[]=$v['id'];
                _childrenids($data,$v['id']);
            }
        }
        return $arr;
    }

    //处理批量删除
    function pdel($cateids){
        $goodscat_db = pc_base::load_model('goodscat_model');
        $child=childrenids($cateids);
        $child=implode(',', $child);
        if(empty($child)){
            $child=$cateids;
        }else{
            $child=$cateids.','.$child;
        }
       
        // $childrenidsarr[]=childrenids($v);
        // $_childrenidsarr=array();
        // foreach ($childrenidsarr as $k => $v) {
        //     if(is_array($v)){
        //         foreach ($v as $k1 => $v1) {
        //            $_childrenidsarr[]=$v1;
        //         }
        //     }else{
        //         $_childrenidsarr[]=$v;
        //     }
        // }
        //$_childrenidsarr=array_unique($_childrenidsarr);
        $where = ' id in ('.$child.') ';
        $data = $goodscat_db->delete($where); 
   }
    
     
   /**
    *处理数组信息
    * @param $arr 原数组
    * @param $num 判断类型
    */
   function getcatinfo($arr,$num){
     $newarr=[];
     foreach ($arr as $k => $v) {
        if($num == 1){
            $newarr[$v['id']] = $v['cate_name'];
        }elseif($num == 2){
            $newarr[$v['id']] = $v['brandname'];
        }elseif($num == 3){
            $newarr[$v['id']] = $v['type_name'];
        }      
     } 
     return $newarr;  
   }


    /**
     *检测商品有效性
     * @param $gid 商品id
     * @param $gspec 商品规格类型
     */
    function checkspec($gid,$gspec){
        $goods_db = pc_base::load_model('goods_model');
        $goods_specs_db = pc_base::load_model('goods_specs_model');

        $info = $goods_db->get_one(['on_sale'=>1,'isok'=>1,'id'=>$gid]);
        $infos = $goods_specs_db->get_one(['specid'=>$gspec,'status'=>1,'goodsid'=>$gid]);
        $num = count($infos);

        if ( !$info || ( $num > 0 && $gspec == 0 ) || ( $info['isspec'] == 0 && $gspec != 0 ) ) {
            $result = [
                'status' => 'error',
                'code' => -2,
                'message' => '添加失败，无效的商品信息',
            ];
            return $result;
        }

        if ( $info['isspec'] == 0 ) {
            $stock = $info['stock'];
        }else{
            $stock = $infos['specstock'];
        }

        $result = [
            'status' => 'success',
            'code' => 1,
            'message' => '有效',
            'data' => [
                'stock'=> $stock,
                'sid' => $info['shopid']
            ],
        ];
        return $result;
    }



    /**
     *获取商品规格
     * @param $fid 商品类型id
     * @param $gid 商品id(后期备用)
     */
    function getspec($fid,$gid){
        $goodsattr_db = pc_base::load_model('goodsattr_model');
        $info = $goodsattr_db->select(array('goodstypeid'=>$fid,'attrtype'=>1,'isshow'=>1),'attrval,attrname','','sort ASC');
        $infos = $goodsattr_db->select(array('goodstypeid'=>$fid,'attrtype'=>0,'isshow'=>1),'*','','sort DESC,id DESC');
        $num = count($info);
        $ninfo = makespec($info,$num);
        $narr = [];
        foreach ($info as $k => $v) {
            $varr = explode(',', $v['attrval']);
            $narrs = [];
            foreach ($varr as $ks => $vs) {
                $narrs[] = [
                    'key'=> $ks + 1,
                    'val'=> $vs
                ];
            }
            $narr[] = [
                'name'=>$v['attrname'],
                'valarr' => $narrs
            ];
        }
        $data = [
            // 'attr' => $infos,
            'spec' => $ninfo,
            'specname' => $narr
        ];
        return $data;
    }



    /**
     * 获取属性搭配信息
     * @param $arr 原数组
     * @param $num 属性个数
     * @param $time 运行次数
     * @param $data 所需数据
     */
    function makespec($arr,$num,$time=0,$data=[]) {
        if($num == 0){
            return '0';
        }
        $sarr = explode(',',$arr[$time]['attrval']);
        
        if( empty($data) ){
            foreach ($sarr as $k => $v) {
            
                $data[] = [
                    'specdata' => ['0'=>$v],
                    'keys' => $k+1,
                    'vals' => $v
                ];
            }
        }else{
            $narr = [];

            foreach ($data as $ks => $vs) {
                
                foreach ($sarr as $k => $v) {
                    $lsarr = $data;
                    $lsarr[$ks]['specdata'][] = $v;
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
            $data = makespec($arr,$num,$time,$data);
        }

        return $data;
        
    }   
    

?>
