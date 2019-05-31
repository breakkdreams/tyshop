<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header','admin');
?>

<link href="statics/css/bootstrap.css" rel="stylesheet">
<script src="statics/js/bootstrap.min.js"></script>
<script src="statics/js/jquery.js"></script>
<style>
.mapbtn {
	padding: 8px 15px;
	background: #00A0E9;
	color: #fff;
	border-radius: 3px;
}
#areaAdd{
	text-align: center;
	margin-top: 20px;
}
.diqu{
	outline: none;
	border: 0;
	height:50px;
	width:500px;
	overflow:hidden
}
.default{
	display: inline-block;
	width:130px;
}
.default1{
	display: inline-block;
	width:60px;
	
}
.btn:hover{
	background-color: #0893E6;
}
.fl{
	float:left; 
}
.flw{
	float:left;
	width:80px; 
}
.table {
    width: 100%;
    border-width: 1px 0px 0px 1px;
    border-color: #bbb;
    border-style: solid;
	margin-left:60px;
}
.table td,th{
	border-width: 0px 1px 1px 0px;
    border-color: #bbb;
    /* border-style: solid; */
}
.input-text {
    height: 20px;
    line-height: 20px;
	width:65px;
    padding: 1px 0;
    border: 1px solid #7f9db9;
}
.area-group{
    padding-right: 3em;
    display: block;
	width:280px;
	float:left;
}
.link{
	color:#0893E6;
	cursor: pointer;
}
.area-edit{
	margin-right:10px;
	display:block;
	float: right;
}
.mysub{
    padding: 5px 10px;
    border-radius: 10px;
    background-color: #0893E6;
    color: white;
}
</style>
<script type="text/javascript">

	// $(function(){
	// $.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){window.top.art.dialog({content:msg,lock:true,width:'200',height:'50'}, function(){this.close();$(obj).focus();})}});
	// })
	var pc_hash = GetQueryString('pc_hash');

	function GetQueryString(name) {
		var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
		var r = window.location.search.substr(1).match(reg);
		if (r != null) return unescape(r[2]); return null;
	}

	//地区三级联动,收获地址修改加载省城市地址
	function gsloadRegions(type, target,parent,sel){
		
		$.get("?m=freight&c=freight&a=regionlist&pc_hash="+pc_hash,{ parent: parent},function(result){
			result = JSON.parse(result);
			if(result&&result.length>0){
				$("#"+target).empty();
				for(var i=0; i<result.length; i++){
					if(i == 0) {
						var txt = "";
						if(type == 1) {
							txt = "请选择省";
						}else if(type == 2) {
							txt = "请选择市";
						}else if(type == 3) {
							txt = "请选择区";
						}
						var d_item = $('<option></option>').val(0).text(txt);
						$("#"+target).append(d_item);
					}
					var li_item = $('<option></option>').val(result[i].region_id).text(result[i].region_name);
					if(result[i].region_id == sel) {
						li_item.attr('selected', true);
					}
					$("#"+target).append(li_item);
				}
			}
		});
	}

	

function add_table(event){
	var price_way=$(event).attr("price_way");
	$p=$(event).parent().parent().find("p").eq(0);
	if(price_way==1){
		$p.after(kuaidi_anjian_table);
		add_tr(event);
	}
	if(price_way==2){
		$p.after(kuaidi_anzhong_table);
	}
	if(price_way==3){
		$p.after(kuaidi_antiji_table);
	}
	$(event).attr("onclick","add_tr(this)");
}	




