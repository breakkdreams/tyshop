<?php
defined('IN_ADMIN') or exit('No permission resources.');
$show_dialog = 1;
include $this->admin_tpl('header', 'admin');
?>

<!-- <div class="pad-lr-10">
    <div class="explain-col">
    申请产品分类：待完成。
    </div>
</div>
<div class="bk10"></div> -->

<!-- 菜单联动 -->
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>layui/css/layui.css">
<script src="<?php echo JS_PATH?>/layui/layui.all.js"></script>

<!-- 样式库 -->
<style type="text/css">
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
    .btn-danger {
        background-color: #FF5722;
    }
    .btn-info {
        background-color: #1E9FFF;
    }


    .btn-sm {
        height: 30px;
        line-height: 30px;
        padding: 0 10px;
        font-size: 12px;
    }
    .btn-xs {
        height: 22px;
        line-height: 22px;
        padding: 0 5px;
        font-size: 12px;
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
                cityContent += '<option value="'+obj.name+'" data-id="'+obj.linkageid+'" >'+obj.name+'</option>';

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
                districeContent += '<option value="'+obj.name+'" data-id="'+obj.id+'" >'+obj.name+'</option>';

            });
            districeContent = $("#districe").html(districeContent);

            //alert('success');
          },        //当请求成功时运行的函数。
          error:function( xhr,status,error ){
            alert('error');
          },        //如果请求失败要运行的函数。
        });

    }
</script>


<div class="pad-lr-10">
<form name="searchform" action="" method="get" >
<input type="hidden" value="zymember" name="m">
<input type="hidden" value="member" name="c">
<input type="hidden" value="manager_manage" name="a">
<input type="hidden" value="<?php echo $_GET['menuid']?>" name="menuid">
<div class="explain-col search-form">
<select name="type" >
    <option value="">请选择</option>
    <option value="1" <?php if ($_GET['type']==1) {?>selected<?php }?>>用户名</option>
    <option value="2" <?php if ($_GET['type']==2) {?>selected<?php }?>>用户ID</option>
    <option value="3" <?php if ($_GET['type']==3) {?>selected<?php }?>>姓名</option>
    <option value="4" <?php if ($_GET['type']==4) {?>selected<?php }?>>手机号</option>
    <option value="5" <?php if ($_GET['type']==5) {?>selected<?php }?>>昵称</option>
</select>
<input type="text" value="<?php echo $_GET['q']?>" class="input-text" name="q">
<!-- 注册日期  <?php echo form::date('start_addtime',$_GET['start_addtime'])?><?php echo L('to')?>   <?php echo form::date('end_addtime',$_GET['end_addtime'])?> -->


<!-- 地区 -->
地区：
<select name="province" id="province" onchange ="changeCity()">
    <option value="" selected="">省</option>
    <?php foreach ($province_arr as $key) {?>
        <option value="<?php echo $key['name']?>" data-id="<?php echo $key['linkageid']?>"><?php echo $key['name']?></option>
        <!-- <option value="<?php echo $key['name']?>" <?php if ($_GET['province']==$key['name']) {?>selected<?php }?> data-id="<?php echo $key['id']?>"><?php echo $key['name']?></option> -->
    <?php }?>

</select>
<select name="city" id="city">
    <option value="" selected="">市</option>
</select>
<!-- 地区 -->

<select name="memberinfo_types">
    <option value="" selected="">等级</option>
    <option value="11" <?php if ($_GET['memberinfo_types']==11) {?>selected<?php }?>>一级</option>
    <option value="12" <?php if ($_GET['memberinfo_types']==12) {?>selected<?php }?>>二级</option>
    <option value="13" <?php if ($_GET['memberinfo_types']==13) {?>selected<?php }?>>三级</option>
</select>


<select name="disable">
    <option value="" selected="">正常/禁用</option>
    <option value="1" <?php if ($_GET['disable']==1) {?>selected<?php }?>>正常</option>
    <option value="2" <?php if ($_GET['disable']==2) {?>selected<?php }?>>禁用</option>
</select>
<!-- <select name="product_types">
    <option value="" selected="">申请产品分类</option>
    <option value="通过循环来获取" <?php if ($_GET['product_types']=='通过循环来获取') {?>selected<?php }?>>通过循环来获取</option>
    <option value="通过循环来获取" <?php if ($_GET['product_types']=='通过循环来获取') {?>selected<?php }?>>通过循环来获取</option>
</select> -->


<input type="submit" value="<?php echo L('search')?>" class="button" name="dosubmit">
</div>

</form>


<div class="explain-col search-form">
    <div style="margin-bottom: 5px;">

        <form method="post" action="?m=zymember&c=member&a=import_manager" enctype="multipart/form-data">
            <input type="file" name="file_stu"/>
            <input type="submit" name="dosubmit" value="导入" style="padding: 3px 6px;"/>
        </form>

    </div>
    <div class="clear"></div>
</div>




