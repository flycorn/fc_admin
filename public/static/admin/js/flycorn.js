/**
 * 后台js方法工具
 * Author: flycorn
 * Email: yuming@flycorn.com
 * Date: 16/6/27
 */

// 加载遮罩层
var loadShow = function(bool){
    if(arguments.length == 0){
        bool = 1;
    }
    if(bool) {
        //开启
        $("#loading").show();
        return;
    }
    //关闭
    $("#loading").fadeOut(500);
}

/**
 * 提示信息
 * @param msg 消息
 * @param type 1 正常 0 错误
 */
var fc_msg = function (msg, type)
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
var fc_confirm = function (msg, yesfn, nofn)
{
    var params = arguments.length;
    layer.confirm(msg, {
        btn: ['确定','取消'] //按钮
    }, function(){
        if(params >= 2){
            yesfn();
            layer.closeAll();
            return;
        }
    }, function(){
        if(params == 3){
            nofn();
            layer.closeAll();
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
var fc_alert = function (msg, icon, title)
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
var loading = function (bool)
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
var fc_ajax = function (url, data, type, dataType, successfn, errorfn)
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
                    fc_msg("没有权限执行该操作!", 0);
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

/**
 * 预览图片
 * @param ele input上传组件 (如：#upload_img)
 * @param previewEle 图片 (如：#show_img)
 */
var fc_preview = function (ele, previewEle) {
    var file = $(ele)[0];
    var previewPic = $(previewEle)[0];

    //判断是否支持FileReader
    if(window.FileReader){
        //chrome,firefox7+,opera,IE10+
        oFReader = new FileReader();
        oFReader.readAsDataURL(file.files[0]);
        oFReader.onload = function (oFREvent) {previewPic.src = oFREvent.target.result;};
    } else if(document.all){
        //IE9-//IE使用滤镜，实际测试IE6设置src为物理路径发布网站通过http协议访问时还是没有办法加载图片
        file.select();
        file.blur();
        var reallocalpath = document.selection.createRange().text//IE下获取实际的本地文件路径
        previewPic.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod='image',src=\"" + reallocalpath + "\")";
        previewPic.src = 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==';//设置img的src为base64编码的透明图片，要不会显示红xx
    } else if(file.files){
        //firefox6-
        if (file.files.item(0)) {
            url = file.files.item(0).getAsDataURL();
            previewPic.src = url;
        }
    }
}

/**
 * 上传图片
 * @param ele 上传input组件 (如：#upload_img)
 * @param configParam 配置参数
 *        @params
 *        form 是否已存在表单 (默认: false)
 *        formEle 表单节点id名 (默认: #fc_upload_form)
 *        url 上传地址
 *        param 额外提交数据
 *        dataType 返回数据类型 (默认: json)
 *        beforeUpload 上传前处理方法
 *        afterUpload 上传后处理方法
 *        async ajax上传是否异步 (默认: false)
 * @param successfn 上传成功回调方法
 * @param errorfn 上传失败回调方法
 */
var fc_upload_img = function (ele, configParam, successfn, errorfn){
    //基础配置
    var config = {
        form: false,
        formEle: '#fc_upload_form',
        url: '',
        param: null,
        dataType: 'json',
        beforeUpload: null, //上传前处理
        afterUpload: null, //上传后处理
        async: true
    };
    //配置参数
    if(configParam.hasOwnProperty('form')){
        config.form = configParam.form;
    }
    if(configParam.hasOwnProperty('formEle')){
        config.formEle = configParam.formEle;
    }
    if(configParam.hasOwnProperty('url')){
        config.url = configParam.url;
    }
    if(configParam.hasOwnProperty('param')){
        config.param = configParam.param;
    }
    if(configParam.hasOwnProperty('beforeUpload')){
        config.beforeUpload = configParam.beforeUpload;
    }
    if(configParam.hasOwnProperty('afterUpload')){
        config.afterUpload = configParam.afterUpload;
    }
    if(configParam.hasOwnProperty('async')){
        config.async = configParam.async;
    }
    //验证是否需要创建表单
    var eleStr = config.formEle.substr(1);
    if(!config.form){
        var formHtml = '<form id="'+eleStr+'" method="POST" enctype="multipart/form-data">';
        if(config.param != null){
            $.each(config.param, function(i, val) {
                formHtml += '<input type="hidden" name="'+i+'" form="'+eleStr+'" value="'+val+'" />';
            });
        }
        formHtml += '</form>';
        $("body").prepend(formHtml);
    } else {
        if(config.param != null){
            var formHtml = '';
            $.each(config.param, function(i, val) {
                formHtml += '<input type="hidden" name="'+i+'" value="'+val+'" />';
            });
            $(formEle).append(formHtml);
        }
    }

    //绑定form表单
    $(ele).attr('form', eleStr);

    //绑定事件
    $(ele).on("change", function(){

        var inputFile = $(this);

        //判断是否有图片上传
        if(inputFile.val() == "" || inputFile.val() == null) return;

        //上传前处理
        if(typeof config.beforeUpload == 'function'){
            config.beforeUpload(inputFile);
        }

        //获取表单数据
        var formData = new FormData($(config.formEle)[0]);

        //ajax上传
        $.ajax({
            url: config.url,
            type: 'POST',
            async: config.async,
            data: formData,
            dataType: config.dataType,
            headers: {
                'X-XSRF-TOKEN': $.cookie('XSRF-TOKEN')
            },
            processData:false,
            contentType:false,
            success:function(d){
                //上传后处理
                if(typeof config.afterUpload == 'function'){
                    config.afterUpload(inputFile);
                }
                //成功
                if(typeof successfn == 'function'){
                    successfn(d);
                    return;
                }
            },
            error:function(e){
                //上传后处理
                if(typeof config.afterUpload == 'function'){
                    config.afterUpload(inputFile);
                }
                //失败
                if(typeof errorfn == 'function'){
                    errorfn(e);
                    return;
                }
            }
        })

    })
}
