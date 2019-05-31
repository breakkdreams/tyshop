<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_func('global');
pc_base::load_sys_class('form', '', 0);
pc_base::load_sys_class('format', '', 0);


class goods_api
{

    function __construct()
    {
        $this->get_db = pc_base::load_model('get_model');
        $this->admin = pc_base::load_model('admin_model');
        $this->member_db = pc_base::load_model('member_model');
        $this->member_detail_db = pc_base::load_model('member_detail_model');

        //商品表
        $this->goods_db = pc_base::load_model('goods_model');
        //分类栏目表
        $this->goodscat_db = pc_base::load_model('goodscat_model');
        //商品类型表
        $this->goodstype_db = pc_base::load_model('goodstype_model');

        //商品类型属性表
        $this->goodsattr_db = pc_base::load_model('goodsattr_model');
        //商品属性组合表
        $this->goods_specs_db = pc_base::load_model('goods_specs_model');
        //商品属性表
        $this->goods_attr_db = pc_base::load_model('goods_attr_model');
        //购物车表
        $this->goodscarts_db = pc_base::load_model('goodscarts_model');
        //商品搜索历史表
        $this->goods_sh_db = pc_base::load_model('goods_sh_model');
        $this->zyaddr_db = pc_base::load_model('zyaddr_model');

    }

