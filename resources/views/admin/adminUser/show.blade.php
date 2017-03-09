@extends('admin.layouts.admin')

@section('title', '管理员详情')

@section('content')

    <div class="row">

        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">管理员详情</h3>
                    <a href="{{url('admin/adminUser')}}" class='btn btn-default btn-xs pull-right'>返回上页</a>
                </div>
                <div class="box-body">
                    <form class="form-horizontal">

                        <div class="form-group">
                            <label for="role" class="col-md-3 control-label">所属角色</label>
                            <div class="col-md-5">
                                @if($user->id==1)
                                    <span class="form-control-show">超级管理员</span>
                                @elseif(count($role_list))
                                    @foreach($role_list as $k => $item)
                                        <span>{{$item->name}} @if(($k+1) != count($role_list)) &nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp; @endif</span>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="username" class="col-md-3 control-label">管理员ID</label>
                            <div class="col-md-5">
                                <span class="form-control-show">{{ $user -> id }}</span>
                            </div>
                        </div>

                        <div class="form-group" class="col-md-3 control-label">
                            <label for="username" class="col-md-3 control-label">用户名</label>
                            <div class="col-md-5">
                                <span class="form-control-show">{{ $user -> name }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nickname" class="col-md-3 control-label">昵称</label>
                            <div class="col-md-5">
                                <span class="form-control-show">{{ $user -> nickname }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="avatar" class="col-md-3 control-label">头像</label>
                            <div class="col-md-5">
                                <img id="avatar_img" src="{{ asset($user -> avatar) }}" class="img-lg img-circle">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-md-3 control-label">邮箱</label>
                            <div class="col-md-5">
                                <span class="form-control-show">{{ $user -> email }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="introduction" class="col-md-3 control-label">简介</label>
                            <div class="col-md-5">
                                <div class="form-control-show">{{ $user -> introduction }}</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="created_at" class="col-md-3 control-label">创建时间</label>
                            <div class="col-md-5">
                                <span class="form-control-show">{{ $user -> created_at }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="updated_at" class="col-md-3 control-label">编辑时间</label>
                            <div class="col-md-5">
                                <span class="form-control-show">{{ $user -> updated_at }}</span>
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