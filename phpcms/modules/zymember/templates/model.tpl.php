<?php 
	defined('IN_ADMIN') or exit('No permission resources.');
	include $this->admin_tpl('header', 'admin');
?>
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

<div class="pad-10">
	<div class="common-form">
		<fieldset>
			<legend>会员基本配置</legend>
			<div class="bk10"></div>
			<div class="explain-col search-form">
				1、请谨慎删除模型，当模型里存在会员时请使用“移动”功能将该模型里的会员移动到其他会员模型中。<br>2、移动模型会员，将会把原有模型里的会员信息删除，将不能修复。 
			</div>



			<div class="table-list">
				<form name="myform" id="myform" action="?m=zymember&c=zymember&a=model_delete" method="post" onsubmit="check();return false;">
					<table width="100%" cellspacing="0"  class="layui-table">
						<thead>
							<tr>
								<th align="left" style="width: 5%;"><input type="checkbox" value="" id="check_box" onclick="selectall('modelid[]');"></th>
								<th align="left" style="width: 5%;">ID</th>
								<th align="left">模型名称</th>
								<th align="left">数据表名</th>
								<th>操作</th>
							</tr>
						</thead>

						<tbody>	
						    <tr>
								<td align="left" style="width: 5%;"><input type="checkbox" value="<?php echo $v['modelid']?>" name="modelid[]" <?php if($v['modelid']==10) echo "disabled";?>></td>
								<td align="left" style="width: 5%;">1</td>
								<td align="left">会员主表</td>
								<td align="left">zy_member</td>
								<td align="center">
								<a onclick="_M(892);" href="?m=zymember&c=zymember&a=model_field&modelid=1" class="btn btn-sm" style="padding-top: 0;padding-bottom: 0;">字段管理</a> <a href="?m=zymember&c=zymember&a=model_clear&modelid=1" onClick="return confirm('非程序员请勿操作?')" class="btn btn-sm btn-danger" style="padding-top: 0;padding-bottom: 0;">清空</a><!--  <a href="javascript:void(0);" onclick="edit('1')">插入</a> -->
								</td>
						    </tr>

						    <tr>
								<td align="left" style="width: 5%;"><input type="checkbox" value="<?php echo $v['modelid']?>" name="modelid[]" <?php if($v['modelid']==10) echo "disabled";?>></td>
								<td align="left" style="width: 5%;">2</td>
								<td align="left">会员组表</td>
								<td align="left">zy_member_group</td>
								<td align="center">
								<a onclick="_M(892);" href="?m=zymember&c=zymember&a=model_field&modelid=2" class="btn btn-sm" style="padding-top: 0;padding-bottom: 0;">字段管理</a> <a href="?m=zymember&c=zymember&a=model_clear&modelid=2" onClick="return confirm('非程序员请勿操作?')" class="btn btn-sm btn-danger" style="padding-top: 0;padding-bottom: 0;">清空</a><!--  <a href="javascript:void(0);" onclick="edit('2')">插入</a> -->
								</td>
						    </tr>

						    <tr>
								<td align="left" style="width: 5%;"><input type="checkbox" value="<?php echo $v['modelid']?>" name="modelid[]" <?php if($v['modelid']==10) echo "disabled";?>></td>
								<td align="left" style="width: 5%;">3</td>
								<td align="left">会员附表</td>
								<td align="left">zy_member_detail</td>
								<td align="center">
								<a onclick="_M(892);" href="?m=zymember&c=zymember&a=model_field&modelid=3" class="btn btn-sm" style="padding-top: 0;padding-bottom: 0;">字段管理</a> <a href="?m=zymember&c=zymember&a=model_clear&modelid=3"  onClick="return confirm('非程序员请勿操作?')" class="btn btn-sm btn-danger" style="padding-top: 0;padding-bottom: 0;">清空</a><!--  <a href="javascript:void(0);" onclick="edit('3')">插入</a> -->
								</td>
						    </tr>

						    <tr>
								<td align="left" style="width: 5%;"><input type="checkbox" value="<?php echo $v['modelid']?>" name="modelid[]" <?php if($v['modelid']==10) echo "disabled";?>></td>
								<td align="left" style="width: 5%;">4</td>
								<td align="left">会员sso表</td>
								<td align="left">zy_member_sso</td>
								<td align="center">
								<a onclick="_M(892);" href="?m=zymember&c=zymember&a=model_field&modelid=4" class="btn btn-sm" style="padding-top: 0;padding-bottom: 0;">字段管理</a> <a href="?m=zymember&c=zymember&a=model_clear&modelid=4" onClick="return confirm('非程序员请勿操作?')" class="btn btn-sm btn-danger" style="padding-top: 0;padding-bottom: 0;">清空</a><!--  <a href="javascript:void(0);" onclick="edit('4')">插入</a> -->
								</td>
						    </tr>
						</tbody>
					</table>
				</form>
			</div>


		</fieldset>

		
	</div>
</div>






</body>
</html>

<script>
function edit(id) {
    window.top.art.dialog({
        id:'edit',
        iframe:'?m=zymember&c=zymember&a=module_insert&id='+id,
        title:'添加数据',
        width:'700',
        height:'500',
        lock:true
    },
    function(){
        var d = window.top.art.dialog({id:'edit'}).data.iframe;
        var form = d.document.getElementById('dosubmit');
        form.click();
        return false;
    },
    function(){
        window.top.art.dialog({id:'edit'}).close()
    });
    void(0);
}
</script>

