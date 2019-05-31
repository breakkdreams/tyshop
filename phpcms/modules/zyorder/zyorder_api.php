<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_func('global');
pc_base::load_sys_class('form', '', 0);
pc_base::load_sys_class('format', '', 0);

class zyorder_api
{
    function __construct()
    {
        $this->get_db = pc_base::load_model('get_model');
        $this->order_db = pc_base::load_model('zy_order_model');
        $this->logistics_db = pc_base::load_model('zy_logistics_model');
        $this->evaluate_set_db = pc_base::load_model('zy_evaluate_set_model');
        $this->evaluate_db = pc_base::load_model('zy_evaluate_model');
        $this->logistics_company_db = pc_base::load_model('zy_logistics_company_model');
        //订单商品表
        $this->ordergoods_db = pc_base::load_model('zy_order_goods_model');
        $this->express_db = pc_base::load_model('zyexpress_model');
        //团购订单表
        $this->group_buy_order_db = pc_base::load_model('group_buy_order_model');
        //商品表
        $this->goods_db = pc_base::load_model('goods_model');
    }

    /**
     * CURL方式的GET传值
     * @param  [type] $url  [GET传值的URL]
     * @return [type]       [description]
     */
    function _crul_get($url)
    {
        $oCurl = curl_init();
        if (stripos($url, "https://") !== FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if (intval($aStatus["http_code"]) == 200) {
            return $sContent;
        } else {
            return false;
        }
    }


    /**
     * 添加订单
     */
    public function addorder()
    {
        $_userid = $_POST['userid'];
        $freight = $_POST['freight'];
        $is_group = $_POST['is_group'];
        if ($_userid == null) {
            $result = [
                'status' => 'error',
                'code' => -1,
                'message' => '访问受限，缺少参数',
            ];
            exit(json_encode($result, JSON_UNESCAPED_UNICODE));
        }

        $addtime = time();//生成下单时间
        $data = [
            "userid" => $_userid,
            "province" => $_POST['province'],  //收货地址省
            "city" => $_POST['city'],//收货地址市
            "area" => $_POST['area'],//收货地址区
            "status" => 1,//待支付
            "address" => $_POST['address'], //详细地址
            "lx_mobile" => $_POST['lx_mobile'], //联系电话
            "lx_name" => $_POST['lx_name'], //联系人
            "lx_code" => $_POST['lx_code'], //联系邮编
            "addtime" => $addtime,
        ];

        foreach ($data as $k => $v) {
            if (empty($v) && $k != "usernote") {
                $result = [
                    'status' => 'error',
                    'code' => -1,
                    'message' => '访问受限，缺少参数',
                ];
                exit(json_encode($result, JSON_UNESCAPED_UNICODE));
            }

        }

        $idarr = [];
        foreach ($_POST['shopdata'] as $ks => $vs) {

            $newdata = $data;
            $newdata['storeid'] = $vs['storeid'];//商铺id
            $newdata['ordersn'] = time() + mt_rand(100, 999);//订单编号
            $newdata['usernote'] = $vs['usernote'];//备注
            $newdata['totalprice'] = $vs['stprice']+$freight;//总价
            $newdata['freight'] = $freight;//运费
            $id = $this->order_db->insert($newdata, true);

            $idarr[] = [
                'oid' => $id,
                'order_sn' => $newdata['ordersn']
            ];
            $len = count($vs['cartinfo']);
            $sql = "insert into zy_order_goods ( `order_id`, `goods_id`, `goods_name`, `goods_num`, `goods_img`, `goods_price`, `specid`, `specid_name`) values";
            foreach ($vs['cartinfo'] as $key => $val) {
                $sql .= "(" . $id . ", " . $val['goodsid'] . ", '" . $val['goodsname'] . "', '" . $val['cartnum'] . "', '" . $val['goodsimg'] . "', '" . $val['goodsprice'] . "', '" . $val['goodsspec'] . "', '" . $val['goodsspecs'] . "'),";

                if($is_group == 1){
                    //添加团购订单表
                    $where = ' id = ' . $val['goodsid'] ;
                    $info = $this->goods_db->get_one($where);
                    $datas = [
                        "userid" => $_userid,
                        "orderid" => $id,
                        "goodsid" => $val['goodsid'],
                        "waitingtime" => $info['waiting_time'],
                        "addtime" => $addtime,
                    ];
                    $results = $this->group_buy_order_db->insert($datas);
                    if($results){
                        $lastp = $info['waiting_time']-1;
                        if($lastp<0){
                            $lastp =0;
                        }
                        $this->order_db->update(array('isgroup_buy' => 1,'lastperson' => $lastp), array('id' => $id));
                    }
                }
            };
            $sql = substr($sql, 0, strlen($sql) - 1);
            $this->ordergoods_db->query($sql);
        }



        $result = [
            'status' => 'success',
            'code' => 1,
            'message' => 'OK',
            'data' => $idarr
        ];
        exit(json_encode($result, JSON_UNESCAPED_UNICODE));
    }


    /**
     * 获取订单列表（店铺用户通用）
     */
    public function order_list()
    {
        //用户id和用户名
        $pageindex = $_POST['pageindex'];
        $pagesize = $_POST['pagesize'];

        $userid = $_POST['userid'];//用户id，APP端必须传
        //非APP端直接用$_userid
        if ($pageindex == null) {
            $pageindex = 1;
        }
        if ($pagesize == null) {
            $pagesize = 10;
        }
        if ($userid == null) {
            $this->empty_userid();
        }
        $where = ' userid = ' . $userid;
        if ($_POST['status'] == 1) {
            $where .= ' AND status=1';
        } else if ($_POST['status'] == 2) {
            $where .= ' AND status=2';
        } else if ($_POST['status'] == 3) {
            $where .= ' AND status=3';
        } else if ($_POST['status'] == 4) {
            $where .= ' AND status=4';
        } else if ($_POST['status'] == 10) {
            $where .= ' AND status=10';
        } else {
            $where .= ' AND 1';
        }

        if ($_POST['shstatus'] == 1) {
            $where .= ' AND shstatus=1';
        } else if ($_POST['shstatus'] == 2) {
            $where .= ' AND shstatus=2';
        } else if ($_POST['shstatus'] == 3) {
            $where .= ' AND shstatus=3';
        } else if ($_POST['shstatus'] == 4) {
            $where .= ' AND shstatus in (4,5,6,7)';
        } else {
            $where .= ' AND 1';
        }

        $sql = 'SELECT storeid from zy_order WHERE userid = ' . $userid . ' GROUP BY storeid';
        $sqlrs = $this->order_db->query($sql);
        $sqlres = $this->order_db->fetch_array($sqlrs);
        $idarr = '';
        foreach ($sqlres as $key => $value) {
            if (empty($idarr)) {
                $idarr = $value['storeid'];
            } else {
                $idarr .= ',' . $value['storeid'];
            }
        }

        $token_url = APP_PATH . 'index.php?m=zymember&c=zymember_api&a=zyshop_nickname';
        $data = array('ids' => $idarr);
        $content = http_build_query($data);
        $content_length = strlen($content);
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' =>
                    "Content-type: application/x-www-form-urlencoded\r\n" .
                    "Content-length: $content_length\r\n",
                'content' => $content
            )
        );
        $token = json_decode(file_get_contents($token_url, false, stream_context_create($options)));
        $rs = json_decode(json_encode($token), true);
        $snamarr = [];
        foreach ($rs['data'] as $ks => $vs) {
            $snamarr[$vs['userid']] = $vs;
        }

