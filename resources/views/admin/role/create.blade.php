@extends('admin.layouts.admin')

@section('title', '添加角色')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-body">
                    <form action="{{ url('admin/role') }}" method="POST">
                        @include('admin.role._form', ['form_type' => 'create'])
                        <button type="submit" class="btn btn-primary">添加</button>
                    </form>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
@endsection