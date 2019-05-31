<?php defined('IN_ADMIN') or exit('No permission resources.');?>
<?php include $this->admin_tpl('header', 'admin');?>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>formvalidator.js" charset="UTF-8"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>formvalidatorregex.js" charset="UTF-8"></script>
<script type="text/javascript">
<!--
$(function(){
	$.formValidator.initConfig({autotip:true,formid:"myform",onerror:function(msg){}});

	/* $("#username").formValidator({onshow:"请输入用户名",onfocus:"用户名应该为2-20位之间"}).inputValidator({min:2,max:20,onerror:"用户名应该为2-20位之间"}).regexValidator({regexp:"ps_username",datatype:"enum",onerror:"用户名格式错误"}).ajaxValidator({
	    type : "get",
		url : "",
		data :"m=member&c=member&a=public_checkname_ajax",
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
		onerror : "禁止注册或用户已存在。",
		onwait : "请稍候..."
	}); */
	$("#password").formValidator({onshow:"请输入密码",onfocus:"密码应该为6-20位之间"}).inputValidator({min:6,max:20,onerror:"密码应该为6-20位之间"});
	$("#pwdconfirm").formValidator({onshow:"请输入确认密码",onfocus:"请输入两次密码不同。",oncorrect:"密码输入一致"}).compareValidator({desid:"password",operateor:"=",onerror:"请输入两次密码不同。"});
	$("#point").formValidator({tipid:"pointtip",onshow:"请输入积分点数，积分点数将影响会员用户组",onfocus:"积分点数应该为1-8位的数字"}).regexValidator({regexp:"^\\d{1,8}$",onerror:"积分点数应该为1-8位的数字"});
	$("#email").formValidator({onshow:"请输入邮箱",onfocus:"邮箱格式错误",oncorrect:"邮箱格式正确"}).inputValidator({min:2,max:32,onerror:"邮箱应该为2-32位之间"}).regexValidator({regexp:"email",datatype:"enum",onerror:"邮箱格式错误"}).ajaxValidator({
	    type : "get",
		url : "",
		data :"m=member&c=member&a=public_checkemail_ajax",
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
		onerror : "禁止注册或邮箱已存在",
		onwait : "请稍候..."
	});
	$("#nickname").formValidator({onshow:"请输入昵称",onfocus:"昵称应该为2-20位之间"}).inputValidator({min:2,max:20,onerror:"昵称应该为2-20位之间"}).regexValidator({regexp:"ps_username",datatype:"enum",onerror:"昵称格式错误"}).ajaxValidator({
	    type : "get",
		url : "",
		data :"m=member&c=index&a=public_checknickname_ajax",
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
		onerror : "已经存在已经存在",
		onwait : "请稍候..."
	}).defaultPassed();
});
//-->
</script>
<div class="pad-10">
<div class="common-form">
<form name="myform" action="?m=zymember&c=zymember&a=manage_add" method="post" id="myform">
<fieldset>
	<legend>基本信息</legend>
	<table width="100%" class="table_form">
		<!-- <tr>
			<td width="80">用户名</td> 
			<td><input type="text" name="info[username]"  class="input-text" id="username"></input></td>
		</tr> -->
		<tr>
			<td width="80">用户名</td> 
			<td>随机生成</td>
		</tr>
		<tr>
			<td>密码</td> 
			<td><input type="password" name="info[password]" class="input-text" id="password" value=""></input></td>
		</tr>
		<tr>
			<td>确认密码</td> 
			<td><input type="password" name="info[pwdconfirm]" class="input-text" id="pwdconfirm" value=""></input></td>
		</tr>
		<tr>
			<td>昵称</td> 
			<td><input type="text" name="info[nickname]" id="nickname" value="" class="input-text"></input></td>
		</tr>
		<tr>
			<td>邮箱</td>
			<td>
			<input type="text" name="info[email]" value="" class="input-text" id="email" size="30"></input>
			</td>
		</tr>
		<tr>
			<td>手机号码</td>
			<td>
			<input type="text" name="info[mobile]" value="<?php echo $memberinfo['mobile']?>" class="input-text" id="mobile" size="15"></input>
			</td>
		</tr>
		<tr>
			<td>会员组</td>
			<td>
			<?php echo form::select($grouplist, '2', 'name="info[groupid]"', '');?>
			</td>
		</tr>
		<tr>
			<td>积分点数</td>
			<td>
			<input type="text" name="info[point]" value="" class="input-text" id="point" size="10"></input>
			</td>
		</tr>
		<tr>
			<td>vip会员</td>
			<td>
			是否为vip会员 <input type="checkbox" name="info[vip]" value=1 />
			过期时间 <?php echo form::date('info[overduedate]', '', 1)?>
			</td>
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