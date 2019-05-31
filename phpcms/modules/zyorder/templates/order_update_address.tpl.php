<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<style type="text/css">
.table_form th{text-align: left;}
</style>

<form name="myform" id="myform" action="?m=zyorder&c=order&a=order_update_address" method="post">
<input type="hidden" name="id" value="<?php echo $orderid?>" >
<div class="pad-10">
<div class="col-tab">
	<div id="div_setting_2" class="contentList pad-10">
    	<fieldset>
        <legend>收货地址信息</legend>
		<table width="100%" class="table_form">
			<tbody>
                <tr>
					<th><strong>联系人</strong></th>
					<td><input type="text" value="<?php echo $info['lx_name']?>" name="lx_name" style="width:220px"></td>
				</tr>
                <tr>
                    <th><strong>联系号码</strong></th>
                    <td><input type="text" value="<?php echo $info['lx_mobile']?>" name="lx_mobile" style="width:220px"></td>
                </tr>
                <tr>
					<th><strong>收货地址(省)</strong></th>
					<td><input type="text" value="<?php echo $info['province']?>" name="province" style="width:220px"></td>
				</tr>
                <tr>
                    <th><strong>收货地址(市)</strong></th>
                    <td><input type="text" value="<?php echo $info['city']?>" name="city" style="width:220px"></td>
                </tr>
                <tr>
                    <th><strong>收货地址(区)</strong></th>
                    <td><input type="text" value="<?php echo $info['area']?>" name="area" style="width:220px"></td>
                </tr>
                <tr>
                    <th><strong>收货地址(详情地址)</strong></th>
                    <td><input type="text" value="<?php echo $info['address']?>" name="address" style="width:220px"></td>
                </tr>
			</tbody>
		</table>
        </fieldset>
        <div class="bk15"></div>

	</div>
    <input name="dosubmit" type="submit" id="dosubmit" value="<?php echo L('submit')?>" class="dialog">

</div>
</div>
</form>

</div>

</body>
</html>
