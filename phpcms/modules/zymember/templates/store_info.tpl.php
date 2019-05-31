<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<style type="text/css">
.table_form th{text-align: left;}
</style>

<form name="myform" id="myform" action="?m=zymember&c=member&a=store_info" method="post">
<div class="pad-10">
<div class="table-list">
<div class="common-form">
	<div id="div_setting_2" class="contentList">

    	<fieldset>
        <legend>基本信息</legend>
		<table width="100%" class="table_form">
			<tbody>
				<tr>
					<th width="120">店铺名称</th>
					<td><?php echo $member['shopname']?></td>
				</tr>
                <tr>
					<th>店铺logo</th>
					<td><img src="<?php echo $member['store_logo']?>" height="90" width="90"></td>
				</tr>
				<?php if($member['store_audit']==3){?>
				<tr>
					<th width="120">驳回理由</th>
					<td><?php echo $member['store_audit_no']?></td>
				</tr>		
				<?php }?>		
			</tbody>
		</table>
        </fieldset>
        <div class="bk15"></div>

	</div>
<input type="button" class="dialog" name="dosubmit" id="dosubmit" onclick="window.top.art.dialog({id:'view'}).close();"/>

</div>
</div>

</div>
</div>
</form>

</body>
</html>
