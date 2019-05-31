<?php
defined('IN_ADMIN') or exit('No permission resources.');
$show_dialog = 1;
include $this->admin_tpl('header', 'admin');
?>
<link rel="stylesheet" type="text/css" href="<?php echo APP_PATH?>statics/zymember/layui/css/layui.css">
<script src="<?php echo APP_PATH?>statics/zymember/layui/layui.all.js"></script>


<!-- <div class="pad-lr-10">
    <div class="explain-col">
    修改会员：待完成。<br>
    </div>
</div>
<div class="bk10"></div> -->


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



<div class="pad-lr-10">
<form name="searchform" action="" method="get" >
<input type="hidden" value="zymember" name="m">
<input type="hidden" value="zymember" name="c">
<input type="hidden" value="manage" name="a">
<input type="hidden" value="<?php echo $_GET['menuid']?>" name="menuid">
<div class="explain-col search-form">
<select name="type" >
    <option value="">请选择</option>
    <option value="1" <?php if ($_GET['type']==1) {?>selected<?php }?>>用户名</option>
    <option value="2" <?php if ($_GET['type']==2) {?>selected<?php }?>>用户ID</option>
    <!-- <option value="3" <?php if ($_GET['type']==3) {?>selected<?php }?>>姓名</option> -->
    <option value="4" <?php if ($_GET['type']==4) {?>selected<?php }?>>手机号</option>
    <option value="5" <?php if ($_GET['type']==5) {?>selected<?php }?>>昵称</option>
</select>
<input type="text" value="<?php echo $_GET['q']?>" class="input-text" name="q">

注册日期  <?php echo form::date('start_addtime',$_GET['start_addtime'])?><?php echo L('to')?>   <?php echo form::date('end_addtime',$_GET['end_addtime'])?>

<select name="islock">
    <option value="" selected="">正常/禁用</option>
    <option value="1" <?php if ($_GET['islock']==1) {?>selected<?php }?>>正常</option>
    <option value="2" <?php if ($_GET['islock']==2) {?>selected<?php }?>>禁用</option>
</select>

<select name="groupid">
    <option value="" selected="">会员组</option>
    <?php foreach($member_group as $group){?>
    <option value="<?php echo $group['groupid']?>" <?php if ($_GET['groupid']==$group['groupid']) {?>selected<?php }?>><?php echo $group['name']?></option>
    <?php }?>
</select>


<input type="submit" value="<?php echo L('search')?>" name="dosubmit" class="btn btn-sm" style="padding: 0 10px;">
</div>
</form>




<form name="myform" id="myform" action="?m=zymember&c=zymember&a=manage_del" method="post" >
<div class="table-list">
<table width="100%" cellspacing="0" class="layui-table">
	<thead>
		<tr>
			<th width="35" align="center"><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></th>
            <th align="center"><strong>用户ID</strong></th>
            <th align="center"><strong>用户名</strong></th>
            <th align="center"><strong>昵称</strong></th>
            <th align="center"><strong>手机号码</strong></th>
            <th align="center"><strong>邮箱</strong></th>
            <th align="center"><strong>会员组</strong></th>
            <th align="center"><strong>注册ip</strong></th>
            <th align="center"><strong>最后登录</strong></th>
			<th align="center"><strong>金钱总数</strong></th>
            <th align="center"><strong>积分点数</strong></th>
            <th align="center"><strong>状态</strong></th>
            <th align="center"><strong>店铺申请</strong></th>
            <th align="center"><strong>操作</strong></th>
		</tr>
	</thead>
