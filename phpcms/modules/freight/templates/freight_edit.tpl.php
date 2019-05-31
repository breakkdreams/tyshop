<?php
include $this->admin_tpl('header','admin');
?>
<link href="statics/css/style.css" rel="stylesheet">
<div class="pad_10">
    <table class="parkManageTab detailTab">
        <thead>
        <tr style="border:none">
            <th style="border-right:none;text-align:left;color:blue" colspan=3 >模板名称：<?php echo $template['template_name'] ?></th>
            <th style="border-right:none;border-left:none" colspan=2>添加时间：<?php echo date('Y-m-d H:i:s',$template['create_date']);?></th>
            <th style="border-left:none"></th>
        </tr>
        <tr>
            <th>运送方式</th>
            <th>运送到</th>
            <th>
                <?php if($template['price_way']==1){?>
                    <?php echo '首件(个)'?>
                <?php } else if($template['price_way']==2){?>
                    <?php echo '首重(kg)'?>
                <?php } else echo '首体积(m³)'?>
            </th>
            <th>首费(元)</th>
            <th>
                <?php if($template['price_way']==1){?>
                    <?php echo '续件(个)'?>
                <?php } else if($template['price_way']==2){?>
                    <?php echo '续重(kg)'?>
                <?php } else echo '续体积(m³)'?>
            </th>
            <th>续费(元)</th>
        </tr>
        </thead>
        <tbody>

        <?php
            if(is_array($shipping_way)){
                foreach($shipping_way as $info){
        ?>
            <tr>
                <td>
                    快递
                </td>
                <td><?php echo $info['area_name']?></td>
                <td><?php echo $info['first_num']?></td>
                <td><?php echo $info['first_fee']?></td>
                <td><?php echo $info['continue_num']?></td>
                <td><?php echo $info['continue_fee']?></td>
            </tr>
        <?php
                }
            }
        ?>

        </tbody>
    </table>

</div>
</body>
</html>

