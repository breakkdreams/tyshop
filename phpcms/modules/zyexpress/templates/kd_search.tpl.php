<?php
defined('IN_ADMIN') or exit('No permission resources.');
$show_dialog = 1;
include $this->admin_tpl('header', 'admin');
?>
<link rel="stylesheet" type="text/css" href="<?php echo APP_PATH?>statics/zyexpress/layui/css/layui.css">
<script src="<?php echo APP_PATH?>statics/zyexpress/layui/layui.all.js"></script>

<form name="myform" id="myform" action="index.php?m=zyexpress&c=zyexpress&a=kd_search" method="post" >				
	<table id="mytable">
		<tbody>
			<tr>
				<th width="125">快递公司</th>
				<td>
					<select name="ShipperCode" required="">
						<option value="">请选择</option>
						<?php foreach($info as $k => $r){?>
						<option value="<?php echo $r['code']?>"><?php echo $r['company']?></option>
						<?php }?>
					</select>
				</td>
				<th width="125">物流单号</th>
				<td><input type="text" name="LogisticCode" id="LogisticCode" class="input-text" required=""></td>
				<th width="125">订单编号</th>
				<td><input type="text" name="OrderCode" id="OrderCode" class="input-text"></td>
				
				<td><input class="layui-btn" name="dosubmit" id="dosubmit" type="submit" value="查询"/></td>
				
			</tr>
			
		</tbody>
	</table>
			
</form>
	<?php if($msg['Success']==true){ ?>
	<div class="pad-10">
		<fieldset>
		<legend>搜索结果</legend>
		<table id="mytable">
			<tbody>
			
				<tr>
					<th width="125">快递公司:</th>
					<td>
						<?php echo $msg['company']?>
						
					</td>
				</tr>
				<tr>
					<th width="125">物流单号:</th>
					<td><?php echo $msg['LogisticCode']?></td>
				</tr>
				<tr>
					<th width="125">物流状态:</th>
					<td><?php echo $msg['state']?></td>
				</tr>
				<tr>
					<th width="125">物流跟踪:</th>
					<td>
					<?php foreach($msg['Traces'] as $k => $r){?>
					<span><?php echo $r['AcceptStation'];?></span>
					<span><?php echo $r['AcceptTime'];?></span><br>
					
					<?php }?>
					</td>
				</tr>
			</tbody>
		</table>
		</fieldset>
	</div>
	<?php }?>
	<script>
		
	</script>
</body>
</html>
