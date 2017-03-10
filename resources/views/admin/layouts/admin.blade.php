@extends('admin.layouts.base')

@section('body')
<!-- AdminLTE Skins. We have chosen the skin-blue for this starter
   page. However, you can choose any other skin. Make sure you
   apply the skin class to the body tag so the changes take effect.
-->
<link rel="stylesheet" href="{{ asset('static/admin/dist/css/skins/'.config('fc_admin.skin').'.css') }}">

<!--
    load css
-->
<link href="{{ asset('static/admin/dist/css/load/load.css') }}" rel="stylesheet">

<body class="hold-transition {{config('fc_admin.skin')}} sidebar-mini">

<!-- 加载 -->
<div id="loading">
    <div id="loading-center">
        <div id="loading-center-absolute">
            <div class="object" id="object_four"></div>
            <div class="object" id="object_three"></div>
            <div class="object" id="object_two"></div>
            <div class="object" id="object_one"></div>
        </div>
    </div>
</div>

<div class="wrapper">

    <!-- Main Header -->
    @include('admin.layouts.mainHeader')

    <!-- Left side column. contains the logo and sidebar -->
    @include('admin.layouts.mainSidebar')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <!-- 面包屑 -->
        <section class="content-header">
            {{ createBreadCrumb($breadcrumbs) }}
            @yield('breadcrumb')
        </section>

        <!-- 主体内容区 -->
        <section class="content">

            <!-- 提示消息 -->
            <div class="row">
                <div class="box-body">
                    @if(session('prompt'))
                        @include('admin.layouts.prompt')
                    @endif
                </div>
            </div>

            @yield('content')
        </section>

    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    @include('admin.layouts.mainFooter')
</div>
<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src="{{ asset('static/admin/plugins/jQuery/jquery-2.2.3.min.js') }}"></script>
<!-- Bootstrap 3.3.6 -->
<script src="{{ asset('static/admin/bootstrap/js/bootstrap.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('static/admin/dist/js/app.min.js') }}"></script>
<script src="{{ asset('static/admin/js/jquery.cookie.js') }}"></script>
<!-- layer弹窗 -->
<script src="{{ asset('static/admin/js/layer/layer.js') }}"></script>
<script src="{{ asset('static/admin/js/flycorn.js') }}"></script>

<script>
//    $(function () {
//        //防止被嵌入子页面
//        if(window.top != window)
//        {
//            window.top.location.href = document.location.href;
//        }
//    });

//页面加载
window.onload = function(){
    loadShow(0);
}
$(document).ready(function(){
    loadShow(1);
})
</script>
</body>
@endsection

