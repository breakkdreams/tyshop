<?php 
	defined('IN_ADMIN') or exit('No permission resources.');
	include $this->admin_tpl('header', 'admin');
?>


<div class="pad-10">
<div class="common-form">
<form name="myform" action="?m=zyorder&c=order&a=order_logistics_edit" method="post" id="myform">
<input type="hidden" name="id" id="id" size="25" value="<?php echo $id ?>" class="input-text">
<table width="100%" class="table_form">
<tr>
<td width="120"><?php echo '快递公司：'?></td> 
<td><input type="text" name="name" id="name" size="25" value="<?php echo $info['name'] ?>" class="input-text"></td> 
</tr>

<tr>
<td width="120"><?php echo '公司代码：'?></td> 
<td><input type="text" name="value" id="value" size="25" value="<?php echo $info['value'] ?>" class="input-text"></td> 
</tr>


<tr>

</tr>
</table>
<div class="bk15"></div>
<input class="dialog" name="dosubmit" id="dosubmit" type="submit" value="确认"/>
</form>
</div>
</body>
</html>
