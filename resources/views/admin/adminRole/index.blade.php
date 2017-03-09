@extends('admin.layouts.admin')

@section('title', '角色列表')

@section('css')
    <link rel="stylesheet" href="{{ asset('static/admin/plugins/datatables/dataTables.bootstrap.css') }}">
@endsection

@section('js')
    <script src="{{ asset('static/admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('static/admin/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script>
        var dataTable = null;
        $(function(){

            //数据表格
            dataTable = $("#dataTable").DataTable({
                //配置
                "bServerSide": true,
                "bSort": true,
                "sAjaxSource": "/adminApi/adminRole",
                "iDisplayLength": 10,
                "oLanguage": {
                    "sProcessing": "正在加载中...",
                    "sLengthMenu": "每页显示 _MENU_ 条记录",
                    "sZeroRecords": "抱歉, 没有匹配的数据",
                    "sInfo": "从 _START_ 到 _END_ /共 _TOTAL_ 条数据",
                    "sInfoEmpty": "没有数据",
                    "sInfoFiltered": "(从 _MAX_ 条数据中检索)",
                    "sSearch": "搜索",
                    "sLengthMenu": "_MENU_ 页/条",
                    "oPaginate": {
                        "sFirst": "首页",
                        "sPrevious": "上一页",
                        "sNext": "下一页",
                        "sLast": "尾页"
                    },
                    "sZeroRecords": "没有检索到数据"
                },
                "aaSorting": [
                    [0, 'desc'],
                    [3]
                ],
                "aoColumns": [
                    { "data": "id" },
                    { "data": "name", "bSortable": false },
                    { "data": "description", "bSortable": false },
                    { "data": "updated_at" },
                    { "data": "id", "bSortable": false },
                ],
                "columnDefs" : [
                    //操作
                    {
                        "render" : function(data, type, row) {
                            if(data > 0) {
                                var opt_html = '';
                                @if(adminAuth('admin.adminRole.show'))
                                opt_html += "<a href='{{ url('admin/adminRole') }}/"+data+"' class='X-Small btn-xs text-info'><i class='fa fa-send'></i> 详情</a>";
                                @endif
                                @if(adminAuth('admin.adminRole.auth'))
                                opt_html += "<a href='{{ url('admin/adminRole') }}/"+data+"/auth' class='X-Small btn-xs text-purple'><i class='fa fa-leaf'></i> 授权</a>";
                                @endif
                                @if(adminAuth('admin.adminRole.edit'))
                                opt_html += "<a href='{{ url('admin/adminRole') }}/"+data+"/edit' class='X-Small btn-xs text-success'><i class='fa fa-edit'></i> 编辑</a>";
                                @endif
                                @if(adminAuth('admin.adminRole.delete'))
                                opt_html += "<a href='javascript:;' onclick='delData("+data+")' class='X-Small btn-xs text-danger'><i class='fa fa-times-circle'></i> 删除</a>";
                                @endif
                                return opt_html;
                            }
                        },
                        "targets": 4
                    }
                ],
            });

            dataTable.on('preXhr.dt', function () {
                loadShow();
            });
            dataTable.on('draw.dt', function () {
                loadShow(0);
            });
        })

        /**
         * 删除数据
         * @param $id
         */
        function delData(id)
        {
            id = parseInt(id);
            fc_confirm("您要确认删除该角色么?", function(){
                fc_ajax("/adminApi/adminRole/"+id, {_method:'delete'}, 'post', 'json', function(res){
                    if(res.status == 'successful'){
                        //刷新数据
                        dataTable.ajax.reload();
                        fc_msg("删除成功!", 1);
                        return;
                    }
                    fc_msg(res.errors.message, 0);
                });
            });
        }
    </script>
@endsection

@section('content')

    <div class="box">
    <div class="box-header with-border">
    <h3 class="box-title">角色列表</h3>
    </div>
    <!-- /.box-header -->
        <div class="box-body table-responsive">
            <table id="dataTable" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th colspan="6">
                        @if(adminAuth('admin.adminRole.create'))
                        <a href="{{ url('admin/adminRole/create') }}" class='btn btn-success btn-sm'>添加角色</a>
                        @endif
                    </th>
                </tr>
                <tr>
                    <th>角色ID</th>
                    <th>角色名称</th>
                    <th>角色描述</th>
                    <th>编辑时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                <tr>
                    <th>角色ID</th>
                    <th>角色名称</th>
                    <th>角色描述</th>
                    <th>编辑时间</th>
                    <th>操作</th>
                </tr>
                </tfoot>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
@endsection
