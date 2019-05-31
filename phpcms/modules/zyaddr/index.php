<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_func('global');
pc_base::load_sys_class('form', '', 0);
pc_base::load_sys_class('format', '', 0);
require_once dirname(__FILE__).'/zyaddr_api.php';

class index{
	function __construct() {
		$this->get_db = pc_base::load_model('get_model');
		$this->zyconfig_db = pc_base::load_model('zyconfig_model');
		$this->zyaddr_db = pc_base::load_model('zyaddr_model');
		$this->userid = param::get_cookie('_userid'); // 模拟数据
	}

	/*
	 * 地址管理列表
	 * */
	public function init(){
		$url = $this->zyconfig_db->get_one(array('key'=>"zyaddr1"));
		$params = array('type'=>2,'userid'=>$this->userid);
		$paramstring = http_build_query($params);
		$data = json_decode($this->juhecurl($url['url'],$paramstring,1),true);
		$lists = $data['data'];
		include template('zyaddr',"index");
	}

	/*
	 * 地址添加
	 * */
	public function add(){
		include template('zyaddr',"add");
	}

	/*
	 * 地址添加
	 * */
	public function addr_add(){
		$data = $_POST;
		$data['userid'] = $this->userid;
		if(empty($data['default'])){
			$data['default'] = 0;
		}

		$zyaddr_api = new zyaddr_api();
		echo $zyaddr_api->add($data);
	}

	/*
	 * 地址编辑
	 * */
	public function edit(){
		$lists = $this->zyaddr_db->get_one(array('id'=>$_GET['id']));
		include template('zyaddr',"edit");
	}

	/*
	 * 地址编辑
	 * */
	public function addr_edit(){
		$data = $_POST;
		$data['userid'] = $this->userid;
		$zyaddr_api = new zyaddr_api();
		echo $zyaddr_api->edit($data);
	}

	/*
	 * 地址删除
	 * */
	public function del(){
		$zyaddr_api = new zyaddr_api();
		echo $zyaddr_api->del($_POST['id']);
	}

	public function changeDefault(){
		$default = $_POST['default'];
		$id = $_POST['id'];

		$zyaddr_api = new zyaddr_api();
		echo $zyaddr_api->change($id,$default,$this->userid);
	}

	public function addresslists(){
		include template("zyaddr","addressmanage");
	}

	public function addressedit(){
		include template("zyaddr","addressedit");
	}

	/**
     * 请求接口返回内容
     * @param  string $url [请求的URL地址]
     * @param  string $params [请求的参数]
     * @param  int $ipost [是否采用POST形式]
     * @return  string
     */
    public function juhecurl($url,$params=false,$ispost=0){
        $httpInfo = array();
        $ch = curl_init();

        curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
        curl_setopt( $ch, CURLOPT_USERAGENT , 'JuheData' );
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 60 );
        curl_setopt( $ch, CURLOPT_TIMEOUT , 60);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        if( $ispost )
        {
            curl_setopt( $ch , CURLOPT_POST , true );
            curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
            curl_setopt( $ch , CURLOPT_URL , $url );
        }
        else
        {
            if($params){
                curl_setopt( $ch , CURLOPT_URL , $url.'&'.$params );
            }else{
                curl_setopt( $ch , CURLOPT_URL , $url);
            }
        }
        $response = curl_exec( $ch );

        if ($response === FALSE) {
            //echo "cURL Error: " . curl_error($ch);
            return false;
        }
        $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
        $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
        curl_close( $ch );
        return $response;
    }
}
?>