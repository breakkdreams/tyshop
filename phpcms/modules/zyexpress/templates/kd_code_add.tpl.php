<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<form name="myform" id="myform" action="" method="post" >
	<div class="pad-10">
		<div class="common-form">
			<div id="div_setting_2" class="contentList">
				<fieldset>
				<legend>基本信息</legend>
				<table width="100%" class="table_form" id="mytable">
					<tbody>
                        <tr>
                            <th width="125">快递公司名称</th>
                            <td><input style="width: 50%;" type="text" name="company" id="company" class="input-text" required=""></td>
                        </tr>
						<tr>
							<th width="125">快递公司编码</th>
							<td><input style="width: 50%;" type="text" name="code" id="code" class="input-text" required=""></td>
						</tr>
					</tbody>
				</table>
				</fieldset>
			</div>
			<input class="dialog" name="dosubmit" id="dosubmit" type="submit" value="确认"/>

		</div>
	</div>
</form>
</body>
</html>