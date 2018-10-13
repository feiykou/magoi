function edit(title,url) {
    var index = layer.open({
        type: 2,
        title: title,
        content: url
    });
    layer.full(index);
}

/* 添加或者编辑缩小的屏幕 */
function s_edit(title,url,w,h) {
    layer_show(title,url,w,h);
}


/* 删除 */
function del(id,url){
    layer.confirm("确认要删除吗？",function(index){
        window.location.href = url;
    });
}


function btn_del(obj,url){
    layer.confirm('确认要删除吗？',function(index){
        $.ajax({
            type: 'POST',
            url: url,
            dataType: 'json',
            success: function(data){
                $(obj).parents("tr").remove();
                layer.msg('已删除!',{icon:1,time:1000});
            },
            error:function(data) {
                layer.msg(data.msg,{icon:1,time:1000});
            },
        });
    });
}


var loadPage = {
    trimBr: function (str) {
        str=str.replace(/\\r\\n|\\n|&nbsp;|^"|"$|\\|tttt/g,"");
        return str;
    },
    init: function (opts) {
        var that = this;
        // 点击编辑
        opts.clickDom.on("click",function(){
            var curThat = $(this);
            var param_str = '';
            if(opts.attrArr){
                opts.attrArr.forEach(function(attr){
                    param_str += ("&"+attr+"="+curThat.data(attr+''));
                })
                if(param_str.indexOf('&') === 0){
                    param_str = param_str.replace(/&/,'?');
                }
            }
            opts.modalConDom.load(opts.url+param_str,'',function(result){
                opts.modalConDom.html(that.trimBr(result));
            });
        });
    }
};


/**
 *
 * 针对图片上传获取字符串
 * $childsDom  元素集
 * attr        属性值
 * delimiter   分隔符
 * len         截取个数
 *
 **/
function setUpdateUrl($childsDom,attr,delimiter,len){
    var params = "";
    var index = 0;
    var delimiter = delimiter?delimiter:";";
    var attr = attr?attr:"src";
    len = len?len:false;
    for(var i=0;i<$childsDom.length;i++){
        $li = $childsDom.eq(i);
        if($li.hasClass("state-complete")){
            index++;
            if(len && index>len){
                break;
            }
            params += $li.data(attr) + delimiter;
        }
    }
    // 删除最后一个分隔符
    params = params.substring(0,params.length-1);
    return params;
}


// 删除选中的列表
function getSelectAllTr(url){

    $childrens = $(".table .table-body").children();

    $childsArr = Array.prototype.slice.call($childrens);
    var idsArr = [];
    var checkDom = [];
    $childsArr.forEach(function(trDom){

        if($(trDom).find('.checkbox_sel').is(":checked")){
            idsArr.push($(trDom).data('id'));
            checkDom.push(trDom);
        }
    });
    // console.log($childrens);
    console.log(checkDom);
    if(idsArr.length == 0){
        layer.msg('请先选择要删除的产品!',{icon:1,time:1500});
        return;
    }
    layer.confirm('确认要删除吗？',function(index){
        $.ajax({
            url: url,
            type: "post",
            data: {idsArr:idsArr},
            success: function(data){
                checkDom.forEach(function(trDom){
                    trDom.remove();
                });
                layer.msg('已删除!',{icon:1,time:1000});
            },
            error:function(data) {
                console.log(data.msg);
            },
        });
    });

}

