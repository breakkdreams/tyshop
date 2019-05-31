<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<style type="text/css">
.table_form th{text-align: left;}
</style>

<div class="pad-10">
<div class="col-tab">
	<div id="div_setting_2" class="contentList pad-10">
    	<fieldset>
        <legend>收货人信息</legend>
		<table width="100%" class="table_form">
			<tbody>
				<tr> 
					<th width="100"><strong>收货人</strong></th>
					<td><?php echo $info['lx_name']?></td> 
				</tr>
                <tr>
					<th><strong>收货地址</strong></th>  
					<td><?php echo $info['lx_address']?></td>
				</tr>
				<tr>
					<th><strong>收货联系方式</strong></th>  
					<td><?php echo $info['lx_tel']?></td>
				</tr>
                <tr> 
					<th><strong>收货人邮编</strong></th>
					<td><?php echo $info['lx_code']?></td> 
				</tr>
			</tbody>
		</table>
        </fieldset>
        <div class="bk15"></div>
    	<fieldset>
        <legend>物流单号信息</legend>
		<table width="100%" class="table_form">
			<tbody>
				<tr> 
					<th width="100"><strong>快递类型</strong></th>
					<td><?php echo $info['logistics_name']?></td> 
				</tr>
                <tr>
					<th><strong>快递单号</strong></th>  
					<td><?php echo $info['logistics_order']?></td>
				</tr>
			</tbody>
		</table>
        </fieldset>
        <div class="bk15"></div>
        <fieldset>
		<legend>订单详细信息</legend>
        <table width="100%" class="table_form">
			<tbody>
				<tr> 
					<th width="100"><strong>订单编号</strong></th>
					<td><?php echo $info['order_sn']?></td> 
				</tr>
                <tr>
					<th><strong>购买者ID</strong></th>  
					<td><?php echo $info['userid']?></td>
				</tr>
				<tr>
					<th><strong>购买者帐号</strong></th>  
					<td><?php echo $info['username']?></td>
				</tr>
                <tr>
					<th><strong>购买者手机</strong></th>  
					<td><?php echo $info['mobile']?></td>
				</tr>
                <tr>
					<th><strong>总价</strong></th>  
					<td><?php echo $info['total']?></td>
				</tr>
				<tr>
					<th><strong>添加时间</strong></th>  
					<td><?php echo $info['add_time'] ? date("Y-m-d H:i:s", $info['add_time']) : '--'?></td>
				</tr>
                <tr>
					<th><strong>发货时间</strong></th>  
					<td><?php echo $info['fh_time'] ? date("Y-m-d H:i:s", $info['fh_time']) : '--'?></td>
				</tr>

				</tr>
					<th><strong>收货时间</strong></th>   
					<td><?php echo $info['sh_time'] ? date("Y-m-d H:i:s", $info['sh_time']) : '--'?></td>
				</tr>
				</tr>
					<th><strong>用户留言</strong></th>   
					<td><?php echo $info['usernote']?></td>
				</tr>
                </tr>
					<th><strong>状态</strong></th>   
					<td>
						<?php if($info['status'] =='1'){?>
                        <font color="red">待支付</font>
                        <?php }?>
                        <?php if($info['status'] =='2'){?>
                        <font color="blue">待发货</font>
                        <?php }?>
                        <?php if($info['status'] =='3'){?>
                        <font color="#333">待收货</font>
                        <?php }?>
                        <?php if($info['status'] =='4'){?>
                        <font color="#333">待评价</font>
                        <?php }?>
                        <?php if($info['status'] =='5'){?>
                        <font color="#999">已评价</font>
                        <?php }?>
                        <?php if($info['status'] =='6'){?>
                        <font color="#999">已删除</font>
                        <?php }?>
                    </td>
				</tr>
			</tbody>
		</table>
        </fieldset>
        
	</div>
    <input type="button" class="dialog" name="dosubmit" id="dosubmit" onclick="window.top.art.dialog({id:'view'}).close();"/>


</div>
</div>

</div>

</body>
</html>
