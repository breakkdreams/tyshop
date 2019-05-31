<?php 
	defined('IN_ADMIN') or exit('No permission resources.');
	include $this->admin_tpl('header', 'admin');
?>

<div class="pad-10">
<div class="common-form">
<fieldset>
<form name="myform" action="?m=zyorder&c=order&a=order_keyword" method="post">
<legend>搜索关键词设置</legend>
<div class="bk15"></div>
<table width="100%" class="table_form">
<tr>
<td  width="120">搜索栏主关键词：</td> 
<td><?php echo $keyword_db[0]['word'] ?></td>
</tr>
<tr>
<td  width="120">搜索栏副多关键词：</td> 
<td><?php echo $keyword_db[1]['word'] ?>&nbsp;<font color="red">(多个以逗号隔开)</font></td>
</tr>
<tr>
<td  width="120">搜索栏主关键词:</td> 
<td><input type="text" name="word1" value="<?php echo $keyword_db[0]['word'] ?>"></td>
</tr>
<tr>
<td  width="120">搜索栏副多关键词:</td> 
<td>
<textarea name="word2" cols="50" rows="6"><?php echo $keyword_db[1]['word'] ?></textarea>
</td>
</tr>
</table>
<div class="bk15"></div>
	<input name="dosubmit" style="float: left;" type="submit" value="提交" class="button" id="dosubmit">
</form>

<!--<form name="myform" action="?m=zyshop&c=zyfx&a=zyfx_config" method="post">
	<input name="dosubmit" style="float: left; margin-left: 50px;" type="submit" value="执行" class="button" id="dosubmit">
</form>-->
</fieldset>
</div>
</body>
</html>