    /**
     * 首页
     * param:rid(首页1),
     */
    public function index_page()
    {
        $where = ' and a.isok = 1 and a.on_sale = 1 ';
        $sql = 'SELECT a.id,a.goods_name,a.thumb,a.summary,a.market_price,a.shop_price FROM zy_goods a WHERE ' . $where . ' ORDER BY a.addtime DESC';
        $page = $_POST['page'] ? $_POST['page'] : '1';
        $goods_list = $this->get_db->multi_listinfo($sql, $page, $pagesize = 10);
        //banner图

        //头条

        $result = [
            'status' => 'success',
            'code' => 200,
            'message' => '成功',
            'goods_list' => $goods_list,
        ];
        exit(json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }

    /**
     *商品列表
     * param:uid(用户id),is_search(搜索传1),sercon(搜索的标题),is_sort(销量1 价格2 新品3),sort(排序1.asc 2.desc),catid(分类id),page(分页页数)
     * goods_type:商品类型(0,普通商品 1.促销商品 2.团购商品)
     * shopid:商铺id
     */
    public function goods_list_page()
    {
        $uid = $_POST['uid'];
        $is_search = $_POST['is_search'];//1是搜索
        $where = ' isok = 1 and on_sale = 1 ';
        if ($is_search == 1) {
            if (!empty($_POST['sercon'])) {
                $where .= " and goods_name like '%" . $_POST['sercon'] . "%' ";
                if (!empty($uid)) {
                    $his = $this->goods_sh_db->get_one(['userid' => $uid]);
                    if (count($his) == 0) {
                        $hisarr = [];
                        $hisarr[] = $_POST['sercon'];
                        $hiscon = array2string($hisarr);
                        $this->goods_sh_db->insert(['userid' => $uid, 'searchHistory' => $hiscon]);
                    } else {
                        $hisarr = string2array($his['searchHistory']);
                        foreach ($hisarr as $k => $v) {
                            if ($_POST['sercon'] == $v) {
                                unset($hisarr[$k]);
                                array_values($hisarr);
                                break;
                            }
                        }
                        if (count($hisarr) < 10) {
                            array_unshift($hisarr, $_POST['sercon']);
                        } else {
                            unset($hisarr[9]);
                            array_unshift($hisarr, $_POST['sercon']);
                        }
                        $hiscon = array2string($hisarr);
                        $this->goods_sh_db->update(['searchHistory' => $hiscon], ['userid' => $uid]);
                    }
                }
            }
        }

        $rid = $_POST['catid'];
        if ($rid != null) {
            $where .= ' and catid in (' . $rid . ')';
        }

        if(!empty($_POST['goods_type'])){
            $where .= ' and goods_type = '.$_POST['goods_type'];
        }
        if(!empty($_POST['shopid'])){
            $where .= ' and shopid = '.$_POST['shopid'];
        }

        $order = ' id desc ';
        $is_sort = $_POST['is_sort'];//销量1 价格2 新品3
        $sort = $_POST['sort'];//排序(1.asc 2.desc)
        if ($is_sort == 1) {
            $order = ' salesnum ' . $sort;
        } else if ($is_sort == 2) {
            $order = ' shop_price ' . $sort;
        } else if ($is_sort == 3) {
            $order = ' addtime ' . $sort;
        }

        $sql = 'SELECT id,goods_name,thumb,summary,market_price,shop_price,salesnum,score_price FROM zy_goods WHERE ' . $where . 'ORDER BY' . $order;
        $page = $_POST['page'] ? $_POST['page'] : '1';
        $info = $this->get_db->multi_listinfo($sql, $page, $pagesize = 10);

        $sqls = 'SELECT COUNT(*) as num FROM zy_goods WHERE ' . $where . 'ORDER BY' . $order;
        $res = $this->goods_db->query($sqls);
        $page = $this->goods_db->fetch_array($res);
        $totalnum = $page[0]['num'];
        $totalpage = ceil($totalnum / 10);

        $result = [
            'status' => 'success',
            'code' => 1,
            'message' => 'OK',
            'data' => $info,
            'page' => [
                'pagesize' => 10,
                'totalpage' => $totalpage,
                'totalnum' => $totalnum
            ]
        ];
        $jg = json_encode($result, JSON_UNESCAPED_UNICODE);
        $jg = stripslashes($jg);
        exit($jg);
    }

    /**
     *商品详情
     */
    public function goodsinfo()
    {
        $gid = $_POST['gid'];

        if (!$gid || !is_numeric($gid)) {
            $result = [
                'status' => 'error',
                'code' => 0,
                'message' => '参数异常！',
            ];
            exit(json_encode($result, JSON_UNESCAPED_UNICODE));
        }

        $where = ' id = ' . $gid . ' and isok = 1 and on_sale = 1 ';
        $info = $this->goods_db->get_one($where);
        if (count($info) == 0) {
            $result = [
                'status' => 'error',
                'code' => -1,
                'message' => '商品不存在或已经下架！',
            ];
            exit(json_encode($result, JSON_UNESCAPED_UNICODE));
        }
        $info['album'] = string2array($info['album']);
        if ($info['isspec'] == 1) {
            $where = ' goodsid = ' . $gid;
            $sinfo = $this->goods_specs_db->select($where, 'id,specid,specids,specprice,specstock,status', '', $order = ' id ASC ');
            $info['specdata'] = $sinfo;
        }
        //计算运费
        $data = [
            "tid" => $info['template_id'],
            "province" => $_POST['province'],
            "weight" => $info['weight'],
            "volume" => $info['volume'],
            "count" => 1,
        ];
        $url = APP_PATH . 'index.php?m=freight&c=freightApi&a=getfreightinfo';
        $return = json_decode($this->_crul_post($url, $data), true);
        $yunfei = $return['data'];
        $result = [
            'status' => 'success',
            'code' => 1,
            'message' => 'OK',
            'data' =>
                [
                    'info' => $info,
                    'yunfei' => $yunfei,
                ]
        ];
        $jg = json_encode($result, JSON_UNESCAPED_UNICODE);
        $jg = stripslashes($jg);

        exit($jg);


    }

    /**
     *分类栏目
     * GET
     */
    public function allcat()
    {
        require('classes/PHPTree.class.php');//加载树形结构类
        $where = '1';
        $infos = $this->goodscat_db->select($where, 'id,cate_name,cate_img,pid', '', $order = 'sort ASC,id ASC');
        $data = catetree($infos);

        $r = PHPTree::makeTree($data, array());
        $rdata = [

            'status' => 'success',
            'code' => 1,
            'message' => 'OK',
            'data' => $r
        ];
        $content = json_encode((object)$rdata, JSON_UNESCAPED_UNICODE);
        $content = preg_replace("/cate_name/", "name", $content);
        $content = preg_replace("/cate_img/", "img", $content);

        exit($content);
    }

    /**
     *获取用户商品搜索记录
     * param:uid(用户id)
     */
    public function goods_sh()
    {
        $uid = $_POST['uid'];

        if (!$uid) {
            $result = [
                'status' => 'error',
                'code' => 0,
                'message' => '请先登录！',
            ];
            exit(json_encode($result, JSON_UNESCAPED_UNICODE));
        }

        $info = $this->goods_sh_db->get_one(['userid' => $uid]);
        $hisarr = string2array($info['searchHistory']);
        $result = [
            'status' => 'success',
            'code' => 1,
            'message' => 'OK',
            'data' => [
                'hiscon' => $hisarr,
            ]
        ];

        exit(json_encode($result, JSON_UNESCAPED_UNICODE));
    }


    /**
     *购物车修改操作
     */
    public function operacars()
    {
        $uid = $_POST['uid'];
        if (!$uid) {
            $result = [
                'status' => 'error',
                'code' => 0,
                'message' => '请先登录！',
            ];
            exit(json_encode($result, JSON_UNESCAPED_UNICODE));
        }
        if (!$_POST['gid'] || !isset($_POST['spec']) || !$_POST['cnum']) {
            $result = [
                'status' => 'error',
                'code' => -1,
                'message' => '访问受限，缺少必要参数',
            ];
            exit(json_encode($result, JSON_UNESCAPED_UNICODE));
        }

        $check = checkspec($_POST['gid'], $_POST['spec']);
        if ($check['code'] == -2) {
            exit(json_encode($check, JSON_UNESCAPED_UNICODE));
        }

        if ($check['data']['stock'] < $_POST['cnum']) {
            $result = [
                'status' => 'error',
                'code' => -3,
                'message' => '加入购物车失败，商品库存不足',
            ];
            exit(json_encode($result, JSON_UNESCAPED_UNICODE));
        }
        $this->goodscarts_db->update(['cartnum' =>$_POST['cnum']], ['userid' => $uid, 'goodsspecId' => $_POST['spec'], 'goodsid' => $_POST['gid']]);

        $result = [
            'status' => 'success',
            'code' => 1,
            'message' => '操作成功',
        ];
        exit(json_encode($result, JSON_UNESCAPED_UNICODE));
    }


    /**
     *加入购物车
     */
    public function addbuycart()
    {
        $uid = $_POST['uid'];

        if (!$uid) {
            $result = [
                'status' => 'error',
                'code' => 0,
                'message' => '请先登录！',
            ];
            exit(json_encode($result, JSON_UNESCAPED_UNICODE));
        }
        if (!$_POST['gid'] || !isset($_POST['spec']) || !$_POST['cnum']) {
            $result = [
                'status' => 'error',
                'code' => -1,
                'message' => '访问受限，缺少必要参数',
            ];
            exit(json_encode($result, JSON_UNESCAPED_UNICODE));
        }

        $check = checkspec($_POST['gid'], $_POST['spec']);
        if ($check['code'] == -2) {
            exit(json_encode($check, JSON_UNESCAPED_UNICODE));
        }

        if ($check['data']['stock'] < $_POST['cnum']) {
            $result = [
                'status' => 'error',
                'code' => -3,
                'message' => '加入购物车失败，商品库存不足',
            ];
            exit(json_encode($result, JSON_UNESCAPED_UNICODE));
        }

        $info = $this->goodscarts_db->get_one(['userid' => $uid, 'goodsspecId' => $_POST['spec'], 'goodsid' => $_POST['gid']]);

        if (count($info) == 0) {
            $data = [];
            $data['userid'] = $uid;
            $data['goodsid'] = $_POST['gid'];
            $data['goodsspecid'] = $_POST['spec'];
            $data['ischeck'] = 1;
            $data['cartnum'] = $_POST['cnum'];
            $this->goodscarts_db->insert($data);
        } else {
            $this->goodscarts_db->update(['cartnum' => '+=' . $_POST['cnum']], ['userid' => $uid, 'goodsspecId' => $_POST['spec'], 'goodsid' => $_POST['gid']]);
        }

        $result = [
            'status' => 'success',
            'code' => 1,
            'message' => '操作成功',
        ];
        exit(json_encode($result, JSON_UNESCAPED_UNICODE));
    }


    /**
     *删除购物车商品
     */
    public function delcars()
    {
        $uid = $_POST['uid'];
        if (!$uid) {
            $result = [
                'status' => 'error',
                'code' => 0,
                'message' => '请先登录！',
            ];
            exit(json_encode($result, JSON_UNESCAPED_UNICODE));
        }
        if (!$_POST['cid']) {
            $result = [
                'status' => 'error',
                'code' => -1,
                'message' => '访问受限，缺少必要参数',
            ];
            exit(json_encode($result, JSON_UNESCAPED_UNICODE));
        }

        if (is_numeric($_POST['cid'])) {
            $op = $this->goodscarts_db->delete(['userid' => $uid, 'id' => $_POST['cid']]);
        } else {
            $op = $this->goodscarts_db->delete(' id in (' . $_POST['cid'] . ') and userid = ' . $uid . ' and ischeck <> 2');
        }

        if ($op) {
            $result = [
                'status' => 'success',
                'code' => 1,
                'message' => '删除商品成功',
            ];
            exit(json_encode($result, JSON_UNESCAPED_UNICODE));
        }
        $result = [
            'status' => 'success',
            'code' => -2,
            'message' => '删除商品失败',
        ];
        exit(json_encode($result, JSON_UNESCAPED_UNICODE));
    }


    /**
     *购物车数据
     */
    public function getcarts()
    {
        $uid = $_POST['uid'];

        if (!$uid) {
            $result = [
                'status' => 'error',
                'code' => 0,
                'message' => '请先登录！',
            ];
            exit(json_encode($result, JSON_UNESCAPED_UNICODE));
        }

        $sql = 'SELECT b.id, b.shopid, b.thumb, b.goods_name, b.shop_price, b.stock, a.id as cartid, a.goodsspecid, a.cartnum, c.specprice, c.specstock, c.specid, c.specids FROM phpcms_goodscarts a INNER JOIN phpcms_goods b ON a.goodsid = b.id and a.userid = ' . $uid . ' and a.ischeck <> 2 LEFT OUTER JOIN phpcms_goods_specs c ON a.goodsspecid = c.specid and c.shopid = b.shopid and c.goodsid = b.id';
        // $sql = 'SELECT a.id, a.thumb, a.goods_name, a.shop_price, a.stock, b.goodsspecid, b.cartnum, c.specprice, c.specid, c.specids FROM phpcms_goods a LEFT OUTER JOIN phpcms_goodscarts b ON a.id = b.goodsid and b.userid = '.$uid.' LEFT OUTER JOIN phpcms_goods_specs c ON a.id = c.goodsid and c.shopid = '.$uid;
        $sqls = 'SELECT b.shopid as id FROM phpcms_goodscarts a INNER JOIN phpcms_goods b ON a.goodsid = b.id and a.userid = ' . $uid . ' LEFT OUTER JOIN phpcms_goods_specs c ON a.goodsspecid = c.specid and c.shopid = ' . $uid . ' group by b.shopid ';
        $page = $_POST['page'] ? $_POST['page'] : '1';
        $info = $this->get_db->multi_listinfo($sql, $page, 88888888);
        $infos = $this->get_db->multi_listinfo($sqls, $page, 88888888);
        $idarr = '';
        foreach ($infos as $key => $value) {
            if (empty($idarr)) {
                $idarr = $value['id'];
            } else {
                $idarr .= ',' . $value['id'];
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
        $narr = [];
        foreach ($info as $k => $v) {
            if (!isset($narr[$v['shopid']])) {
                $narr[$v['shopid']] = [
                    'shopid' => $v['shopid'],
                    'shopname' => $snamarr[$v['shopid']]['shopname'],
                ];
            }

            if ($v['goodsspecid'] != 0) {
                $jg = $v['specprice'];
            } else {
                $jg = $v['shop_price'];
            }
            $narr[$v['shopid']]['cartinfo'][] = [
                'cartid' => $v['cartid'],
                'goodsid' => $v['id'],
                'goodsname' => $v['goods_name'],
                'goodsimg' => $v['thumb'],
                'goodsspec' => $v['specid'],
                'goodsspecs' => $v['specids'],
                'goodsprice' => $jg,
                'cartnum' => $v['cartnum'],
            ];
        }

        $result = [
            'status' => 'success',
            'code' => 1,
            'message' => 'OK',
            'data' => [
                'carts' => array_values($narr),
                'uid' => $uid
            ],
        ];
        $jg = json_encode($result, JSON_UNESCAPED_UNICODE);
        $jg = stripslashes($jg);
        exit($jg);
    }


    /**
     *立即购买前置操作
     */
    public function buynow()
    {
        $uid = $_POST['uid'];
        if (!$uid) {
            $result = [
                'status' => 'error',
                'code' => 0,
                'message' => '请先登录！',
            ];
            exit(json_encode($result, JSON_UNESCAPED_UNICODE));
        }
        if (!$_POST['gid'] || !isset($_POST['spec']) || !$_POST['cnum']) {
            $result = [
                'status' => 'error',
                'code' => -1,
                'message' => '访问受限，缺少必要参数',
            ];
            exit(json_encode($result, JSON_UNESCAPED_UNICODE));
        }

        $check = checkspec($_POST['gid'], $_POST['spec']);
        if ($check['code'] == -2) {
            exit(json_encode($check, JSON_UNESCAPED_UNICODE));
        }

        if ($check['data']['stock'] < $_POST['cnum']) {
            $result = [
                'status' => 'error',
                'code' => -3,
                'message' => '购买失败，商品库存不足',
            ];
            exit(json_encode($result, JSON_UNESCAPED_UNICODE));
        }

        if ($check['data']['stock'] < $_POST['cnum']) {
            $result = [
                'status' => 'error',
                'code' => -3,
                'message' => '购买失败，商品库存不足',
            ];
            exit(json_encode($result, JSON_UNESCAPED_UNICODE));
        }

        $info = $this->goodscarts_db->get_one(['userid' => $uid, 'ischeck' => 2]);

        if (count($info) == 0) {
            $data = [];
            $data['userid'] = $uid;
            $data['goodsid'] = $_POST['gid'];
            $data['goodsspecid'] = $_POST['spec'];
            $data['ischeck'] = 2;
            $data['cartnum'] = $_POST['cnum'];
            $this->goodscarts_db->insert($data);
        } else {
            $data = [];
            $data['goodsid'] = $_POST['gid'];
            $data['goodsspecid'] = $_POST['spec'];
            $data['cartnum'] = $_POST['cnum'];
            $this->goodscarts_db->update($data, ['userid' => $uid, 'ischeck' => 2]);
        }

        $result = [
            'status' => 'success',
            'code' => 1,
            'message' => 'OK',
        ];
        exit(json_encode($result, JSON_UNESCAPED_UNICODE));
    }

    /**
     *订单结算预览
     */
    public function settlement()
    {
        $uid = $_POST['uid'];
        $method = $_POST['met'];
        $cids = $_POST['cids'];

        if (!$uid) {
            $result = [
                'status' => 'error',
                'code' => 0,
                'message' => '请先登录！',
            ];
            exit(json_encode($result, JSON_UNESCAPED_UNICODE));
        }
        if ($method == 1) {
            $info = $this->goodscarts_db->select(['userid' => $uid, 'ischeck' => 2]);
            if (count($info) == 0) {
                $result = [
                    'status' => 'error',
                    'code' => -1,
                    'message' => '访问受限，参数无效',
                ];
                exit(json_encode($result, JSON_UNESCAPED_UNICODE));
            }
            $cids = $info[0]['id'];
        } else {
            if (empty($cids)) {
                $result = [
                    'status' => 'error',
                    'code' => -2,
                    'message' => '访问受限，缺少参数',
                ];
                exit(json_encode($result, JSON_UNESCAPED_UNICODE));
            }
            $arr = explode(',', $cids);
            $where = ' userid = ' . $uid . ' and id in(' . $cids . ') and ischeck <> 2 ';
            $info = $this->goodscarts_db->select($where);
            if (count($info) != count($arr)) {
                $result = [
                    'status' => 'error',
                    'code' => -1,
                    'message' => '访问受限，参数无效',
                ];
                exit(json_encode($result, JSON_UNESCAPED_UNICODE));
            }
        }


        $sql = 'SELECT b.id, b.shopid, b.thumb, b.goods_name, b.shop_price, b.stock, a.id as cartid, a.goodsspecid, a.cartnum, c.specprice, c.specstock, c.specid, c.specids FROM phpcms_goodscarts a INNER JOIN phpcms_goods b ON a.goodsid = b.id and a.userid = ' . $uid . ' and a.id in(' . $cids . ') LEFT OUTER JOIN phpcms_goods_specs c ON a.goodsspecid = c.specid and c.shopid = b.shopid and c.goodsid = b.id';

        $sqls = 'SELECT b.shopid as id FROM phpcms_goodscarts a INNER JOIN phpcms_goods b ON a.goodsid = b.id and a.userid = ' . $uid . ' and a.id in(' . $cids . ') LEFT OUTER JOIN phpcms_goods_specs c ON a.goodsspecid = c.specid and c.shopid = ' . $uid . ' group by b.shopid ';

        $page = $_POST['page'] ? $_POST['page'] : '1';
        $info = $this->get_db->multi_listinfo($sql, $page, 88888888);
        $infos = $this->get_db->multi_listinfo($sqls, $page, 88888888);
        $idarr = '';
        foreach ($infos as $key => $value) {
            if (empty($idarr)) {
                $idarr = $value['id'];
            } else {
                $idarr .= ',' . $value['id'];
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
        $narr = [];
        $total = 0;
        $tnum = 0;
        $yunfei = 0;
        foreach ($info as $k => $v) {
            if (!isset($narr[$v['shopid']])) {
                $narr[$v['shopid']] = [
                    'shopid' => $v['shopid'],
                    'shopname' => $snamarr[$v['shopid']]['shopname'],
                    'stprice' => 0,
                    'stnum' => 0
                ];
            }

            if ($v['goodsspecid'] != 0) {
                $jg = $v['specprice'];
            } else {
                $jg = $v['shop_price'];
            }
            $narr[$v['shopid']]['stprice'] += $jg * $v['cartnum'];
            $narr[$v['shopid']]['stnum'] += $v['cartnum'];
            $total += $jg * $v['cartnum'];
            $tnum += $v['cartnum'];
            $narr[$v['shopid']]['cartinfo'][] = [
                'cartid' => $v['cartid'],
                'goodsid' => $v['id'],
                'goodsname' => $v['goods_name'],
                'goodsimg' => $v['thumb'],
                'goodsspec' => $v['specid'],
                'goodsspecs' => $v['specids'],
                'goodsprice' => $jg,
                'cartnum' => $v['cartnum'],
            ];

            //计算运费
            $wheres = ' id = ' . $v['id'] . ' and isok = 1 and on_sale = 1 ';
            $goodsinfo = $this->goods_db->get_one($wheres);
            //默认地址
            $res = $this->zyaddr_db->get_one(array("userid"=>$uid,"default"=>1));
            $datas = [
                "tid" => $goodsinfo['template_id'],
                "province" => $res['province'],
                "weight" => $goodsinfo['weight'],
                "volume" => $goodsinfo['volume'],
                "count" => $v['cartnum'],
            ];
            $url = APP_PATH . 'index.php?m=freight&c=freightApi&a=getfreightinfo';
            $return = json_decode($this->_crul_post($url, $datas), true);
            $yunfei += $return['data'];
        }

        $result = [
            'status' => 'success',
            'code' => 1,
            'message' => 'OK',
            'data' => [
                'shops' => array_values($narr),
                'uid' => $uid,
                'totalprice' => $total,
                'yunfei' => $yunfei,
                'totalnum' => $tnum
            ],
        ];
        $jg = json_encode($result, JSON_UNESCAPED_UNICODE);
        $jg = stripslashes($jg);
        exit($jg);
    }

    /**
     *订单确认订单生成
     */
    public function sureMakeOrder()
    {
        $is_group = $_POST['is_group'];//是否团购(0.不是 1.是)
        $uid = $_POST['uid'];
        $method = $_POST['met'];
        $cids = $_POST['cids'];
        $addid = $_POST['address'];
        $freight = $_POST['freight'];//运费

        if (!$uid) {
            $result = [
                'status' => 'error',
                'code' => 0,
                'message' => '请先登录！',
            ];
            exit(json_encode($result, JSON_UNESCAPED_UNICODE));
        }
        if ($method == 1) {
            $info = $this->goodscarts_db->select(['userid' => $uid, 'ischeck' => 2]);
            if (count($info) == 0) {
                $result = [
                    'status' => 'error',
                    'code' => -1,
                    'message' => '访问受限，参数无效',
                ];
                exit(json_encode($result, JSON_UNESCAPED_UNICODE));
            }
            $cids = $info[0]['id'];
        } else {
            if (empty($cids)) {
                $result = [
                    'status' => 'error',
                    'code' => -2,
                    'message' => '访问受限，缺少参数',
                ];
                exit(json_encode($result, JSON_UNESCAPED_UNICODE));
            }
            $arr = explode(',', $cids);
            $where = ' userid = ' . $uid . ' and id in(' . $cids . ') and ischeck <> 2 ';
            $info = $this->goodscarts_db->select($where);
            if (count($info) != count($arr)) {
                $result = [
                    'status' => 'error',
                    'code' => -1,
                    'message' => '访问受限，参数无效',
                ];
                exit(json_encode($result, JSON_UNESCAPED_UNICODE));
            }
        }

        if (empty($addid)) {
            $result = [
                'status' => 'error',
                'code' => -5,
                'message' => '访问受限，缺少参数',
            ];
            exit(json_encode($result, JSON_UNESCAPED_UNICODE));
        }

        $token_url = APP_PATH . 'index.php?m=zyaddr&c=zyaddr_api&a=getaddr';
        $data = array(
            'userid' => $uid,
            'id' => $addid,
        );
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

        $province = $rs['data']['province']/*$_POST['province']*/
        ;  //收货地址省
        $city = $rs['data']['city']/*$_POST['city']*/
        ;//收货地址市
        $area = $rs['data']['district']/*$_POST['area']*/
        ;//收货地址区
        $address = $rs['data']['address']/*$_POST['address']*/
        ; //详细地址
        $lx_mobile = $rs['data']['phone']/* $_POST['lx_mobile']*/
        ; //联系电话
        $lx_name = $rs['data']['name']/*$_POST['lx_name']*/
        ; //联系人
        $lx_code = '　'/*$_POST['lx_code']*/
        ; //联系邮编
        $mes = $_POST['note'];//用户留言

        if (empty($province) || empty($city) || empty($area) || empty($address)) {
            $result = [
                'status' => 'error',
                'code' => -3,
                'message' => '地址信息不全',
            ];
            exit(json_encode($result, JSON_UNESCAPED_UNICODE));
        }

        if (empty($lx_mobile) || empty($lx_name) || empty($lx_code)) {
            $result = [
                'status' => 'error',
                'code' => -4,
                'message' => '联系人信息不全',
            ];
            exit(json_encode($result, JSON_UNESCAPED_UNICODE));
        }


        $sql = 'SELECT b.id, b.shopid, b.thumb, b.goods_name, b.shop_price, b.stock, a.id as cartid, a.goodsspecid, a.cartnum, c.specprice, c.specstock, c.specid, c.specids FROM phpcms_goodscarts a INNER JOIN phpcms_goods b ON a.goodsid = b.id and a.userid = ' . $uid . ' and a.id in(' . $cids . ') LEFT OUTER JOIN phpcms_goods_specs c ON a.goodsspecid = c.specid and c.shopid = b.shopid and c.goodsid = b.id';

        $sqls = 'SELECT b.shopid as id FROM phpcms_goodscarts a INNER JOIN phpcms_goods b ON a.goodsid = b.id and a.userid = ' . $uid . ' and a.id in(' . $cids . ') LEFT OUTER JOIN phpcms_goods_specs c ON a.goodsspecid = c.specid and c.shopid = ' . $uid . ' group by b.shopid ';

        $page = $_POST['page'] ? $_POST['page'] : '1';
        $info = $this->get_db->multi_listinfo($sql, $page, 88888888);

        $narr = [];
        $total = 0;
        // $tnum = 0;
        foreach ($info as $k => $v) {
            if (!isset($narr[$v['shopid']])) {
                $narr[$v['shopid']] = [
                    'shopid' => $v['shopid'],
                    'stprice' => 0,
                    'stnum' => 0
                ];
            }

            if ($v['goodsspecid'] != 0) {
                $jg = $v['specprice'];
            } else {
                $jg = $v['shop_price'];
            }

            $narr[$v['shopid']]['stprice'] += $jg * $v['cartnum'];
            $narr[$v['shopid']]['stnum'] += $v['cartnum'];
            $total += $jg * $v['cartnum'];
            $narr[$v['shopid']]['cartinfo'][] = [
                'cartid' => $v['cartid'],
                'goodsid' => $v['id'],
                'goodsname' => $v['goods_name'],
                'goodsimg' => $v['thumb'],
                'goodsspec' => $v['specid'],
                'goodsspecs' => $v['specids'],
                'goodsprice' => $jg,
                'cartnum' => $v['cartnum'],
            ];
        }

        $token_url = APP_PATH . 'index.php?m=zyorder&c=zyorder_api&a=addorder';

        $data = array(
            'userid' => $uid,
            'province' => $province,
            'city' => $city,
            'area' => $area,
            'address' => $address,
            'lx_mobile' => $lx_mobile,
            'lx_name' => $lx_name,
            'lx_code' => $lx_code,
            'usernote' => $mes,
            'is_group' => $is_group,
            'freight' => $freight,
            'shopdata' => $narr
        );
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

        if ($rs['data']['code'] != 1) {

        }
        if ($where) {
            $this->goodscarts_db->delete($where);
        }
        $result = [
            'status' => 'success',
            'code' => 1,
            'message' => 'OK',
            'data' => $rs['data']
        ];
        exit(json_encode($result, JSON_UNESCAPED_UNICODE));
    }


    /**
     *添加商品获取商品类型相关规格数据
     */
    public function goodstypedata()
    {
        $fid = $_POST['fid'];
        if (!$fid || !is_numeric($fid)) {
            $result = [
                'status' => 'error',
                'code' => 0,
                'message' => '参数异常！',
            ];
            exit(json_encode($result, JSON_UNESCAPED_UNICODE));
        }
        $info = getspec($fid);
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
     *获取所有商品类型
     */
    public function getgoodstype()
    {
        $info = $this->goodstype_db->select('1', 'id,type_name', '', $order = 'id ASC');

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
     *订单支付完成计算销量库存
     */
    public function sales_balance()
    {

        $_userid = param::get_cookie('_userid');
        $userid = $_POST['uid'];
        $oids = $_POST['oids'];

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

        if (!$oids) {
            $result = [
                'status' => 'error',
                'code' => -1,
                'message' => '缺少必要参数！',
            ];
            exit(json_encode($result, JSON_UNESCAPED_UNICODE));
        }


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

}

?>