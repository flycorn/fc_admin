{{ csrf_field() }}

<div class="form-group @if(session('errors.name')) has-error @endif">
    <label for="name">角色名称</label>
    <input type="text" name="name" id="name" required="required" class="form-control" value="{{ $form_type == 'create' ? old('name') : $role->name }}" placeholder="请输入角色名称">
    @if(session('errors.name'))
        <span class="help-block">{{ session('errors.name') }}</span>
    @endif
</div>

<div class="form-group @if(session('errors.display_name')) has-error @endif">
    <label for="display_name">角色标签</label>
    <input type="text" name="display_name" required="required" id="display_name" class="form-control" value="{{ $form_type == 'create' ? old('display_name') : $role->display_name }}" placeholder="请输入角色标签">
    @if(session('errors.display_name'))
        <span class="help-block">{{ session('errors.display_name') }}</span>
    @endif
</div>

<div class="form-group">
    <label for="description">角色描述</label>
    <textarea class="form-control" id="description" style="resize:vertical;" name="description" rows="3" placeholder="请输入角色描述">{{ $form_type == 'create' ? old('description') : $role->description }}</textarea>
</div>