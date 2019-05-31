<?php 
	defined('IN_ADMIN') or exit('No permission resources.');
	include $this->admin_tpl('header', 'admin');
?>

<div class="subnav">
    <div class="content-menu ib-a blue line-x">
    <a class="add fb"><em>请输入物流信息</em></a>　    
    </div>
</div>



<div class="pad-10">
<div class="common-form">
<form name="myform" action="?m=zyorder&c=order&a=order_logistics_add" method="post" id="myform">
<table width="100%" class="table_form">
<tr>
<td width="120"><?php echo '快递公司：'?></td> 
<td><input type="text" name="name" id="name" size="25" class="input-text"></td> 
</tr>

<tr>
<td width="120"><?php echo '公司代码：'?></td> 
<td><input type="text" name="value" id="value" size="25" class="input-text"></td> 
</tr>


<tr>

</tr>
</table>
<div class="bk15"></div>
<input name="dosubmit" type="submit" value="<?php echo L('submit')?>" class="button" id="dosubmit">
</form>
</div>
</body>
</html>
