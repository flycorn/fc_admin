@extends('admin.layouts.base')

@section('body')
<body class="hold-transition {{$skin}} sidebar-mini">

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
{{--            @yield('breadcrumbs')--}}
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
    $(function () {
        //防止被嵌入子页面
        if(window.top != window)
        {
            window.top.location.href = document.location.href;
        }
    });
</script>
</body>
@endsection

