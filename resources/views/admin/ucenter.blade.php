@extends('admin.layouts.admin')

@section('title', '个人中心')

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="javascript:;"><i class="fa fa-user"></i> 个人中心</a></li>
    </ol>
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('static/admin/js/uploadify/uploadify.css')}}">
    <style>
        .uploadify{display:inline-block;}
        .uploadify-button{border:none; border-radius:5px; margin-top:8px;}
    </style>
@endsection

@section('js')
    <script src="{{asset('static/admin/js/uploadify/jquery.uploadify.min.js')}}" type="text/javascript"></script>
    <script>
        $(function () {
            //上传组件
            $('#file_upload').uploadify({
                'buttonText' : '头像上传',
                'formData'     : {
                    'timestamp' : '{{ time() }}',
                    '_token'     : "{{csrf_token()}}",
                    'type' : 0, //类型
                },
                'swf'      : "{{asset('static/admin/js/uploadify/uploadify.swf')}}",
                'uploader' : "{{url('admin/upload/image')}}",
                'onUploadSuccess' : function(file, data, response) {
                    if(response){
                        //成功
                        var res = eval('('+data+')');
                        if(res.status <= 0){
                            fc_msg(res.msg, 0);
                            return;
                        }
                        //成功
                        $("#avatar_val").val(res.data.img);
                        $('#avatar_img').attr('src', res.data.img_url);
                    }
                }
            });
        });
    </script>
@endsection


@section('content')
 <div class="row">

     <div class="col-md-6">
         <div class="box box-info">
             <div class="box-header">
                 <h3 class="box-title">修改个人资料</h3>
             </div>
             <div class="box-body">
                 <form action="{{ url('admin/edit') }}" method="POST">
                    {{ csrf_field() }}
                     <div class="form-group">
                         <label>用户ID:&nbsp;&nbsp;&nbsp;&nbsp;{{ $user -> user_id }}</label>
                     </div>
                     <div class="form-group">
                         <label>用户名:&nbsp;&nbsp;&nbsp;&nbsp;{{ $user -> username }}</label>
                     </div>
                     <div class="form-group @if(session('errors.nickname')) has-error @endif">
                         <label for="nickname">昵称</label>
                         <input type="text" name="nickname" id="nickname" class="form-control" value="{{ $user -> nickname }}" placeholder="请输入昵称">
                         @if(session('errors.nickname'))
                             <span class="help-block">{{ session('errors.nickname') }}</span>
                         @endif
                     </div>
                     <div class="form-group">
                         <label for="avatar">头像</label>
                         <input type="hidden" name="avatar" id="avatar_val" value="" />
                         <img id="avatar_img" src="{{ asset($user -> avatar) }}" class="img-lg img-circle" style="float: none !important;">
                     </div>
                     <div class="form-group">
                         <label for="avatar">上传头像</label>
                         <input id="file_upload" name="file_upload" type="file" multiple="false">
                     </div>
                     <div class="form-group @if(session('errors.email')) has-error @endif">
                         <label for="email">邮箱</label>
                         <input type="email" name="email" id="email" class="form-control" value="{{ $user -> email }}" placeholder="请输入邮箱">
                         @if(session('errors.email'))
                             <span class="help-block">{{ session('errors.email') }}</span>
                         @endif
                     </div>
                     <div class="form-group">
                         <label for="introduction">简介</label>
                         <textarea class="form-control" id="introduction" style="resize:vertical;" name="introduction" rows="3" placeholder="请输入简介">{{ $user -> introduction }}</textarea>
                     </div>

                     <div class="form-group">
                         <label>编辑时间:&nbsp;&nbsp;&nbsp;&nbsp;{{ $user -> updated_at }}</label>
                     </div>
                     <div class="form-group">
                         <label>创建时间:&nbsp;&nbsp;&nbsp;&nbsp;{{ $user -> created_at }}</label>
                     </div>

                     <button type="submit" class="btn btn-primary">编辑</button>
                 </form>
             </div>
             <!-- /.box-body -->
         </div>
         <!-- /.box -->
     </div>
     <!-- /.col (left) -->
     <div class="col-md-6">
         <div class="box box-success">
             <div class="box-header">
                 <h3 class="box-title">修改密码</h3>
             </div>
             <div class="box-body">
                 <form action="{{ url('admin/password') }}" method="POST">
                     {{ csrf_field() }}
                    <div class="form-group @if(session('errors.old_password')) has-error @endif">
                         <label for="old_password">原密码</label>
                         <input type="password" name="old_password" id="old_password" class="form-control" value="" placeholder="请输入原密码">
                         @if(session('errors.old_password'))
                             <span class="help-block">{{ session('errors.old_password') }}</span>
                         @endif
                     </div>
                     <div class="form-group @if(session('errors.new_password')) has-error @endif">
                         <label for="new_password">新密码</label>
                         <input type="password" name="new_password" id="new_password" class="form-control" value="" placeholder="请输入新密码">
                         @if(session('errors.new_password'))
                             <span class="help-block">{{ session('errors.new_password') }}</span>
                         @endif
                     </div>

                    <button type="submit" class="btn btn-primary">设置</button>
                 </form>
             </div>
             <!-- /.box-body -->
         </div>
         <!-- /.box -->
     </div>
     <!-- /.col (right) -->
 </div>
@endsection