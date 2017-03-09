@extends('admin.layouts.base')

@section('title', '登录')

@section('css')
    <link rel="stylesheet" href="{{ asset('static/admin/plugins/iCheck/square/blue.css') }}">
@endsection

@section('body')
    <body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url('/admin') }}"><b>FC</b>Admin</a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">登录</p>

            <form action="{{ url('adminApi/login') }}" method="post">
                {{ csrf_field() }}
                <div class="form-group has-feedback @if(session('errors.user')) has-error @endif">
                    <input type="text" name="user" required="required" class="form-control" value="{{ old('user') }}" placeholder="用户名或邮箱">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    @if(session('errors.user'))
                        <span class="help-block">{{ session('errors.user') }}</span>
                    @endif
                </div>

                <div class="form-group has-feedback @if(session('errors.password')) has-error @endif">
                    <input type="password" name="password" required="required" class="form-control" value="{{ old('password') }}" placeholder="密码">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    @if(session('errors.password'))
                        <span class="help-block">{{ session('errors.password') }}</span>
                    @endif
                </div>

                <div class="row margin-bottom">
                    <div class="col-xs-6">
                        <img src="{{url('admin/captcha')}}" style="border:1px solid #d2d6de;cursor: pointer;overflow: hidden;" title="点击切换验证码" onclick="this.src='{{ url('admin/captcha') }}?'+Math.random()" />
                    </div>
                    <div class="form-group has-feedback col-xs-6 @if(session('errors.captcha')) has-error @endif">
                        <input type="text" class="form-control" required="required" name="captcha" value="" placeholder="验证码">
                        @if(session('errors.captcha'))
                            <span class="help-block">{{ session('errors.captcha') }}</span>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-8">
                        <div class="checkbox icheck">
                            <label>
                                <input type="checkbox" name="remember" value="1"> 记住密码
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">登录</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
            {{--<br/>--}}
            {{--<a href="javascript:;">忘记密码</a><br>--}}
            {{--<a href="javascript:;" class="text-center">注册账号</a>--}}

        </div>
        <!-- /.login-box-body -->

    </div>
    <!-- /.login-box -->

    <!-- jQuery 2.2.3 -->
    <script src="{{ asset('static/admin/plugins/jQuery/jquery-2.2.3.min.js') }}"></script>
    <!-- Bootstrap 3.3.6 -->
    <script src="{{ asset('static/admin/bootstrap/js/bootstrap.min.js') }}"></script>
    <!-- iCheck -->
    <script src="{{ asset('static/admin/plugins/iCheck/icheck.min.js') }}"></script>
    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
            //防止被嵌入子页面
            if(window.top != window)
            {
                window.top.location.href = document.location.href;
            }
        });
    </script>
    </body>
@endsection