@section('css')
    <link rel="stylesheet" href="{{ asset('static/admin/plugins/iCheck/all.css') }}">
@endsection

@section('js')
    <script src="{{ asset('static/admin/plugins/iCheck/icheck.min.js') }}"></script>
    <script>
        $(function () {

            //上传组件
            fc_upload_img("#file_upload", { url: "{{url('adminApi/upload/image')}}", param: { type: 0 }, beforeUpload: function(){ loadShow() }, afterUpload: function(){ loadShow(0) }}, function(res){
                if(res.status <= 0){
                    fc_msg(res.msg, 0);
                    return;
                }
                //成功
                $("#avatar_val").val(res.data.img);
                $('#avatar_img').attr('src', res.data.img_url);
                $('#avatar_img').css("display", "block");
            });

            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue'
            });
        });
    </script>
@endsection


{{ csrf_field() }}

<div class="form-group @if(session('errors.name')) has-error @endif">
    <label for="name" class="col-md-3 control-label">用户名</label>
    <div class="col-md-5">
        <input type="text" name="name" {{ $form_type == 'create' ? '' : ' readonly="readonly" ' }} required="required" id="name" class="form-control" value="{{ $form_type == 'create' ? old('name') : $user -> name }}" placeholder="请输入用户名">
        @if(session('errors.name'))
            <span class="help-block">{{ session('errors.name') }}</span>
        @endif
    </div>
</div>
<div class="form-group @if(session('errors.nickname')) has-error @endif">
    <label for="nickname" class="col-md-3 control-label">昵称</label>
    <div class="col-md-5">
        <input type="text" name="nickname" required="required" id="nickname" class="form-control" value="{{ $form_type == 'edit' && !old('nickname') ? $user -> nickname : old('nickname') }}" placeholder="请输入昵称">
        @if(session('errors.nickname'))
            <span class="help-block">{{ session('errors.nickname') }}</span>
        @endif
    </div>
</div>
<div class="form-group">
    <label for="avatar" class="col-md-3 control-label">头像</label>
    <div class="col-md-5">
        <input type="hidden" name="avatar" id="avatar_val" value="{{$form_type == 'edit' && !old('avatar') ? '' : ( empty(old('avatar')) ? '' : old('avatar') ) }}" />
        <img id="avatar_img" src="{{ $form_type == 'edit' && !old('avatar') ? asset($user -> avatar) : (!empty(old('avatar')) ? asset(old('avatar')) : '') }}" class="img-lg img-circle" style="{{ $form_type == 'create' && empty(old('avatar')) ? 'display: none;' : '' }}float: none !important;">
    </div>
</div>
<div class="form-group">
    <label for="avatar" class="col-md-3 control-label">上传头像</label>
    <div class="col-md-5">
        <em class="btn bg-purple btn-sm fc-upload-btn"><input id="file_upload" name="Filedata" type="file" multiple="false" accept="image/gif,image/jpeg,image/jpg,image/png">选择头像</em>
    </div>
</div>
<div class="form-group @if(session('errors.email')) has-error @endif">
    <label for="email" class="col-md-3 control-label">邮箱</label>
    <div class="col-md-5">
        <input type="email" name="email" id="email" required="required" class="form-control" value="{{ $form_type == 'edit' && !old('email') ? $user -> email : old('email') }}" placeholder="请输入邮箱">
        @if(session('errors.email'))
            <span class="help-block">{{ session('errors.email') }}</span>
        @endif
    </div>
</div>
<div class="form-group">
    <label for="introduction" class="col-md-3 control-label">简介</label>
    <div class="col-md-5">
        <textarea class="form-control" id="introduction" name="introduction" style="resize:vertical;" rows="3" placeholder="请输入简介">{{ $form_type == 'edit' && !old('introduction') ? $user -> introduction : old('introduction') }}</textarea>
    </div>
</div>
<div class="form-group @if(session('errors.password')) has-error @endif">
    <label for="password" class="col-md-3 control-label">密码</label>
    <div class="col-md-5">
        <input type="password" name="password" id="password" class="form-control" value="" placeholder="请输入密码">
        @if(session('errors.password'))
            <span class="help-block">{{ session('errors.password') }}</span>
        @endif
    </div>
</div>
<div class="form-group">
    <label for="role" class="col-md-3 control-label">所属角色</label>
    <div class="col-md-5">
        <div>
            @if(count($role_list))
                @foreach($role_list as $k => $item)
                <label class="control-label">{{$item->name}}&nbsp;&nbsp;<input type="checkbox" class="minimal" name="role_ids[]"  @if($form_type!='create' && in_array($item->id, $user_role_ids)) checked="checked"  @endif value="{{$item->id}}" />&nbsp;&nbsp;</label>@if(($k+1) != count($role_list)) &nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;@endif
                @endforeach
            @endif
        </div>
    </div>
</div>