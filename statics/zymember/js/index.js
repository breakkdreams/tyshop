/* 操作：编辑、删除 */
function operateFormatter(value, row, index) {
    return [
        '<div class="sui-btn-group">',
        '<a href="javascript:void(0);" class="layui-btn layui-btn-success layui-btn-sm edit">编辑</a>',
        '<a href="javascript:void(0);" class="layui-btn layui-btn-danger layui-btn-sm delete">删除</a>',
        '</div>',
    ].join('');
}

// 查询参数 数组序列：[{name: '', value: ''}]
function setsearch() {
    $searchparam = {};
    $searchparam = $("#searchform").serializeArray();
}

/* 传递参数 */
function queryparams(params) {
    if($searchparam == null){
        setsearch();
    }

    $.each($searchparam, function(i, field){
        params[field.name] = field.value;
    });

    return params;
}

/**
 * 设置表格
 * method         服务器数据的请求方式 'get' 或 'post'。
 * pagination     设置为 true, 会在表格底部显示分页条。
 * sidePagination 设置在哪里进行分页，可选值为 'client' 或者 'server'。
 *                设置 'server'时，必须设置服务器数据地址（url）或者重写ajax方法。
 * contentType    发送到服务器的数据编码类型。
 */
$.extend($.fn.bootstrapTable.defaults, {
    method: 'post',
    pagination: true,
    sidePagination: 'server',
    pageSize:10,
    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
    onClickCell: function (field, value, row, $element) {},
    onLoadSuccess: function () {}
});

/**
 * 设置表格的列
 * align   单元格对齐方式
 * valign  单元格对齐方式
 */
$.extend($.fn.bootstrapTable.columnDefaults, {
    align: 'center',
    valign: 'middle'
});