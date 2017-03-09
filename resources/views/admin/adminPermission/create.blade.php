@extends('admin.layouts.admin')

@section('title', '添加权限')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">所属权限：{{ $pid ? (isset($parent_permission->display_name) ? $parent_permission->display_name : '') : '顶级权限'}} | 添加权限</h3>
                    <a href="{{url('admin/adminPermission')}}" class='btn btn-default btn-xs pull-right'>返回上页</a>
                </div>
                <div class="box-body">
                    <form class="form-horizontal" action="{{ url('adminApi/adminPermission') }}" method="POST">
                        @include('admin.adminPermission._form', ['form_type' => 'create'])

                        <div class="form-group">
                            <div class="col-md-7 col-md-offset-3">
                                <button type="submit" class="btn btn-primary">添加</button>
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