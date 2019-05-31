<?php
defined('IN_ADMIN') or exit('No permission resources.');
$show_dialog = 1;
include $this->admin_tpl('header', 'admin');
?>
<link rel="stylesheet" type="text/css" href="<?php echo APP_PATH?>statics/funds/layui/css/layui.css">
<script src="<?php echo APP_PATH?>statics/funds/layui/layui.all.js"></script>
<style>
    .subnav{display:none;}
</style>
<div class="pad-lr-10" style="margin-top:20px;">
    <form name="searchform" action="" method="get" >
        <input type="hidden" value="zyaddr" name="m">
        <input type="hidden" value="zyaddr" name="c">
        <input type="hidden" value="init" name="a">

        <div class="explain-col search-form">
            <select name="type" >
                <option value="">请选择</option>
                <option value="1" <?php if ($_GET['type']==1) {?>selected<?php }?>>收件人</option>
                <option value="2" <?php if ($_GET['type']==2) {?>selected<?php }?>>手机号</option>
                <option value="3" <?php if ($_GET['type']==3) {?>selected<?php }?>>省</option>
                <option value="4" <?php if ($_GET['type']==4) {?>selected<?php }?>>市</option>
                <option value="5" <?php if ($_GET['type']==5) {?>selected<?php }?>>区</option>
                <option value="6" <?php if ($_GET['type']==6) {?>selected<?php }?>>地址</option>
            </select>
            <input type="text" value="<?php echo $_GET['q']?>" class="input-text" name="q">
            <input type="submit" value="搜索" class="layui-btn layui-btn-success layui-btn-sm">
        </div>
    </form>
<form name="myform" id="myform" action="index.php?m=zyaddr&c=zyaddr&a=del" method="post" onsubmit="checkuid();return false;" >
    <input type="hidden" value="board" name="m">
    <input type="hidden" value="board" name="c">
    <input type="hidden" value="lists" name="a">
    <div class="table-list">
        <table width="100%" cellspacing="0" class="layui-table">
            <thead>
            <tr>
                <th width="35" style="text-align:center;"><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></th>
                <th style="text-align:center;">ID</th>
                <th style="text-align:center;">用户ID</th>
                <th style="text-align:center;">收件人姓名</th>
                <th style="text-align:center;">手机号码</th>
                <th style="text-align:center;">详细地址</th>
                <th style="text-align:center;">默认</th>
                <th style="text-align:center;">操作</th>
            </tr>
            </thead>
            <tbody>
                <?php foreach($info as $k => $r){?>
                    <tr>
                        <td align="center" width="35"><input type="checkbox" name="id[]" value="<?php echo $r['id']?>"></td>
                        <td align="center"><?php echo $r['id']?></td>
                        <td align="center"><?php echo $r['userid']?></td>
                        <td align="center"><?php echo $r['name']?></td>
                        <td align="center"><?php echo $r['phone']?></td>
                        <td align="center"><?php echo $r['province']." ".$r['city']." ".$r['district']." ".$r['address']?></td>
                        <td align="center">
                            <?php if($r['default']==1){?>
                                <img src="<?php echo IMG_PATH?>toggle_enabled.gif"/>
                            <?php }else{?>
                                <img src="<?php echo IMG_PATH?>toggle_disabled.gif"/>
                            <?php }?>
                        </td>
                        <td align="center">
                            <a href="javascript:;" class="layui-btn layui-btn-success layui-btn-sm"
                               onclick="edit('<?php echo $r['id']?>')">编辑</a>
                            <a href="javascript:confirmurl('?m=zyaddr&c=zyaddr&a=del&id=<?php echo $r['id']?>', '确定删除')"
                               class="layui-btn layui-btn-danger layui-btn-sm">删除</a>
                        </td>
                    </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
    <div class="btn" style="height:45px;line-height:45px;width:100%;text-align:left;padding-top:0;">
        <label for="check_box">全选/取消</label>
        <input type="submit" class="layui-btn layui-btn-normal layui-btn-sm" name="dosubmit" value="批量删除" onclick="return confirm('确定删除')"/>
    </div>
    <div id="pages"><?php echo $pages?></div>
</form>
</div>
</body>
<script>
    function edit(id)
    {
        window.top.art.dialog(
            {
                id:'edit',
                iframe:'?m=zyaddr&c=zyaddr&a=edit&id='+id,
                title:'修改信息',
                width:'500',
                height:'400',
                lock:true
            },
            function()
            {
                var d = window.top.art.dialog({id:'edit'}).data.iframe;
                var form = d.document.getElementById('dosubmit');
                form.click();
                return false;
            },
            function()
            {
                window.top.art.dialog({id:'edit'}).close()
            });
        void(0);
    }

    function checkuid() {
        var ids='';
        $("input[name='id[]']:checked").each(function(i, n){
            ids += $(n).val() + ',';
        });
        if(ids=='') {
            window.top.art.dialog({
                    content:'请先选择记录',
                    lock:true,
                    width:'200',
                    height:'50',
                    time:1.5
                },
                function(){});
            return false;
        } else {
            myform.submit();
        }
    }
</script>
</html>
