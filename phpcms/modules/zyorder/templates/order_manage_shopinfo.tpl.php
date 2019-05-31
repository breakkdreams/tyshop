<?php
defined('IN_ADMIN') or exit('No permission resources.');
$show_dialog = 1;
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-lr-10">
<form name="myform" id="myform" action="" method="post" >
<div class="table-list">
<table width="100%" cellspacing="0">
	<thead>
		<tr>
            <th align="center"><strong>产品图</strong></th>
            <th align="center"><strong>产品名称</strong></th>
            <th align="center"><strong>配置</strong></th>
            <th align="center"><strong>数量</strong></th>
            <th align="center"><strong>总价</strong></th>
		</tr>
	</thead>
<tbody>
<?php
if(is_array($info)){
	foreach($info as $info){
		?>
	<tr>
        <td align="center"><img src="<?php echo thumb($info['goods_img'],200,200)?>" width="30"></td>
         <td align="center"><a target="_blank" href="<?php echo $info['url']?>"><?php echo $info['goods_name']?></a></td>
         <td align="center"><?php echo $info['specid_name']?></td>
         <td align="center"><?php echo $info['goods_num']?></td>
        <td align="center"><?php echo $info['goods_price']?></td>
        
	</tr>
	<?php
	}
}
?>
</tbody>
</table>
</div>
<input name="dosubmit" type="submit" id="dosubmit" value="<?php echo L('submit')?>" class="dialog">
</form>
</div>




</body>
</html>
