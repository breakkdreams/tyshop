<?php defined('IN_ADMIN') or exit('No permission resources.');?>
<?php include $this->admin_tpl('header', 'admin');?>
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


<form name="myform" id="myform" action="?m=zymember&c=zymember&a=group_del" method="post" onsubmit="check();return false;">
<div class="pad-lr-10">
<div class="table-list">

<table width="100%" cellspacing="0" class="layui-table">
	<thead>
		<tr>
			<th align="left" width="30px"><input type="checkbox" value="" id="check_box" onclick="selectall('groupid[]');"></th>
			<th align="left">ID</th>
			<th>排序</th>
			<th>用户组名</th>
			<th>系统组</th>
			<th>会员数</th>
			<th>操作</th>
		</tr>
		
	</thead>
<tbody>
<?php
	foreach($member_group_list as $k=>$v) {
?>
    <tr>
		<td align="left"><?php if(!$v['issystem']) {?><input type="checkbox" value="<?php echo $v['groupid']?>" name="groupid[]"><?php }?></td>
		<td align="left"><?php echo $v['groupid']?></td>
		<td align="center"><input type="text" name="sort[<?php echo $v['groupid']?>]" class="input-text" size="1" value="<?php echo $v['sort']?>"></th>
	<td align="center" title="<?php echo $v['description']?>" style="color:<?php echo $v['usernamecolor']?>"><?php if($v['icon']){?><img src="statics/<?php echo $v['icon']?>" height="18" width="18"><?php }?><?php echo $v['name']?></td>
		<td align="center"><?php echo $v['issystem'] ? L('icon_unlock') : L('icon_locked')?></td>
		<td align="center"><?php echo $v['membernum']?></th>
		<td align="center"><a href="javascript:edit(<?php echo $v['groupid']?>, '<?php echo $v['name']?>')" class="btn btn-sm" style="padding-top: 0;padding-bottom: 0;">修改</a></td>
    </tr>
<?php
	}
?>
</tbody>
 </table>

<div class="btn" style="height:50px;line-height:40px;width:100%;text-align:left;color:#444;"><label for="check_box">全选/取消</label> 
<input type="submit" style="padding:0 10px;" class="btn btn-sm btn-danger" name="dosubmit" value="删除" onclick="return confirm('您确定要删除吗？')"/>
<input type="submit" style="padding:0 10px;" class="btn btn-sm" name="dosubmit" onclick="document.myform.action='?m=zymember&c=zymember&a=group_sort'" value="排序"/>
</div> 
<div id="pages"><?php echo $pages?></div>
</div>
</div>
</form>
<div id="PC__contentHeight" style="display:none">160</div>
<script language="JavaScript">
<!--
function edit(id, name) {
	window.top.art.dialog({id:'edit'}).close();
	window.top.art.dialog({title:'修改会员组《'+name+'》',id:'edit',iframe:'?m=zymember&c=zymember&a=group_edit&groupid='+id,width:'600',height:'400'}, function(){var d = window.top.art.dialog({id:'edit'}).data.iframe;d.document.getElementById('dosubmit').click();return false;}, function(){window.top.art.dialog({id:'edit'}).close()});
}

function check() {
	if(myform.action == '?m=zymember&c=zymember&a=group_del') {
		var ids='';
		$("input[name='groupid[]']:checked").each(function(i, n){
			ids += $(n).val() + ',';
		});
		if(ids=='') {
			window.top.art.dialog({content:'请选择会员组',lock:true,width:'200',height:'50',time:1.5},function(){});
			return false;
		}
	}
	myform.submit();
}
//-->
</script>
</body>
</html>