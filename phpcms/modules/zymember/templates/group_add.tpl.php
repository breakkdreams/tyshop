<?php defined('IN_ADMIN') or exit('No permission resources.');?>
<?php include $this->admin_tpl('header', 'admin');?>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>formvalidator.js" charset="UTF-8"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>formvalidatorregex.js" charset="UTF-8"></script>
<script type="text/javascript">
<!--
$(function(){
	$.formValidator.initConfig({autotip:true,formid:"myform",onerror:function(msg){}});
	$("#name").formValidator({onshow:"请输入用户组名",onfocus:"用户组名应该为2-8位之间"}).inputValidator({min:2,max:15,onerror:"用户组名应该为2-8位之间"}).regexValidator({regexp:"ps_username",datatype:"enum",onerror:"用户组名格式错误"}).ajaxValidator({
	    type : "get",
		url : "",
		data :"m=zymember&c=zymember&a=group_checkname_ajax",
		datatype : "html",
		async:'false',
		success : function(data){	
            if( data == "1" ) {
                return true;
			} else {
                return false;
			}
		},
		buttons: $("#dosubmit"),
		onerror : "用户组名已存在",
		onwait : "请稍候..."
	});

});
//-->
</script>
<div class="pad-10">
<div class="common-form">
<form name="myform" action="?m=zymember&c=zymember&a=group_add" method="post" id="myform">
<fieldset>
	<legend>基本信息</legend>
	<table width="100%" class="table_form">
		<tr>
			<td width="120">会员组名称</td> 
			<td><input type="text" name="info[name]"  class="input-text" id="name"></td>
		</tr>
	</table>
</fieldset>
<div class="bk15"></div>
<fieldset>
	<legend>详细信息</legend>
	<table width="100%" class="table_form">
	
		<tr>
			<td width="80">用户名颜色</td> 
			<td><input type="text" name="info[usernamecolor]" class="input-text" id="usernamecolor" size="8" value="#000000"></td>
		</tr>
		<tr>
			<td width="80">用户组图标</td> 
			<td><input type="text" name="info[icon]" class="input-text" id="icon" value="zymember/images/vip.jpg" size="40"></td>
		</tr>
		<tr>
			<td width="80">简洁描述</td> 
			<td><input type="text" name="info[description]" class="input-text" size="60"></td>
		</tr>
	</table>
</fieldset>
    <div class="bk15"></div>
    <input name="dosubmit" type="submit" id="dosubmit" value="<?php echo L('submit')?>" class="dialog">
</form>
</div>
</div>
</body>
</html>