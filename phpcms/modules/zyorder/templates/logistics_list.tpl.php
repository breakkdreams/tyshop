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
       
            <th align="center"><strong>订单编号</strong></th>
            <th align="center"><strong>联系人</strong></th>
            <th align="center"><strong>联系电话</strong></th>
            <th align="center"><strong>收货地址</strong></th>
			<th align="center"><strong>状态</strong></th>
			<th align="center"><strong>操作</strong></th>
        </tr>
    </thead>
<tbody>
<?php
if(is_array($info)){
    foreach($info as $info1){
        ?>
    <tr>
     
        <td align="center"><?php echo $info1['id']?></td>
		<td align="center"><?php echo $info1['lx_name']?></td>
		<td align="center"><?php echo $info1['lx_mobile']?></td>
		<td align="center"><?php echo $info1['province']?><?php echo $info1['city']?><?php echo $info1['area']?><?php echo $info1['address']?></td>	
		<td align="center"><?php 
		switch($info1['status']){
			case 3: echo '已发货';break;
		    default : echo '状态出错';break;
		}?></td>
		<td align="center"><a onclick="view(<?php echo $info1['id']?>)"><font color="blue">查看物流</font></a></td>
    </tr>
    <?php
    }
}
?>
</tbody>
</table>
<div id="pages"><?php echo $pages?></div>


</form>
</div>



<style type="text/css">
    .red {
        color:red;
    }
</style>
<script>
function view(id) {
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
