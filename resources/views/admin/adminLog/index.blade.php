@extends('admin.layouts.admin')

@section('title', '系统日志')

@section('css')

@endsection

@section('js')

@endsection

@section('content')

    <iframe src="/admin/log-viewer" frameborder="0" style="width: 100%;min-height: 650px;"></iframe>

@endsection