var a = 0;
var m =0;
//ajax获取区域列表
$.ajax({
	type : "GET",
	url : "?m=freight&c=freight&a=getarea&pc_hash="+pc_hash,
	dataType : "json",
	success : function(data){
		var html = '';
		$(data).each(function(k,v){
			html += '<p style="line-height:30px;"><input type="checkbox" onclick="checkAll(this)" value="aa"><span style="font-weight:bold;display:inline;">'+v.large_area_name+"</span>&ensp;&ensp;&ensp;&ensp;";
				var childname = v.arr_child_name.split(",");
				var childid = v.arr_child.split(",");
				for(var i =0;i<childname.length;i++){
					html += '<input type="checkbox" name=chk value="'+childid[i]+"-"+childname[i]+'">'+childname[i]+"&ensp;&ensp;&ensp;"
				}
			html +='</p>'
		})
		$('.hide_body').html(html);
	}
});
function removeDiv(e){
        $(e).parent().parent().remove();
    }
 function checkAll(obj){
	$(obj).parent().find('input').prop('checked', $(obj).prop('checked'));
}
 function fn(e){
	 $(".modal-body").find("input").removeAttr("disabled");
	 //m = $(e).parent().find('textarea').attr('id');
	 m = $(e).parent().find('p').attr('id');
	 /* 判断选中和禁用 */
	 var xuanzhong_id = $(e).parent().find('input').eq(1).val();
	 var quanbuyixuan_id = $(e).parent().parent().parent().find("input[name$='][area_id]']").map(function(index,elem) {
		 if(($(elem).val())!=''){
			 return $(elem).val()
		 }
		}).get().join(',');
		/* 禁用的地区*/ 	 
		var arr2=new Array();
		 arr2=quanbuyixuan_id.split(',');
	   $("input:checkbox[name='chk']").each(function(){
			 var id=$(this).val().split('-')[0];
			 for(i=0;i<arr2.length;i++){
				  if(arr2[i]==id){
					  $(this).attr("disabled","disabled");
					  $(this).parent().find('input').eq(0).prop("disabled","disabled");
				  }
				 }
		 })
		 
	/* 选中的地区*/
	var arr=new Array();
	arr=xuanzhong_id.split(',');
   $("input:checkbox[name!='shipping_way[]']").each(function(){
		 this.checked=false;
		 var id=$(this).val().split('-')[0];
		 for(i=0;i<arr.length;i++){
			  if(arr[i]==id){
				  this.checked=true;
				  $(this).removeAttr("disabled");
			  }
			 }
	 })
	 
}
function closeDiv(){
	text = $("input:checkbox[name='chk']:checked").map(function(index,elem) {
		return $(elem).val().split('-')[1]
	}).get().join(',');
	textId = $("input:checkbox[name='chk']:checked").map(function(index,elem) {
		return $(elem).val().split('-')[0]
	}).get().join(',');
	$('#'+m).text(text);
	$('#'+m+'name').val(text);
	$('#'+m+'id').val(textId);
}

</script>

<div class="pad_10">
<form action="?m=freight&c=freight&a=add" method="post" name="myform" id="myform">
<table cellpadding="2" cellspacing="1" class="table_form" width="100%">
	
	<tr>
		<th width="100"><?php echo '模板名称'?>：</th>
		<td colspan="12"><input type="text" name="template_name" id="template_name" size="30" class="input-text"></td>
	</tr>
	
	<tr>
		<th width="100"><?php echo '宝贝地址'?>：</th>
		<td>
		<select style="border: 1px solid #CCC;" class="ddcg_select fc_66" name="country" id="selCountries" onchange="gsloadRegions(1,'selProvinces', this.value,'province')">
			<option value="0">请选择国家</option>
			<option value="<?php echo $country['region_id']?>"><?php echo $country['region_name']?></option>
		</select>  
		<select style="border: 1px solid #CCC;" name="province" id="selProvinces" onchange="gsloadRegions(2, 'selCities', this.value, 'city')">
			<option value="0">请选择省</option>
		</select>  
		<select style="border: 1px solid #CCC;" name="city" id="selCities" onchange="gsloadRegions(3, 'selDistricts', this.value, 'area')">
			<option value="0">请选择市</option>
		</select> 
		<select style="border: 1px solid #CCC;" name="area" id="selDistricts" >
			<option value="0">请选择区</option>
		</select><span style="color:red;" id='area_err'></span>	
		</td>
	</tr>

	<tr id="logolink">
		<th width="100"><?php echo '是否包邮'?>：</th>
		<td>
			<input type="radio" name="is_free" value="0" id="is_free0" checked/>自定义运费<span class="default1"></span>
			<input type="radio" name="is_free" value="1" id="is_free1"/>卖家承担运费
		</td>
	</tr>
	
	<tr>
		<th width="100"><?php echo '计价方式'?>：</th>
		<td>
			<input type="radio" name="price_way" value="1" id="price_way0" checked/>按件数<span class="default1"></span>
			<input type="radio" name="price_way" value="2" id="price_way1"/>按重量<span class="default1"></span>
			<input type="radio" name="price_way" value="3" id="price_way2"/>按体积
		</td>
	</tr>
 
	<tr>
		<th><?php echo '运送方式'?>：</th>
		<td><span>除指定地区外，其余地区的运费采用“默认运费”</span></td>
	</tr>

	<tr>
		<th></th>
		<td><input type="checkbox" name="shipping_way[]" value="0" price_way="1" id="kuaidi" />快递</p>
			<div id="jifeifangshi_kuaidi"></td>
	</tr>


	<tr>
		<th></th>
		<td>
            <input type="submit" name="dosubmit" id="dosubmit" class="mysub" value="提交">
        </td>
	</tr>

