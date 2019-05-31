<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header','admin');
?>
<script type="text/javascript">
<!--
	$(function(){
	$.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){window.top.art.dialog({content:msg,lock:true,width:'200',height:'50'}, function(){this.close();$(obj).focus();})}});
	$("#link_name").formValidator({onshow:"<?php echo L("input").L('link_name')?>",onfocus:"<?php echo L("input").L('link_name')?>"}).inputValidator({min:1,onerror:"<?php echo L("input").L('link_name')?>"});
 	$("#link_url").formValidator({onshow:"<?php echo L("input").L('url')?>",onfocus:"<?php echo L("input").L('url')?>"}).inputValidator({min:1,onerror:"<?php echo L("input").L('url')?>"}).regexValidator({regexp:"^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&]*([^<>])*$",onerror:"<?php echo L('link_onerror')?>"})
	 
	})
//-->
</script>
</script>
<div class="pad_10">
<form action="?m=zymember&c=member_config&a=module_insert" method="post" name="myform" id="myform">
<table cellpadding="2" cellspacing="1" class="table_form" width="100%">
<?php foreach($sql1 as $info){
	?>
<?php if($info['COLUMN_KEY'] != PRI){ ?>
	<tr>
		<th width="100"><?php echo $info["COLUMN_NAME"] ?></th>
		<td><input type="text" name="post[<?php echo $info['COLUMN_NAME']?>]" 
			size="30" class="input-text"  ></td>
	</tr>


<?php 
}
?>
<?php 
}
?>
<tr>
		<th></th>
		<td><input
		type="submit" name="dosubmit" id="dosubmit" class="dialog"
		value=" <?php echo L('submit')?> "></td>
	</tr>

</table>
</form>
</div>
</body>
</html> 