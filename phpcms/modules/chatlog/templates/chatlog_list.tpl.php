<!DOCTYPE html>
<html ng-app="demo">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="statics/resource/widget/css/RongIMWidget.min.css"/>
    <script src="statics/resource/widget/jquery-1.11.1.min.js"></script>
    <script language="javascript" type="text/javascript" src="statics/resource/My97DatePicker/WdatePicker.js"></script>
    <style>
        .addbtn{
            background-color: #0a98ec;
            color: #FFFFFF;
            padding: 5px;
            border: #0a98ec;
            border-radius: 5px;
        }
    </style>
</head>
<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>


<body ng-controller="main">
<div style="padding: 10px;">
    <input type="text" id="datacode" placeholder="时间(格式:2019051313)">
    <button class="addbtn" onclick="downloadLog()">下载聊天记录</button>
    <a href="" id="home_keleyi_com" hidden style="color: red;padding: 10px;">点击下载</a>
</div>


<input type="hidden" id="uid" value="<?php echo $shopid ?>">
<button ng-click="setconversation()" hidden>设置会话</button>
<input ng-model="targetType" hidden>
<input ng-model="targetId" hidden>
<div>
    <rong-widget></rong-widget>
</div>
<!--客服列表框-->

<form action="?m=chatlog&c=chatlog&a=chatloglist" style="padding: 0 10px 10px 10px;" method="post">
    时间: <input type="text" autocomplete="off" class="Wdate" name="createtime" onClick="WdatePicker({el:this,dateFmt:'yyyy-MM-dd'})" value="<?php echo $createtime?>">
    发送人: <input type="text" name="fromuserid" value="<?php echo $fname?>">
    接收人: <input type="text" name="touserid" value="<?php echo $tname?>">
    <input type="submit" class="addbtn">
</form>

<div class="pad-lr-10">


    <div class="table-list">
        <table width="100%" cellspacing="0">
            <thead>
            <tr>
                <th width="35" align="center">时间</th>
                <th width="35" align="center">发送人</th>
                <th width="35" align="center">接收人</th>
                <th width="35" align="center">内容</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if(is_array($infos)){
                foreach($infos as $info){
                    ?>
                    <tr>
                        <td align="center" width="35"><?php echo date('Y-m-d H:i:s',$info['create_date']);?></td>
                        <td align="center" width="35"><?php echo $info['fromusername']?></td>
                        <td align="center" width="35"><?php echo $info['tousername']?></td>
                        <td align="center" width="35"><?php echo $info['content']?></td>
                    </tr>
                    <?php
                }
            }
            ?>
            </tbody>
        </table>
    </div>

    <div id="pages"><?php echo $pages?></div>

</div>



</body>


<script src="statics/resource/widget/angular.js"></script>
<script src="statics/resource/widget/RongIMWidget.js"></script>
<script type="text/javascript">

    let demo = angular.module("demo", ["RongWebIMWidget"]);

    demo.config(function($logProvider){
        //$logProvider.debugEnabled(false);
    });

    demo.controller("main", ["$scope","WebIMWidget", "$http", function($scope,WebIMWidget,$http) {
        $scope.show = function() {
            WebIMWidget.show();
        };

        $scope.hidden = function() {
            WebIMWidget.hidden();
        };

        $scope.server = WebIMWidget;
        $scope.targetType=1;

        $scope.setconversation=function(){
            WebIMWidget.setConversation(Number($scope.targetType), $scope.targetId, "自定义:"+$scope.targetId);
        };

        WebIMWidget.init({
            appkey: "sfci50a7s3dri",
            token: "A3aZK6re2a0HlpzyIZwgK7PGhvo/EbwKE/hTc8F7A68ZiUMyXRlVoP+m5UPep5cRV0dJUlzCzvsflZxprHSU1w==",
            style:{
                width:600,
                positionFixed:true,
                bottom:20,
            },
            displayConversationList:true,
            conversationListPosition:WebIMWidget.EnumConversationListPosition.right,
            hiddenConversations:[{type:WebIMWidget.EnumConversationType.PRIVATE,id:'bb'}],
            onSuccess:function(id){
                // console.log(id);
            },
            onError:function(error){
                console.log("error:"+error);
            }
        });

        WebIMWidget.show();

        WebIMWidget.setUserInfoProvider(function(targetId,obj){
            $http({
                url:"?m=chatlog&c=chatlogApi&a=userInfo"
            }).success(function(rep){
                var user;
                rep.forEach(function(item){
                    if(item.fromuserid==targetId){
                        user=item;
                    }
                });

                if(user){
                    obj.onSuccess({id:user.touserid,name:user.tousername,portraitUri:user.portraitUri});
                }else{
                    obj.onSuccess({id:targetId,name:"陌："+targetId});
                }
            })
        });

        WebIMWidget.setOnlineStatusProvider(function(arr,obj){
            // $http({
            //     url:"?m=chatlog&c=chatlogApi&a=onLine"
            // }).success(function(rep){
            //     obj.onSuccess(rep.data);
            // })
        });


        WebIMWidget.onClose=function(){
            console.log("已关闭");
        };

        WebIMWidget.hidden();
    }]);

function downloadLog() {
    let datacode = $("#datacode").val();
    if(datacode == ''){
        alert("请输入时间");
    }
    $.ajax({
        url:'?m=chatlog&c=chatlogApi&a=downlog',
        data:{datacode:datacode},
        dataType:'json',
        type:'post',
        success:function(res){
            res = JSON.parse(res);
            if(res.url == ''){
                $('#home_keleyi_com').hide();
                alert("暂无记录");
            }else{
                $('#home_keleyi_com').show();
                $('#home_keleyi_com').attr('href',res.url);

            }
        },

    });
}
</script>
</body>
</html>
