<?php
defined('IN_ADMIN') or exit('No permission resources.');
$show_dialog = 1;
include $this->admin_tpl('header', 'admin');
?>



<div class="pad-lr-10">
<form name="searchform" action="" method="get" >
<input type="hidden" value="zyorder" name="m">
<input type="hidden" value="order" name="c">
<input type="hidden" value="order_logistics" name="a">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
        <tr>
        <td>
        <div class="explain-col">

            快递公司：<input name="name" type="text" value="<?php if(isset($_GET['name'])) {echo $_GET['name'];}?>" class="input-text" />
                   <input type="submit" name="search" class="button" value="<?php echo L('search')?>" />
        </div>
        </td>
        </tr>
    </tbody>
</table>
</form>
<form name="myform" id="myform" action="" method="post" >
<div class="table-list">
<table width="100%" cellspacing="0">
    <thead>
        <tr>
            <th width="35" align="center"><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></th>
            <th align="center"><strong>编号</strong></th>
            <th align="center"><strong>快递公司</strong></th>
            <th align="center"><strong>公司代码</strong></th>

        </tr>
    </thead>
<tbody>
<?php
if(is_array($info)){
    foreach($info as $info1){
        ?>
    <tr>
        <td align="center" width="35"><input type="checkbox" name="id[]" value="<?php echo $info1['id']?>"></td>
        <td align="center"><?php echo $info1['id']?></td>

         <td align="center"><a href="#" onClick="edit(<?php echo $info1['id']?>);"><?php echo $info1['name']?></a></td>
         <td align="center"><?php echo $info1['value']?></td>

    </tr>
    <?php
    }
}
?>
</tbody>
</table>

<div class="btn"><a href="#" onClick="javascript:$('input[type=checkbox]').attr('checked', true)"><?php echo L('selected_all')?></a>/<a href="#" onClick="javascript:$('input[type=checkbox]').attr('checked', false)"><?php echo L('cancel')?></a> <input type="submit" class="button" name="dosubmit" value="<?php echo L('delete')?>" onClick="document.myform.action='?m=zyorder&c=order&a=order_logistics_del';return confirm('确定删除？')"/></div>
<div id="pages"><?php echo $pages?></div>


</form>
</div>



<style type="text/css">
    .red {
        color:red;
    }
</style>
<script>
function edit(id) {
    window.top.art.dialog({
        id:'edit',
        iframe:'?m=zyorder&c=order&a=order_logistics_edit&id='+id,
        title:'修改物流',
        width:'400',
        height:'200',
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
