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

<script type="text/javascript">
  $(document).ready(function() {
	$.formValidator.initConfig({autotip:true,formid:"myform",onerror:function(msg){}});	
	$("#cname").formValidator({onshow:"请填写分类名称",onfocus:"请填写分类名称"}).inputValidator({min:1,max:999,onerror:"分类名称不能为空"}).ajaxValidator({

	    type : "get",
		url : "",
		data :"m=hpshop&c=goods&a=checkcat_ajax&id=<?php echo $info['id'];?>",
		datatype : "html",
		async:'false',
		success : function(data){
		    var s = data.indexOf('FALSE');	
            if(s == -1)
			{
				$("#balances").html('');
                return true;
			}
            else
			{
				$("#balances").html(data);
                return false;
			}
		},
		buttons: $("#dosubmit"),
		onerror : "<?php echo L('分类名称已经存在')?>",
		onwait : "<?php echo L('正在查询')?>"
	});
  });

</script>


<style type="text/css">
.table_form th{text-align: left;}
</style>

<form name="myform" id="myform" action="" method="post" >
<input type="hidden" name="id" value="<?php echo $info['id'];?>">
<div class="pad-10">
<div class="common-form">
	<div id="div_setting_2" class="contentList">
    
    	<fieldset>
        <legend>基本信息</legend>
		<table width="100%" class="table_form">
			<tbody>
     			<tr>
					<th width="125">上级分类</th>  
					<td>
						<select name="pid" >
						    <option value="0">顶级分类</option>
						    <?php 
								if(is_array($infos)){	
									foreach($infos as $v){
							?>  
						    <option value="<?php echo $v['id'] ;?>" <?php if ($info['pid']==$v['id']) {?>selected<?php }?>><?php if ($v['level'] != 0) echo '|'; ?><?php echo str_repeat('-', $v['level']*8)?><?php echo $v['cate_name'] ;?></option>
						    <?php 
									}
								}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<th width="125">分类名称</th>  
					<td><input type="text" name="cname" id="cname" class="input-text" value="<?php echo $info['cate_name'];?>" required=""><span id="balance"></span></input></td>
				</tr>
				<tr>
					<th>分类栏目图片</th>
					<td>
						<div class='upload-pic img-wrap'><input type='hidden' name='thumb' id='thumb' value='<?php echo $info['cate_img'];?>'>
							<a href='javascript:void(0);' onclick="flashupload('thumb_images', '附件上传','thumb',thumb_images,'1,jpg|jpeg|gif|png|bmp,1,,,0','content','6','<?php echo $authkey;?>');return false;">
			<img src='<?php if($info['cate_img']) { echo $info['cate_img']; } else { echo "statics/images/icon/upload-pic.png"; } ?>' id='thumb_preview' width='135' height='113' style='cursor:hand' /></a><input type="button" style="width: 66px;" class="button" onclick="crop_cut_thumb($('#thumb').val());return false;" value="裁切图片"><input type="button" style="width: 66px;" class="button" onclick="$('#thumb_preview').attr('src','statics/images/icon/upload-pic.png');$('#thumb').val(' ');return false;" value="取消图片"><script type="text/javascript">function crop_cut_thumb(id){
	if (id=='') { alert('请先上传缩略图');return false;}
	window.top.art.dialog({title:'裁切图片', id:'crop', iframe:'index.php?m=content&c=content&a=public_crop&module=content&catid='+0+'&picurl='+encodeURIComponent(id)+'&input=thumb&preview=thumb_preview', width:'680px', height:'480px'}, 	function(){var d = window.top.art.dialog({id:'crop'}).data.iframe;
	d.uploadfile();return false;}, function(){window.top.art.dialog({id:'crop'}).close()});
};</script>
						</div>
					</td>
				</tr>
                <tr>
					<th>显示到导航</th>  
					<td>
						<input type="radio" name="status" id="pt" value="1" <?php if ($info['isshow']==1) {?>checked="checked"<?php }?>>
						<label for="pt">是</label>

				        <input type="radio" name="status" id="fxs" value="2" <?php if ($info['isshow']==2) {?>checked="checked"<?php }?> >
						<label for="fxs">否</label>
					</td>
				</tr>

				<tr>
					<th width="125">描述</th>  
					<td><textarea style=" width: 98%; height: 108px; resize: vertical;" name="desc"><?php echo $info['description'];?></textarea></td>
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
