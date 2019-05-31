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
<form name="myform" action="?m=zylinkage&c=zylinkage&a=public_sub_add" method="post" id="myform">
<table width="100%" class="table_form contentWrap">
<tr>
<td>上级菜单</td>
<td>
<?php echo $list?>
</td>
</tr>

<tr>
<td>菜单名称</td>
<td>
<input type="text" name="info[name]" id="name" class="inputtext">
</td>
</tr>

<tr>
<td>菜单描述</td>
<td>
<textarea name="info[description]" rows="6" cols="40" id="description" class="inputtext"><?php echo $description?></textarea>
</td>
</tr>

</table>

    <div class="bk15"></div>
    <input type="hidden" name="keyid" value="<?php echo $keyid?>">
    <input name="dosubmit" type="submit" value="<?php echo L('submit')?>" class="dialog" id="dosubmit">
</form>
</div>
</div>
</body>
</html>
