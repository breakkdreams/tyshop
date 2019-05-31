<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>

<link rel="stylesheet" type="text/css" href="<?php echo APP_PATH?>statics/funds/layui/css/layui.css">
<script src="<?php echo APP_PATH?>statics/funds/layui/layui.all.js"></script>


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

input[type="radio"] + label::before { content: "\a0"; /*不换行空格*/ display: inline-block; vertical-align: middle; font-size: 16px; width: 1em; height: 1em; margin-right: .4em; border-radius: 50%; border: 1px solid #01cd78; text-indent: .15em; line-height: 1; margin-right: 5px; margin-top: 5px; -moz-box-sizing: border-box;  /*Firefox3.5+*/-webkit-box-sizing: border-box; /*Safari3.2+*/-o-box-sizing: border-box; /*Opera9.6*/-ms-box-sizing: border-box; /*IE8*/box-sizing: border-box; margin-top: -2px;}
input[type="radio"]:checked + label::before { background-color: #01cd78; background-clip: content-box; padding: .18em; font-size: 16px;}
input[type="radio"] { position: absolute; clip: rect(0, 0, 0, 0);}

.chk_1,.chk_2,.chk_3,.chk_4 { display: none;}
.chk_1 + label { background-color: #FFF; border: 1px solid #C1CACA; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05), inset 0px -15px 10px -12px rgba(0, 0, 0, 0.05);
 padding: 9px; border-radius: 5px; display: inline-block; position: relative; margin-right: 120px;}
.chk_1 + label:active { box-shadow: 0 1px 2px rgba(0,0,0,0.05), inset 0px 1px 3px rgba(0,0,0,0.1);}
.chk_1:checked + label { background-color: #ECF2F7; border: 1px solid #01cd78;/*#92A1AC;*/ box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05), inset 0px -15px 10px -12px rgba(0, 0, 0, 0.05), inset 15px 10px -12px rgba(255, 255, 255, 0.1); color: #243441;}
.chk_1:checked + label:after { content: '\2714'; position: absolute; top: -3px; left: 0px; color: #01cd78;/*#758794;*/ width: 100%; text-align: center; font-size: 1.3em; padding: 1px 0 0 0; vertical-align: text-top;}
.chk_1 + label span{ position: absolute; left: 25px; top: -1px; width: 120px;}


</style>

<script type="text/javascript">
  $(document).ready(function() {
	$.formValidator.initConfig({autotip:true,formid:"myform",onerror:function(msg){}});	
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
		<table width="100%" class="table_form" id="mytable">
			<tbody>
     
				<tr>
					<th width="125">商品名称</th>  
					<td><input type="text" style="width: 50%;" name="gname" id="gname" class="input-text" value="<?php echo $info['goods_name'];?>" required=""><span id="balance"></span></input></td>
				</tr>

				<tr>
					<th width="125">商品简述</th>  
					<td><textarea rows="4" cols="60" name="summary" style="resize: vertical;"><?php echo $info['summary'];?></textarea></td>
				</tr>

                <tr>
                    <th width="125">所属店铺</th>
                    <td>
                        <select name="shopid" required="required" >
                            <option value="">请选择店铺</option>
                            <?php
                            if(is_array($minfo)){
                                foreach($minfo as $v){
                                    ?>
                                    <option value="<?php echo $v['userid']?>" <?php if ($info['shopid']==$v['userid']) {?>selected<?php }?> ><?php echo $v['nickname']?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th width="125">运费模板</th>
                    <td>
                        <select name="template_id" required="required" >
                            <option value="">请选择运费模板</option>
                            <?php
                            if(is_array($fnfos)){
                                foreach($fnfos as $v){
                                    ?>
                                    <option value="<?php echo $v['template_id']?>" <?php if ($info['template_id']==$v['template_id']) {?>selected<?php }?>  ><?php echo $v['template_name'] ;?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>

				<tr>
					<th width="125">所属栏目</th>  
					<td>
						<select name="cid" required="required" >
						    <option value="">请选择栏目</option>
						    <?php 
								if(is_array($cinfo)){	
									foreach($cinfo as $v){
							?>  
						    <option value="<?php echo $v['id'] ;?>" <?php if ($info['catid']==$v['id']) {?>selected<?php }?>><?php if ($v['level'] != 0) echo '|'; ?><?php echo str_repeat('-', $v['level']*8)?><?php echo $v['cate_name'] ;?></option>
						    <?php 
									}
								}
							?>
						</select>
					</td>
				</tr>

				<tr id="addtrId">
					<th width="125">商品类型</th>  
					<td>
						<select name="tid" required="required" >
						    <option value="">请选择商品类型</option>
						    <?php 
								if(is_array($tinfo)){	
									foreach($tinfo as $t){
							?>  
						    <option value="<?php echo $t['id'] ;?>" <?php if ($info['type_id']==$t['id']) {?>selected<?php }?>><?php echo $t['type_name'] ;?></option>
						    <?php 
									}
								}
							?>
						</select>
					</td>
				</tr>

				<?php 
				if( !empty($gattr) && $info['isspec'] == 1 ){	
				?> 
				<tr class="cylstr">
					<th width="125">商品规格</th>  
					<td>
						<table id="spectable" class="layui-table">
							<thead>
								<tr>
									<?php
										foreach($gattr as $kn=>$vn){
									?> 
									<th><?php echo $vn['attrname'];?></th>
									<?php 
										}
									?>
									<th>本店价</th>
									<th>库存</th>
									<th>操作</th>
								</tr>
							</thead>

							<tbody>
								<tr>
									<?php
										foreach($gattr as $kn=>$vn){
									?> 
									<td></td>
									<?php 
										}
									?>
									
									<td><input type="text" id="tcj" placeholder="请输入价格" class="layui-input"></td>
									<td><input type="text" id="tck" placeholder="请输入库存" class="layui-input"></td>
									<td><button type="button" onclick="filldata()" class="layui-btn  layui-btn-sm layui-btn-normal"><i class="layui-icon">&#xe631;</i>批量添加</button></td>
								</tr>

								<?php 
								if(is_array($gspec)){	
									foreach($gspec as $ks=>$vs){
								?>
								<?php $zarr = explode(',',$vs['specids']) ;?>
								<tr>
									<?php
										foreach($zarr as $kz=>$vz){
									?> 
									<td><?php echo $vz;?></td>
									<?php 
										}
									?>
									<input type="hidden" name="goodsspecs[<?php echo $vs['id']?>][open]" value="<?php echo $vs['status']; ?>">
									<td><input type="text" name="goodsspecs[<?php echo $vs['id']?>][bprice]" required placeholder="请输入价格" class="layui-input bp" value="<?php echo $vs['specprice']; ?>"></td>
									<td><input type="text" name="goodsspecs[<?php echo $vs['id']?>][stock]" placeholder="请输入库存" class="layui-input st" value="<?php echo $vs['specstock']; ?>"></td>
									<input type="hidden" name="goodsspecs[<?php echo $vs['id']?>][key]" value="<?php echo $vs['specid']; ?>">
									<input type="hidden" name="goodsspecs[<?php echo $vs['id']?>][val]" value="<?php echo $vs['specids']; ?>">

									<?php 
									if($vs['status'] == 1){	
									?>
									<td><button type="button" onclick="changeopen(this)" class="layui-btn  layui-btn-sm layui-btn-primary"><i class="layui-icon">&#xe620;</i>禁用</button></td>
									<?php 
									}else{	
									?>
									<td><button type="button" onclick="changeopen(this)" class="layui-btn  layui-btn-sm "><i class="layui-icon">&#xe620;</i>启用</button></td>
									<?php 
									}
									?>

								</tr>
								<?php 
									}
								}
								?>	


							</tbody>
						</table>

                    </td>
				</tr>
				<?php 
					}
				?>	

				<?php 
				if(is_array($gattrs)){	
					foreach($gattrs as $k=>$v){
				?> 
				<tr class="cylstr">
					<th width="125"><?php echo $v['attrname'];?></th>  
					<td><input type="text" name="goods_attrs[<?php echo $v['id'];?>][val]" class="input-text" value="<?php echo $v['val'];?>" required="required"></input>
					<input  type="hidden" name="goods_attrs[<?php echo $v['id'];?>][aid]" value="<?php echo $v['attrid']?>"></td>
				</tr>
				<?php 
					}
				}
				?>	


				<tr>
					<th>商品主图</th>
					<td>
						<div style="width: 161px; text-align: center;">
							<div class='upload-pic img-wrap'><input type='hidden' name='thumb' id='thumb' value='<?php echo $info['thumb'];?>'>
								<a href='javascript:void(0);' onclick="flashupload('thumb_images', '附件上传','thumb',thumb_images,'1,jpg|jpeg|gif|png|bmp,1,,,0','content','6','<?php echo $authkey;?>');return false;">
				<img src='<?php if($info['thumb']) { echo $info['thumb']; } else { echo "statics/images/icon/upload-pic.png"; } ?>' id='thumb_preview' width='135' height='113' style='cursor:hand; margin-left: 13px;' /></a><!-- <input type="button" style="width: 66px;" class="button" onclick="crop_cut_thumb($('#thumb').val());return false;" value="裁切图片"> --><input type="button" style="width: 66px;" class="button" onclick="$('#thumb_preview').attr('src','http://localhost/cs/statics/images/icon/upload-pic.png');$('#thumb').val('');return false;" value="取消图片"><script type="text/javascript">function crop_cut_thumb(id){
		if (id=='') { alert('请先上传缩略图');return false;}
		window.top.art.dialog({title:'裁切图片', id:'crop', iframe:'index.php?m=content&c=content&a=public_crop&module=content&catid='+0+'&picurl='+encodeURIComponent(id)+'&input=thumb&preview=thumb_preview', width:'680px', height:'480px'}, 	function(){var d = window.top.art.dialog({id:'crop'}).data.iframe;
		d.uploadfile();return false;}, function(){window.top.art.dialog({id:'crop'}).close()});
	};</script>
							</div>
						</div>
					</td>
				</tr>

				<tr>
			      <th width="80">商品相册</th>
			      <td><input name="goodsimg" type="hidden" value="1">
					<fieldset class="blue pad-10">
					<legend>图片列表</legend>	
					<?php 
						if(empty($alinfo)){	
							
					?>	
			        <center><div class='onShow' id='nameTip'>您最多可以同时上传 <font color='red'>10</font> 张</div></center><div id="goodsimg" class="picList"></div>
			        <?php 
						}else{
							foreach($alinfo as $ks=>$vs){
					?>
			        
			        <div id='image_caseimg_<?php echo $ks;?>' style='padding:1px'>
			        	<img src="<?php echo $vs['url'];?>" height="40" />
			        	<input type='text' name='goodsimg_url[]' value='<?php echo $vs['url'];?>' style='width:310px;' ondblclick='image_priview(this.value);' class='input-text'> 
			        	<input type='text' name='goodsimg_alt[]' value='<?php echo $vs['alt'];?>' style='width:160px;' class='input-text'> <a href="javascript:remove_div('image_caseimg_<?php echo $ks;?>')">移除</a>
			        </div>
			        <?php 
						}
					}
					?>
			        <div id="goodsimg" class="picList"></div>

					</fieldset>
					<div class="bk10"></div>
					<script type="text/javascript" src="statics/js/swfupload/swf2ckeditor.js"></script><div class='picBut cu'><a href='javascript:void(0);' onclick="javascript:flashupload('goodsimg_images', '附件上传','goodsimg',change_images,'10,gif|jpg|jpeg|png|bmp,0','content','6','<?php echo $authkeys;?>')"/> 选择图片 </a></div>  </td>

			    </tr>

                <tr>
					<th>上架</th>  
					<td>
						<input type="radio" name="status" id="pt" value="1" <?php if ($info['on_sale']==1) {?>checked="checked"<?php }?>>
						<label for="pt">是</label>
						&nbsp;&nbsp;&nbsp;&nbsp;
				        <input type="radio" name="status" id="fxs" value="2" <?php if ($info['on_sale']==2) {?>checked="checked"<?php }?>>
						<label for="fxs">否</label>
					</td>
				</tr>

				<tr>
					<th width="125">市场价</th>  
					<td><input type="number" name="mprice" class="input-text" value="<?php echo $info['market_price'];?>" required="required"></input></td>
				</tr>
				<tr>
					<th width="125">本店价</th>  
					<td><input type="number" name="sprice" class="input-text" value="<?php echo $info['shop_price'];?>" required="required"></input></td>
				</tr>
                <tr>
                    <th width="125">积分价</th>
                    <td><input type="number" name="scoreprice" class="input-text" value="<?php echo $info['score_price'];?>" required="required"></input></td>
                </tr>
				<tr>
					<th width="125">总库存</th>  
					<td><input type="number" name="stock" class="input-text" value="<?php echo $info['stock'];?>"></input><span> （若填写了商品规格则可不填）</span></td>
				</tr>


                <tr>
                    <th width="125">重量</th>
                    <td><input type="number" name="weight" class="input-text" value="<?php echo $info['weight']?>"/><span> （单位:g）</span></td>
                </tr>

                <tr>
                    <th width="125">体积</th>
                    <td><input type="number" name="volume" class="input-text" value="<?php echo $info['volume']?>"/><span> （单位:m³）</span></td>
                </tr>

				<tr>
					<th width="125">商品内容信息</th>  
					<td>
						<div id='content_tip'></div><textarea name="content" id="content" boxid="content"><?php echo $info['content'];?></textarea><script type="text/javascript" src="statics/js/ckeditor/ckeditor.js"></script>
						<script type="text/javascript">
							CKEDITOR.replace( 'content',{height:300,pages:true,subtitle:true,textareaid:'content',module:'content',catid:'0',
							flashupload:true,alowuploadexts:'',allowbrowser:'1',allowuploadnum:'10',authkey:'<?php echo $authkeyss;?>',
							filebrowserUploadUrl : 'index.php?m=attachment&c=attachments&a=upload&module=content&catid=0&dosubmit=1',
							toolbar :
							[
								['Source','-','Templates'],
							    ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print'],
							    ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],['ShowBlocks'],['Image','Capture','Flash','flashplayer','MyVideo'],['Maximize'],
							    '/',
							    ['Bold','Italic','Underline','Strike','-'],
							    ['Subscript','Superscript','-'],
							    ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
							    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
							    ['Link','Unlink','Anchor'],
							    ['Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
							    '/',
							    ['Styles','Format','Font','FontSize'],
							    ['TextColor','BGColor'],
							    ['attachment'],
							]
							});
						</script>
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

<!-- <script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>swfupload/swf2ckeditor.js"></script> -->
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>content_addtop.js"></script>
<script type="text/javascript">
    $("select[name=tid]").change(function(){
        var tid=$(this).val();
        $.ajax({
            type:"POST",
            url:"index.php?m=hpshop&c=goods&a=getattr"+"&pc_hash="+"<?php echo $_SESSION['pc_hash'];?>",
            data:{tid:tid},
            dataType:"json",
            success:function(data){
                var list = document.getElementsByClassName('cylstr');

				for (i = list.length-1; i >= 0; --i){
			        list[i].remove();	    	
				}

                $(data.attr).each(function(k,v){
         
					var tr = document.getElementById("mytable").insertRow();
					tr.className="cylstr";

					tr.innerHTML = "<th width='125'>"+v.attrname+"</th><td><input type='text' name=goods_attr["+v.id+"] class='input-text' required='required' ></input></td>";
					$("#addtrId").after(tr);				
					                  
                });
                if(data.spec != 0){
                	var tr = document.getElementById("mytable").insertRow();
					tr.className="cylstr";

					var html = "<th width='125'>"+"商品规格"+"</th><td>";
					html += '<table id="spectable" class="layui-table"><thead><tr>';
					$(data.specname).each(function(k,v){
                        html+="<th>"+v.attrname+"</th>";
                    });
                    html += "<th>本店价</th><th>库存</th><th>操作</th>";
                    html += '</tr></thead><tbody>';
                    html += '<tr>';	
                    $(data.specname).each(function(k,v){
                        html+="<td></td>";
                    });
                    html+='<td><input type="text" id="tcj" placeholder="请输入价格" class="layui-input"></td>';
	                html+='<td><input type="text" id="tck" placeholder="请输入库存" class="layui-input"></td>';
	                html+='<td><button type="button" onclick="filldata()" class="layui-btn  layui-btn-sm layui-btn-normal"><i class="layui-icon">&#xe631;</i>批量添加</button></td>';
	                html += '</tr>';    
                    for(var i=0; i<data.spec.length; i++){
                    	html += '<tr>';	
                        $(data.specname).each(function(k,v){
	                        html+="<td>"+data.spec[i][k]+"</td>";
	                    });
	                    html+='<input type="hidden" name="goodsspec['+i+'][open]" value="1">';
	                    html+='<td><input type="text" name="goodsspec['+i+'][bprice]" required placeholder="请输入价格" class="layui-input bp"></td>';
	                    html+='<td><input type="text" name="goodsspec['+i+'][stock]" placeholder="请输入库存" class="layui-input st"></td>';
	                    html+='<td><button type="button" onclick="changeopen(this)" class="layui-btn  layui-btn-sm layui-btn-primary"><i class="layui-icon">&#xe620;</i>禁用</button></td>';
	                    
	                    html+='<input type="hidden" name="goodsspec['+i+'][key]" value="'+data.spec[i]['keys']+'">';
	                    html+='<input type="hidden" name="goodsspec['+i+'][val]" value="'+data.spec[i]['vals']+'">';
	                    html += '</tr>';	
                    }
    				html += '</tbody></table></td>';
    				tr.innerHTML = html;
					$("#addtrId").after(tr);
                }
            }
        });
    });

    function addrow(o){
        var div=$(o).parent();
        if($(o).html() == '[+]'){
            var newdiv=div.clone();    
            newdiv.find('a').html('[-]');
            newdiv.find('a').css("font-size","15px");;
            div.after(newdiv);
        }else{
            div.remove();
        }
    }

    function filldata(){
        var bp = $('#tcj').val();
        var st = $('#tck').val();

        $(".bp").val(bp);
        $(".st").val(st);
    }

    function changeopen(o){
        if($(o).hasClass('layui-btn-primary')){

        	$(o).removeClass('layui-btn-primary');
        	$(o).html('<i class="layui-icon">&#xe620;</i>启用');
        	var no = $(o).parent().parent().find("input[type=hidden]")[0];
        	$(no).val(0);
        }else{
        	$(o).addClass('layui-btn-primary');
        	$(o).html('<i class="layui-icon">&#xe620;</i>禁用');
        	var no = $(o).parent().parent().find("input[type=hidden]")[0];
        	$(no).val(1);
        }
    }

</script>
