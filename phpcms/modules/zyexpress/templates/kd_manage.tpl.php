<?php
defined('IN_ADMIN') or exit('No permission resources.');
$show_dialog = 1;
include $this->admin_tpl('header', 'admin');
?>
<link rel="stylesheet" type="text/css" href="<?php echo APP_PATH?>statics/zyexpress/layui/css/layui.css">
<script src="<?php echo APP_PATH?>statics/zyexpress/layui/layui.all.js"></script>

<form name="myform" id="myform" action="index.php?m=zyexpress&c=zyexpress&a=kd_manage" method="post">
    <div class="pad-10">
		<div class="common-form">
			<div id="div_setting_2" class="contentList">
				<fieldset>
				<legend>接口配置</legend>
				<table width="100%" class="table_form" id="mytable">
					<tbody>
                        <tr>
                            <th width="125">申请网址</th>
                            <td><a href="http://www.kdniao.com/UserCenter/v2/UserHome.aspx" target="_blank" rel="noopener noreferrer" style="color:red;">快递鸟</a></td>
                        </tr>
                        <tr>
                            <th width="125">电商ID</th>
                            <td><input style="width: 50%;" type="text" name="EBusinessID" id="EBusinessID" class="input-text" required="" value="<?php echo $EXinfo['EBusinessID'] ?>"></td>
                        </tr>
						<tr>
							<th width="125">电商私钥</th>
							<td><input style="width: 50%;" type="text" name="AppKey" id="AppKey" class="input-text" required="" value="<?php echo $EXinfo['AppKey'] ?>"></td>
						</tr>
						<tr>
							<th width="125">请求地址</th>
							<td><input style="width: 50%;" type="text" name="ReqURL" id="ReqURL" class="input-text" required="" placeholder="http://api.kdniao.com/Ebusiness/EbusinessOrderHandle.aspx" value="<?php echo $EXinfo['ReqURL'] ?>"></td>
						</tr>
					</tbody>
				</table>
				</fieldset>
			</div>
            <div class="bk15"></div>
			<input class="layui-btn" name="dosubmit" id="dosubmit" type="submit" value="确认"/>
		</div>
	</div>
</form>
</body>

</html>
