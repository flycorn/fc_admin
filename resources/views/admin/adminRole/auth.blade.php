@extends('admin.layouts.admin')

@section('title', '角色授权')

@section('css')
    <link rel="stylesheet" href="{{ asset('static/admin/plugins/iCheck/all.css') }}">
@endsection

@section('js')
    <script src="{{ asset('static/admin/plugins/iCheck/icheck.min.js') }}"></script>
    <script>
        $(function(){
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue'
            });
        })
    </script>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title">角色：{{$role->name}} | 角色授权</h3>
                    <a href="{{url('admin/adminRole')}}" class='btn btn-default btn-xs pull-right'>返回上页</a>
                </div>
            </div>

            <form action="{{ url('adminApi/adminRole/'.$role->id.'/auth') }}" method="POST">
                <input type="hidden" name="_method" value="PUT">
                {{csrf_field()}}
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">权限列表</h3>
                </div>

                <div class="box-body">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th>权限名称</th>
                                <th>权限规则</th>
                                <th>选择</th>
                            </tr>
                            @if(count($permission_list))
                                @foreach($permission_list as $k => $item)
                                <tr>
                                <td>{{$item['_name']}}</td>
                                <td>{{$item['name']}}</td>
                                <td>
                                    <input type="checkbox" class="minimal" name="perm_ids[]" @if(in_array($item['id'], $role_perms_ids)) checked="checked" @endif value="{{$item['id']}}">
                                </td>
                            </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">设置</button>
                </div>
                <!-- /.box-footer -->

            </div>
            <!-- /.box -->
            </form>
        </div>
    </div>
@endsection