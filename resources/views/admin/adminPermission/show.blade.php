@extends('admin.layouts.admin')

@section('title', '权限详情')

@section('content')
    <div class="row">

        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">权限详情</h3>
                    <a href="{{url('admin/adminPermission')}}" class='btn btn-default btn-xs pull-right'>返回上页</a>
                </div>
                <div class="box-body">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label for="parent_permission" class="col-md-3 control-label">所属权限</label>
                            <div class="col-md-5">
                                <span class="form-control-show">{{ $permission -> pid ? (isset($parent_permission->name) ? $parent_permission->name : '') : '顶级' }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-md-3 control-label">权限名称</label>
                            <div class="col-md-5">
                                <span class="form-control-show">{{ $permission -> name }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="rule" class="col-md-3 control-label">权限规则</label>
                            <div class="col-md-5">
                                <span class="form-control-show">{{ $permission -> rule }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="icon" class="col-md-3 control-label">ICON图</label>
                            <div class="col-md-5">
                                <span class="form-control-show"><i class='fa {{ $permission -> icon }}'>&nbsp;</i></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="sort" class="col-md-3 control-label">权限排序</label>
                            <div class="col-md-5">
                                <span class="form-control-show">{{ $permission -> sort }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="is_menu" class="col-md-3 control-label">是否菜单</label>
                            <div class="col-md-5">
                                <span class="form-control-show">{{ $permission -> is_menu ? '是' : '' }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description" class="col-md-3 control-label">权限描述</label>
                            <div class="col-md-5">
                                <div class="form-control-show">{{ $permission -> description }}</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="created_at" class="col-md-3 control-label">创建时间</label>
                            <div class="col-md-5">
                                <span class="form-control-show">{{ $permission -> created_at }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="updated_at" class="col-md-3 control-label">编辑时间</label>
                            <div class="col-md-5">
                                <span class="form-control-show">{{ $permission -> updated_at }}</span>
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