<form name="myform" id="myform" action="?m=zymember&c=member&a=member_manage_del" method="post" >
<div class="table-list">
<table width="100%" cellspacing="0">
    <thead>
        <tr>
            <th width="35" align="center"><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></th>
            <th align="center"><strong>用户ID</strong></th>
            <th align="center"><strong>用户帐号</strong></th>
            <th align="center"><strong>用户昵称</strong></th>
            <th align="center"><strong>真实姓名</strong></th>
            <th align="center"><strong>手机号码</strong></th>
            <th align="center"><strong>等级</strong></th>
            <th align="center"><strong>地区</strong></th>
            <th align="center"><strong>注册日期</strong></th>
            <th align="center"><strong>最后登录</strong></th>
            <th align="center"><strong>状态</strong></th>
            <th align="center"><strong>操作</strong></th>
        </tr>
    </thead>
<tbody>
<?php
if(is_array($info)){
    foreach($info as $info){
        ?>
    <tr <?php if($info['audit']==4){?> style="text-decoration: line-through; color: #999;"<?php } ?>>
        <td align="center" width="35"><input type="checkbox" name="id[]" value="<?php echo $info['userid']?>"></td>
        <td align="center"><?php echo $info['userid']?></td>
        <td align="center">
        <?php if(empty($info['headimgurl'])){?>
            <img src="statics/images/member/nophoto.gif" height="18" width="18" onerror="this.src='statics/images/member/nophoto.gif'">
        <?php }else{ ?>
            <img src="<?php echo $info['headimgurl']?>" height="18" width="18">
        <?php }?>
        <?php echo $info['username']?>
        <a href="javascript:void(0);" onclick="view('<?php echo $info['userid']?>')"><img src="<?php echo IMG_PATH?>admin_img/detail.png"></a>

        </td>
        <td align="center"><?php echo $info['nickname']?></td>
        <td align="center"><?php echo $info['name']?></td>
        <td align="center"><?php echo $info['mobile']?></td>
        <td align="center">
            <?php if($info['memberinfo_types']==11){?>
                一级
            <?php }?>
            <?php if($info['memberinfo_types']==12){?>
                二级
            <?php }?>
            <?php if($info['memberinfo_types']==13){?>
                三级
            <?php }?>
        </td>
        <td align="center">
            <?php if($info['province']){?>
                <?php echo $info['province']?>
            <?php }?>
            <?php if($info['city']){?>
                ,<?php echo $info['city']?>
            <?php }?>
            <?php if($info['districe']){?>
                ,<?php echo $info['districe']?>
            <?php }?>
        </td>

        <td align="center"><?php echo date('Y-m-d H:i:s',$info['regdate']);?></td>
        <td align="center"><?php if($info['lastdate']){?><?php echo date('Y-m-d H:i:s',$info['lastdate']);?><?php }else{ ?>--- ---<?php }?></td>
        <td align="center">
            <?php if($info['disable']==1){ ?>
                正常
            <?php }?>
            <?php if($info['disable']==2){ ?>
                禁用
            <?php }?>
        </td>
        <td align="center">
            <?php if($info['disable']==1){ ?>
                <a href="?m=zymember&c=member&a=member_disable&userid=<?php echo $info['userid']?>&disable=1" onClick="return confirm('确认禁用?')" class="btn btn-sm" style="padding-top: 0;padding-bottom: 0;">[禁用]</a>
            <?php }?>
            <?php if($info['disable']==2){ ?>
                <a href="?m=zymember&c=member&a=member_disable&userid=<?php echo $info['userid']?>&disable=2" onClick="return confirm('确认开启?')" class="btn btn-sm" style="padding-top: 0;padding-bottom: 0;">[启用]</a>
            <?php }?>

            <a href="javascript:void(0);" onclick="view('<?php echo $info['userid']?>')" class="btn btn-sm btn-info" style="padding-top: 0;padding-bottom: 0;">[查看]</a>

            <a href="?m=zymember&c=member&a=member_manage_del&id=<?php echo $info['userid']?>" onClick="return confirm('确认删除?')" class="btn btn-danger btn-sm" style="padding-top: 0;padding-bottom: 0;">[删除]</a>

        </td>
        </tr>
    <?php
    }
}
?>
</tbody>
</table>
</div>
<!--<div class="btn"> <label for="check_box"><?php echo L('selected_all')?>/取消</label>
<input type="submit" class="button" name="dosubmit" onClick="document.myform.action='?m=zysystem&c=zysystem&a=member_manage_del'" value="批量删除"/></div>-->
<div id="pages"><?php echo $pages?></div>
</form>
</div>





<script>
function view(id) {
    window.top.art.dialog({
        id:'view',
        iframe:'?m=zymember&c=member&a=member_manage_view&type=3&userid='+id,
        title:'用户信息',
        width:'500',
        height:'500',
        lock:true
    },
    function(){
        var d = window.top.art.dialog({id:'view'}).data.iframe;
        var form = d.document.getElementById('dosubmit');
        form.click();
        return false;
    },
    function(){
        window.top.art.dialog({id:'view'}).close()
    });
    void(0);
}
</script>


<script>
function edit(id) {
    window.top.art.dialog({
        id:'edit',
        iframe:'?m=zymember&c=member&a=audit_rejected&userid='+id,
        title:'用户驳回',
        width:'600',
        height:'400',
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
