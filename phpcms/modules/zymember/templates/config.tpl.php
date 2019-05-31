<?php 
	defined('IN_ADMIN') or exit('No permission resources.');
	include $this->admin_tpl('header', 'admin');
?>
<link rel="stylesheet" type="text/css" href="<?php echo APP_PATH?>statics/zymember/layui/css/layui.css">
<script src="<?php echo APP_PATH?>statics/zymember/layui/layui.all.js"></script>


<!-- 样式库 -->
<style type="text/css">
    .clear{ clear: both; }
    .btn:hover{text-decoration: none;}
    .btn {display: inline-block; height: 34px; line-height: 34px; padding: 0 14px; background-color: #009688; color: #fff; white-space: nowrap; text-align: center; font-size: 14px; border: none; border-radius: 2px; cursor: pointer; transition: all .3s; -webkit-transition: all .3s; box-sizing: border-box;}
    .btn:hover {opacity: .8;color: #fff;}
    .btn-primary {
        background-color: #fff;
        border: 1px solid #C9C9C9;
        color: #555;
    }
    .btn-warm {
        background-color: #FFB800;
    }
    .btn-danger {
        background-color: #FF5722;
    }
    .btn-info {
        background-color: #1E9FFF;
    }


    .btn-sm {
        height: 30px;
        line-height: 30px;
        padding: 0 10px;
        font-size: 12px;
    }
    .btn-xs {
        height: 22px;
        line-height: 22px;
        padding: 0 5px;
        font-size: 12px;
    }
</style>

<div class="pad-lr-10">

	<div class="common-form">
    <fieldset>
        <legend>会员基本设置</legend>
		<form name="myform" action="?m=zymember&c=zymember&a=config_edit&pc_hash=<?php echo $_SESSION['pc_hash'];?>"
		 method="post">
			<div class="common-form">
				<div id="div_setting_2" class="contentList">

					<table width="100%" class="table_form">
						<tbody>
							<tr>
								<td width="200">新会员默认积分点数</td>
								<td><input type="text" name="info[defualtpoint]" class="input-text" value="<?php echo $member_setting['defualtpoint']?>"></td>
							</tr>

							<tr>
								<td>会员注册协议</td>
								<td>
									<textarea name="info[regprotocol]" id="regprotocol" style="width:80%;height:120px;"><?php echo $member_setting['regprotocol']?></textarea>
								</td>
							</tr>

						</tbody>
					</table>

					<div class="bk15"></div>

				</div>
				<input class="btn btn-sm" name="dosubmit" id="dosubmit" type="submit" value="提交" style="padding: 0 10px;" />
			</div>

        </fieldset>
		</form>

		<div class="bk10"></div>
	</div>
</div>



<div class="pad-lr-10">

	<div class="common-form">
    <fieldset>
        <legend>第三放登录配置</legend>
		<form name="myform" action="?m=zymember&c=zymember&a=config_edit_sys&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post">
			<div class="common-form">
				<div id="div_setting_2" class="contentList">

					<table width="100%" class="table_form">
						<tbody>
							<tr>
								<td width="200">是否开启微信登录</td>
								<td>
									<label><input name="setconfig['wechat_off']" type="radio" <?php if($wechat_off==0){ ?>checked="checked"<?php }?> value="0"/>开</label> 
									<label><input name="setconfig['wechat_off']" type="radio" <?php if($wechat_off==1){ ?>checked="checked"<?php }?> value="1"/>关</label>
									<a target="_blank" href="https://mp.weixin.qq.com/" style="color:red;">（https://mp.weixin.qq.com/）</a>
                                </td>
							</tr>
							<tr>
								<td width="200">是否开启微信开放平台</td>
								<td>
									<label><input name="setconfig['wechat_kaifang']" type="radio" <?php if($wechat_kaifang==0){ ?>checked="checked"<?php }?> value="0"/>开</label> 
									<label><input name="setconfig['wechat_kaifang']" type="radio" <?php if($wechat_kaifang==1){ ?>checked="checked"<?php }?> value="1"/>关</label>
									<a target="_blank" href="https://open.weixin.qq.com" style="color:red;">（https://open.weixin.qq.com）</a>
								</td>
							</tr>
							<tr>
								<td width="200">微信公众号登录appid</td>
								<td><input type="text" name="setconfig['wechat_appid']" class="input-text" value="<?php echo $wechat_appid ?>" style="width:300px;"></td>
							</tr>
							<tr>
								<td width="200">微信公众号登录appsecret</td>
								<td><input type="text" name="setconfig['wechat_appsecret']" class="input-text" value="<?php echo $wechat_appsecret ?>" style="width:300px;"></td>
							</tr>
							<tr>
								<td width="200">微信扫码登陆appid</td>
								<td><input type="text" name="setconfig['wechatpc_appid']" class="input-text" value="<?php echo $wechatpc_appid ?>" style="width:300px;"></td>
							</tr>
							<tr>
								<td width="200">微信扫码登陆appsecret</td>
								<td><input type="text" name="setconfig['wechatpc_appsecret']" class="input-text" value="<?php echo $wechatpc_appsecret ?>" style="width:300px;"></td>
							</tr>
							<tr>
								<td width="200">微信APP登陆appid</td>
								<td><input type="text" name="setconfig['wechatapp_appid']" class="input-text" value="<?php echo $wechatapp_appid ?>" style="width:300px;"></td>
							</tr>
							<tr>
								<td width="200">微信APP登陆appsecret</td>
								<td><input type="text" name="setconfig['wechatapp_appsecret']" class="input-text" value="<?php echo $wechatapp_appsecret ?>" style="width:300px;"></td>
							</tr>
							<tr>
								<td width="200">微信小程序登陆appid</td>
								<td><input type="text" name="setconfig['wechatxcx_appid']" class="input-text" value="<?php echo $wechatxcx_appid ?>" style="width:300px;"></td>
							</tr>
							<tr>
								<td width="200">微信小程序登陆appsecret</td>
								<td><input type="text" name="setconfig['wechatxcx_appsecret']" class="input-text" value="<?php echo $wechatxcx_appsecret ?>" style="width:300px;"></td>
							</tr>

						</tbody>
					</table>

					<div class="bk15"></div>

				</div>
				<input class="btn btn-sm" name="dosubmit" id="dosubmit" type="submit" value="提交" style="padding: 0 10px;" />
			</div>

        </fieldset>
		</form>

		<div class="bk10"></div>
	</div>
</div>



</body>

</html>