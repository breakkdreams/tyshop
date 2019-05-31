<?php 
	defined('IN_ADMIN') or exit('No permission resources.');
	include $this->admin_tpl('header','admin');
?>
<link rel="stylesheet" type="text/css" href="<?php echo APP_PATH?>statics/funds/layui/css/layui.css">
<script src="<?php echo APP_PATH?>statics/funds/layui/layui.all.js"></script>

<style type="text/css">
    div.btn { padding: 0px; text-indent: 1em;}
</style>

<div class="pad_10">
    <div class="table-list">
        <form name="myform" id="myform" action="?m=zyorder&c=order&a=configdel" method="post" onsubmit="checkuid();return false;" >
            <div class="table-list">
                <table width="100%" cellspacing="0" class="layui-table">
                    <thead>
                        <tr>
                            <th width="35" style="text-align:center;"><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></th>
                            <th style="text-align:center;">配置名称</th>
                            <th style="text-align:center;">API地址</th>
                            <th style="text-align:center;">所需模块</th>
                            <th style="text-align:center;">关键词</th>
                            <th style="text-align:center;">操作</th>
                        </tr>
                    </thead>
                <tbody>
                    <?php
                        foreach($info as $r)
                        {
                    ?>
                        <tr>
                            <td align="center" width="35"><input type="checkbox" name="id[]" value="<?php echo $r['id']?>"></td>
                            <td  align="center"><?php echo $r['config_name']?></td>
                            <td  align="center"><?php echo $r['url']?></td>
                            <td  align="center"><?php echo $r['model_name']?></td>
                            <td  align="center"><?php echo $r['key']?></td>
                            <td align="center">
                                <a href="" class="layui-btn layui-btn-normal layui-btn-sm" onclick="edit('<?php echo $r['id']?>')">编辑配置</a>
                                <a href="" class="layui-btn layui-btn-normal layui-btn-sm" onclick="show('<?php echo $r['id']?>')">查看详情</a>
                                <a href="javascript:confirmurl('?m=zyfunds&c=zyfunds&a=configdel&id=<?php echo $r['id'] ?>', '确定删除')"
                                   class="layui-btn layui-btn-danger layui-btn-sm">删除</a>
                            </td>
                        </tr>
                    <?php
                        }
                    ?>
                </tbody>
                </table>
            </div>
            <div class="btn" style="height:40px;line-height:40px;width:100%;text-align:left;">
                <label for="check_box">全选/取消</label>
                <input type="submit" class="layui-btn layui-btn-danger layui-btn-sm" name="dosubmit" value="批量删除" onclick="return confirm('确定删除')"/>
            </div>
        </form>
    </div>
</div>
<script>
/**
 * api地址编辑
 */
function edit(id) 
{
    window.top.art.dialog(
	{
        id:'edit',
        iframe:'?m=zyorder&c=order&a=configedit&id='+id,
        title:'配置信息',
        width:'800',
        height:'265',
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

/**
 * 文档编辑
 */
function show(id) 
{
    window.top.art.dialog(
	{
        id:'show',
        iframe:'?m=zyorder&c=order&a=configeditD&id='+id,
        title:'配置详情',
        width:'1000',
        height:window.innerHeight,
        lock:true
    },
    function()
	{
        var d = window.top.art.dialog({id:'show'}).data.iframe;
        var form = d.document.getElementById('dosubmit');
        form.click();
        return false;
    },
    function()
	{
        window.top.art.dialog({id:'show'}).close()
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
</body>
</html>