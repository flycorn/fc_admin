@extends('admin.layouts.admin')

@section('title', '系统日志')

@section('css')

    @include('log-viewer::_template.style')

@endsection

@section('js')
    <script src="{{asset('static/admin/plugins/logViewer/js/Chart.min.js')}}"></script>
    @yield('scripts')
@endsection

@section('content')

    <div class="row">

        <div class="container-fluid">
            @yield('content')
        </div>

    </div>

    <script>
        Chart.defaults.global.responsive      = true;
        Chart.defaults.global.scaleFontFamily = "'Source Sans Pro'";
        Chart.defaults.global.animationEasing = "easeOutQuart";
    </script>
    @yield('modals')

@endsection
