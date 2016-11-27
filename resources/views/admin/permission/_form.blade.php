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

<div class="form-group @if(session('errors.display_name')) has-error @endif">
    <label for="display_name">权限名称</label>
    <input type="text" name="display_name" required="required" id="display_name" class="form-control" value="{{ $form_type == 'create' ? old('display_name') : $permission->display_name }}" placeholder="请输入权限名称">
    @if(session('errors.display_name'))
        <span class="help-block">{{ session('errors.display_name') }}</span>
    @endif
</div>
<div class="form-group @if(session('errors.name')) has-error @endif">
    <label for="name">权限规则</label>
    <input type="text" name="name" id="name" required="required" class="form-control" value="{{ $form_type == 'create' ? old('name') : $permission->name }}" placeholder="请输入权限规则">
    @if(session('errors.name'))
        <span class="help-block">{{ session('errors.name') }}</span>
    @endif
</div>

<div class="form-group">
    <label for="tag" class="control-label">ICON图</label>
    <div>
        <!-- Button tag -->
        <button id="tag" class="btn btn-default" name="icon" data-iconset="fontawesome" data-icon="{{ $form_type == 'create' ? 'fa-sliders' : $permission->icon }}" role="iconpicker"></button>
    </div>
</div>


<div class="form-group @if(session('errors.sort')) has-error @endif">
    <label for="sort">权限排序</label>
    <input type="number" name="sort" id="sort" class="form-control" value="{{ $form_type == 'create' ? old('sort') : $permission->sort }}" placeholder="请输入权限排序">
    @if(session('errors.sort'))
        <span class="help-block">{{ session('errors.sort') }}</span>
    @endif
</div>

<div class="form-group @if(session('errors.is_menu')) has-error @endif">
    <label for="is_menu">是否菜单</label>
    <input type="checkbox" class="minimal" id="is_menu" name="is_menu" {{ $form_type == 'create' ? (old('is_menu') ? ' checked="checked" ' : '') : ($permission->is_menu ? ' checked="checked" ' : '') }} value="1">
    @if(session('errors.is_menu'))
        <span class="help-block">{{ session('errors.is_menu') }}</span>
    @endif
</div>

<div class="form-group">
    <label for="description">规则描述</label>
    <textarea class="form-control" id="description" name="description" style="resize:vertical;" rows="3" placeholder="请输入规则描述">{{ $form_type == 'create' ? old('description') : $permission->description }}</textarea>
</div>