<?php
defined('IN_ADMIN') or exit('No permission resources.');
$show_dialog = 1;
include $this->admin_tpl('header', 'admin');
?>

<div class="pad-10">

<div class="explain-col"><div style="text-align: center;font-size:20px;">选择日期删除表中以前所有的数据！！！<font color="red">(足迹/搜索记录)</font></div></div>
<div class="bk10"></div>

<form name="myform" id="myform" action="?m=zyorder&c=order&a=order_clearcache" method="post" >
<fieldset>
	<legend>数据删除</legend>
	<table width="100%"  class="table_form">
  <tr>
    <th width="120">日期</th>
    <td class="y-bg"><?php echo form::date('time',$_GET['time'])?></td>
  </tr>
</table>

<div class="bk15"></div>
<input type="submit" name="dosubmit" class="button"/>
</fieldset>
</form>
</div>
</body>
</html>
