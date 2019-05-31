<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');?>
<div class="pad_10">
<div class="explain-col">
温馨提示：添加联动菜单后，请点击联动菜单后“更新缓存”按钮
</div>
<div class="bk10"></div>
<form name="myform" action="?m=zylinkage&c=zylinkage&a=listorder" method="post">
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
		<tr>
		<th width="10%">ID</th>
		<th width="10%" align="left" >菜单名称</th>
		<th width="10%" align="left" >菜单描述</th>
		<th width="10%" >是否显示</th>
		<th width="20%" >管理操作</th>
		</tr>
        </thead>
        <tbody>
		<?php 
		if(is_array($infos)){
			foreach($infos as $info){
		?>
		<tr>
		<td width="10%" align="center"><?php echo $info['linkageid']?></td>
		<td width="10%" ><?php echo $info['name']?></td>
		<td width="10%" ><?php echo $info['description']?></td>
		<td width="10%" align="center">
			<?php 
			if($info['isshow']=='1'){
				echo '<img src="'.APP_PATH.'statics/zylinkage/images/toggle_enabled.gif">';
			}else{
				echo '<img src="'.APP_PATH.'statics/zylinkage/images/toggle_disabled.gif">';
			}
			?>
		</td>
		<td width="20%" class="text-c">
			<a href="?m=zylinkage&c=zylinkage&a=public_manage_submenu&keyid=<?php echo $info['linkageid']?>">管理子菜单</a>
			| <a href="javascript:void(0);" onclick="edit('<?php echo $info['linkageid']?>','<?php echo new_addslashes($info['name'])?>')">编辑</a>
			| <a href="javascript:confirmurl('?m=zylinkage&c=zylinkage&a=delete&linkageid=<?php echo $info['linkageid']?>', '是否删除菜单?')">删除</a>
			| <a href="?m=zylinkage&c=zylinkage&a=public_cache&linkageid=<?php echo $info['linkageid']?>">更新缓存</a>
		</td>
		</tr>
		<?php 
			}
		}
		?>
</tbody>
</table>
</div>
</div>
</form>
<script type="text/javascript">
<!--
function edit(id, name) {
	window.top.art.dialog({id:'edit'}).close();
	window.top.art.dialog({title:name,id:'edit',iframe:'?m=zylinkage&c=zylinkage&a=edit&linkageid='+id,width:'500',height:'200'}, function(){var d = window.top.art.dialog({id:'edit'}).data.iframe;d.document.getElementById('dosubmit').click();return false;}, function(){window.top.art.dialog({id:'edit'}).close()});
}
//-->
</script>
</body>
</html>