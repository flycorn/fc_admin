{{ csrf_field() }}

<div class="form-group @if(session('errors.name')) has-error @endif">
    <label for="name" class="col-md-3 control-label">角色名称</label>
    <div class="col-md-5">
        <input type="text" name="name" id="name" required="required" class="form-control" value="{{ $form_type == 'edit' && !old('name') ? $role->name : old('name') }}" placeholder="请输入角色名称">
        @if(session('errors.name'))
            <span class="help-block">{{ session('errors.name') }}</span>
        @endif
    </div>
</div>

<div class="form-group">
    <label for="description" class="col-md-3 control-label">角色描述</label>
    <div class="col-md-5">
        <textarea class="form-control" id="description" style="resize:vertical;" name="description" rows="3" placeholder="请输入角色描述">{{ $form_type == 'edit' && !old('description') ? $role->description : old('description') }}</textarea>
    </div>
</div>