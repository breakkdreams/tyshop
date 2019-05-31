// JavaScript source code

$(function () {

    //取消订单
    $('.cancel').on('click', function () {
        var othis = $(this);
        layer.confirm('确定取消订单？', {
            btn: ['确定', '取消'] //按钮
        }, function () {
            layer.msg('删除成功', {
                icon: 1,
                time: 1000
            });
            setTimeout(function () { othis.parent().parent().parent().remove() }, 1000);//略微延时与上方同步
        }, function () {

        });
    });

    //付款
    $('.payment').on('click', function () {
        window.location.href = "订单中心-付款.html";
    });
    //提醒发货
    $('.remind').on('click', function () {
        layer.msg('提醒卖家发货成功');
    });
    //查看物流
    $('.logistics').on('click', function () {
        window.location.href = "物流详情.html";
    });
    //确认收货
    $('.confirm-receipt').on('click', function () {
        layer.confirm('确认收货？<br>（到货后再确认收货）', {
            btn: ['确定', '取消'] //按钮
        }, function () {
            layer.prompt({ title: '请输入支付密码，并确认', formType: 1 }, function (pass) {
                layer.msg('确认收货成功', {
                    time: 1000
                });
                setTimeout(function () { layer.closeAll() }, 1000);
            });
        }, function () {

        });
    });
    //删除订单
    $('.delete').on('click', function () {
        var othis = $(this);
        layer.confirm('确定取消订单？', {
            btn: ['确定', '取消'] //按钮
        }, function () {
            layer.msg('删除成功', {
                icon: 1,
                time: 1000
            });
            setTimeout(function () { othis.parent().parent().parent().remove() }, 1000);//略微延时与上方同步
            ;
        }, function () {

        });
    });
    //评价
    $('.evaluate').on('click', function () {
        window.location.href = "评价待完成.html";
    });
});