</table>

<div id="myModal2" class="modal" data-easein="fadeInRight"
		data-easeout="fadeOutRight" tabindex="-1" role="dialog"
		aria-labelledby="myModalLabel" aria-hidden="true" style="top: 20%";>
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title">选择运送地区</h4>
				</div>
				<div class="modal-body">
					<div class="hide_body">
					</div>

				</div>
				<div class="modal-footer">
			     <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">关闭</button>
                 <button class="btn btn-primary" onclick="closeDiv()" data-dismiss="modal" aria-hidden="true">确认</button>
				</div>
			</div>
		</div>
	</div>
</form>
</div>
<script>
$(":radio[name='price_way']").change(function(){
		if(this.checked==true){
			if(confirm('切换计价方式后，所设置当前模板的运输信息将被清空，确定继续吗？')){
				$(":checkbox[name='shipping_way[]']").attr("price_way",$(this).val()).attr("checked",false);
				$("#jifeifangshi_pingyou").empty();
				$("#jifeifangshi_ems").empty();
				$("#jifeifangshi_kuaidi").empty();
			}	
		}
	})

	var kuaidi_anjian_table='<table class="table" id="table">'
			+'<tr>'
			+'<th width="200">运送到</th>'
			+'<th width="70">首件（个）</th>'
			+'<th width="70">首费（元）</th>'
			+'<th width="70">续件（个）</th>'
			+'<th width="70">续费（元）</th>'
			+'<th width="50">操作</th>'
			+'</tr>'
			+'</table>';
var kuaidi_anzhong_table='<table class="table">'
			+'<tr>'
			+'<th width="200">运送到</th>'
			+'<th width="70">首重（kg）</th>'
			+'<th width="70">首费（元）</th>'
			+'<th width="70">续重（kg）</th>'
			+'<th width="70">续费（元）</th>'
			+'<th width="50">操作</th>'
			+'</tr>'
			+'<tr>'
			+'<td style="text-algin:right;">'
			+'<div class="area-group">'
			+'<p id="a">未指定区域</p>'
			+'</div>'
			+'<a href="#myModal2" class="link area-edit" data-toggle="modal" onclick="fn(this)">编辑</a>'
			+'<input id="aname" name="other[0][area_name]" type="hidden" value="">'
			+'<input type="hidden" id="aid" name="other[0][area_id]" value="" >'
			+'</td>'
			+'<td>'
			+'<input class="flw" type="hidden" name="other[0][shipping_way]" value="0"/>'
			+'<input type="text" name="other[0][first_num]" value="1" class="input-text">'
			+'</td>'
			+'<td><input type="text" name="other[0][first_fee]" value="1" class="input-text"></td>'
			+'<td><input type="text" name="other[0][continue_num]" value="1" class="input-text"></td>'
			+'<td><input type="text" name="other[0][continue_fee]" value="1" class="input-text"></td>'
			+'<td><a class="link" onclick="removeDiv(this)">删除</a></td>'
			+'</tr>'
			+'</table>';