        $where .= ' AND status < 6 ';
        $order = ' id DESC ';
        $page = $pageindex ? $pageindex : '1';
        $orders = $this->order_db->listinfo($where, $order, $page, $pagesize); //读取数据库里的字段
        $totalcount = $this->order_db->count($where);
        foreach ($orders as $k => $v) {
            $goodsinfo = $this->uordgoodsinfo(0, $v['id']);
            $orders[$k]['goodsinfo'] = $goodsinfo;
            $orders[$k]['storename'] = $snamarr[$v['storeid']]['shopname'];
        }
        //查询商品信息
        $totalpage = ceil($totalcount / $pagesize);
        $data = [
            "status" => 'success',
            "code" => 1,
            "message" => '操作成功',
            "data" => $orders,
            'page' => [
                'pagesize' => $pagesize,
                'totalpage' => $totalpage,
                'totalnum' => $totalcount
            ]
        ];
        echo json_encode($data);
    }


    //订单详情
    public function order_info()
    {
        //用户id和用户名
        $oid = $_POST['oid'];
        $userid = $_POST['userid'];

        if ($userid == null) {
            exit($this->empty_userid());
        }
        if ($this->check_uid($oid, $userid)) {
            $order = $this->order_db->get_one(array('id' => $oid, 'userid' => $userid));
            $data = [
                "ids" => $order['storeid']
            ];
            $url = APP_PATH . 'index.php?m=zymember&c=zymember_api&a=zyshop_nickname';
            $return = json_decode($this->_crul_post($url, $data), true);
            if ($this->check_uid_status($oid, $userid, 3)) {
                $KdApi = pc_base::load_app_class('KdApiSearch');
                $KdApi = new KdApiSearch();
                $order = $this->order_db->get_one(array('id' => $oid, 'userid' => $userid));
                $logisticResult = $KdApi->getOrderTracesByJson($order['shipper_code'], $order['logistics_order']);

            }

            $goods = $this->uordgoodsinfo(0, $order['id']);
            $order['goodsinfo'] = $goods;
            $order['totalprice'] = 0;
            foreach ($goods as $good) {
                $order['totalprice'] += $good['final_price'];
            }
            $order['goodsnum'] = count($goods);
            $order['storename'] = $return['data'][0]['shopname'];
            $order['wuliu'] = $logisticResult;
            $this->caozuo_success($order);
        } else {
            $this->error_check_uid();
        }
    }


    public function uordgoodsinfo($ischeck = 1, $orderid = 0)
    {

        if ($_POST['oid']) {
            $oid = $_POST['oid'];//订单id
        } else {
            $oid = $orderid;//订单id
        }
        if (empty($oid)) {
            $result = [
                'status' => 'error',
                'code' => -1,
                'message' => '访问受限，缺少参数',
            ];
            exit(json_encode($result, JSON_UNESCAPED_UNICODE));
        }

        if ($ischeck == 1) {
            $userid = $_POST['userid'];//用户id，APP端必须传
            if (!$userid) {
                $result = [
                    'status' => 'error',
                    'code' => 0,
                    'message' => '请先登录！',
                ];
                exit(json_encode($result, JSON_UNESCAPED_UNICODE));
            }

            $count = $this->order_db->count(['id' => $oid, 'userid' => $userid]);
            if ($count == 0) {
                $result = [
                    'status' => 'error',
                    'code' => -2,
                    'message' => '非法访问',
                ];
                exit(json_encode($result, JSON_UNESCAPED_UNICODE));
            }
        }

        $info = $this->ordergoods_db->select(['order_id' => $oid], 'id,goods_id,goods_name,goods_num,final_price,goods_price,specid,specid_name,is_comment,goods_img');
        if ($ischeck != 1) {
            return $info;
            exit(0);
        }
        $result = [
            'status' => 'success',
            'code' => 1,
            'message' => 'OK',
            'data' => $info,
        ];
        $jg = json_encode($result, JSON_UNESCAPED_UNICODE);
        $jg = stripslashes($jg);
        exit($jg);
    }


    /**
     * CURL方式的POST传值
     * @param  [type] $url  [POST传值的URL]
     * @param  [type] $data [POST传值的参数]
     * @return [type]       [description]
     */
    function _crul_post($url, $data)
    {

        //初始化curl
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        //post提交方式
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //运行curl
        $result = curl_exec($curl);

        //返回结果
        if (curl_errno($curl)) {
            return 'Errno' . curl_error($curl);
        }
        curl_close($curl);
        return $result;
    }

    /**
     * 提醒发货
     */
    public function order_txfh()
    {
        $id = $_POST['id'];
        $_userid = $_POST['userid'];
        if ($_userid == null) {
            $this->empty_userid();
        }
        if ($this->check_uid_status($id, $_userid, 2)) {
            $result = $this->order_db->update(array('remind' => '提醒发货'), array('id' => $id));
            if ($result) {
                $this->caozuo_success("操作成功");
            }
        } else {
            $this->error_check_uid_status();
        }
    }

    /**
     * 订单中心_待支付_取消订单
     */
    public function order_cancel()
    {

        $id = $_POST['id'];
        $userid = $_POST['userid'];

        if ($userid == null) {
            $this->empty_userid();
        }
        if ($this->check_uid_status($id, $userid, 1)) {
            $result = $this->order_db->update(array('status' => 6), array('id' => $id));
            if ($result) {
                $this->caozuo_success("取消成功");
            } else {
                $this->caozuo_fail();
            }
        } else {
            $this->error_check_uid_status();
        }
    }

    /**
     * 删除订单
     *
     */
    public function order_delete()
    {
        $id = $_POST['id'];
        $userid = $_POST['userid'];

        if ($userid == null) {
            $this->empty_userid();
        }
        if ($this->check_uid_status($id, $userid, 5)) {
            $result = $this->order_db->delete(array('id' => $id));
            if ($result) {
                $this->caozuo_success("删除成功");
            } else {
                $this->caozuo_fail();
            }
        } else {
            $this->error_check_uid_status();
        }
    }

    /**
     * 确认收货
     */
    public function order_qrsh()
    {
        $id = $_POST['id'];
        $_userid = $_POST['userid'];
        if ($_userid == null) {
            $this->empty_userid();
        }
        if ($this->check_uid_status($id, $_userid, 3)) {
            $result = $this->order_db->update(array('status' => 4), array('id' => $id));
            if ($result) {
                $this->caozuo_success("确认收货");
            } else {
                $this->caozuo_fail();
            }
        } else {
            $this->error_check_uid_status();
        }
    }

    /**
     * 订单管理_物流
     */
    public function kuaidi_ajx()
    {
        $id = $_POST['id'];
        $_userid = $_POST['userid'];
        if ($this->check_uid_status($id, $_userid, 3)) {
            $KdApi = pc_base::load_app_class('KdApiSearch');
            $KdApi = new KdApiSearch();
            $order = $this->order_db->get_one(array('id' => $id, 'userid' => $_userid));
            $logisticResult = $KdApi->getOrderTracesByJson($order['shipper_code'], $order['logistics_order']);
            $logisticResult = json_decode($logisticResult);
            echo $logisticResult;
        }
    }

    //申请售后
    function apply_sh()
    {
        $_userid = $_POST['userid'];
        $orderid = $_POST['id'];
        $tk_reason = $_POST['tk_reason'];
        $sale_type = $_POST['sale_type'];//售后类型(1.直接退款 2.退货退款)


        if ($_userid == null) {
            $this->empty_userid();
        }
        if ($this->check_uid($orderid, $_userid)) {
            $order = $this->order_db->get_one(array('id' => $orderid));

            if ($order['status'] == 5) {
                $result = $this->order_db->update(array('status' => 10, 'prestatus' => $order['status'], 'tk_reason' => $tk_reason, 'sale_type' => $sale_type, 'shstatus' => 1), array('id' => $orderid));
                if ($result) {
                    $this->caozuo_success("申请成功");
                } else {
                    $this->caozuo_fail("申请失败");
                }
            } else {
                $this->caozuo_fail("该状态下不支持售后");
            }
        } else {
            $this->error_check_uid();
        }
    }

    //卖家审核同意
    function after_sale_pass()
    {
        $orderid = $_POST['id'];
        $storeid = $_POST['storeid'];
        if ($this->check_storeid_shstatus($storeid, '10')) {//退货*********************************
            $order = $this->order_db->get_one(array('id' => $orderid));

            if ($order['shstatus'] == 1 && $order['sale_type'] == 2) {
                $result = $this->order_db->update(array('shstatus' => 2), array('id' => $orderid));
                if ($result) {
                    $this->caozuo_success("操作成功");
                } else {
                    $this->caozuo_fail("操作失败");
                }
            } elseif ($order['shstatus'] == 1 && $order['sale_type'] == 1) {//直接退款*********************************
                $this->agree_apply_th();
            } else {
                $this->caozuo_fail("该状态下不支持售后");
            }
        } else {
            $this->error_check_uid();
        }
    }

    //卖家审核拒绝
    function after_sale_refuse()
    {
        $orderid = $_POST['id'];
        $storeid = $_POST['storeid'];
        $refuse_reason = $_POST['refuse_reason'];
        if ($this->check_storeid_shstatus($storeid, '10')) {
            $order = $this->order_db->get_one(array('id' => $orderid));

            if ($order['shstatus'] == 1 || $order['shstatus'] == 3) {
                $result = $this->order_db->update(array('status' => 5, 'prestatus' => $order['status'], 'refuse_reason' => $refuse_reason, 'shstatus' => 5), array('id' => $orderid));
                if ($result) {
                    $this->caozuo_success("操作成功");
                } else {
                    $this->caozuo_fail("操作失败");
                }
            } else {
                $this->caozuo_fail("该状态下不支持售后");
            }
        } else {
            $this->error_check_uid();
        }
    }

    //买家撤回申请
    function withdraw_sale()
    {
        $orderid = $_POST['id'];
        $_userid = $_POST['userid'];
        if ($_userid == null) {
            $this->empty_userid();
        }
        if ($this->check_uid($orderid, $_userid)) {
            $order = $this->order_db->get_one(array('id' => $orderid));

            if ($order['shstatus'] == 1 || $order['shstatus'] == 2) {
                $result = $this->order_db->update(array('status' => 5, 'prestatus' => $order['status'], 'shstatus' => 4), array('id' => $orderid));
                if ($result) {
                    $this->caozuo_success("撤回成功");
                } else {
                    $this->caozuo_fail("撤回失败");
                }
            } else {
                $this->caozuo_fail("该状态下不支持售后");
            }
        } else {
            $this->error_check_uid();
        }
    }

    //物流公司列表
    function logistics_list()
    {
        $info = $this->express_db->select();
        $data = [
            "status" => 'success',
            "code" => 200,
            "message" => '操作成功',
            "data" => $info,
        ];
        echo json_encode($data);
    }

    //填写快递单号并修改状态
    function add_logistics()
    {
        $logistics_order = $_POST['logistics_order'];
        $sale_shipper_code = $_POST['sale_shipper_code'];
        $order_id = $_POST['order_id'];
        $_userid = $_POST['userid'];
        if ($_userid == null) {
            $this->empty_userid();
        }
        if ($this->check_uid($order_id, $_userid)) {
            $wuliu = $this->express_db->get_one(array('code'=>$sale_shipper_code));
            $info = $this->order_db->update(array('shipper_name' => $wuliu['company'], 'shipper_code' => $sale_shipper_code, 'sale_logistics_order' => $logistics_order, 'shstatus' => '3'), array('id' => $order_id));
            if ($info) {
                $data = [
                    "status" => 'success',
                    "code" => 200,
                    "message" => '操作成功',
                ];
            } else {
                $data = [
                    "status" => 'error',
                    "code" => 300,
                    "message" => '操作失败',
                ];
            }
            echo json_encode($data);
        } else {
            $this->error_check_uid();
        }
    }

    //自动取消售后
    function order_time_out()
    {
        $orderid = $_POST['id'];
        $_userid = $_POST['userid'];
        if ($_userid == null) {
            $this->empty_userid();
        }
        if ($this->check_uid($orderid, $_userid)) {
            $order = $this->order_db->get_one(array('id' => $orderid));

            if ($order['status'] == 10 && $order['shstatus'] == 2) {
                $result = $this->order_db->update(array('status' => 5, 'prestatus' => $order['status'], 'shstatus' => 6), array('id' => $orderid));
                if ($result) {
                    $this->caozuo_success("取消成功");
                } else {
                    $this->caozuo_fail("取消失败");
                }
            } else {
                $this->caozuo_fail("该状态下不支持售后");
            }
        } else {
            $this->error_check_uid();
        }
    }

    //卖家同意退货
    function agree_apply_th()
    {
        $id = $_POST['id'];
        $storeid = $_POST['storeid'];
        if ($storeid == null) {
            exit($this->empty_storeid());
        }
        if ($this->check_storeid_shstatus($storeid, '10')) {
            $order = $this->order_db->get_one(array('id' => $id));
            $result = $this->order_db->update(array('status' => 10, 'shstatus' => 7), array('id' => $id));
            if ($result) {
                //退钱
                if ($order['pay_type'] == 1) {//余额退款
                    $data = [
                        "describe" => "售后退款",
                        "module" => "order",
                        "amount" => $order['totalprice'],//订单价格
                        "type" => 2,
                        "userid" => $order['userid'],
                    ];
                    $url = APP_PATH . 'index.php?m=zymember&c=zymember_api&a=pub_increaseamount';
                    $return = json_decode($this->_crul_get($url, $data), true);
                    if ($return['code'] == '200') {
                        $this->caozuo_success("退款成功");
                    } else {
                        $this->caozuo_fail();
                    }
                } elseif ($order['pay_type'] == 2) {//支付宝

                } elseif ($order['pay_type'] == 3) {//微信

                }

                $this->caozuo_success("退款成功");
            } else {
                $this->caozuo_fail();
            }
        } else {
            $this->error_check_storeid_status();
        }
    }


    //店铺订单列表
    public function order_list_shop()
    {
        $_storeid = $_POST['storeid'];
        $pageindex = $_POST['pageindex'];
        $pagesize = $_POST['pagesize'];
        if ($pageindex == null) {
            $pageindex = 1;
        }
        if ($pagesize == null) {
            $pagesize = 10;
        }
        $where = 'storeid=' . $_storeid;
        if ($_POST['status'] == 1) {
            $where .= ' AND status=1';
        } else if ($_POST['status'] == 2) {
            $where .= ' AND status=2';
        } else if ($_POST['status'] == 3) {
            $where .= ' AND status=3';
        } else if ($_POST['status'] == 4) {
            $where .= ' AND status=4';
        } else if ($_POST['status'] == 10) {
            $where .= ' AND status=10';
        } else {
            $where .= ' AND 1';
        }

        if ($_POST['shstatus'] == 1) {
            $where .= ' AND shstatus=1';
        } else if ($_POST['shstatus'] == 2) {
            $where .= ' AND shstatus=2';
        } else if ($_POST['shstatus'] == 3) {
            $where .= ' AND shstatus=3';
        } else if ($_POST['shstatus'] == 4) {
            $where .= ' AND shstatus in (4,5,6,7)';
        } else {
            $where .= ' AND 1';
        }

        $order = 'id DESC';
        $page = $pageindex ? $pageindex : '1';
        $orders = $this->order_db->listinfo($where, $order, $page, $pagesize); //读取数据库里的字段
        $totalcount = $this->order_db->count($where);
        foreach ($orders as $k => $v) {
            $goodsinfo = $this->uordgoodsinfo(0, $v['id']);
            $orders[$k]['goodsinfo'] = $goodsinfo;
        }
        //查询商品信息
        $totalpage = ceil($totalcount / $pagesize);
        $data = [
            "status" => 'success',
            "code" => 1,
            "message" => '操作成功',
            "data" => $orders,
            'page' => [
                'pagesize' => $pagesize,
                'totalpage' => $totalpage,
                'totalnum' => $totalcount
            ]
        ];
        echo json_encode($data);
    }

    //卖家关闭订单
    function close_order()
    {
        $orderid = $_POST['id'];
        $storeid = $_POST['storeid'];
        if ($this->check_storeid_shstatus($storeid, '10')) {
            $order = $this->order_db->get_one(array('id' => $orderid));

            if ($order['status'] == 1) {
                $result = $this->order_db->update(array('status' => 11, 'prestatus' => $order['status']), array('id' => $orderid));
                if ($result) {
                    $this->caozuo_success("操作成功");
                } else {
                    $this->caozuo_fail("操作失败");
                }
            } else {
                $this->caozuo_fail("该状态下不支持操作");
            }
        } else {
            $this->error_check_uid();
        }
    }

    //商铺修改订单收货地址
    function editaddress()
    {
        $id = $_POST['id'];
        $storeid = $_POST['storeid'];
        if ($storeid == null) {
            exit($this->show_error("no storeid"));
        }
        $lx_name = $_POST['lx_name'];
        $lx_mobile = $_POST['lx_mobile'];
        $lx_code = $_POST['lx_code'];
        $province = $_POST['province'];
        $city = $_POST['city'];
        $area = $_POST['area'];
        $address = $_POST['address'];
        $result = $this->order_db->update(array('lx_mobile' => $lx_mobile, 'lx_code' => $lx_code, 'lx_name' => $lx_name, 'province' => $province, 'city' => $city, 'area' => $area, 'address' => $address), array('id' => $id));
        if ($result) {
            $this->show_success("修改地址成功");
        } else {
            $this->show_error("修改地址错误");
        }
    }

    //商铺修改商品价格和运费(计算总价)
    function editorderprice()
    {
        $orderid = $_POST['id'];
        $storeid = $_POST['storeid'];
        if ($storeid == null) {
            exit($this->show_error("no storeid"));
        }
        if($this->check_storeid_status($orderid,$storeid,1)){
            $freeship = $_POST['freeship'];//运费
            //数据类型 [{"id":"1","goods_price":"1"},{"id":"2","goods_price":"1"}]
            $data = $_POST['itemdata'];
            $datainfo = json_decode($data, true);
            for ($i = 0; $i < count($datainfo); $i++) {
                $order_goods_id = $datainfo[$i]['id'];
                $goods_price = $datainfo[$i]['goods_price'];
                $result = $this->ordergoods_db->update(array('goods_price' => $goods_price), array('id' => $order_goods_id));
            }
            //计算总价
            $updateprice = 0;
            $where = ' order_id = ' . $orderid;
            $list = $this->ordergoods_db->select($where);
            for ($j = 0; $j < sizeof($list); $j++) {
                $updateprice += $list[$j]['goods_price'];
            }
            $updateprice += $freeship;

            $result = $this->order_db->update(array('totalprice' => $updateprice, 'freeship' => $freeship), array('id' => $orderid));

            if ($result) {
                $this->show_success("修改成功");
            } else {
                $this->show_error("修改错误");
            }
        }else{
            $this->error_check_storeid_status();
        }
    }

    //订单发货
    public function order_ddfh(){
        $id = $_POST['id'];
        $shippercode = $_POST['shippercode'];
        $logistics_order = $_POST['logistics_order'];
        $storeid = $_POST['storeid'];

        $kd_type = $_POST['kd_type'];
        if($kd_type == 1){
            //有快递
            if($this->check_storeid_status($id,$storeid,2)){
                $wuliu = $this->express_db->get_one(array('code'=>$shippercode));
                $result = $this->order_db->update(array('shipper_name' =>$wuliu['company'],'shipper_code' =>$wuliu['code'],'logistics_order' =>$logistics_order,'fhtime'=>time(),'status'=>'3','kd_type'=>$kd_type),array('id'=>$id));
                if($result){
                    $this->caozuo_success("发货成功");
                }else{
                    $this->caozuo_fail();
                }
            }else{
                $this->error_check_storeid_status();
            }
        }elseif ($kd_type == 2){
            //没快递
            if($this->check_storeid_status($id,$storeid,2)){
                $result = $this->order_db->update(array('fhtime'=>time(),'status'=>'3','kd_type'=>$kd_type),array('id'=>$id));
                if($result){
                    $this->caozuo_success("发货成功");
                }else{
                    $this->caozuo_fail();
                }
            }else{
                $this->error_check_storeid_status();
            }

        }
    }



























    /**
     * 订单中心_支付
     */
    public function order_pay()
    {
        $id = $_POST['id'];
        $_userid = $_POST['userid'];
        $password = $_POST['password'];
        $totalprice = $_POST['totalprice'];

        if ($_userid == null) {
            $this->empty_userid();
        }
        $data = [
            "userid" => $_userid,
            "pay_password" => $password
        ];
        $url = APP_PATH . 'index.php?m=zymember&c=zymember_api&a=zyorder_offpaypas';
        $return = json_decode($this->_crul_post($url, $data), true);

        if ($return['code'] == '200') {
            if ($this->check_uid_status($id, $_userid, 1)) {
                $order = $this->order_db->get_one(array('id' => $id));
                if ($order['totalprice'] != $totalprice) {
                    $this->caozuo_fail("totalprice !=");
                }
                $paytime = time();
                $result = $this->order_db->update(array('status' => 2, 'paytime' => $paytime), array('id' => $id));
                if ($result) {
                    $this->caozuo_success("支付成功");
                } else {
                    $this->caozuo_fail();
                }
            } else {
                $this->error_check_uid();
            }
        } else {
            exit(json_encode($return));
        }
    }


    public function kuaidi_ajx2()
    {
        $shipper_code = $_POST['shipper_code'];
        $logistics_order = $_POST['logistics_order'];
        $KdApi = pc_base::load_app_class('KdApiSearch');
        $KdApi = new KdApiSearch();
        $logisticResult = $KdApi->getOrderTracesByJson($shipper_code, $logistics_order);
        return $logisticResult;
    }


    //评价
    public function evaluate()
    {
        echo json_encode($_POST);
        exit();
        $_userid = $_POST['userid'];
        $id = $_POST['id'];
        $evalute_arr = $_POST['evalute_arr'];
        if ($_userid == null) {
            $this->empty_userid();
        }
        if ($this->check_uid_status($id, $_userid, 4)) {
            foreach ($evalute_arr as $val) {
                $shopid = $val['shopid'];
                $content = $val['content'];
                $star = [];
                $setarr = [];
                $evaluate_set = $this->evaluate_set_db->select(1);
                foreach ($evaluate_set as $v) {
                    array_push($setarr, $v['value']);
                }

                foreach ($_POST as $k => $v) {
                    if (in_array($k, $setarr)) {
                        $star[$k] = $v;
                    }
                }
                $result = $this->uploadimg($_FILES, $_userid);
                $data = [
                    'orderid' => $id,
                    'shopid' => $shopid,
                    'content' => $content,
                    'star' => $star,
                    'userid' => $_userid,
                    'img' => $result,
                    'addtime' => time()
                ];
                $evaluateid = $this->evaluate_db->insert($data, true);
                $result = $this->order_db->update(array('status' => 5), array('id' => $_POST['id']));
                if ($result) {
                    $this->caozuo_success($result);
                } else {
                    $this->caozuo_fail();
                }
            }
        } else {
            $this->error_check_uid_status();
        }
    }






    function uploadimg($files, $_userid)
    {

        $basepath = str_replace('\\', '/', dirname(dirname(dirname(dirname(__FILE__)))));

        $savePath = $basepath . '/uploadfile/order/' . $_userid . '/' . date('Ymd', time());

        if (!file_exists($savePath)) {
            mkdir($savePath, 0777, true);
        }
        $result = [];
        foreach ($files as $key => $val) {
            $imgName = time() . rand(1000, 9999);//随机数
            $file_dir = $savePath . "/" . $imgName . ".jpg";
            if (move_uploaded_file($val["tmp_name"], $file_dir)) {
                $result['src'] = APP_PATH . 'uploadfile/zyshop/' . $_userid . '/' . date('Ymd', time()) . '/' . $imgName . ".jpg";
            } else {
                $info = [
                    'code' => 0,
                    'msg' => 'Error',
                    'data' => ''
                ];
                $info = json_encode($info, JSON_UNESCAPED_UNICODE);
                $info = stripslashes($info);
                return $info;
            }
        }
        $info = [
            'code' => 1,
            'msg' => 'OK',
            'data' => $result
        ];
        $info = json_encode($info, JSON_UNESCAPED_UNICODE);
        $info = stripslashes($info);
        return $info;

    }


    /**
     * 订单模块_待支付
     * @param  [type] $userid [*用户id]
     * @param  [type] $shopid [*商品id]
     * @param  [type] $orderid [*订单id]
     * @param  [type] $type [*类型：1web端、2APP端]
     * @param  [type] $forward [接下来该跳转的页面链接]
     * @return [json]         [数据组]
     */
    public function shop_pay()
    {
        $userid = $_POST['userid'];    //用户id
        $shopid = $_POST['shopid'];    //商品id
        $orderid = $_POST['orderid'];    //商品订单号id
        $type = $_POST['type'] ? $_POST['type'] : 1;    //类型：1web端、2APP端
        $forward = $_POST['forward'] ? urldecode($_POST['forward']) : APP_PATH . 'index.php?m=member&c=index';    //接下来该跳转的页面链接


        //==================	操作失败-验证 START
        //参数不能为空
        if (!$userid || !$shopid || !$orderid) {
            $result = [
                'status' => 'error',
                'code' => -1,
                'message' => '请填写完整的参数',
            ];
            exit(json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }
        //请先登录！
        if (!$userid) {
            $result = [
                'status' => 'error',
                'code' => -2,
                'message' => '请先登录！',
            ];
            exit(json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }
        //用户不存在
        $this->zyconfig_db = pc_base::load_model('zyconfig_model');
        $config = $this->zyconfig_db->get_one(array('key' => 'zymember1'), "url");
        $curl = [
            'userid' => $userid,
            'field' => 'userid,username',
        ];
        $memberinfo = $this->_crul_post($config['url'], $curl);
        $memberinfo = json_decode($memberinfo, true);

        if ($memberinfo['code'] != 200) {
            $result = [
                'status' => 'error',
                'code' => -3,
                'message' => '用户不存在',
            ];
            exit(json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }
        //判断订单号是否正确
        $shopid = explode(",", $shopid);
        $orderid = explode(",", $orderid);
        $shopcount = count($shopid);
        for ($i = 0; $i < $shopcount; $i++) {
            $orderarr[$i] = $this->order_db->get_one(['id' => $shopid[$i], 'ordersn' => $orderid[$i]], '`id`,`storeid`,`status`,`userid`,`lx_mobile`,`lx_name`,`lx_code`,`province`,`city`,`area`,`address`,`totalprice`,`freight`,`usernote`');

            $orderinfo['dianpu'][$i] = $this->order_db->get_one(['id' => $shopid[$i], 'ordersn' => $orderid[$i]], '`id`,`ordersn`');

            if (!$orderarr[$i]) {
                $result = [
                    'status' => 'error',
                    'code' => -4,
                    'message' => '订单号错误',
                ];
                exit(json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            }

            //商品信息
            $orderinfo['dianpu'][$i]['shopinfo'] = $this->ordergoods_db->select('order_id in (' . $orderarr[$i]['id'] . ')', '`goods_name`,`goods_num`,`goods_img`,`final_price`,`goods_price`,`specid_name`');

            //地址管理
            $orderinfo['address']['lx_mobile'] = $orderarr[$i]['lx_mobile'];
            $orderinfo['address']['lx_name'] = $orderarr[$i]['lx_name'];
            $orderinfo['address']['lx_code'] = $orderarr[$i]['lx_code'];
            $orderinfo['address']['province'] = $orderarr[$i]['province'];
            $orderinfo['address']['city'] = $orderarr[$i]['city'];
            $orderinfo['address']['area'] = $orderarr[$i]['area'];
            $orderinfo['address']['address'] = $orderarr[$i]['address'];

            //杂七杂八
            $orderinfo['other']['usernote'] = $orderarr[$i]['usernote'];
            $orderinfo['other']['freight'] += $orderarr[$i]['freight'];
            $orderinfo['other']['total'] += $orderarr[$i]['totalprice'];

        }


        //==================	操作失败-验证 END

        //==================	操作成功-更新数据 START

        $data = [

        ];
        $result = [
            'status' => 'success',
            'code' => 200,
            'message' => '操作成功',
            'data' => $orderinfo,
        ];
        exit(json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        //==================	操作成功-更新数据 END

    }


    /**
     *订单余额支付
     */
    public function prepay_for_balance()
    {
        //dump($_POST,true);
        $_userid = param::get_cookie('_userid');
        $userid = $_POST['uid'];
        $oids = $_POST['oids'];
        $pass = $_POST['paycode'];


        if ($_userid) {
            $uid = $_userid;
        } else {
            $uid = $userid;
        }

        if (!$uid) {
            $result = [
                'status' => 'error',
                'code' => 0,
                'message' => '请先登录！',
            ];
            exit(json_encode($result, JSON_UNESCAPED_UNICODE));
        }

        if (!$oids || !$pass) {
            $result = [
                'status' => 'error',
                'code' => -1,
                'message' => '访问受限，缺少必要参数',
            ];
            exit(json_encode($result, JSON_UNESCAPED_UNICODE));
        }

        foreach ($oids as $key => $value) {
            if (isset($oid)) {
                $oid .= ',' . $value;
            } else {
                $oid = $value;
            }
        }

        $where = ' id in (' . $oid . ') and status = 1 and userid = ' . $uid;
        $count = $this->order_db->count($where);
        if ($count != count($oids)) {
            $result = [
                'status' => 'error',
                'code' => -2,
                'message' => '非法访问',
            ];
            exit(json_encode($result, JSON_UNESCAPED_UNICODE));
        }
        $data = [
            "userid" => $uid,
            "pay_password" => $pass
        ];
        $url = APP_PATH . 'index.php?m=zymember&c=zymember_api&a=zyorder_offpaypas';    //yyy
        $return = json_decode($this->_crul_post($url, $data), true);

        if ($return['code'] == '200') {
            //$where = ' id in ('.$oid.') and userid = '.$uid;
            $sql = ' SELECT SUM(totalprice) as tprcie from zy_order where ' . $where;
            $rs = $this->order_db->query($sql);
            $res = $this->order_db->fetch_array($rs);
            $tprice = $res[0]['tprcie'];
            $data = [
                'userid' => $uid,
                'amount' => $tprice,
                'describe' => '余额支付',
                'module' => 'zyorder'
            ];
            $url = APP_PATH . "index.php?m=zymember&c=zymember_api&a=pub_reduceamount&userid=$uid&amount=$tprice&describe=余额支付&module=zyorder";
            $return = json_decode($this->_crul_get($url, $data), true);

            if ($return['code'] == '200') {
                $result = $this->order_db->update(array('status' => 2), $where);
                $result = [
                    'status' => 'success',
                    'code' => 1,
                    'message' => 'OK',
                ];
                exit(json_encode($result, JSON_UNESCAPED_UNICODE));
            } else {
                $result = [
                    'status' => 'error',
                    'code' => -5,
                    'message' => $return['message'],
                ];
                exit(json_encode($result, JSON_UNESCAPED_UNICODE));
            }


        } else {
            if ($return['code'] == '-3') {
                $code = -3;
            } else {
                $code = -4;
            }
            $result = [
                'status' => 'error',
                'code' => $code,
                'message' => $return['message'],
            ];
            exit(json_encode($result, JSON_UNESCAPED_UNICODE));
        }

    }


    /********************************************** /
     *        统一判断状态方法                     * /
     */                                            /**/

    //订单与用户相关
    public function check_uid($id, $_userid)
    {
        if ($_userid != null) {
            $order = $this->order_db->get_one(array('id' => $id));
            if ($order['userid'] != $_userid) {
                return false;
            }
            return true;
        }
    }

    //订单与商户相关
    public function check_storeid($id, $storeid)
    {
        if ($storeid != null) {
            $order = $this->order_db->get_one(array('storeid' => $storeid));
            if ($order['storeid'] != $storeid) {
                return false;
            }
            return true;
        }
    }

    //订单与用户，订单状态相关
    public function check_uid_status($id, $_userid, $status)
    {
        if ($_userid != null) {
            $order = $this->order_db->get_one(array('id' => $id));
            if ($order['status'] != $status || $order['userid'] != $_userid) {
                return false;
            }
            return true;
        }
    }

    //订单与商户，订单状态相关
    public function check_storeid_status($id, $storeid, $status)
    {
        if ($storeid != null) {
            $order = $this->order_db->get_one(array('storeid' => $storeid));
            if ($order['status'] != $status || $order['storeid'] != $storeid) {
                return false;
            }
            return true;
        }
    }

    //订单与商户，订单状态相关
    public function check_storeid_shstatus($storeid, $status)
    {
        if ($storeid != null) {
            $order = $this->order_db->get_one(array('storeid' => $storeid));
            if ($order['status'] != $status || $order['storeid'] != $storeid) {
                return false;
            }
            return true;
        }
    }



    /********************************************** /
     *        统一输出格式                          * /
     */                                            /**/


    //check_uid
    function error_check_uid()
    {
        $data = [
            "status" => 'error',
            "code" => 203,
            "message" => '订单与用户不匹配',
            "data" => ''
        ];
        exit(json_encode($data));

    }

    //check_uid_status
    function error_check_uid_status()
    {
        $data = [
            "status" => 'error',
            "code" => 204,
            "message" => '订单与商户，订单状态不匹配',
            "data" => ''
        ];
        exit(json_encode($data));
    }

    //check_storeid
    function error_check_storeid()
    {
        $data = [
            "status" => 'error',
            "code" => 205,
            "message" => '订单与商户不匹配',
            "data" => ''
        ];
        exit(json_encode($data));
    }

    //check_storeid_status
    function error_check_storeid_status()
    {
        $data = [
            "status" => 'error',
            "code" => 206,
            "message" => '订单与商户，订单状态不匹配',
            "data" => ''
        ];
        exit(json_encode($data));
    }


    function caozuo_success($e)
    {
        $data = [
            "status" => 'success',
            "code" => 200,
            "message" => '操作成功',
            "data" => $e
        ];
        exit(json_encode($data));
    }

    function caozuo_fail()
    {
        $data = [
            "status" => 'error',
            "code" => 201,
            "message" => '操作失败',
            "data" => ''
        ];
        exit(json_encode($data));
    }

    function empty_userid()
    {
        $data = [
            "status" => 'error',
            "code" => 207,
            "message" => '用户id为空',
            "data" => ''
        ];
        exit(json_encode($data));
    }

    function empty_storeid()
    {
        $data = [
            "status" => 'error',
            "code" => 208,
            "message" => '商户id为空',
            "data" => ''
        ];
        exit(json_encode($data));
    }

    public function order_stats_info()
    {
        if ($_GET['d'] == '0') {
            $beginYesterday = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
            $endYesterday = mktime(0, 0, 0, date('m'), date('d'), date('Y')) - 1;
            $sql = " addtime >= " . $beginYesterday . " AND addtime <= " . $endYesterday;
            $census = [
                'date' => 'Yesterday'
            ];
        } else if ($_GET['d'] == '1') {
            $beginToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endToday = mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')) - 1;
            $sql = " addtime >= " . $beginToday . " AND addtime <= " . $endToday;
            $census = [
                'date' => 'Today'
            ];
        } else {
            exit($this->show_error('no params'));
        }
        $arr = [];
        $orders = $this->order_db->select($sql);
        foreach ($orders as $order) {
            array_push($arr, $order['userid']);
        }
        $count = array_count_values($arr);

        foreach ($count as $k => $v) {
            $census['tj'][] = [
                'userid' => $k,
                'count' => $v
            ];
        }
        $this->caozuo_success($census);

    }


    public function order_stats()
    {
        $beginToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        $endToday = mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')) - 1;
        $beginYesterday = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
        $endYesterday = mktime(0, 0, 0, date('m'), date('d'), date('Y')) - 1;
        $year = date('Y', time());
        $stats = [];

        for ($i = 1; $i <= 12; $i++) {
            $arr = $this->mFristAndLast($year, $i);
            $sql = " addtime >= " . $arr['firstday'] . " AND addtime <= " . $arr['lastday'];
            $order = $this->order_db->select($sql);
            $stats[] = [
                "month" . $i => count($order)
            ];

            $sql = " addtime >= " . $beginToday . " AND addtime <= " . $endToday;
            $order = $this->order_db->select($sql);
            $stats[] = [
                "today" . $i => count($order)
            ];
            $sql = " addtime >= " . $beginYesterday . " AND addtime <= " . $endYesterday;
            $order = $this->order_db->select($sql);
            $stats[] = [
                "yesterday" . $i => count($order)
            ];
        }
        echo json_encode($stats);
    }

    function mFristAndLast($y = "", $m = "")
    {
        if ($y == "") $y = date("Y");
        if ($m == "") $m = date("m");
        $m = sprintf("%02d", intval($m));
        $y = str_pad(intval($y), 4, "0", STR_PAD_RIGHT);

        $m > 12 || $m < 1 ? $m = 1 : $m = $m;
        $firstday = strtotime($y . $m . "01000000");
        $firstdaystr = date("Y-m-01", $firstday);
        $lastday = strtotime(date('Y-m-d 23:59:59', strtotime("$firstdaystr +1 month -1 day")));

        return array(
            "firstday" => $firstday,
            "lastday" => $lastday
        );
    }


}

?>