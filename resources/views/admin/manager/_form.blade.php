@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('static/admin/js/uploadify/uploadify.css')}}">
    <link rel="stylesheet" href="{{ asset('static/admin/plugins/iCheck/all.css') }}">
    <style>
        .uploadify{display:inline-block;}
        .uploadify-button{border:none; border-radius:5px; margin-top:8px;}
    </style>
@endsection

@section('js')
    <script src="{{asset('static/admin/js/uploadify/jquery.uploadify.min.js')}}" type="text/javascript"></script>
    <script src="{{ asset('static/admin/plugins/iCheck/icheck.min.js') }}"></script>
    <script>
        $(function () {
            //上传组件
            $('#file_upload').uploadify({
                'buttonText' : '头像上传',
                'formData'     : {
                    'timestamp' : '<?php echo time();?>',
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
                        $('#avatar_img').css("display", "block");
                    }
                }
            });

            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue'
            });
        });
    </script>
@endsection


{{ csrf_field() }}
<div class="form-group @if(session('errors.username')) has-error @endif">
    <label for="username">用户名</label>
    <input type="text" name="username" required="required" id="username" class="form-control" value="{{ $form_type == 'create' ? old('username') : $user -> username }}" placeholder="请输入用户名">
    @if(session('errors.username'))
        <span class="help-block">{{ session('errors.username') }}</span>
    @endif
</div>
<div class="form-group @if(session('errors.nickname')) has-error @endif">
    <label for="nickname">昵称</label>
    <input type="text" name="nickname" required="required" id="nickname" class="form-control" value="{{ $form_type == 'create' ? old('nickname') : $user -> nickname }}" placeholder="请输入昵称">
    @if(session('errors.nickname'))
        <span class="help-block">{{ session('errors.nickname') }}</span>
    @endif
</div>
<div class="form-group">
    <label for="avatar">头像</label>
    <input type="hidden" name="avatar" id="avatar_val" value="{{$form_type == 'create' && !empty(old('avatar')) ? old('avatar') : '' }}" />
    <img id="avatar_img" src="{{ $form_type == 'create' ? (!empty(old('avatar')) ? asset(old('avatar')) : '') : asset($user -> avatar) }}" class="img-lg img-circle" style="{{ $form_type == 'create' && empty(old('avatar')) ? 'display: none;' : '' }}float: none !important;">
</div>
<div class="form-group">
    <label for="avatar">上传头像</label>
    <input id="file_upload" name="file_upload" type="file" multiple="false">
</div>
<div class="form-group @if(session('errors.email')) has-error @endif">
    <label for="email">邮箱</label>
    <input type="email" name="email" id="email" required="required" class="form-control" value="{{ $form_type == 'create' ? old('email') : $user -> email }}" placeholder="请输入邮箱">
    @if(session('errors.email'))
        <span class="help-block">{{ session('errors.email') }}</span>
    @endif
</div>
<div class="form-group">
    <label for="introduction">简介</label>
    <textarea class="form-control" id="introduction" name="introduction" style="resize:vertical;" rows="3" placeholder="请输入简介">{{ $form_type == 'create' ? old('introduction') : $user -> introduction }}</textarea>
</div>
<div class="form-group @if(session('errors.password')) has-error @endif">
    <label for="password">密码</label>
    <input type="password" name="password" id="password" class="form-control" value="" placeholder="请输入密码">
    @if(session('errors.password'))
        <span class="help-block">{{ session('errors.password') }}</span>
    @endif
</div>
<div class="form-group">
    <label for="role">所属角色</label>
    <div>
        @if(count($role_list))
            @foreach($role_list as $k => $item)
            <label>{{$item->name}}&nbsp;&nbsp;<input type="checkbox" class="minimal" name="role_ids[]" @if($form_type!='create' && in_array($item->id, $user_role_ids)) checked="checked"  @endif value="{{$item->id}}" />&nbsp;&nbsp;</label>@if(($k+1) != count($role_list)) &nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;@endif
            @endforeach
        @endif
    </div>
</div>