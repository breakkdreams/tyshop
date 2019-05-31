<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>formvalidator.js" charset="UTF-8"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>formvalidatorregex.js" charset="UTF-8"></script>

<style type="text/css">
.table_form th{text-align: left;}
</style>


<form name="myform" id="myform" action="?m=zymember&c=zymember&a=store_audit" method="post">
<input type="hidden" name="userid" value="<?php echo $_GET['userid']?>"></input>
<input type="hidden" name="store_audit" value="3"></input>

<div class="pad-10">
<div class="table-list">
<div class="common-form">
	<div class="contentList">

		<fieldset>
		<legend>驳回信息</legend>
        <table width="100%" class="table_form">
			<tbody>
                <tr>
					<th>驳回理由</th>  
					<td><textarea name="store_audit_on" rows="8" cols="30"></textarea></td>
				</tr>
			</tbody>
		</table>
        </fieldset>


	</div>
    <input name="dosubmit" id="dosubmit" type="submit" value="<?php echo L('submit')?>" class="dialog">

</div>
</div>

</div>
</div>
</form>

</body>
</html>
