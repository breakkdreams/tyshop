<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<style type="text/css">
.table_form th{text-align: left;}
</style>

<form name="myform" id="myform" action="?m=zymember&c=member&a=audit_view" method="post">
<div class="pad-10">
<div class="table-list">
<div class="common-form">
	<div id="div_setting_2" class="contentList">
    
    	<fieldset>
        <legend>基本信息</legend>
		<table width="100%" class="table_form">
			<tbody>
				<tr align="left">
					<td>编号：<?php echo $member['userid']?></td>
					<td>角色：代理商</td>
					<td>状态：
			            <?php if($member['disable']==1){ ?>
			                正常
			            <?php }?>
			            <?php if($member['disable']==2){ ?>
			                禁用
			            <?php }?>
					</td>
				</tr>
				<tr align="left">
					<td>姓名：<?php echo $member['name']?></td>
					<td>等级：
			            <?php if($member['memberinfo_types']==21){ ?>
			                实习专员
			            <?php }?>
			            <?php if($member['memberinfo_types']==22){ ?>
			                渠道专员
			            <?php }?>
			            <?php if($member['memberinfo_types']==23){ ?>
			                渠道经理
			            <?php }?>
					</td>
					<td>职业类型：<?php echo $member['institution']?></td>
				</tr>
				<tr align="left">
					<td width="180">手机号：<?php echo $member['mobile']?></td>
					<td>身份证号：<?php echo $member['idcard']?></td>
					<td></td>
				</tr>
				<tr align="left">
					<td width="240">地区：<?php echo $member['province'];?>,<?php echo $member['city'];?>,<?php echo $member['districe'];?></td>
					<td>上级代理：<?php echo $member['pid']?></td>
					<td></td>
				</tr>

				<?php if($member['enterprise']==2){ ?>
				<tr align="left">
					<td>单位公司：<?php echo $member['company']?></td>
					<td>纳税识别号：<?php echo $member['credit_code']?></td>
					<td></td>
				</tr>
				<tr align="left">
					<td>法人：<?php echo $member['oper_name']?></td>
					<td>股东：<?php echo $member['artners_name']?></td>
					<td>股份占比：<?php echo $member['artners_pct']?></td>
				</tr>
				<?php }?>

			</tbody>
		</table>
        </fieldset>
        <div class="bk15"></div>

        <fieldset>
		<legend>收益情况</legend>
        <table width="100%" class="table_form">
			<tbody>
				<tr align="left">
					<td>累计收益：<?php echo $member['mobile']?></td>
					<td>当月收益：<?php echo $member['mobile']?></td>
					<td>当日收益：<?php echo $member['mobile']?></td>
				</tr>
				<tr align="left">
					<td>累计客户：<?php echo $member['mobile']?></td>
					<td>当月新增客户：<?php echo $member['mobile']?></td>
					<td>当日新增客户：<?php echo $member['mobile']?></td>
				</tr>
				<tr align="left">
					<td>累计下级代理商：<?php echo $member['mobile']?></td>
					<!-- <td>当月新增代理商：<?php echo $member['mobile']?></td>
					<td>当日新增代理商：<?php echo $member['mobile']?></td> -->
				</tr>
			</tbody>
		</table>
        </fieldset>

        <div class="bk15"></div>

        <fieldset>
		<legend>银行卡信息</legend>
        <table width="100%" class="table_form">
			<tbody>
			    <?php foreach($bankinfo as $bank){?>
				<tr align="left">
					<td>开户行：<?php echo $bank['bname']?></td>
					<td>卡号：<?php echo $bank['bnum']?></td>
					<td>开户人：<?php echo $bank['accname']?></td>
				</tr>
			    <?php }?>
			</tbody>
		</table>
        </fieldset>

        <div class="bk15"></div>

        <fieldset>
		<legend>其他信息</legend>
        <table width="100%" class="table_form">
			<tbody>
				<tr align="left">
					<td>身份证正面：<a href="<?php echo $member['idcard_img'][0]?>" target="view_window"><img src="<?php echo $member['idcard_img'][0]?>" style="width: 200px;"></a></td>
					<td>身份证反面：<a href="<?php echo $member['idcard_img'][1]?>" target="view_window"><img src="<?php echo $member['idcard_img'][1]?>"  style="width: 200px;"></a></td>
				</tr>
				<tr align="left">
					<td>手持身份证：<a href="<?php echo $member['idcard_img'][2]?>" target="view_window"><img src="<?php echo $member['idcard_img'][2]?>" style="width: 200px;"></a></td>
					<td>营业执照：<a href="<?php echo $member['agent_business_img']?>" target="view_window"><img src="<?php echo $member['agent_business_img']?>" style="width: 200px;"></a></td>					
				</tr>
				<!-- <tr align="left">
					<td>渠道专员证书：<img src=""></td>
					<td>中级咨询师：<img src=""></td>
				</tr> -->
			</tbody>
		</table>
        </fieldset>

        <div class="bk15"></div>

	</div>
<input type="button" class="dialog" name="dosubmit" id="dosubmit" onclick="window.top.art.dialog({id:'view'}).close();"/>

</div>
</div>

</div>
</div>
</form>

</body>
</html>
