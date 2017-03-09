@extends('admin.layouts.admin')

@section('title', '角色详情')

@section('content')
    <div class="row">

        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">角色详情</h3>
                    <a href="{{url('admin/adminRole')}}" class='btn btn-default btn-xs pull-right'>返回上页</a>
                </div>
                <div class="box-body">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label for="introduction" class="col-md-3 control-label">角色id</label>
                            <div class="col-md-5">
                                <span class="form-control-show">{{ $role -> id }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-md-3 control-label">角色名称</label>
                            <div class="col-md-5">
                                <span class="form-control-show">{{ $role -> name }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description" class="col-md-3 control-label">权限描述</label>
                            <div class="col-md-5">
                                <span class="form-control-show">{{ $role -> description }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="created_at" class="col-md-3 control-label">创建时间</label>
                            <div class="col-md-5">
                                <span class="form-control-show">{{ $role -> created_at }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="updated_at" class="col-md-3 control-label">编辑时间</label>
                            <div class="col-md-5">
                                <span class="form-control-show">{{ $role -> updated_at }}</span>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
@endsection