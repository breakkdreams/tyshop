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
				<tr>
					<th width="120">用户编号</th>
					<td><?php echo $member['userid']?>
			            <?php if($member['enterprise']==1){ ?>
			                （个人）
			            <?php }?>
			            <?php if($member['enterprise']==2){ ?>
			                （企业）
			            <?php }?>

					</td>
				</tr>
				<tr>
					<th width="120">账号名称</th>
					<td><?php echo $member['username']?></td>
				</tr>
                <tr>
					<th>头像</th>
					<td>
					<?php if(empty($member['headimgurl'])){?>
                        <img src="statics/images/member/nophoto.gif" height="90" width="90" onerror="this.src='statics/images/member/nophoto.gif'">
                    <?php }else{ ?>
                        <img src="<?php echo $member['headimgurl']?>" height="90" width="90">
                    <?php }?>
                    </td>
				</tr>
                <tr>
					<th>昵称</th>
					<td><?php echo $member['nickname']?></td>
				</tr>
                <tr>
					<th>真实姓名</th>
					<td><?php echo $member['name']?></td>
				</tr>
                <tr>
					<th>手机号码</th>
					<td><?php echo $member['mobile']?></td>
				</tr>
                <tr>
					<th>注册日期</th>
					<td><?php echo date('Y-m-d H:i:s',$member['regdate']);?></td>
				</tr>
                <tr>
					<th>注册ip</th>
					<td><?php echo $member['regip'];?></td>
				</tr>
                <tr>
					<th>地区</th>
					<td><?php echo $member['province'];?>,<?php echo $member['city'];?></td>
				</tr>
                <!-- <tr>
					<th>产品分类</th>
					<td><?php echo $member['product_types'];?></td>
				</tr> -->
                <tr>
					<th>上级代理</th>
					<td><?php echo $member['pid'];?></td>
				</tr>
                <tr>
					<th>认证情况</th>
					<td>
			            <?php if($member['certification']==1){ ?>
			                已认证
			            <?php }?>
			            <?php if($member['certification']==2){ ?>
			                未认证
			            <?php }?>
			            <?php if($member['certification']==3){ ?>
			                待审核
			            <?php }?>
			            <?php if($member['certification']==4){ ?>
			                认证失败
			            <?php }?>
					</td>
				</tr>
                <tr>
					<th>状态</th>
					<td>
			            <?php if($member['disable']==1){ ?>
			                正常
			            <?php }?>
			            <?php if($member['disable']==2){ ?>
			                禁用
			            <?php }?>
					</td>
				</tr>
			</tbody>
		</table>
        </fieldset>
        <div class="bk15"></div>

        <?php if($member['enterprise']==1){?>
        <fieldset>
		<legend>个人信息</legend>
        <table width="100%" class="table_form">
			<tbody>

				<tr>
					<td width="120">身份证照片</td>
					<td><a href="<?php echo $member['idcard_img'] ?>" target="view_window"><img src="<?php echo $member['idcard_img'] ?>" style="width: 200px;"></a></td>
				</tr>

			</tbody>
		</table>
        </fieldset>
        <?php }?>

        <?php if($member['enterprise']==2){?>
        <fieldset>
		<legend>公司信息</legend>
        <table width="100%" class="table_form">
			<tbody>

				<tr>
					<td width="120">单位公司</td>
					<td><?php echo $member['company'] ?></td>
				</tr>
				<tr>
					<td>纳税识别号</td>
					<td><?php echo $member['credit_code'] ?></td>
				</tr>
				<tr>
					<td width="90">法人</td>
					<td><?php echo $member['oper_name'] ?></td>
				</tr>
				<tr>
					<td>股东</td>
					<td><?php echo $member['artners_name'] ?></td>
				</tr>


			</tbody>
		</table>
        </fieldset>
        <?php }?>

	</div>
<input type="button" class="dialog" name="dosubmit" id="dosubmit" onclick="window.top.art.dialog({id:'view'}).close();"/>

</div>
</div>

</div>
</div>
</form>

</body>
</html>
