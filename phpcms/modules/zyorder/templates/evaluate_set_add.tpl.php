<?php 
	defined('IN_ADMIN') or exit('No permission resources.');
	include $this->admin_tpl('header', 'admin');
?>

<div class="pad-10">
<div class="common-form">
<form  action="?m=zyorder&c=order&a=evaluate_set_add" method="post" id="myform">
<table width="100%" class="table_form">
<tr>
<td width="120"><?php echo '配置名称：'?></td> 
<td><input type="text" name="name" size="25" class="input-text"></td> 
</tr>
<tr>
<td width="120"><?php echo '配置字段：'?></td> 
<td><input type="text" name="value" size="25" class="input-text"></td> 
</tr>
</table>
<div class="bk15"></div>
</form>
</div>
</body>
</html>