var kuaidi_antiji_table='<table class="table">'
			+'<tr>'
			+'<th width="200">运送到</th>'
			+'<th width="70">首体积（m³）</th>'
			+'<th width="70">首费（元）</th>'
			+'<th width="70">续体积（m³）</th>'
			+'<th width="70">续费（元）</th>'
			+'<th width="50">操作</th>'
			+'</tr>'
			+'<tr>'
			+'<td style="text-algin:right;">'
			+'<div class="area-group">'
			+'<p id="a">未指定区域</p>'
			+'</div>'
			+'<a href="#myModal2" class="link area-edit" data-toggle="modal" onclick="fn(this)">编辑</a>'
			+'<input id="aname" name="other[0][area_name]" type="hidden" value="">'
			+'<input type="hidden" id="aid" name="other[0][area_id]" value="" >'
			+'</td>'
			+'<td>'
			+'<input class="flw" type="hidden" name="other[0][shipping_way]" value="0"/>'
			+'<input type="text" name="other[0][first_num]" value="1" class="input-text">'
			+'</td>'
			+'<td><input type="text" name="other[0][first_fee]" value="1" class="input-text"></td>'
			+'<td><input type="text" name="other[0][continue_num]" value="1" class="input-text"></td>'
			+'<td><input type="text" name="other[0][continue_fee]" value="1" class="input-text"></td>'
			+'<td><a class="link" onclick="removeDiv(this)">删除</a></td>'
			+'</tr>'
			+'</table>';
			var b=0;

$(":checkbox[name='shipping_way[]']").change(function(){
	var id=$(this).attr("id");
	if(this.checked){
		b++;
		var kuaidi_anjian='<div id="kuaidishow" style="padding-left:60px;">'
			+'<span class="default1 fl">默认运费：</span>'
			+'<input class="flw" type="hidden" name="default['+b+'][shipping_way]" value="0"/>'
			+'<input class="flw" type="hidden" name="default['+b+'][area_id]" value="1"/>'
			+'<input class="flw" type="hidden" name="default['+b+'][is_default]" value="1"/>'
			+'<input class="flw" type="text" name="default['+b+'][first_num]" value="1"/>'
			+'<span class="fl">件内</span>'
			+'<input class="flw" type="text"  name="default['+b+'][first_fee]"  value="1"/>'
			+'<span class="fl">元，每增加：</span>'
			+'<input class="flw" type="text" name="default['+b+'][continue_num]" value="1"/>'
			+'<span class="fl">件，增加运费:</span>'
			+'<input class="flw" type="text"  name="default['+b+'][continue_fee]"  value="1"/>'
			+'<span class="fl">元</span>'
			+'</div>'
			+'<p>&nbsp;</p>'
			+'<span style="margin-left:60px;" ><a class="link" price_way="1" onclick="add_table(this)" >为指定地区设置运费</a></span>';
			
var kuaidi_anzhong='<div id="kuaidishow" style="padding-left:60px;">'
			+'<span class="default1 fl">默认运费：</span>'
			+'<input class="flw" type="hidden" name="default['+b+'][shipping_way]" value="1"/>'
			+'<input class="flw" type="hidden" name="default['+b+'][area_id]" value="1"/>'
			+'<input class="flw" type="hidden" name="default['+b+'][is_default]" value="1"/>'
			+'<input class="flw" type="text" name="default['+b+'][first_num]" value="1"/>'
			+'<span class="fl">kg内</span>'
			+'<input class="flw" type="text"  name="default['+b+'][first_fee]"  value="1"/>'
			+'<span class="fl">元，每增加：</span>'
			+'<input class="flw" type="text" name="default['+b+'][continue_num]" value="1"/>'
			+'<span class="fl">kg，增加运费:</span>'
			+'<input class="flw" type="text"  name="default['+b+'][continue_fee]"  value="1"/>'
			+'<span class="fl">元</span>'
			+'</div>'
			+'<p>&nbsp;</p>'
			+'<span id="kuaidispan" style="margin-left:60px;" ><a class="link" price_way="2" onclick="add_table(this)" >为指定地区设置运费</a></span>';;
var kuaidi_antiji='<div id="kuaidishow" style="padding-left:60px;">'
			+'<span class="default1 fl">默认运费：</span>'
			+'<input class="flw" type="hidden" name="default['+b+'][shipping_way]" value="2"/>'
			+'<input class="flw" type="hidden" name="default['+b+'][area_id]" value="1"/>'
			+'<input class="flw" type="hidden" name="default['+b+'][is_default]" value="1"/>'
			+'<input class="flw" type="text" name="default['+b+'][first_num]" value="1"/>'
			+'<span class="fl">㎥内</span>'
			+'<input class="flw" type="text"  name="default['+b+'][first_fee]"  value="1"/>'
			+'<span class="fl">元，每增加：</span>'
			+'<input class="flw" type="text" name="default['+b+'][continue_num]" value="1"/>'
			+'<span class="fl">㎥，增加运费:</span>'
			+'<input class="flw" type="text"  name="default['+b+'][continue_fee]"  value="1"/>'
			+'<span class="fl">元</span>'
			+'</div>'
			+'<p>&nbsp;</p>'
			+'<span id="kuaidispan" style="margin-left:60px;" ><a class="link" price_way="3" onclick="add_table(this)" >为指定地区设置运费</a></span>';

			$("#jifeifangshi_"+id).show();
		var price_way=$(this).attr("price_way");
		if(id=="kuaidi"){
			if(price_way==1){
				$("#jifeifangshi_kuaidi").html(kuaidi_anjian);
			}
			if(price_way==2){
				$("#jifeifangshi_kuaidi").html(kuaidi_anzhong);
			}
			if(price_way==3){
				$("#jifeifangshi_kuaidi").html(kuaidi_antiji);
			}
			$("#jifeifangshi_kuaidi").find(".link").attr("shipping_way","0");
			$("#jifeifangshi_kuaidi").find("input[name$='][shipping_way]']").val(0);
		}
	}else{
		$("#jifeifangshi_"+id).hide();
	}
})

