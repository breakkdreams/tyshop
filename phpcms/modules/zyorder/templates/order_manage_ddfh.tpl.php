<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<style type="text/css">
.table_form th{text-align: left;}
</style>

<form name="myform" id="myform" action="?m=zyorder&c=order&a=order_manage_ddfh" method="post">
<input type="hidden" name="id" value="<?php echo $orderid?>" >
<div class="pad-10">
<div class="col-tab">
	<div id="div_setting_2" class="contentList pad-10">
    	<fieldset>
        <legend>物流单号信息</legend>
		<table width="100%" class="table_form">
			<tbody>
				<tr>
					<th width="80"><strong>快递公司</strong></th>
					<td>
                      
                        <select name="shippercode" style="width:220px">
                            <option value ="">-请选择快递公司-</option>
                        <?php
                        foreach ($infok as $vx) { ?>

                            <option value ="<?php echo $vx['code'] ?>" label="<?php echo $vx['company'] ?>"></option>
						<?php
                        }
                          ?>


                        </select>
                      </td>
				</tr>
                <tr>
					<th><strong>快递单号</strong></th>
					<td><input type="text" name="logistics_order" style="width:220px"></td>
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
