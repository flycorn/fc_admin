@section('css')
    <link rel="stylesheet" href="{{ asset('static/admin/plugins/bootstrap-iconpicker/icon-fonts/font-awesome-4.2.0/css/font-awesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('static/admin/plugins/bootstrap-iconpicker/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('static/admin/plugins/iCheck/all.css') }}">
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('static/admin/plugins/bootstrap-iconpicker/bootstrap-iconpicker/js/iconset/iconset-fontawesome-4.3.0.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('static/admin/plugins/bootstrap-iconpicker/bootstrap-iconpicker/js/bootstrap-iconpicker.js') }}"></script>
    <script src="{{ asset('static/admin/plugins/iCheck/icheck.min.js') }}"></script>
    <script>
        $(function(){
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue'
            });
        })
    </script>
@endsection

{{ csrf_field() }}
<input type="hidden" name="pid" value="{{$pid}}" />

<div class="form-group @if(session('errors.name')) has-error @endif">
    <label for="name" class="col-md-3 control-label">权限名称</label>
    <div class="col-md-5">
        <input type="text" name="name" required="required" id="name" class="form-control" value="{{ $form_type == 'edit' && !old('name') ? $permission->name : old('name') }}" placeholder="请输入权限名称">
        @if(session('errors.name'))
            <span class="help-block">{{ session('errors.name') }}</span>
        @endif
    </div>
</div>
<div class="form-group @if(session('errors.rule')) has-error @endif">
    <label for="rule" class="col-md-3 control-label">权限规则</label>
    <div class="col-md-5">
        <input type="text" name="rule" id="rule" required="required" class="form-control" value="{{ $form_type == 'edit' && !old('rule') ? $permission->rule : old('rule') }}" placeholder="请输入权限规则">
        @if(session('errors.rule'))
            <span class="help-block">{{ session('errors.rule') }}</span>
        @endif
    </div>
</div>

<div class="form-group">
    <label for="tag" class="col-md-3 control-label">ICON图</label>
    <div class="col-md-5">
        <button id="tag" class="btn btn-default" name="icon" data-iconset="fontawesome" data-icon="{{ $form_type == 'edit' && !old('icon') ?  $permission->icon : old('icon') }}" role="iconpicker"></button>
    </div>
</div>


<div class="form-group @if(session('errors.sort')) has-error @endif">
    <label for="sort" class="col-md-3 control-label">权限排序</label>
    <div class="col-md-5">
        <input type="number" name="sort" id="sort" class="form-control" value="{{ $form_type == 'edit' && !old('sort') ? $permission->sort : (!old('sort') ? 0 : old('sort')) }}" placeholder="请输入权限排序">
        @if(session('errors.sort'))
            <span class="help-block">{{ session('errors.sort') }}</span>
        @endif
    </div>
</div>

<div class="form-group @if(session('errors.is_menu')) has-error @endif">
    <label for="is_menu" class="col-md-3 control-label">是否菜单</label>
    <div class="col-md-5">
        <input type="checkbox" class="minimal" id="is_menu" name="is_menu" @if(($form_type == 'edit' && $permission->is_menu == 1) || (is_numeric(old('is_menu')) && old('is_menu')==1)) checked="checked" @endif value="1">
        @if(session('errors.is_menu'))
            <span class="help-block">{{ session('errors.is_menu') }}</span>
        @endif
    </div>
</div>

<div class="form-group">
    <label for="description" class="col-md-3 control-label">规则描述</label>
    <div class="col-md-5">
        <textarea class="form-control" id="description" name="description" style="resize:vertical;" rows="3" placeholder="请输入规则描述">{{ $form_type == 'edit' && !old('description') ? $permission->description : old('description') }}</textarea>
    </div>
</div>