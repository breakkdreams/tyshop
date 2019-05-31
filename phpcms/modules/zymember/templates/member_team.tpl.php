<?php
defined('IN_ADMIN') or exit('No permission resources.');
$show_dialog = 1;
include $this->admin_tpl('header', 'admin');
?>

<!-- <div class="pad-lr-10">
    <div class="explain-col">
    分销数据调用：待完成。
    </div>
</div>
<div class="bk10"></div>
 -->
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

    .top_content .line {
        margin: 10px 0;
    }
    .top_content .block {
        display: inline-block;
        width: 23%;
        font-weight: bold;
        font-size:16px;
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


<div class="explain-col search-form">
    <div style="margin-bottom: 5px;" class="top_content">
        <div class="line">
            <div class="block">团队人数：20</div>
            <div class="block">团队累计收益：200</div>
            <div class="block">团队当月收益：200</div>
            <div class="block">团队当日收益：200</div>
        </div>
        <div class="line">
            <div class="block">今日新增（人）：2</div>
            <div class="block">团队累计客户：200</div>
            <div class="block">团队当月新增客户：200</div>
            <div class="block">团队当日新增客户：200</div>            
        </div>
        <div class="line">
            <div class="block">本月新增（人）：2</div>
        </div>
        <div class="line">
            <!-- <div class="block">区域经理(人)：10</div> -->
            <div class="block">渠道经理(人)：10</div>
            <div class="block">渠道专员(人)：10</div>
            <div class="block">实习专员(人)：10</div>            
        </div>
    </div>
</div>


<form name="searchform" action="" method="get" >
<input type="hidden" value="zymember" name="m">
<input type="hidden" value="member" name="c">
<input type="hidden" value="member_team" name="a">
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
    <option value="21" <?php if ($_GET['memberinfo_types']=='21') {?>selected<?php }?>>实习专员</option>
    <option value="22" <?php if ($_GET['memberinfo_types']=='22') {?>selected<?php }?>>渠道专员</option>
    <option value="23" <?php if ($_GET['memberinfo_types']=='23') {?>selected<?php }?>>渠道经理</option>
</select>


<input type="submit" value="<?php echo L('search')?>" class="button" name="dosubmit">
</div>

<div class="explain-col search-form">
    <div style="margin-bottom: 5px;">
        <a href="?m=zymember&c=member&a=member_team&fenxiao=1" class="btn" style="margin-right: 10px;">按累计客户排名</a>
        <a href="?m=zymember&c=member&a=member_team&fenxiao=2" class="btn" style="margin-right: 10px;">按累计收益排名</a>
        <a href="?m=zymember&c=member&a=member_team&fenxiao=3" class="btn" style="margin-right: 10px;">按当月增客户排名</a>
        <a href="?m=zymember&c=member&a=member_team&fenxiao=4" class="btn" style="margin-right: 10px;">按当月收益排名</a>
        <a href="?m=zymember&c=member&a=member_team&fenxiao=5" class="btn" style="margin-right: 10px;">按当日增客户排名</a>
        <a href="?m=zymember&c=member&a=member_team&fenxiao=6" class="btn">按当日收益排名</a>
    </div>
</div>

</form>




<form name="myform" id="myform" action="?m=zymember&c=member&a=member_manage_del" method="post" >
<div class="table-list">
<table width="100%" cellspacing="0">
	<thead>
		<tr>
			<th width="35" align="center"><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></th>
            <th align="center"><strong>姓名</strong></th>
            <th align="center"><strong>等级</strong></th>
            <th align="center"><strong>手机号</strong></th>
            <th align="center"><strong>地区</strong></th>
            <th align="center"><strong>代理商身份</strong></th>
            <th align="center"><strong>累计代理商</strong></th>
            <th align="center"><strong>累计客户</strong></th>

            <!-- 这里涉及到分销 -->
            <th align="center"><strong>累计收益</strong></th>
            <th align="center"><strong>当月新增客户</strong></th>
            <th align="center"><strong>当月收益</strong></th>
            <th align="center"><strong>当日新增客户</strong></th>
            <th align="center"><strong>当日收益</strong></th>
            <th align="center"><strong>加入时间</strong></th>
            <!-- 这里涉及到分销 -->
		</tr>
	</thead>
<tbody>
<?php
if(is_array($info)){
	foreach($info as $info){
		?>
	<tr <?php if($info['audit']==4){?> style="text-decoration: line-through; color: #999;"<?php } ?>>
		<td align="center" width="35"><input type="checkbox" name="id[]" value="<?php echo $info['userid']?>"></td>
        <td align="center"><?php echo $info['userid']?>
            <?php if($info['enterprise']==1){ ?>
                （个人）
            <?php }?>
            <?php if($info['enterprise']==2){ ?>
                （企业）
            <?php }?>
        </td>
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
        <td align="center">
            <?php if($info['memberinfo_types']==21){?>
                实习专员
            <?php }?>
            <?php if($info['memberinfo_types']==22){?>
                渠道专员
            <?php }?>
            <?php if($info['memberinfo_types']==23){?>
                渠道经理
            <?php }?>
        </td>
        <td align="center"><?php echo $info['mobile']?></td>
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

        <!-- 这里涉及到分销 -->
        <td align="center"><?php echo $info['fx_ljkh']?></td>
        <td align="center"><?php echo $info['fx_ljsy']?></td>
        <td align="center"><?php echo $info['fx_dyxzkh']?></td>
        <td align="center"><?php echo $info['fx_dysy']?></td>
        <td align="center"><?php echo $info['fx_drxzkh']?></td>
        <td align="center"><?php echo $info['fx_drsy']?></td>
        <!-- 这里涉及到分销 -->

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
        iframe:'?m=zymember&c=member&a=member_manage_view&type=2&userid='+id,
        title:'用户信息',
        width:'800',
        height:'600',
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
