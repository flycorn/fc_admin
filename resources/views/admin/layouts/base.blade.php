<!DOCTYPE html>
<html>
<head>
 <meta charset="utf-8">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <title>@yield('title') | FcAdmin后台脚手架</title>
 <!-- Tell the browser to be responsive to screen width -->
 <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
 <meta name="_token" content="{{ csrf_token() }}" />
 <!-- Bootstrap 3.3.6 -->
 <link rel="stylesheet" href="{{ asset('static/admin/bootstrap/css/bootstrap.min.css') }}">
 <!-- Font Awesome -->
 <link rel="stylesheet" href="{{ asset('static/admin/css/font-awesome.min.css') }}">
 <!-- Ionicons -->
 <link rel="stylesheet" href="{{ asset('static/admin/css/ionicons.min.css') }}">
 <!-- Theme style -->
 <link rel="stylesheet" href="{{ asset('static/admin/dist/css/AdminLTE.min.css') }}">
 <!-- Fc_admin style -->
 <link rel="stylesheet" href="{{ asset('static/admin/css/fc_admin.css') }}">

 <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
 <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
 <!--[if lt IE 9]>
 <script src="{{ asset('static/admin/js/html5shiv.min.js') }}"></script>
 <script src="{{ asset('static/admin/js/respond.min.js') }}"></script>
 <![endif]-->

 <!-- 加载样式 -->
 @yield('css')
</head>
@yield('body')
</html>
<!-- 加载定制脚本 -->
@yield('js')