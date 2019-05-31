<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');?>
<div class="pad_10">
<form name="myform" action="?m=zylinkage&c=zylinkage&a=public_listorder" method="post">
<input type="hidden" name="keyid" value="<?php echo $keyid?>">
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
		<tr>
		<th width="10%">排序</th>
		<th width="10%">ID</th>
		<th width="10%" align="left" >菜单名称</th>
		<th width="20%">菜单描述</th>
		<th width="10%" >是否显示</th>
		<th width="15%">管理操作</th>
		</tr>
        </thead>
        <tbody>
		<?php echo $submenu?>
		</tbody>
	</table>
	<div class="btn"><input type="submit" class="button" name="dosubmit" value="排序" /></div>  </div>
</div>
</div>
</form>
<script type="text/javascript">
<!--
function add(id, name,linkageid) {
	window.top.art.dialog({id:'add'}).close();
	window.top.art.dialog({title:name,id:'add',iframe:'?m=zylinkage&c=zylinkage&a=public_sub_add&keyid='+id+'&linkageid='+linkageid,width:'500',height:'320'}, function(){var d = window.top.art.dialog({id:'add'}).data.iframe;d.document.getElementById('dosubmit').click();return false;}, function(){window.top.art.dialog({id:'add'}).close()});
}

function edit(id, name,parentid) {
	window.top.art.dialog({id:'edit'}).close();
	window.top.art.dialog({title:name,id:'edit',iframe:'?m=zylinkage&c=zylinkage&a=edit&linkageid='+id+'&parentid='+parentid,width:'500',height:'200'}, function(){var d = window.top.art.dialog({id:'edit'}).data.iframe;d.document.getElementById('dosubmit').click();return false;}, function(){window.top.art.dialog({id:'edit'}).close()});
}
//-->
</script>
</body>
</html>