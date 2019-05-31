<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');?>
<script type="text/javascript">
  $(document).ready(function() {
	$.formValidator.initConfig({autotip:true,formid:"myform",onerror:function(msg){}});
	$("#name").formValidator({onshow:"请输入菜单名称",onfocus:"菜单名称不为空"}).inputValidator({min:1,max:999,onerror:"菜单名称不为空"});
  })
</script>
<div class="pad_10">
<div class="common-form">
<form name="myform" action="?m=zylinkage&c=zylinkage&a=add" method="post" id="myform">
<table width="100%" class="table_form contentWrap">
<tr>
<td>菜单名称</td>
<td>
<input type="text" name="info[name]" value="<?php echo $name?>" class="input-text" id="name" size="30"></input>
</td>
</tr>

<tr>
<td>菜单描述</td>
<td>
<textarea name="info[description]" rows="8" cols="40" id="description" class="inputtext"><?php echo $description?></textarea>
</td>
</tr>

</table>

    <div class="bk15"></div>
    <input name="dosubmit" type="submit" value="提交" class="dialog" id="dosubmit">
</form>
</div>
</div>
</body>
</html>
