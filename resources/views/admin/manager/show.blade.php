@extends('admin.layouts.admin')

@section('title', '管理员详情')

@section('content')
 <div class="row">

  <div class="col-md-12">
   <div class="box box-info">
    {{--<div class="box-header">--}}
    {{--<h3 class="box-title">添加管理员</h3>--}}
    {{--</div>--}}
    <div class="box-body">
      <div class="form-group">
        <label for="role">所属角色</label>
        @if($user->user_id==1)
            <span>超级管理员</span>
        @elseif(count($role_list))
            @foreach($role_list as $k => $item)
            <span>{{$item->name}} @if(($k+1) != count($role_list)) &nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp; @endif</span>
            @endforeach
        @endif
      </div>
      <div class="form-group">
        <label for="username">用户ID</label>
        <h4>{{ $user -> user_id }}</h4>
      </div>
      <div class="form-group">
       <label for="username">用户名</label>
       <h4>{{ $user -> username }}</h4>
      </div>
      <div class="form-group">
       <label for="nickname">昵称</label>
       <h4>{{ $user -> nickname }}</h4>
      </div>
      <div class="form-group">
       <label for="avatar">头像</label>
       <img id="avatar_img" src="{{ asset($user -> avatar) }}" class="img-lg img-circle" style="float: none !important;">
      </div>
      <div class="form-group">
       <label for="email">邮箱</label>
       <h4>{{ $user -> email }}</h4>
      </div>
      <div class="form-group">
       <label for="introduction">简介</label>
       <h4>{{ $user -> introduction }}</h4>
      </div>
      <div class="form-group">
       <label for="created_at">创建时间</label>
       <h4>{{ $user -> created_at }}</h4>
      </div>
      <div class="form-group">
       <label for="updated_at">编辑时间</label>
       <h4>{{ $user -> updated_at }}</h4>
      </div>
    </div>
    <!-- /.box-body -->
   </div>
   <!-- /.box -->
  </div>
 </div>
@endsection