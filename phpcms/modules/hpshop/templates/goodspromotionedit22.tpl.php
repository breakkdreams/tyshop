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
                            <th width="125">商品选择</th>
                            <td>
                                <select name="goodsid" required="required">
                                    <option value="">请选择商品</option>
                                    <?php
                                    if (is_array($info)) {
                                        foreach ($info as $v) {
                                            ?>
                                            <option value="<?php echo $v['id']?>" <?php if ($infos['goodsid']==$v['id']) {?>selected<?php }?>  ><?php echo $v['goods_name']?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <th width="125">开始时间</th>
                            <td>
                                <?php echo form::date('start_time',date('Y-m-d',$infos['starttime']))?>
                            </td>
                        </tr>

                        <tr>
                            <th width="125">结束时间</th>
                            <td>
                                <?php echo form::date('end_time',date('Y-m-d',$infos['endtime']))?>
                            </td>
                        </tr>

                        <tr>
                            <th width="125">促销价</th>
                            <td><input type="number" name="promotion_price" value="<?php echo $infos['promotion_price'] ?>" class="input-text" required="required"/></td>
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

</script>
