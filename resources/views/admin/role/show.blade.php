@extends('admin.layouts.admin')

@section('title', '角色详情')

@section('content')
    <div class="row">

        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-body">
                    <div class="form-group">
                        <label for="introduction">角色id</label>
                        <h4>{{ $role -> id }}</h4>
                    </div>
                    <div class="form-group">
                        <label for="name">角色名称</label>
                        <h4>{{ $role -> name }}</h4>
                    </div>
                    <div class="form-group">
                        <label for="display_name">角色标签</label>
                        <h4>{{ $role -> display_name }}</h4>
                    </div>
                    <div class="form-group">
                        <label for="description">权限描述</label>
                        <h4>{{ $role -> description }}</h4>
                    </div>
                    <div class="form-group">
                        <label for="created_at">创建时间</label>
                        <h4>{{ $role -> created_at }}</h4>
                    </div>
                    <div class="form-group">
                        <label for="updated_at">编辑时间</label>
                        <h4>{{ $role -> updated_at }}</h4>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
@endsection