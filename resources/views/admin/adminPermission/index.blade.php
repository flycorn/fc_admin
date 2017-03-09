@extends('admin.layouts.admin')

@section('title', '权限列表')

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
        "sAjaxSource": "/adminApi/adminPermission/{{$pid}}",
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
           [3],
           [5],
           [6]
        ],
        "aoColumns": [
         { "data": "id" },
         { "data": "name", "bSortable": false },
         { "data": "rule", "bSortable": false },
         { "data": "is_menu" },
         { "data": "icon", "bSortable": false },
         { "data": "sort" },
         { "data": "updated_at" },
         { "data": "id", "bSortable": false },
        ],
        "columnDefs" : [
            //是否菜单
            {
                "render" : function(data) {
                    if(data == 1){
                        return "<span class='btn btn-info btn-xs'>是</span>";
                    }
                    return "";
                },
                "targets": 3
            },
            //icon
            {
                "render" : function(data) {
                    if(data != ""){
                        return "<i class='fa "+data+"'>&nbsp;</i>";
                    }
                    return "";
                },
                "targets": 4
            },
            //操作
            {
                "render" : function(data, type, row) {
                    if(data > 0) {
                        var opt_html = '';
                        @if(adminAuth('admin.adminPermission.show'))
                        opt_html += "<a href='{{ url('admin/adminPermission') }}/"+data+"/show' class='X-Small btn-xs text-info'><i class='fa fa-send'></i> 详情</a>";
                        @endif
                        @if(adminAuth('admin.adminPermission.index'))
                        opt_html += "<a href='{{ url('admin/adminPermission') }}/"+data+"' class='X-Small btn-xs text-navy'><i class='fa fa-navicon'></i> 子级权限</a>";
                        @endif
                        @if(adminAuth('admin.adminPermission.edit'))
                        opt_html += "<a href='{{ url('admin/adminPermission') }}/"+data+"/edit' class='X-Small btn-xs text-success'><i class='fa fa-edit'></i> 编辑</a>";
                        @endif
                        @if(adminAuth('admin.adminPermission.delete'))
                        opt_html += "<a href='javascript:;' onclick='delData("+data+")' class='X-Small btn-xs text-danger'><i class='fa fa-times-circle'></i> 删除</a>";
                        @endif

                        return opt_html;
                    }
                },
                "targets": 7
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
      fc_confirm("您要确认删除该权限么?", function(){
          fc_ajax("/adminApi/adminPermission/"+id, {_method:'delete'}, 'post', 'json', function(res){
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
   <h3 class="box-title">权限列表</h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body table-responsive">
   <table id="dataTable" class="table table-bordered table-striped">
    <thead>
    <tr>
        <th colspan="8">
            <span class="btn-flat text-info">所属权限：@if($parent_permission) {{$parent_permission->name}} @else 顶级权限 @endif</span>&nbsp;&nbsp;&nbsp;&nbsp;
            @if(adminAuth('admin.adminPermission.create'))
            <a href="{{ url('admin/adminPermission/'.$pid.'/create') }}" class='btn btn-success btn-sm'>添加权限</a>
            @endif
            @if($pid)&nbsp;&nbsp;&nbsp;&nbsp;<a href="{{ url('admin/adminPermission/'.$parent_permission->pid) }}" class='btn btn-default btn-sm'>返回上级</a>@endif
        </th>
    </tr>
    <tr>
     <th>权限ID</th>
     <th>权限名称</th>
     <th>权限规则</th>
     <th>是否菜单</th>
     <th>ICON</th>
     <th>排序</th>
     <th>编辑时间</th>
     <th>操作</th>
    </tr>
    </thead>
    <tbody>
    </tbody>
    <tfoot>
        <tr>
            <th>权限ID</th>
            <th>权限名称</th>
            <th>权限规则</th>
            <th>是否菜单</th>
            <th>ICON</th>
            <th>排序</th>
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