<tbody>
<?php
if(is_array($info)){
	foreach($info as $info){
		?>
	<tr <?php if($info['audit']==4){?> style="text-decoration: line-through; color: #999;"<?php } ?>>
		<td align="center" width="35"><input type="checkbox" name="id[]" value="<?php echo $info['userid']?>"></td>
        <td align="center"><?php echo $info['userid']?></td>
        <td align="center">
        <?php if(empty($info['headimgurl'])){?>
            <img src="statics/images/member/nophoto.gif" height="18" width="18" onerror="this.src='statics/images/member/nophoto.gif'">
        <?php }else{ ?>
        	<img src="<?php echo $info['headimgurl']?>" height="18" width="18">
        <?php }?>
        <?php if($info['vip']) {?><img title="VIP会员" src="<?php echo IMG_PATH?>icon/vip.gif"><?php }?>
        <?php echo $info['username']?>
        <a href="javascript:void(0);" onclick="view('<?php echo $info['userid']?>')"><img src="<?php echo IMG_PATH?>admin_img/detail.png"></a>

        </td>
        <td align="center"><?php echo $info['nickname']?></td>
        <td align="center"><?php echo $info['mobile']?></td>
        <td align="center"><?php echo $info['email']?></td>
        <td align="center">
            <?php 
                foreach($member_group as $group){
                    if($group['groupid']==$info['groupid']){
                        if($group['icon']){
                            echo '<img src="statics/'.$group['icon'].'" height="18" width="18">';
                        }
                        echo $group['name'];
                    }

                }
            ?>
        </td>

        <td align="center"><?php echo $info['regip']?></td>
        <td align="center"><?php if($info['lastdate']){?><?php echo date('Y-m-d H:i:s',$info['lastdate']);?><?php }else{ ?>--- ---<?php }?></td>
		<td align="center"><?php echo $info['amount']?></td>
		<td align="center"><?php echo $info['point']?></td>
        <td align="center">
            <?php if($info['islock']==0){ ?>
                正常
            <?php }?>
            <?php if($info['islock']==1){ ?>
               <?php if($info['islock']) {?><img title="锁定" src="<?php echo IMG_PATH?>icon/icon_padlock.gif"><?php }?> 禁用
            <?php }?>
        </td>
        <td align="center">
            <?php if($info['store_audit']==1){ ?>
                待审核<a href="javascript:void(0);" onclick="view2('<?php echo $info['userid']?>')"><img src="<?php echo IMG_PATH?>admin_img/detail.png"></a>
            <?php }?>
            <?php if($info['store_audit']==2){ ?>
               通过<a href="javascript:void(0);" onclick="view2('<?php echo $info['userid']?>')"><img src="<?php echo IMG_PATH?>admin_img/detail.png"></a>
            <?php }?>
            <?php if($info['store_audit']==3){ ?>
               驳回<a href="javascript:void(0);" onclick="view2('<?php echo $info['userid']?>')"><img src="<?php echo IMG_PATH?>admin_img/detail.png"></a>
            <?php }?>
            <?php if($info['store_audit']==0){ ?>
               无
            <?php }?>

            <?php if($info['store_islock']==0){ ?>
                <a href="?m=zymember&c=zymember&a=store_islock&userid=<?php echo $info['userid']?>&islock=1" onClick="return confirm('确认禁用?')"><img src="<?php echo APP_PATH.'statics/zymember/images/toggle_enabled.gif'?>"></a>
            <?php }?>
            <?php if($info['store_islock']==1){ ?>
                <a href="?m=zymember&c=zymember&a=store_islock&userid=<?php echo $info['userid']?>&islock=0" onClick="return confirm('确认开启?')"><img src="<?php echo APP_PATH.'statics/zymember/images/toggle_disabled.gif'?>"></a>
            <?php }?>


        </td>
        <td align="center">
            <?php if($info['proprietary']==1){ ?>
                <div class="btn-sm btn-primary" style="padding-top: 0;padding-bottom: 0; background-color: #e6e6e6">无法操作</div>
            <?php }else{?>
                <?php if($info['islock']==0){ ?>
                    <a href="?m=zymember&c=zymember&a=member_islock&userid=<?php echo $info['userid']?>&islock=1" onClick="return confirm('确认禁用?')" class="btn btn-sm" style="padding-top: 0;padding-bottom: 0;">禁用</a>
                <?php }?>
                <?php if($info['islock']==1){ ?>
                    <a href="?m=zymember&c=zymember&a=member_islock&userid=<?php echo $info['userid']?>&islock=0" onClick="return confirm('确认开启?')" class="btn btn-sm" style="padding-top: 0;padding-bottom: 0;">启用</a>
                <?php }?>

                <a href="javascript:void(0);" onclick="edit('<?php echo $info['userid']?>','<?php echo $info['username']?>')" class="btn btn-sm" style="padding-top: 0;padding-bottom: 0;">修改</a>

                <a href="javascript:void(0);" onclick="view('<?php echo $info['userid']?>')" class="btn btn-sm btn-info" style="padding-top: 0;padding-bottom: 0;">查看</a>

                <a href="?m=zymember&c=zymember&a=manage_del&id=<?php echo $info['userid']?>" onClick="return confirm('确认删除?')" class="btn btn-danger btn-sm" style="padding-top: 0;padding-bottom: 0;">删除</a>

                <?php if($info['store_audit']==1){ ?>
                    <a href="?m=zymember&c=zymember&a=store_audit&userid=<?php echo $info['userid']?>&store_audit=2&types=<?php echo $info['enterprise']?>" onClick="return confirm('确认通过?')" class="btn btn-sm" style="padding-top: 0;padding-bottom: 0;">[通过]</a>

                    <a href="javascript:void(0);" onclick="store_audit_no('<?php echo $info['userid']?>','<?php echo $info['shopname']?>')" class="btn btn-sm" style="padding-top: 0;padding-bottom: 0;">[不通过]</a>
                <?php }?>
            <?php }?>



        </td>
        </tr>
	<?php
	}
}
?>
</tbody>
</table>
</div>
<!--<div class="btn"> <label for="check_box"><?php echo L('selected_all')?>/取消</label>
<input type="submit" class="button" name="dosubmit" onClick="document.myform.action='?m=zymember&c=zymember&a=manage_del'" value="批量删除"/></div>-->
<div id="pages"><?php echo $pages?></div>
</form>
</div>





<script>
function view(id) {
    window.top.art.dialog({
        id:'view',
        iframe:'?m=zymember&c=zymember&a=manage_view&type=1&userid='+id,
        title:'会员信息',
        width:'500',
        height:'500',
        lock:true
    },
    function(){
        var d = window.top.art.dialog({id:'view'}).data.iframe;
        var form = d.document.getElementById('dosubmit');
        form.click();
        return false;
    },
    function(){
        window.top.art.dialog({id:'view'}).close()
    });
    void(0);
}
function view2(id) {
    window.top.art.dialog({
        id:'view',
        iframe:'?m=zymember&c=zymember&a=store_info&userid='+id,
        title:'店铺资料',
        width:'400',
        height:'300',
        lock:true
    },
    function(){
        var d = window.top.art.dialog({id:'view'}).data.iframe;
        var form = d.document.getElementById('dosubmit');
        form.click();
        return false;
    },
    function(){
        window.top.art.dialog({id:'view'}).close()
    });
    void(0);
}
</script>


<script>
function edit(id,name) {
    window.top.art.dialog({
        id:'edit',
        iframe:'?m=zymember&c=zymember&a=manage_edit&userid='+id,
        title:'修改会员《'+name+'》',
        width:'600',
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
function store_audit_no(id,shopname) {
    window.top.art.dialog({
        id:'edit',
        iframe:'?m=zymember&c=zymember&a=store_audit&userid='+id,
        title:'驳回店铺《'+shopname+'》',
        width:'400',
        height:'300',
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


</body>
</html>
