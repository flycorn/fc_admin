/**
 * Author: flycorn
 * Email: yuming@flycorn.com
 * Date: 16/6/27
 */
/**
 * 提示信息
 * @param msg 消息
 * @param type 1 正常 0 错误
 */
function fc_msg(msg, type)
{
    var icon_type = 6;
    if(arguments.length != 2){
        type = 1;
    }
    switch(type){
        case 1:
            icon_type = 6;
            break;
        default:
            icon_type = 5;
            break;
    }
    layer.msg(msg, {icon: icon_type});
}

/**
 * 确认
 * @param msg
 * @param yesfn
 * @param nofn
 */
function fc_confirm(msg, yesfn, nofn)
{
    var params = arguments.length;
    layer.confirm(msg, {
        btn: ['确定','取消'] //按钮
    }, function(){
        if(params >= 2){
            yesfn();
            return;
        }
    }, function(){
        if(params == 3){
            nofn();
            return;
        }
    });
}

/**
 * 弹框提示
 * @param msg 消息
 * @param icon 0:警告 1:正常 2:错误 3:确认
 * @param title 标题
 */
function fc_alert(msg, icon, title)
{
    //判断传入的参数个数
    if(arguments.length != 3){
        title = "FcBackStage";
    }
    layer.alert(msg, {
        title: title,
        icon: icon,
        skin: 'layer-ext-moon'
    });
}
/**
 * 加载框
 * @param bool
 */
function loading(bool)
{
    bool = parseInt(bool);

    if(bool){
        //显示
        layer.load(1, {shade: [0.6, '#000000']});
        return;
    }
    //隐藏
    layer.closeAll('loading');
}
/**
 * ajax请求
 * @param url
 * @param data
 * @param type
 * @param dataType
 * @param successfn
 * @param errorfn
 */
function fc_ajax(url, data, type, dataType, successfn, errorfn)
{
    $.ajax({
        type: type,
        data: data,
        url: url,
        dataType: dataType,
        headers: {
            'X-XSRF-TOKEN': $.cookie('XSRF-TOKEN')
        },
        success: function(d){
            //验证结果
            if(d.status == 'failed'){
                if(d.errors.status_code == 403){
                    fc_msg("该操作不允许!", 0);
                    return;
                } else if(d.errors.status_code == 401){
                    fc_msg("请登录后操作!", 0);
                    return;
                }
            }
            successfn(d);
        },
        error: function(e){

            if(arguments.length != 6){
                //刷新当前页
                return;
            }

            errorfn(e);
        }
    });
}