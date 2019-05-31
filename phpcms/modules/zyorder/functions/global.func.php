<?php


	/**
	 * 增加足迹记录
	 * @param  [type] $userid 用户id
	 * @param  [type] $id     商品id
	 * @param  [type] $catid  商品catid
	 * @param  [type] $url    商品url
	 * @param  [type] $thumb  缩略图
	 * @param  [type] $title  标题
	 * @param  [type] $price  价格
	 * @return [type]         [description]
	 */
    function footprint_fun($userid,$id,$catid,$url,$thumb,$title,$price){
    	$zyorder_footprint_db = pc_base::load_model('zyorder_footprint_model');
    	$time = time();
    	$footprint_time = strtotime(date('y-m-d 01:00:00',$time));

    	$info = $zyorder_footprint_db->get_one(array('userid'=>$userid,'pid'=>$id,'catid'=>$catid,'footprint_time'=>$footprint_time));

    	if(!$info){
			$data = array(
				'pid'=>$id,
				'catid'=>$catid,
				'url'=>$catid,
				'thumb'=>$thumb,
				'url'=>$url,
				'title'=>$title,
				'price'=>$price,
				'userid'=>$userid,
				'addtime'=>$time,
				'footprint_time'=>$footprint_time,
			);
			$zyorder_footprint_db->insert($data);
    	}
    }

	
?>
