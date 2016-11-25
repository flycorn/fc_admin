@extends('admin.layouts.admin')

@section('title', '添加权限')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header">
                <h3 class="box-title">所属权限：{{ $pid ? (isset($parent_permission->display_name) ? $parent_permission->display_name : '') : '顶级权限'}}</h3>
                </div>
                <div class="box-body">
                    <form action="{{ url('admin/permission') }}" method="POST">
                        @include('admin.permission._form', ['form_type' => 'create'])
                        <button type="submit" class="btn btn-primary">添加</button>
                    </form>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
@endsection