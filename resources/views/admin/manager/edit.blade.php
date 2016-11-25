@extends('admin.layouts.admin')

@section('title', '编辑管理员')

@section('content')
 <div class="row">

  <div class="col-md-12">
   <div class="box box-info">
    {{--<div class="box-header">--}}
     {{--<h3 class="box-title">添加管理员</h3>--}}
    {{--</div>--}}
    <div class="box-body">
     <form action="{{ url('admin/manager/'.$user -> user_id) }}" method="POST">
      <input type="hidden" name="_method" value="PUT">
      @include('admin.manager._form', ['form_type' => 'edit'])
      <button type="submit" class="btn btn-primary">编辑</button>
     </form>
    </div>
    <!-- /.box-body -->
   </div>
   <!-- /.box -->
  </div>
 </div>
@endsection