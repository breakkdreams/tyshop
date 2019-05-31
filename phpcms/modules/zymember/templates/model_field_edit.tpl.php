<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header','admin');
?>
<script src="<?php echo JS_PATH?>jquery-1.4.4.min.js"></script>

<script>
$(document).ready(function(){
  $("#hide").click(function(){
    $("#input").hide();
  });
  $("#show").click(function(){
    $("#input").show();
  });
});
</script>

<script>
</script>
<div class="pad_10">
<form action="?m=zymember&c=zymember&a=model_field_edit" method="post" name="myform" id="myform">
<input type="hidden" name="modelid" value="<?php echo $modelid ?>">

<table cellpadding="2" cellspacing="1" class="table_form" width="100%">
	<tr>
		<th width="100">字段名称</th>
		<td><input type="text" name="name" 
			size="30" class="input-text"  required="required"  value="<?php echo $info["COLUMN_NAME"] ?>">
			<input type="hidden" name="id" 
			size="30" class="input-text"   value="<?php echo $info["COLUMN_NAME"] ?>">
			</td>
	</tr>
	<tr>
		<th width="20%">类型</th>
		<td><select name="type" >
		<option value="1" <?php if($info["DATA_TYPE"] == 'varchar'){ ?> selected  <?php } ?>>单行文本</option>
		<option value="2" <?php if($info["DATA_TYPE"] == 'text'){ ?> selected  <?php } ?>>多行文本</option>
	    <option value="3" <?php if($info["DATA_TYPE"] == 'int'){ ?> selected <?php } ?>>数字类型</option>
		</select></td>
	</tr>
	<tr>
		<th width="100">长度</th>
		<td><input type="text" name="lang" 
			size="30" class="input-text" required="required" value="<?php echo $info["CHARACTER_MAXIMUM_LENGTH"] ?>"></td>
	</tr>
	<tr>
		<th width="100">注释</th>
		<td><input type="text" name="zhushi" 
			size="30" class="input-text" required="required" value="<?php echo $info["COLUMN_COMMENT"] ?>"></td>
	</tr>
	<tr>
		<th width="100">是否有初始值</th>
		<td>有<input type="radio" name="status" 
			size="30" class="input-text" value="1" id="show" <?php if($info["COLUMN_DEFAULT"] or $info["COLUMN_DEFAULT"]=="0"){ ?> checked <?php } ?>>
			无<input type="radio" name="status" 
			size="30" class="input-text" value="2" id="hide" <?php if($info["COLUMN_DEFAULT"] == ""){ ?> checked <?php } ?>> </td>
	</tr>
	
     <?php if($info["COLUMN_DEFAULT"] !=""){ ?>
	<tr id="input">
		<th width="100">初始值</th>
		<td><input type="text" name="null" 
			size="30" class="input-text" value="<?php echo $info["COLUMN_DEFAULT"] ?>">
		</td>
	</tr>
	
<?php } ?> 
	<tr id="input" style="display: none;" >
		<th width="100">初始值</th>
		<td><input type="text" name="null" 
			size="30" class="input-text" value="<?php echo $info["COLUMN_DEFAULT"] ?>">
		</td>
	</tr>
	
	
	
	
<tr>
		<th></th>
		<td>
<input type="submit" name="dosubmit"  id="dosubmit" class="dialog" value=" <?php echo L('submit')?> "></td>
	</tr>

</table>
</form>
</div>
</body>
</html> 