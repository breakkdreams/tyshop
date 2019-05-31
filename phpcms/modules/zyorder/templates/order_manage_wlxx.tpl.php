<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<style type="text/css">
.table_form th{text-align: left;}
.subnav { padding:0px 10px; }
</style>
 <link rel="stylesheet" href="<?php echo LAYUI_PATH?>css/layui.css">
 <script src="<?php echo JS_PATH?>order/layer.js"></script>
<div class="pad-10">
    <div class="col-tab">
       <div id="div_setting_2" class="contentList pad-10">
           <fieldset>
            <legend>物流单号信息</legend>
            <table width="100%" class="table_form">
             <tbody>
                <tr>
                   <th width="100"><strong>快递类型</strong></th>
                   <td ><?php echo $order['shipper_name']?></td>
                   <td id='kuaidi_xx' style="display:none"><?php echo $order['shipper_code']?></td>
               </tr>
               <tr>
                   <th><strong>快递单号</strong></th>
                   <td id='bianhao_xx'><?php echo $order['logistics_order']?></td>
               </tr>
           </tbody>
       </table>
   </fieldset>
   <div class="bk15"></div>
   <fieldset>
      <legend>物流信息</legend>
      <table width="100%" class="table_form">
         <tbody id='tbody'>
            <ul  class="layui-timeline">
           <?php foreach($logisticResult['Traces'] as $info) {?>
                <li class="layui-timeline-item">
                    <i class="layui-icon layui-timeline-axis"></i>
                    <div class="layui-timeline-content layui-text">
                        <div class="layui-timeline-title"><?php echo $info['AcceptStation']?></div>
                        <div><?php echo $info['AcceptTime']?></div>
                    </div>
                </li>
          <?php }?>
            </ul>
        </tbody>
    </table>
</fieldset>

</div>

</div>
</div>
</body>
</html>
