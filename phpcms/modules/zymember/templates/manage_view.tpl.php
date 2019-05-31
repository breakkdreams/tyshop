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
					<th width="120">用户ID</th>
					<td><?php echo $member['userid']?></td>
				</tr>
				<tr>
					<th width="120">用户名</th>
					<td><?php echo $member['username']?><?php if($member['islock']) {?><img title="锁定" src="<?php echo IMG_PATH?>icon/icon_padlock.gif"><?php }?><?php if($member['vip']) {?><img title="VIP会员" src="<?php echo IMG_PATH?>icon/vip.gif"><?php }?></td>
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
					<th>手机号码</th>
					<td><?php echo $member['mobile']?></td>
				</tr>
                <tr>
					<th>邮箱</th>
					<td><?php echo $member['email']?></td>
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
					<th>会员组</th>
					<td>
						<?php 
							foreach($member_group as $group){
								if($group['groupid']==$member['groupid']){
									if($group['icon']){
										echo '<img src="statics/'.$group['icon'].'" height="18" width="18">';
									}
									echo $group['name'];
								}

							}
						?>
					</td>
				</tr>
                <tr>
					<th>积分</th>
					<td><?php echo $member['point'];?></td>
				</tr>
                <tr>
					<th>vip会员</th>
					<td>
					<?php if($member['vip']==0){ ?>
						否
					<?php }?>
					<?php if($member['vip']==1){ ?>
						到期时间：<?php echo date('Y-m-d H:i:s',$member['overduedate']);?>
					<?php }?>

					</td>
				</tr>
                <tr>
					<th>状态</th>
					<td>
			            <?php if($member['islock']==0){ ?>
			                正常
			            <?php }?>
			            <?php if($member['islock']==1){ ?>
			                禁用
			            <?php }?>
					</td>
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
					<th width="120">店铺名称</th>
					<td><?php echo $member['shopname']?></td>
				</tr>
                <tr>
					<th>店铺logo</th>
					<td><img src="<?php echo $member['store_logo']?>" height="90" width="90"></td>
				</tr>
			</tbody>
		</table>
        </fieldset>
        <div class="bk15"></div>
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
