<?php defined('IN_ADMIN') or exit('No permission resources.');?>
<?php include $this->admin_tpl('header', 'admin');?>

<style>
    .clear{ clear: both; }
    .btn:hover{text-decoration: none;}
    .btn {display: inline-block; height: 34px; line-height: 34px; padding: 0 14px; background-color: #009688; color: #fff; white-space: nowrap; text-align: center; font-size: 14px; border: none; border-radius: 2px; cursor: pointer; transition: all .3s; -webkit-transition: all .3s; box-sizing: border-box;}
    .btn:hover {opacity: .8;color: #fff;}
    .btn-primary {
        background-color: #fff;
        border: 1px solid #C9C9C9;
        color: #555;
    }
    .btn-warm {
        background-color: #FFB800;
    }

    #province,#city,#districe{
        width: 100px;
    }

</style>

<!-- 地区联动js -->
<script type="text/javascript">
    /**
     * [changeCity 选择省，出现市]
     * @return {[type]} [description]
     */
    function changeCity(){
        var provinceId = $("#province option:selected").attr("data-id");
        var cityContent = '<option value="" selected="">市</option>';
        $("#districe").html('<option value="" selected="">区</option>');


        $.ajax({
          url: "<?php echo  APP_PATH?>index.php?m=zymember&c=json&a=city_json",
          data: {
            provinceid: provinceId
          },        //规定要发送到服务器的数据。
          dataType:'json',
          type:'GET',       //规定请求的类型（GET 或 POST）。
          success: function( result,status,xhr ) {
            $.each(result, function(i, obj) {
                cityContent += '<option value="'+obj.name+'" data-id="'+obj.id+'" >'+obj.name+'</option>';

            });
            cityContent = $("#city").html(cityContent);

            //alert('success');
          },        //当请求成功时运行的函数。
          error:function( xhr,status,error ){
            alert('error');
          },        //如果请求失败要运行的函数。
        });

    }
    /**
     * [changeDistrice 选择市，出现区]
     * @return {[type]} [description]
     */
    function changeDistrice(){
        var cityId = $("#districe option:selected").attr("data-id");
        var districeContent = '<option value="" selected="">区</option>';

        $.ajax({
          url: "<?php echo  APP_PATH?>index.php?m=zymember&c=json&a=districe_json",
          data: {
            cityid: cityId
          },        //规定要发送到服务器的数据。
          dataType:'json',
          type:'GET',       //规定请求的类型（GET 或 POST）。
          success: function( result,status,xhr ) {


            $.each(result, function(i, obj) {
                districeContent += '<option value="'+obj.name+'" data-id="'+obj.linkageid+'" >'+obj.name+'</option>';

            });
            districeContent = $("#districe").html(districeContent);

            //alert('success');
          },        //当请求成功时运行的函数。
          error:function( xhr,status,error ){
            alert('error');
          },        //如果请求失败要运行的函数。
        });

    }
    /**
     * [enterprise 判断是否是公司]
     * @param  {Number} type [description]
     * @return {[type]}      [description]
     */
    function enterprise(type=1){
    	if (type==1) {
    		$("#enterprise_sf").hide();
    	}else{
    		$("#enterprise_sf").show();
    	}
    }


	/**
     * [productTypes 选择产品大类-出现小类]
     * @return {[type]} [description]
     */
    function productTypes(){
        var product_types = $("#product_types option:selected").attr("data-id");
        var typesContent = '<option value="" selected="">申请产品分类</option>';

        $.ajax({
          url: "<?php echo  APP_PATH?>index.php?m=zymember&c=json&a=product_json",
          data: {
            product_types: product_types
          },        //规定要发送到服务器的数据。
          dataType:'json',
          type:'GET',       //规定请求的类型（GET 或 POST）。
          success: function( result,status,xhr ) {


            $.each(result, function(i, obj) {
                typesContent += '<option value="'+obj.language+'" data-id="'+obj.id+'" >'+obj.language+'</option>';

            });
            typesContent = $("#product_types2").html(typesContent);

            //alert('success');
          },        //当请求成功时运行的函数。
          error:function( xhr,status,error ){
            alert('error');
          },        //如果请求失败要运行的函数。
        });

    }

</script>

<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>formvalidator.js" charset="UTF-8"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>formvalidatorregex.js" charset="UTF-8"></script>
<script type="text/javascript">

<!--
$(function(){
	$.formValidator.initConfig({autotip:true,formid:"myform",onerror:function(msg){}});
	$("#nickname").formValidator({onshow:"请输入昵称",onfocus:"应该为2-20位之间"}).inputValidator({min:2,max:20,onerror:"应该为2-20位之间"}).regexValidator({regexp:"ps_username",datatype:"enum",onerror:"昵称格式错误"});
	$("#name").formValidator({onshow:"请输入姓名",onfocus:"应该为2-20位之间"}).inputValidator({min:2,max:20,onerror:"应该为2-20位之间"}).regexValidator({regexp:"ps_username",datatype:"enum",onerror:"姓名格式错误"});
	$("#idcard").formValidator({onshow:"请输入身份证号",onfocus:"应该为15-24位之间"}).inputValidator({min:2,max:20,onerror:"应该为15-24位之间"}).regexValidator({regexp:"ps_username",datatype:"enum",onerror:"身份证格式错误"});




	$("#password").formValidator({onshow:"请输入密码",onfocus:"密码应该为6-20位之间"}).inputValidator({min:6,max:20,onerror:"密码应该为6-20位之间"});
	$("#pwdconfirm").formValidator({onshow:"请输入确认密码",onfocus:"请输入两次密码不同。",oncorrect:"密码输入一致"}).compareValidator({desid:"password",operateor:"=",onerror:"请输入两次密码不同。"});
	/*$("#point").formValidator({tipid:"pointtip",onshow:"请输入积分点数，积分点数将影响会员用户组",onfocus:"积分点数应该为1-8位的数字"}).regexValidator({regexp:"^\\d{1,8}$",onerror:"积分点数应该为1-8位的数字"});
	$("#email").formValidator({onshow:"请输入邮箱",onfocus:"邮箱格式错误",oncorrect:"邮箱格式正确"}).inputValidator({min:2,max:32,onerror:"邮箱应该为2-32位之间"}).regexValidator({regexp:"email",datatype:"enum",onerror:"邮箱格式错误"}).ajaxValidator({
	    type : "get",
		url : "",
		data :"m=member&c=member&a=public_checkemail_ajax",
		datatype : "html",
		async:'false',
		success : function(data){
            if( data == "1" ) {
                return true;
			} else {
                return false;
			}
		},
		buttons: $("#dosubmit"),
		onerror : "禁止注册或邮箱已存在",
		onwait : "请稍候..."
	});*/


	$("#mobile").formValidator({onshow:"请输入手机号",onfocus:"手机号不能为空"}).inputValidator({min:1,max:999,onerror:"手机号不能为空"}).ajaxValidator({
	    type : "get",
		url : "",
		data :"m=zymember&c=verify&a=public_checkmobile_ajax",
		datatype : "html",
		async:'false',
		success : function(data){
            if(data==1)
			{
                return true;
			}
            else
			{
                return false;
			}
		},
		buttons: $("#dosubmit"),
		onerror : "<?php echo L('该用户已存在')?>",
		onwait : "<?php echo L('正在查询')?>"
	});
});
//-->
</script>
<div class="pad-10">
<div class="common-form">
<form name="myform" action="?m=zymember&c=member&a=agent_manage_add" method="post" id="myform">

<div class="explain-col">
	<fieldset>
		<legend>基本信息</legend>
		<table width="100%" class="table_form">
			<tr>
				<td width="90">昵称</td>
				<td><input type="text" name="nickname" id="nickname" value="" class="input-text"></input></td>
			</tr>
			<tr>
				<td>真实姓名</td>
				<td><input type="text" name="name" id="name" value="" class="input-text"></input></td>
			</tr>
			<tr>
				<td>身份证号</td>
				<td><input type="text" name="idcard" id="idcard" value="" class="input-text" size="30"></input></td>
			</tr>

			<tr>
				<td>手机号码</td>
				<td>
				<input type="text" name="mobile" class="input-text" id="mobile" size="15"></input>
				</td>
			</tr>
			<tr>
				<td>密码</td>
				<td><input type="password" name="password" class="input-text" id="password" value=""></input></td>
			</tr>
			<tr>
				<td>确认密码</td>
				<td><input type="password" name="pwdconfirm" class="input-text" id="pwdconfirm" value=""></input></td>
			</tr>
			<tr>
				<td>地区</td>
				<td>
				<select name="province" id="province" onchange ="changeCity()">
				    <option value="" selected="">省</option>
				    <?php foreach ($province_arr as $key) {?>
				        <option value="<?php echo $key['name']?>" data-id="<?php echo $key['linkageid']?>"><?php echo $key['name']?></option>
				    <?php }?>
				</select>
				<select name="city" id="city">
				    <option value="" selected="">市</option>
				</select>
				<!-- <select name="districe" id="districe">
				    <option value="" selected="">区</option>
				</select> -->
				</td>
			</tr>
			<tr>
				<td>产品分类</td>
				<td>
				<select name="product_types" id="product_types" onchange ="productTypes()">
				    <option value="" selected="">申请产品分类</option>

				    <?php foreach($result as $info){?>
				    	<option value="<?php echo $info['language']?>" data-id="<?php echo $info['id']?>"><?php echo $info['language']?></option>
				    <?php }?>

				</select>
				<select name="product_types2" id="product_types2">
				    <option value="" selected="">申请产品分类</option>
				</select>
				</td>
			</tr>
			<tr>
				<td>等级</td>
				<td>
				<select name="memberinfo_types" id="memberinfo_types">
				    <option value="21" selected="">实习专员</option>
				    <option value="22">渠道专员</option>
				    <option value="23">渠道经理</option>
				</select>
				</td>
			</tr>
			<tr>
				<td>是否让其认证</td>
				<td>
					<input type="radio" name="certification" checked id="certification1" value="1"><label for="certification1">否</label>
					<input type="radio" name="certification" id="certification2" value="2"><label for="certification2">是</label>
				</td>
			</tr>
			<tr>
				<td>是否让其禁用</td>
				<td>
					<input type="radio" name="disable" checked id="disable1" value="1"><label for="disable1">否</label>
					<input type="radio" name="disable" id="disable2" value="2"><label for="disable2">是</label>
				</td>
			</tr>
			<tr>
				<td>个人/企业</td>
				<td>
					<input type="radio" name="enterprise" checked id="enterprise1" value="1" onclick="enterprise('1')"><label onclick="enterprise('1')" for="enterprise1">个人</label>
					<input type="radio" name="enterprise" id="enterprise2" value="2" onclick="enterprise('2')"><label onclick="enterprise('2')" for="enterprise2">企业</label>
				</td>
			</tr>
		</table>
	</fieldset>
</div>

<div class="bk15"></div>

<fieldset id="enterprise_sf" style="display: none;">
	<legend>企业信息</legend>
	<table width="100%" class="table_form">
			<tr>
				<td width="90">单位公司</td>
				<td><input type="text" name="company" id="company" value="" size="40" class="input-text" placeholder="请输入公司全称"></input><a onclick="company_ajax()" class="btn" style="font-size: 10px;height: 26px;line-height: 26px;margin-left: 10px;">验证数据</a></td>
			</tr>
			<tr>
				<td>纳税识别号</td>
				<td><input type="text" name="credit_code" id="credit_code" size="40" value="" class="input-text" readonly="readonly"  placeholder="无法输入"></input></td>
			</tr>
			<tr>
				<td width="90">法人</td>
				<td><input type="text" name="oper_name" id="oper_name" value="" class="input-text" readonly="readonly"  placeholder="无法输入"></input></td>
			</tr>
			<tr>
				<td>股东</td>
				<td><input type="text" name="artners_name" id="artners_name" value="" class="input-text" readonly="readonly" placeholder="无法输入"></input></td>
			</tr>

	</table>
</fieldset>


    <div class="bk15"></div>
    <input name="dosubmit" type="submit" id="dosubmit" value="<?php echo L('submit')?>" class="dialog">
</form>
</div>
</div>








</body>
</html>
