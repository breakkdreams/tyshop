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
<form name="myform" action="?m=zylinkage&c=zylinkage&a=edit" method="post" id="myform">
<table width="100%" class="table_form contentWrap">
<?php
if(isset($_GET['parentid'])) { ?>
<tr>
  <td>上级菜单</td>
  <td>
<?php echo form::select_linkage($info['keyid'], 0, 'info[parentid]', 'parentid', L('cat_empty'), $_GET['parentid'])?>
  </td>
  </tr>
  <?php } ?>
<tr>
<td>菜单名称</td>
<td>
<input type="text" name="info[name]" value="<?php echo $name?>" class="input-text" id="name" size="30"></input>
</td>
</tr>

<tr>
<td>菜单描述</td>
<td>
<textarea name="info[description]" rows="6" cols="40" id="description" class="inputtext"><?php echo $description?></textarea>
</td>
</tr>
<tr>
<td>是否显示</td>
<td>
<input name="info[isshow]" value="0" type="radio" <?php if($isshow==0) {?>checked<?php }?>>&nbsp;否&nbsp;&nbsp;
<input name="info[isshow]" value="1" type="radio" <?php if($isshow==1) {?>checked<?php }?>>&nbsp;是&nbsp;&nbsp;
</td>
</tr>
<?php
if(isset($_GET['parentid'])) { ?>
<input type="hidden" name="info[siteid]" value="<?php echo $this->_get_belong_siteid($keyid)?>" class="input-text" id="name" size="30"></input>
<input type="hidden" name="linkageid" value="<?php echo $linkageid?>">
 <input name="dosubmit" type="submit" value="提交" class="dialog" id="dosubmit">
<?php } else { ?>
    <input type="hidden" name="info[keyid]" value="<?php echo $keyid?>">
    <input type="hidden" name="linkageid" value="<?php echo $linkageid?>">
    <input name="dosubmit" type="submit" value="提交" class="dialog" id="dosubmit">
  <?php } ?>
</table>
    
</form>
</div>
</div>
</body>
</html>
