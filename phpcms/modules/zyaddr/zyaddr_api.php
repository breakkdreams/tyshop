<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_func('global');
pc_base::load_sys_class('form', '', 0);
pc_base::load_sys_class('format', '', 0);
require_once dirname(__FILE__).'/index.php';

class zyaddr_api
{
    public function __construct()
    {
        $this->get_db = pc_base::load_model('get_model');
        $this->zyconfig_db = pc_base::load_model('zyconfig_model');
        $this->zyaddr_db = pc_base::load_model('zyaddr_model');
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

    /**
     * 获取会员信息
     * @param $url
     * @return json
     */
    public function member_api($url,$userid)
    {
        $params = array('userid'=>$userid);
        $paramstring = http_build_query($params);
        $content = $this->juhecurl($url,$paramstring);
        return $content;
    }

    /*
     * 地址列表
     * @param $userid 用户id
     * */
    public function lists($userid){
//        $userid = empty($userid)?$_GET['userid']:$userid;
		$type = $_POST['type'] ? $_POST['type'] : 1;	//类型：1web端、2APP端
		$userid = $type==1 ? param::get_cookie('_userid') : $_POST['userid'];
		
		
        if(empty($userid)){
            $msg = array('status'=>'error','code'=>10001,'message'=>"请输入用户ID");
            echo json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            return false;
        }

        $map = array('userid'=>$userid);
        $address = $this->zyaddr_db->select($map);
        $data = array('status'=>'success','code'=>200,'message'=>'查询成功',"data"=>$address);
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        return false;
    }

    /*
     * 添加地址
     * @param userid 用户id
     * @param name 收件人
     * @param phone 手机号码
     * @param province 省
     * @param city 市
     * @param distrct 区
     * @param address 详细地址
     * @param default 默认
     * */
    public function add($data){
//        $data['userid'] = empty($data['userid'])? $_POST['userid']:$data['userid'];
		$type = $_POST['type'] ? $_POST['type'] : 1;	//类型：1web端、2APP端
		$data['userid'] = $type==1 ? param::get_cookie('_userid') : $_POST['userid'];
		
		
        $data['name'] = empty($data['name'])? $_POST['name']:$data['name'];
        $data['phone'] = empty($data['phone'])? $_POST['phone']:$data['phone'];
        $data['province'] = empty($data['province'])? $_POST['province']:$data['province'];
        $data['city'] = empty($data['city'])? $_POST['city']:$data['city'];
        $data['district'] = empty($data['district'])? $_POST['district']:$data['district'];
        $data['address'] = empty($data['address'])? $_POST['address']:$data['address'];
        $data['default'] = empty($data['default'])? $_POST['default']:$data['default'];

        if(empty($data['userid'])){
            $msg = array('status'=>'error','code'=>10001,'message'=>"请输入用户ID");
            echo json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            return false;
        }

        if(empty($data['name'])){
            $msg = array('status'=>'error','code'=>10002,'message'=>"请输入收件人");
            echo json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            return false;
        }

        if(empty($data['phone'])){
            $msg = array('status'=>'error','code'=>10003,'message'=>"请输入手机号码");
            echo json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            return false;
        }

        $preg_phone='/^1[34578]\d{9}$/';
        if(!preg_match($preg_phone,$_POST['phone'])){
            $msg = array('status'=>'error','code'=>10008,'message'=>"请输入正确的手机号码");
            echo json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            return false;
        }

        if(empty($data['province'])){
            $msg = array('status'=>'error','code'=>10004,'message'=>"请输入省");
            echo json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            return false;
        }

        if(empty($data['city'])){
            $msg = array('status'=>'error','code'=>10005,'message'=>"请输入市");
            echo json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            return false;
        }

        if(empty($data['district'])){
            $msg = array('status'=>'error','code'=>10006,'message'=>"请输入区");
            echo json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            return false;
        }

        if(empty($data['address'])){
            $msg = array('status'=>'error','code'=>10007,'message'=>"请输入详细地址");
            echo json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            return false;
        }

        if($data['default']==1){
            $count = $this->zyaddr_db->count(array('default'=>1));
            $datas = $this->zyaddr_db->get_one(array('default'=>1));
            if($count>0){
               $this->zyaddr_db->update(array('default'=>0),array('id'=>$datas['id']));
            }
        }else{
            $count = $this->zyaddr_db->count();
            if($count<=0){
                $data['default'] = 1;
            }
        }

        if($this->zyaddr_db->insert($data)){
            $msg = array('status'=>'success','code'=>200,'message'=>'操作成功');
            echo json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }else{
            $msg = array('status'=>'error','code'=>10010,'message'=>'操作失败');
            echo json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }
    }

    /*
     * 地址编辑
     * @param userid 用户id
     * @param name 收件人
     * @param phone 手机号码
     * @param province 省
     * @param city 市
     * @param distrct 区
     * @param address 详细地址
     * @param default 默认
     * */
    public function edit($data)
    {
        $data['id'] = empty($data['id'])? $_POST['id']:$data['id'];
//        $data['userid'] = empty($data['userid'])? $_POST['userid']:$data['userid'];
		$type = $_POST['type'] ? $_POST['type'] : 1;	//类型：1web端、2APP端
		$data['userid'] = $type==1 ? param::get_cookie('_userid') : $_POST['userid'];
		
        $data['name'] = empty($data['name'])? $_POST['name']:$data['name'];
        $data['phone'] = empty($data['phone'])? $_POST['phone']:$data['phone'];
        $data['province'] = empty($data['province'])? $_POST['province']:$data['province'];
        $data['city'] = empty($data['city'])? $_POST['city']:$data['city'];
        $data['district'] = empty($data['district'])? $_POST['district']:$data['district'];
        $data['address'] = empty($data['address'])? $_POST['address']:$data['address'];
        $data['default'] = empty($data['default'])? $_POST['default']:$data['default'];

        if(empty($data['id'])){
            $msg = array('status'=>'error','code'=>10011,'message'=>"请输入ID");
            echo json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            return false;
        }

        if(empty($data['userid'])){
            $msg = array('status'=>'error','code'=>10001,'message'=>"请输入用户ID");
            echo json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            return false;
        }

        if(empty($data['name'])){
            $msg = array('status'=>'error','code'=>10002,'message'=>"请输入收件人");
            echo json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            return false;
        }

        if(empty($data['phone'])){
            $msg = array('status'=>'error','code'=>10003,'message'=>"请输入手机号码");
            echo json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            return false;
        }

        if(empty($data['province'])){
            $msg = array('status'=>'error','code'=>10004,'message'=>"请输入省");
            echo json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            return false;
        }

        if(empty($data['city'])){
            $msg = array('status'=>'error','code'=>10005,'message'=>"请输入市");
            echo json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            return false;
        }

        if(empty($data['district'])){
            $msg = array('status'=>'error','code'=>10006,'message'=>"请输入区");
            echo json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            return false;
        }

        if(empty($data['address'])){
            $msg = array('status'=>'error','code'=>10007,'message'=>"请输入详细地址");
            echo json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            return false;
        }

        if($data['default']==1){
            $datas = $this->zyaddr_db->select();
            foreach($datas as $k => $v){
                $this->zyaddr_db->update(array('default'=>0),array('id'=>$v['id']));
            }
        }

        $map = array('id'=>$data['id']);

        if($this->zyaddr_db->update($data,$map)){
            $msg = array('status'=>'success','code'=>200,'message'=>'操作成功');
            echo json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }else{
            $msg = array('status'=>'error','code'=>10010,'message'=>'操作失败');
            echo json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }
    }

    /*
     * 地址删除
     * @param $id 地址id
     * */
    public function del($id)
    {
        $id = empty($id)?$_POST['id']:$id;
		$type = $_POST['type'] ? $_POST['type'] : 1;	//类型：1web端、2APP端
		$userid = $type==1 ? param::get_cookie('_userid') : $_POST['userid'];
		
        if(empty($id)){
            $msg = array('status'=>'error','code'=>10001,'message'=>"请输入地址ID");
            echo json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            return false;
        }
		
		if(empty($userid)){
            $msg = array('status'=>'error','code'=>10001,'message'=>"请输入用户ID");
            echo json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            return false;
        }

        $map = array('id'=>$id);

        $count = $this->zyaddr_db->count();

        if($count>1){
            $sql = "select * from zy_zyaddr where `id`<>".$id;
            $res = $this->get_db->query($sql);
            $data = $this->get_db->fetch_array($res);
            $this->zyaddr_db->update(array('default'=>1),array('id'=>$data[0]['id']));
        }

        if($this->zyaddr_db->delete($map)){
            $msg = array('status'=>'success','code'=>200,'message'=>'操作成功');
            echo json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }else{
            $msg = array('status'=>'error','code'=>10010,'message'=>'操作失败');
            echo json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }
    }

    /*
     * 设置默认
     * @param $id 地址id
     * @param $default 默认
     * */
    public function change($id,$default,$userid){
        $id = empty($id)?$_POST['id']:$id;
        $default = empty($default)? $_POST['default']:$default;
//        $userid = empty($userid)? $_GET['userid']:$userid;
		$type = $_POST['type'] ? $_POST['type'] : 1;	//类型：1web端、2APP端
		$userid = $type==1 ? param::get_cookie('_userid') : $_POST['userid'];
		
        if(empty($id)){
            $msg = array('status'=>'error','code'=>10001,'message'=>'请输入地址ID');
            echo json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            return false;
        }

        if(empty($default)){
            $msg = array('status'=>'error','code'=>10002,'message'=>'请输入默认参数');
            echo json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            return false;
        }

        if(empty($userid)){
            $msg = array('status'=>'error','code'=>10004,'message'=>'请输入用户ID');
            echo json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            return false;
        }

        if($default == 1){
            $lists = $this->zyaddr_db->select(array('userid'=>$userid));
            foreach($lists as $k => $v){
                $this->zyaddr_db->update(array('default'=>0),array('id'=>$v['id'],"userid"=>$userid));
            }
        }

        $data = array('default'=>$default);
        $map = array('id'=>$id,'userid'=>$userid);
        if($this->zyaddr_db->update($data,$map)){
            $msg = array('status'=>'success','code'=>200,'message'=>'操作成功');
            echo json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }else{
            $msg = array('status'=>'error','code'=>10003,'message'=>'操作失败');
            echo json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }
    }

    /*
     * 获取默认地址
     * 
     */
    public function getdefault($userid){
        $userid = empty($userid)? $_POST['userid']:$userid;

        if(empty($userid)){
            $msg = array('status'=>'error','code'=>10001,'message'=>'请输入用户ID');
            echo json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            return false;
        }
        $res = $this->zyaddr_db->get_one(array("userid"=>$userid,"default"=>1));
        $msg = array('status'=>'success','code'=>200,'message'=>'操作成功','data'=>$res);
        exit(json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }

    /*
     * 通过id获取地址
     * @param id int 
     */
    public function getaddr($id,$userid){
        $id = empty($id)?$_POST['id']:$id;
        $_userid = param::get_cookie('_userid');
        $userid = $_POST['userid'];

        if($_userid){
            $userid = $_userid;
        }else{
            $userid = $userid;
        }

        if(empty($id)){
            $msg = array('status'=>'error','code'=>10001,'message'=>'请输入ID');
            exit(json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }

        $res = $this->zyaddr_db->get_one(array('id'=>$id,'userid'=>$userid));
        $msg = array('status'=>'success','code'=>200,'message'=>'操作成功','data'=>$res);
        exit(json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }
}