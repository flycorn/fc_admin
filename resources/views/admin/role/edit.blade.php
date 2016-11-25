@extends('admin.layouts.admin')

@section('title', '编辑角色')

@section('content')
    <div class="row">

        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-body">
                    <form action="{{ url('admin/role/'.$role->id) }}" method="POST">
                        <input type="hidden" name="_method" value="PUT">
                        @include('admin.role._form', ['form_type' => 'edit'])
                        <button type="submit" class="btn btn-primary">编辑</button>
                    </form>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
@endsection