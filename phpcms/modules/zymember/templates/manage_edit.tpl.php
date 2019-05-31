<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>formvalidator.js" charset="UTF-8"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>formvalidatorregex.js" charset="UTF-8"></script>

<style type="text/css">
.table_form th{text-align: left;}
</style>

<script type="text/javascript">
  $(document).ready(function() {
	$.formValidator.initConfig({autotip:true,formid:"myform",onerror:function(msg){}});
	$("#password").formValidator({empty:true,onshow:"不修改密码请留空。",onfocus:"密码应该为6-20位之间"}).inputValidator({min:6,max:20,onerror:"密码应该为6-20位之间"});
	$("#pwdconfirm").formValidator({empty:true,onshow:"不修改密码请留空。",onfocus:"两次密码不同。",oncorrect:"密码输入一致"}).compareValidator({desid:"password",operateor:"=",onerror:"两次密码不同。"});
	$("#point").formValidator({tipid:"pointtip",onshow:"请输入积分点数，积分点数将影响会员用户组",onfocus:"积分点数应该为1-8位的数字"}).regexValidator({regexp:"^\\d{1,8}$",onerror:"积分点数应该为1-8位的数字"});
	$("#email").formValidator({onshow:"请输入邮箱",onfocus:"邮箱格式错误",oncorrect:"邮箱格式正确"}).regexValidator({regexp:"email",datatype:"enum",onerror:"邮箱格式错误"}).ajaxValidator({
	    type : "get",
		url : "",
		data :"m=member&c=member&a=public_checkemail_ajax&phpssouid=3",
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
		onerror : "邮箱已存在",
		onwait : "请稍候..."
	}).defaultPassed();
	$("#nickname").formValidator({onshow:"请输入昵称",onfocus:"昵称应该为2-20位之间"}).inputValidator({min:2,max:20,onerror:"昵称应该为2-20位之间"}).regexValidator({regexp:"ps_username",datatype:"enum",onerror:"昵称格式错误"}).ajaxValidator({
	    type : "get",
		url : "",
		data :"m=member&c=index&a=public_checknickname_ajax&userid="+<?php echo $member['userid'];?>,
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
		onerror : "用户名已经存在",
		onwait : "请稍候..."
	}).defaultPassed();
	$("#mobile").formValidator({onshow:"请输入手机号码",onfocus:"手机号码应该为5-20位之间"}).inputValidator({min:5,max:20,onerror:"手机号码应该为5-20位之间"}).ajaxValidator({
	    type : "get",
		url : "",
		data :"m=zymember&c=index&a=public_checkmobile_ajax&userid="+<?php echo $member['userid'];?>,
		datatype : "html",
		async:'false',
		success : function(data){
			console.log(data);
			console.log('123');
            if( data == "1" ) {
                return true;
			} else {
                return false;
			}
		},
		buttons: $("#dosubmit"),
		onerror : "手机号码已经存在",
		onwait : "请稍候..."
	}).defaultPassed();
  });
</script>



<form name="myform" id="myform" action="?m=zymember&c=zymember&a=manage_edit" method="post">
<input type="hidden" name="info[userid]" id="userid" value="<?php echo $member['userid']?>"></input>
<input type="hidden" name="info[username]" value="<?php echo $member['username']?>"></input>

<div class="pad-10">
<div class="table-list">
<div class="common-form">
	<div id="div_setting_2" class="contentList">

    	<fieldset>
        <legend>基本信息</legend>
		<table width="100%" class="table_form">
			<tbody>
				<tr> 
					<th width="120">账号名称</th>
					<td><?php echo $member['username']?><?php if($member['islock']) {?><img title="锁定" src="<?php echo IMG_PATH?>icon/icon_padlock.gif"><?php }?><?php if($member['vip']) {?><img title="VIP会员" src="<?php echo IMG_PATH?>icon/vip.gif"><?php }?></td> 
				</tr>
                <tr>
					<th>头像</th>  
					<td>
					<?php if(empty($member['header_img'])){?>
                        <img src="statics/images/member/nophoto.gif" height="90" width="90" onerror="this.src='statics/images/member/nophoto.gif'">
                    <?php }else{ ?>
                        <img src="<?php echo $member['header_img']?>" height="90" width="90">
                    <?php }?>
                    <input type="checkbox" name="delavatar" id="delavatar" class="input-text" value="1">
                    <label for="delavatar">删除头像</label>
                    </td>
				</tr>
                <tr>
					<th>密码</th>  
					<td><input type="password" name="info[password]" id="password" class="input-text"></td>
				</tr>
				<tr>
					<th>确认密码</th>  
					<td><input type="password" name="info[pwdconfirm]" id="pwdconfirm" class="input-text"></td>
				</tr>
                <tr> 
					<th>昵称</th>
					<td><input type="text" name="info[nickname]" id="nickname" value="<?php echo $member['nickname'] ?>" class="input-text"></td> 
				</tr>
				<tr>
					<th>邮箱</th>  
					<td><input type="text" name="info[email]" value="<?php echo $member['email'] ?>" class="input-text" id="email" size="30"></td>
				</tr>
                <tr> 
					<th>手机号码</th>
					<td><input type="text" name="info[mobile]" value="<?php echo $member['mobile'] ?>" class="input-text" id="mobile" size="15"></td> 
				</tr>
                <tr> 
					<th>会员组</th>
					<td>
					<select name="info[groupid]">
					<?php foreach($member_group as $group){ ?>
						<option value="<?php echo $group['groupid']?>" <?php if($group['groupid']==$member['groupid']){?>selected=""<?php }?>><?php echo $group['name']?></option>
					<?php }?>
					</select>
					</td> 
				</tr>
                <tr> 
					<th>积分点数</th>
					<td><input type="text" name="info[point]" value="<?php echo $member['point'] ?>" class="input-text" id="point" size="8"></td> 
				</tr>
				<tr>
					<td>vip会员</td>
					<td>
					是否为vip会员 <input type="checkbox" name="info[vip]" value=1 <?php if($member['vip']){?>checked<?php }?>/>
					过期时间 <?php echo $form_overdudate?>
					</td>
				</tr>

			</tbody>
		</table>
        </fieldset>
        <div class="bk15"></div>

		<fieldset>
		<legend>详细信息</legend>
        <table width="100%" class="table_form">
			<tbody>
                <tr>
					<th>生日</th>  
					<td><?php echo form::date('infos[birthday]',$member_detail['birthday']); ?></td>
				</tr>
			</tbody>
		</table>
        </fieldset>
        <div class="bk15"></div>

        <?php if($member['groupid']==18){?>
		<fieldset>
		<legend>店铺信息</legend>
        <table width="100%" class="table_form">
			<tbody>
                <tr>
					<th>店铺名称</th>  
					<td><input type="text" name="info[shopname]" value="<?php echo $member['shopname'] ?>" class="input-text"></td>
				</tr>
                <tr>
					<th>店铺logo</th>  
					<td>
					<img src="<?php echo $member['store_logo']?>" height="90" width="90">
                    <input type="checkbox" name="store_logo" id="store_logo" class="input-text" value="1">
                    <label for="store_logo">删除店铺logo</label>
					</td>
				</tr>
			</tbody>
		</table>
        </fieldset>
    	<?php }?>


	</div>
    <input name="dosubmit" id="dosubmit" type="submit" value="<?php echo L('submit')?>" class="dialog">

</div>
</div>

</div>
</div>
</form>

</body>
</html>
