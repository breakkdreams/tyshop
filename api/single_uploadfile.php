<?php

$types = $_GET['types'];

switch ($types) {
	case '1':	//用户头像

		//========== 更新会员头像-验证
		$type = $_POST['type'] ? $_POST['type'] : 1;	//类型：1web端、2APP端
		$userid = $type==1 ? param::get_cookie('_userid') : $_POST['userid'];	//用户id
		$member_db = pc_base::load_model('member_model');
		$memberinfo = $member_db->get_one(['userid'=>$userid]);
		if(!$memberinfo){
			_return_status(-101);
		}
		//帐号已锁定,无法登录
		if($memberinfo['islock']==1) {
			_return_status(-102);
		}
		//========== 更新会员头像-验证

		$imgurl2 = uploadfile_user('headerimg');
		break;

	case '2':	//店铺logo
		$imgurl2 = uploadfile_user('store_logo');
		break;

	case '3':	//身份证正面
		$imgurl2 = uploadfile_user('store_idcard');
		break;

	case '4':	//身份证反面
		$imgurl2 = uploadfile_user('store_idcard');
		break;

	default:
		//返回
		$result = [
			'status'=>'error',
			'code'=>-400,
			'message'=>'操作失败',
			'data'=>[
				'url'=>'',
				'tmp'=>'',
			],
		];
		$result = json_encode($result , JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
		exit($result);
		break;
}



/**
 * 图片上传
 * @param  string $file_url [文件夹]
 */
function uploadfile_user($file_url){
	//设置允许上传文件后缀
	$agreetype = ['jpg','png','jpeg'];
	$count = count($_FILES["file"]["name"]);


	for ($i = 0; $i < $count; $i++) {
   		 //获取文件名称
		 //$filename = $_FILES["file"]["name"][$i];
		 $filename = $_FILES["file"]["name"];
		 //获取文件类型
		 $filetype = explode(".",$filename);
		 $type = $filetype[1];

		 //获取文件大小
		 $filesize = $_FILES["file"]["size"][$i];

		 //获取临时文件
		 //$tempfile = $_FILES["file"]["tmp_name"][$i];
		 $tempfile = $_FILES["file"]["tmp_name"];

		 //重组文件名称
		 $newsname = time().rand(10,1000).$i.'.'.$type;

		 //设置上传路径
		 $basepath = str_replace( '\\' , '/',dirname(dirname(__FILE__)));
		 $time = date('Ymd',time());
		 $savePath = $basepath.'/uploadfile/'.$file_url.'/'.$time.'/';

		 $savePath2 = APP_PATH.'uploadfile/'.$file_url.'/'.$time.'/';
		 $tmp = 'uploadfile/'.$file_url.'/'.$time.'/';



		//判断有没有这个文件夹名,然后创建文件夹
		MkFolder($savePath);

		//组装文件网络路径
		$imgurl=$savePath.$newsname;
		$imgurl2=$savePath2.$newsname;
		$tmp = $tmp.$newsname;
   		 //var_dump($imgurl);

		//允许文件类型判断
		$typecount = count($agreetype);
		for ($j = 0; $j < $typecount; $j++){
			if ($type == $agreetype[$j]){
				$temptype = $agreetype[$j];
			}
		}
		if (empty($temptype)){

			//不允许上传文件的处理方法
			//echo '不允许上传文件的处理方法';
			//exit;
			$result = [
				'status'=>'error',
				'code'=>-1,
				'message'=>'不允许上传文件的处理方法',
				'data'=>[
					'url'=>'',
					'tmp'=>'',
				],
			];
			$result = json_encode($result , JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
			exit($result);
		}


		 //存储文件
		if(!copy($tempfile, $imgurl)){
			//文件上传失败的处理方法
			//echo $filename.'上传失败';
			$result = [
				'status'=>'error',
				'code'=>-2,
				'message'=>'上传失败',
				'data'=>[
					'url'=>'',
					'tmp'=>'',
				],
			];
			$result = json_encode($result , JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
			exit($result);
		}

	};


	//更新会员头像
	if($_GET['types']==1){
		$type = $_POST['type'] ? $_POST['type'] : 1;	//类型：1web端、2APP端
		$userid = $type==1 ? param::get_cookie('_userid') : $_POST['userid'];	//用户id
		$member_db = pc_base::load_model('member_model');
		$member_db->update(['headimgurl'=>$imgurl2],['userid'=>$userid]);
	}

	//返回
	$result = [
		'status'=>'success',
		'code'=>200,
		'message'=>'上传成功',
		'data'=>[
			'url'=>$imgurl2,
			'tmp'=>$tmp,
		],
	];
	$result = json_encode($result , JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
	exit($result);
}




/**
 * 创建文件夹
 * PHP判断文件夹是否存在和创建文件夹的方法（递归创建多级目录）
 * @param  string $path [请求文件路径]
 */
function MkFolder($path){
	if(!is_readable($path)){
		MkFolder( dirname($path) );
		if(!is_file($path)) mkdir($path,0777);
	}
}





// 最后返回的格式
/*
{
    "status": "success",
    "code": 200,
    "message": "上传成功",
    "data": {
        "url": "http://www.xxx.cn/uploadfile/userheadimg/20190309/1552120096960.jpg",
        "tmp": "uploadfile/userheadimg/20190309/1552120096960.jpg"
    }
}
 */






	/*
	 * 私有返回状态_返回状态
	 * @status [状态] 200操作成功/-100状态码不能为空，操作失败/-101账号不存在/-102帐号已锁定,无法登录/-103请先登录
	 * @param  [type] $status [*状态]
	 * @param  [type] $data [*数据组]
	 * @param  [type] $page [*翻页数据]
	 */
	function _return_status($status,$data,$pages) 
	{
		$status = $status;	//状态
		$data = $data;	//成功：返回数据组
		$pages = $pages;	//成功：返回数据组
		$data = $data;	//成功：返回数据组
		//==================	操作失败-验证 START
			switch ($status) {
				case 200:	//操作成功
					$result = [
						'status'=>'success',
						'code'=>200,
						'message'=>'操作成功',
					];
					if($data){
						$result['data']=$data;
					}
					if($pages){
						$result['page']=$pages;
					}
					exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
					break;
				
				case -101:	//账号不存在
					$result = [
						'status'=>'error',
						'code'=>-101,
						'message'=>'账号不存在',
					];
					exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
					break;
				
				case -102:	//帐号已锁定,无法登录
					$result = [
						'status'=>'error',
						'code'=>-102,
						'message'=>'帐号已锁定,无法登录',
					];
					exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
					break;
				
				case -103:	//请先登录
					$result = [
						'status'=>'error',
						'code'=>-103,
						'message'=>'请先登录',
					];
					exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
					break;
				
				case -104:	//参数不能为空
					$result = [
						'status'=>'error',
						'code'=>-104,
						'message'=>'参数不能为空',
					];
					exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
					break;
				
				default:
					$result = [
						'status'=>'error',
						'code'=>-100,
						'message'=>'操作失败',	//帐号已锁定,无法登录
					];
					exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
					break;
			}
		//==================	操作失败-验证 END
	}




?>
