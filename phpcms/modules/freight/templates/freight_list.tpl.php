<?php
defined('IN_ADMIN') or exit('No permission resources.');
$show_dialog = 1;
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-lr-10">


<div class="table-list">
<table width="100%" cellspacing="0">
	<thead>
		<tr>
			<th width="35" align="center">模板名称</th>
			<th width="35" align="center">发货地区</th>
			<th width="35" align="center">计费方式</th>
			<th width="35" align="center">首件(个)</th>
			<th width="35" align="center">续件(个)</th>
			<th width="35" align="center">首重(kg)</th>
			<th width="35" align="center">续重(kg)</th>
			<th width="35" align="center">首体积(m³)</th>
			<th width="35" align="center">续体积(m³)</th>
			<th width="35" align="center">首费(元)</th>
			<th width="35" align="center">续费(元)</th>
			<th width="35" align="center">操作</th>
		</tr>
	</thead>
<tbody>
<?php
if(is_array($infos)){
	foreach($infos as $info){
		?>
	<tr>
		<td align="center" width="35"><?php echo $info['template_name']?></td>
		<td align="center" width="35"><?php echo $info['province']?><?php echo $info['city']?><?php echo $info['district']?></td>
		<td align="center" width="35">
			<?php if($info['price_way']==1){?>
				<?php echo '按件数'?>
			<?php } else if($info['price_way']==2){?>
				<?php echo '按重量'?>
			<?php } else echo '按体积'?>
		</td>

		<td align="center" width="35">
			<?php if($info['price_way']==1){?>
				<?php echo $info['first_num'];}?>
		</td>
		<td align="center" width="35">
			<?php if($info['price_way']==1){?>
				<?php echo $info['continue_num'];}?>
		</td>
		<td align="center" width="35">
			<?php if($info['price_way']==2){?>
				<?php echo $info['first_num'];}?>
		</td>
		<td align="center" width="35">
			<?php if($info['price_way']==2){?>
				<?php echo $info['continue_num'];}?>
		</td>
		<td align="center" width="35">
			<?php if($info['price_way']==3){?>
				<?php echo $info['first_num'];}?>
		</td>
		<td align="center" width="35">
			<?php if($info['price_way']==3){?>
				<?php echo $info['continue_num'];}?>
		</td>
		<td align="center" width="35"><?php echo $info['first_fee']?></td>
		<td align="center" width="35"><?php echo $info['continue_fee']?></td>
	
		
	
		<td align="center" width="12%"><a href="###"
			onclick="edit(<?php echo $info['template_id']?>)"
			title="查看模板">查看模板</a> |  <a
			href='?m=freight&c=freight&a=delete&template_id=<?php echo $info['template_id']?>'
			onClick="return confirm('<?php echo L('confirm', array('message' => new_addslashes(new_html_special_chars($info['template_name']))))?>')"><?php echo L('delete')?></a>
		</td>
	</tr>
	<?php
	}
}
?>
</tbody>
</table>
</div>

<div id="pages"><?php echo $pages?></div>

</div>
<script type="text/javascript">

function edit(id) {
    var pc_hash = GetQueryString('pc_hash');
    window.location.href='?m=freight&c=freight&a=infopage&template_id='+id+'&pc_hash='+pc_hash;
}
function checkuid() {
	var ids='';
	$("input[name='linkid[]']:checked").each(function(i, n){
		ids += $(n).val() + ',';
	});
	if(ids=='') {
		window.top.art.dialog({content:"<?php echo L('before_select_operations')?>",lock:true,width:'200',height:'50',time:1.5},function(){});
		return false;
	} else {
		myform.submit();
	}
}

function GetQueryString(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return unescape(r[2]); return null;
}

</script>
</body>
</html>
