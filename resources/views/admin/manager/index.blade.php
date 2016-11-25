@extends('admin.layouts.admin')

@section('title', '管理员列表')

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
        "sAjaxSource": "{{ url('admin/manager') }}",
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
           [5]
        ],
        "aoColumns": [
         { "data": "user_id" },
         { "data": "username", "bSortable": false },
         { "data": "nickname", "bSortable": false },
         { "data": "avatar", "bSortable": false },
         { "data": "email", "bSortable": false },
         { "data": "updated_at" },
         { "data": "user_id", "bSortable": false },
        ],
        "columnDefs" : [
            //头像
            {
              "render" : function(data, type, row) {
                 if(data != "") {
                   return "<img src='/"+data+"' class='img-md img-circle' /> ";
                 }
                 return "";
              },
              "targets": 3
            },
            //操作
            {
                "render" : function(data, type, row) {
                    if(data > 0) {
                        var opt_html = '';
                            @if($admin->auth('admin.manager.show'))
                            opt_html += "<a href='{{ url('admin/manager') }}/"+data+"' class='btn btn-flat btn-info btn-xs'>详情</a>";
                            @endif
                        if(data > 1){
                            @if($admin->auth('admin.manager.edit'))
                            opt_html += "<a href='{{ url('admin/manager') }}/"+data+"/edit' class='btn btn-flat btn-primary btn-xs'>编辑</a>";
                            @endif
                            @if($admin->auth('admin.manager.destroy'))
                            opt_html += "<a href='javascript:;' onclick='delData("+data+")' class='btn btn-flat btn-danger btn-xs'>删除</a>";
                            @endif
                        }
                        return opt_html;
                    }
                },
                "targets": 6
            }
        ],
    });
      loading(1);
      dataTable.on('preXhr.dt', function () {
          loading(1);
      });
      dataTable.on('draw.dt', function () {
          loading(0);
      });
  })

  /**
   * 删除数据
   * @param $id
   */
  function delData(id)
  {
      id = parseInt(id);
      fc_confirm("您要确认删除该管理员么?", function(){
          fc_ajax("{{ url('admin/manager')}}/"+id, {_method:'delete'}, 'post', 'json', function(res){
              if(res.status == 0){
                  fc_msg("删除成功!", 1);
                  //刷新数据
                  dataTable.ajax.reload();
                  return;
              }
              fc_msg(res.msg, 0);
          });
      });
  }
 </script>
@endsection

@section('content')
 <div class="box">
  {{--<div class="box-header">--}}
   {{--<h3 class="box-title">Data Table With Full Features</h3>--}}
  {{--</div>--}}
  <!-- /.box-header -->
  <div class="box-body table-responsive">
   <table id="dataTable" class="table table-bordered table-striped">
    <thead>
    <tr>
        <th colspan="7">
            @if($admin->auth('admin.manager.create'))
            <a href="{{ url('admin/manager/create') }}" class='btn btn-success btn-sm'>添加管理员</a>
            @endif
        </th>
    </tr>
    <tr>
     <th>管理员ID</th>
     <th>用户名</th>
     <th>昵称</th>
     <th>头像</th>
     <th>邮箱</th>
     <th>编辑时间</th>
     <th>操作</th>
    </tr>
    </thead>
    <tbody>
    </tbody>
    <tfoot>
        <tr>
            <th>管理员ID</th>
            <th>用户名</th>
            <th>昵称</th>
            <th>头像</th>
            <th>邮箱</th>
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
