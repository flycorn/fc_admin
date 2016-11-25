@extends('admin.layouts.admin')

@section('title', '添加管理员')

@section('content')
 <div class="row">

  <div class="col-md-12">
   <div class="box box-info">
    {{--<div class="box-header">--}}
     {{--<h3 class="box-title">添加管理员</h3>--}}
    {{--</div>--}}
    <div class="box-body">
     <form action="{{ url('admin/manager') }}" method="POST">
      @include('admin.manager._form', ['form_type' => 'create'])
      <button type="submit" class="btn btn-primary">创建</button>
     </form>
    </div>
    <!-- /.box-body -->
   </div>
   <!-- /.box -->
  </div>
 </div>
@endsection