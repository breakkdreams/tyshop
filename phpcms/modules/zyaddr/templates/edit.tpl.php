<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<link rel="stylesheet" type="text/css" href="<?php echo APP_PATH?>statics/plugin/switchery/switchery.min.css" />
<script src="<?php echo APP_PATH?>statics/plugin/switchery/switchery.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo APP_PATH?>statics/plugin/layui/css/layui.css">
<script src="<?php echo APP_PATH?>statics/plugin/layui/layui.all.js"></script>

<form name="myform" id="myform" action="" method="post" >
	<div class="pad-10">
		<div class="common-form">
			<div id="div_setting_2" class="contentList">
				<fieldset>
				<legend>基本信息</legend>
				<table width="100%" class="table_form" id="mytable">
					<tbody>
						<tr>
							<th width="125">收件人</th>
							<td><input style="width: 50%;" type="text" name="name" id="name" class="input-text" required="" value="<?php echo $info['name']?>"></td>
						</tr>
						<tr>
							<th width="125">手机号码</th>
							<td><input style="width: 50%;" type="text" name="phone" id="phone" class="input-text" required="" value="<?php echo $info['phone']?>" /></td>
						</tr>
						<tr>
							<th width="125">省</th>
							<td><input style="width: 50%;" type="text" name="province" id="province" class="input-text" required="" value="<?php echo $info['province']?>" /></td>
						</tr>
						<tr>
							<th width="125">市</th>
							<td><input style="width: 50%;" type="text" name="city" id="city" class="input-text" required="" value="<?php echo $info['city']?>" /></td>
						</tr>
						<tr>
							<th width="125">区</th>
							<td><input style="width: 50%;" type="text" name="district" id="district" class="input-text" required="" value="<?php echo $info['district']?>" /></td>
						</tr>
						<tr>
							<th width="125">详细地址</th>
							<td><input style="width: 50%;" type="text" name="address" id="address" class="input-text" required="" value="<?php echo $info['address']?>" /></td>
						</tr>
						<tr>
							<th>默认</th>
							<td>
								<?php if($info['default']==1){ ?>
									<input type="checkbox" class="js-switch" checked name="default" value="1" />
								<?php }else{?>
									<input type="checkbox" class="js-switch" name="default" value="0" />
								<?php }?>
							</td>
						</tr>
						<script>
							var elem = document.querySelector('.js-switch');
							var init = new Switchery(elem);

							elem.onchange = function() {
								if(elem.checked){
									elem.value = 1;
								}else{
									elem.value = 0;
								}
							};
						</script>
					</tbody>
				</table>
				</fieldset>
			</div>
			<input type="hidden" name="id" value="<?php echo $_GET['id']?>" />
			<input type="hidden" name="userid" value="<?php echo $info['userid']?>" />
			<input class="dialog" name="dosubmit" id="dosubmit" type="submit" value="确认"/>
		</div>
	</div>
</form>
</body>
</html>