@extends('admin.layouts.admin')

@section('title', '权限详情')

@section('content')
    <div class="row">

        <div class="col-md-12">
            <div class="box box-info">
                {{--<div class="box-header">--}}
                {{--<h3 class="box-title">添加管理员</h3>--}}
                {{--</div>--}}
                <div class="box-body">
                    <div class="form-group">
                        <label for="parent_permission">所属权限</label>
                        <h4>{{ $permission -> pid ? (isset($parent_permission->display_name) ? $parent_permission->display_name : '') : '顶级' }}</h4>
                    </div>
                    <div class="form-group">
                        <label for="display_name">权限名称</label>
                        <h4>{{ $permission -> display_name }}</h4>
                    </div>
                    <div class="form-group">
                        <label for="name">权限规则</label>
                        <h4>{{ $permission -> name }}</h4>
                    </div>
                    <div class="form-group">
                        <label for="icon">ICON图</label>
                        <h4><i class='fa {{ $permission -> icon }}'>&nbsp;</i></h4>
                    </div>

                    <div class="form-group">
                        <label for="sort">权限排序</label>
                        <h4>{{ $permission -> sort }}</h4>
                    </div>

                    <div class="form-group">
                        <label for="is_menu">是否菜单</label>
                        <h4>{{ $permission -> is_menu ? '是' : '' }}</h4>
                    </div>

                    <div class="form-group">
                        <label for="description">权限描述</label>
                        <h4>{{ $permission -> description }}</h4>
                    </div>
                    <div class="form-group">
                        <label for="created_at">创建时间</label>
                        <h4>{{ $permission -> created_at }}</h4>
                    </div>
                    <div class="form-group">
                        <label for="updated_at">编辑时间</label>
                        <h4>{{ $permission -> updated_at }}</h4>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
@endsection