function add_tr(data){
	b++;
	var shipping_way=$(data).attr("shipping_way");
	var kuaidi_anjian_tr='<tr>'
		+'<td style="text-algin:right;">'
		+'<div class="area-group">'
		+'<p id="a'+b+'">未指定区域</p>'
		+'<input id="a'+b+'name" name="other['+b+'][area_name]" type="hidden" value="">'
		+'</div>'
		+'<a href="#myModal2" class="link area-edit" data-toggle="modal" onclick="fn(this)">编辑</a>'
		+'<input type="hidden" id="a'+b+'id" name="other['+b+'][area_id]" value="" >'
		+'<input type="hidden" id="a'+b+'id1" name="other['+b+'][is_default]" value="0" >'
		+'<input type="hidden" id="a'+b+'id2" name="other['+b+'][shipping_way]" value="'+shipping_way+'" >'
		+'</td>'
		+'<td><input type="text" name="other['+b+'][first_num]" value="1" class="input-text"></td>'
		+'<td><input type="text" name="other['+b+'][first_fee]" value="1" class="input-text"></td>'
		+'<td><input type="text" name="other['+b+'][continue_num]" value="1" class="input-text"></td>'
		+'<td><input type="text" name="other['+b+'][continue_fee]" value="1" class="input-text"></td>'
		+'<td><a class="link" onclick="removeDiv(this)">删除</a></td>'
		+'</tr>';
		
	$(data).parent().parent().find("table").append(kuaidi_anjian_tr);	
}	

/* 点击包邮的操作 */
$("#is_free0").change(function(){
	$("#kuaidi,#price_way0,#price_way1,#price_way2,input[name='shipping_way[]']").removeAttr('disabled');
});
$("#is_free1").change(function(){
	if(this.checked==true){
		alert("选择'卖家承担运费'后，所有区域的运费将无法设置！");
		//隐藏运费编辑区域
		$("#jifeifangshi_kuaidi,#jifeifangshi_ems,#jifeifangshi_pingyou").hide();
		//移除复选框单击事件
		$("#kuaidi,#price_way0,#price_way1,#price_way2,input[name='shipping_way[]']").prop('disabled','disabled');
		$("#kuaidi,#ems,#pingyou").prop('checked',false);
		/* $("#kuaidi").unbind('click'); */
	}
	
})
</script>
</body>
</html> 