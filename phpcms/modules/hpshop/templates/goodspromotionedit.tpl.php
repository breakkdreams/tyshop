<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>

<link rel="stylesheet" type="text/css" href="<?php echo APP_PATH ?>statics/funds/layui/css/layui.css">
<script src="<?php echo APP_PATH ?>statics/funds/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH ?>member_common.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH ?>formvalidator.js" charset="UTF-8"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH ?>formvalidatorregex.js" charset="UTF-8"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $.formValidator.initConfig({
            autotip: true, formid: "myform", onerror: function (msg) {
            }
        });
    });

</script>


<style type="text/css">
    .table_form th {
        text-align: left;
    }

    .input-text {
        background: #FFF;
    }

</style>

<form name="myform" id="myform" action="" method="post">
    <div class="pad-10">
        <div class="common-form">
            <div id="div_setting_2" class="contentList">

                <fieldset>
                    <legend>基本信息</legend>
                    <table width="100%" class="table_form" id="mytable">
                        <tbody>

                        <tr>
                            <th width="125">商品类型</th>
                            <td>
                                <select name="goods_type" id="goods_type" required="required" onchange="changeType()">
                                    <option value="">请选择商品类型</option>
                                    <option value="0" <?php if ($info['goods_type']==0) {?>selected<?php }?>  >普通商品</option>
                                    <option value="1" <?php if ($info['goods_type']==1) {?>selected<?php }?>  >促销商品</option>
                                    <option value="2" <?php if ($info['goods_type']==2) {?>selected<?php }?>  >团购商品</option>
                                </select>
                            </td>
                        </tr>

                        <tr id="s_time" hidden>
                            <th width="125">开始时间</th>
                            <td>
                                <?php echo form::date('starttime',date('Y-m-d',$info['starttime']))?>
                            </td>
                        </tr>

                        <tr id="e_time" hidden>
                            <th width="125">结束时间</th>
                            <td>
                                <?php echo form::date('endtime',date('Y-m-d',$info['endtime']))?>
                            </td>
                        </tr>

                        <tr id="p_price" hidden>
                            <th width="125">促销价</th>
                            <td><input type="number" name="promotion_price" value="<?php echo $info['promotion_price'] ?>" class="input-text" /></td>
                        </tr>
                        <tr id="g_price" hidden>
                            <th width="125">团购价</th>
                            <td><input type="number" name="group_price" value="<?php echo $info['group_price'] ?>" class="input-text" /></td>
                        </tr>
                        <tr id="p_number" hidden>
                            <th width="125">成团人数</th>
                            <td><input type="number" name="person_number" value="<?php echo $info['person_number'] ?>" class="input-text" /></td>
                        </tr>
                        <tr id="w_time" hidden>
                            <th width="125">等待成团时间(小时)</th>
                            <td><input type="number" name="waiting_time" value="<?php echo $info['waiting_time'] ?>" class="input-text" /></td>
                        </tr>

                        </tbody>
                    </table>
                </fieldset>
                <div class="bk15"></div>
            </div>
            <input class="dialog" name="dosubmit" id="dosubmit" type="submit" value="确认"/>
        </div>
    </div>
    </div>
</form>

</body>
</html>

<script language="javascript" type="text/javascript" src="<?php echo JS_PATH ?>content_addtop.js"></script>
<script type="text/javascript">
    changeType();
    function changeType() {
        let goodstype = $("#goods_type").val();
        if(goodstype == 0){
            $("#s_time").hide();
            $("#e_time").hide();
            $("#p_price").hide();
            $("#g_price").hide();
            $("#p_number").hide();
            $("#w_time").hide();
        }else if(goodstype == 1){
            $("#s_time").show();
            $("#e_time").show();
            $("#p_price").show();
            $("#g_price").hide();
            $("#p_number").hide();
            $("#w_time").hide();
        }else if(goodstype == 2){
            $("#s_time").show();
            $("#e_time").show();
            $("#p_price").hide();
            $("#g_price").show();
            $("#p_number").show();
            $("#w_time").show();
        }
    }

</script>
