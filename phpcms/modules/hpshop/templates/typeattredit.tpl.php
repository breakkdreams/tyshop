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


<style type="text/css">
.table_form th{text-align: left;}
</style>

<form name="myform" id="myform" action="" method="post" >
<input type="hidden" name="id" value="<?php echo $_GET['id'];?>">
<!-- <input type="hidden" name="tid" value="<?php echo $_GET['tid'];?>"> -->
<input type="hidden" id="mnam1">	
<div class="pad-10">
<div class="common-form">
	<div id="div_setting_2" class="contentList">
    
    	<fieldset>
        <legend>基本信息</legend>
		<table width="100%" class="table_form">
			<tbody>
     
				<tr>
					<th width="125">属性名称</th>  
					<td><input type="text" name="aname" id="aname" class="input-text" value="<?php echo $info['attrname'];?>" required=""><span id="balance"></span></input></td>
				</tr>

				<tr>
					<th>是否显示</th>  
					<td>
						<input type="radio" name="show" id="pts" value="1" <?php if ($info['isshow']==1) {?>checked="checked"<?php }?>>
						<label for="pts">显示</label>

				        <input type="radio" name="show" id="fxss" value="0" <?php if ($info['isshow']==0) {?>checked="checked"<?php }?>>
						<label for="fxss">不显示</label>
					</td>
				</tr>

				<tr>
					<th>属性类型</th>  
					<td>
						<input type="radio" name="status" id="pt" value="0" <?php if ($info['attrtype']==0) {?>checked="checked"<?php }?>>
						<label for="pt">输入框</label>

				        <input type="radio" name="status" id="fxs" value="1" <?php if ($info['attrtype']==1) {?>checked="checked"<?php }?>>
						<label for="fxs">多属性</label>
					</td>
				</tr> 

				<tr id="mnam2" <?php if ($info['attrtype']==0) {?>style="display: none;"<?php }?> >
					<th width="125">属性值</th>  
					<td><textarea style=" width: 500px; resize: vertical; height: 100px;" name="value" placeholder="多属性值用,隔开"><?php echo $info['attrval'];?></textarea></td>
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

<script>
	onload = function(){
	    //单选	  
	    var radios = document.getElementsByName('status');
	    for (var i = 0; i < radios.length; i++) {
	          radios[i].indexs = i + 1;
	        radios[i].onchange = function () {
	            if (this.checked) {
	                // document.getElementById("mnam1").style.display="none";
	                // document.getElementById("mnam2").style.display="none";
	                //document.getElementById("mnam" + this.indexs).style.display="block";

	                $('#mnam1').hide();
	                $('#mnam2').hide();
	                $('#mnam'+ this.indexs).show();
	                
	            } 
	        }
	    }
	}

</script>	