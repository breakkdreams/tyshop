<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>

<style type="text/css">
    /*隐藏radio按钮*/
input[type="radio"] { opacity: 0;}
.myradio { display: inline-block; vertical-align: middle; margin: 0; padding: 0; width: 70px; height: 24px; border-radius: 20px; position: relative; overflow: hidden;}
.mrclose { background-color: #f40;/*#e8e8e8;*/}
.mropen { background-color: #67e66c;}
.myradio .open, .myradio .close { width: 22px; height: 22px; font-size: 13px; border-radius: 50%; background: #fff; color: #fff; position: absolute; top: 0; left: 0; border: 1px solid #e8e8e8;}
.myradio .open { color: #fff; background-color: #fff;}
.hidden { display: none}
.disabled { pointer-events: none; cursor: default;}
.myradio .close { left: auto; right: 0; }
.myradio .open:after { content: '开启'; position: absolute; top: 0; left: 30px; width: 28px; height: 24px; line-height: 22px; }
.myradio .close:before { content: '关闭'; position: absolute; top: 0; left: -35px; width: 28px; height: 24px; line-height: 22px;}

input[type="radio"] + label::before { content: "\a0"; /*不换行空格*/ display: inline-block; vertical-align: middle; font-size: 16px; width: 1em; height: 1em; margin-right: .4em; border-radius: 50%; border: 1px solid #01cd78; text-indent: .15em; line-height: 1; margin-left: 10px; margin-top: 5px; -moz-box-sizing: border-box;  /*Firefox3.5+*/-webkit-box-sizing: border-box; /*Safari3.2+*/-o-box-sizing: border-box; /*Opera9.6*/-ms-box-sizing: border-box; /*IE8*/box-sizing: border-box; margin-top: -2px;}
input[type="radio"]:checked + label::before { background-color: #01cd78; background-clip: content-box; padding: .18em; font-size: 16px;}
input[type="radio"] { position: absolute; clip: rect(0, 0, 0, 0);}

</style>

<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>member_common.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>formvalidator.js" charset="UTF-8"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>formvalidatorregex.js" charset="UTF-8"></script>



<style type="text/css">
.table_form th{text-align: left;}
</style>

<form name="myform" id="myform" action="" method="post" >
<div class="pad-10">
<div class="common-form">
    <div id="div_setting_2" class="contentList">
    
        <fieldset>
        <legend>添加配置</legend>
        <table width="100%" class="table_form">
            <tbody>
     
                <tr>
                    <th width="125">配置名称</th>  
                    <td><input type="text" name="config_name" id="name" class="input-text" required=""><span id="balance"></span></input></td>
                </tr>
        
                <tr>
                    <th width="125">API地址</th>  
                    <td><input type="text" name="url" id="contact" class="input-text"><span id="balance"></span></input></td>
                </tr>
                
                <tr>
                    <th width="125">所需模块</th>  
                    <td>
                        <select name="model_name">
                        <?php foreach($into as $r)
                        {
                        ?>              
                        <option value="<?php echo $r['module']." ".$r['name'] ?>"><?php echo $r['module']." ".$r['name'] ?></option>
                        <?php 
                        }
                        ?>
                    </select>
                    </td>
                </tr>

            </tbody>
        </table>
        </fieldset>
        <div class="bk15"></div>
    </div>
<input class="dialog" name="dosubmit" id="dosubmit" type="submit" value="确认"/>

</div>

</div>
</div>
</form>

</body>
</html>

<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>swfupload/swf2ckeditor.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>content_addtop.js"></script>
