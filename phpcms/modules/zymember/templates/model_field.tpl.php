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
            <legend><?php echo $title ?></legend>
            <div class="bk10"></div>
            <div class="table-list">

                <form name="myform" id="myform" action="?m=member&c=member_model&a=delete" method="post" onsubmit="check();return false;">
                    <table width="100%" cellspacing="0" class="layui-table">
                        <thead>
                            <tr>
                                <th align="left" style="width: 4%;">ID</th>
                                <th align="left" style="width: 4%;">主键</th>
                                <th align="left">类型</th>
                                <th align="left">排序规则</th>
                                <th align="left">属性</th>
                                <th align="left">空</th>
                                <th align="left">默认</th>
                                <th align="left">注释</th>
                                <th>操作</th>
                            </tr>
                        </thead>

                        <tbody> 
                           <?php foreach($sql1 as $info){ ?>
                            <tr>
                                <td align="left" style="width: 4%;"><?php echo $info['ORDINAL_POSITION'] ?></td> <!--id-->
                                <td align="left" style="width: 4%;"><span <?php if($info['COLUMN_KEY'] == PRI){ ?>style="color: red" <?php } ?>><?php echo $info['COLUMN_NAME'] ?></span></td>   <!--字段名称-->
                                <td align="left"><?php echo $info['DATA_TYPE'] ?></td>                     
                                <td align="left"><?php echo $info['COLLATION_NAME'] ?></td>
                                <td align="left"><?php echo $info['COLUMN_TYPE'] ?></td>
                                <td align="left"><?php echo $info['IS_NULLABLE'] ?></td>
                                <td align="left"><?php echo $info['COLUMN_DEFAULT'] ?></td>
                                <td align="left"><?php echo $info['COLUMN_COMMENT'] ?></td>                     
                                <td align="center">
                                <a href="#" onclick="edit('<?php echo $info['COLUMN_NAME'] ?>','<?php echo $modelid ?>')" class="btn btn-sm" style="padding-top: 0;padding-bottom: 0;">字段修改</a> <a href='?m=zymember&c=zymember&a=model_field_del&ziduan=<?php echo $info['COLUMN_NAME']?>&modelid=<?php echo $modelid?>' onClick="return confirm('非程序员请勿操作?')" class="btn btn-sm btn-danger" style="padding-top: 0;padding-bottom: 0;">删除</a>
                                </td>
                            </tr>
                               <?php } ?>
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
function edit(id, name) {
	window.top.art.dialog({id:'edit'}).close();
	window.top.art.dialog({title:'编辑字段',id:'edit',iframe:'?m=zymember&c=zymember&a=model_field_edit&modelid='+name+'&ziduan='+id,width:'550',height:'350'}, function(){var d = window.top.art.dialog({id:'edit'}).data.iframe;var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'edit'}).close()});
}
</script>

