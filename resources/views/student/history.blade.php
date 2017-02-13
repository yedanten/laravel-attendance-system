@extends('layouts.adminlte')

@section('csrf')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="/plugins/daterangepicker/daterangepicker.css">
<link rel="stylesheet" type="text/css" href="/plugins/datepicker/datepicker3.css">
<link rel="stylesheet" type="text/css" href="/plugins/timepicker/bootstrap-timepicker.min.css">
<link rel="stylesheet" type="text/css" href="/plugins/datatables/dataTables.bootstrap.css">
@endsection

@section('pageChineseTitle', '历史记录')

@section('pageEnglishTitle', 'History')

@section('title', '教师后台')

@if ($user['email'])
	@section('avatar', Auth::guard('student')->user()->gravatar)
@endif

@section('menu')
<ul class="sidebar-menu">
  <li class="active"><a href="/attend/history"><i class="fa fa-file"></i> <span>我的出勤</span></a></li>
  <li class="treeview">
    <a href="#"><i class="fa fa-reorder"></i> <span>个人中心</span>
      <span class="pull-right-container">
        <i class="fa fa-angle-left pull-right"></i>
      </span>
    </a>
    <ul class="treeview-menu">
      <li><a href="/myinfo">我的信息</a></li>
      <li><a href="/password">修改密码</a></li>
      <li><a href="/email">绑定邮箱</a></li>
    </ul>
  </li>
</ul>
@endsection

@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="box">
		    <div class="box-header with-border">
		        <h3 class="box-title">历史记录查询</h3>
		    </div>
		    <div class="box-body">
          <form>
          {{ csrf_field() }}
            <div class="form-group">
              <label>日期</label>
              <div class="input-group">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>
                <input name="daterange" type="text" class="form-control pull-right" id="reservation">
              </div>
            </div>
            <button type="button" class="btn btn-block btn-info" id="search">查询</button>
          </form>
		    </div>
		</div>
	</div>
</div>
<div id="second-row">
  
</div>
@endsection

@section('js')
<script src="/other/moment.min.js"></script>
<script src="/plugins/daterangepicker/daterangepicker.js"></script>
<script src="/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<script src="/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$('#reservation').daterangepicker(
{
  locale: {
    format: 'YYYY-MM-DD'
  }
}
);
var history = '';
$('body').on('click', '#search', function (e) {
  var daterange = $('#reservation').val();
  var url = "{{ action('AttendController@history') }}"+"/"+daterange;

  $.ajax({
    type: "GET",
    url: url,
    cache: false,
    success: function (msg) {
      history = msg;
      var html = '\
      <div class="row" id="first-row">\
        <div class="col-md-12">\
          <div class="box">\
              <div class="box-header with-border">\
                <h3 class="box-title">查询结果</h3>\
              </div>\
              <div class="box-body">\
              <table id="example2" class="table table-bordered table-hover">\
                <thead>\
                  <tr>\
                    <th class="visible-md">班级</th>\
                    <th class="visible-md">学号</th>\
                    <th>姓名</th>\
                    <th>点名时间</th>\
                    <th>课程名称</th>\
                    <th class="hidden-xs">第几节大课</th>\
                    <th>出勤</th>\
                  </tr>\
                </thead>\
                <tbody>\
      ';
      for (var key in msg) {
        html += ' <tr><td class="visible-md">'+msg[key]['class']+'</td><td class="visible-md">'+msg[key]['stuid']+'</td><td>'+msg[key]['student']['username']+'</td><td>'+msg[key]['created_at']+'</td><td>'+msg[key]['subject']+'</td>'+'<td class="hidden-xs">'+msg[key]['No']+'</td>';
        if (msg[key]['attend'] == 0) {
          html += '<td style="color: red"><b>缺勤</b></td>';
        } else {
          html += '<td>出勤</td>'; 
        }
      }
      html += '</tbody></table></div></div></div></div>';
      $('#second-row').html(html);
      $('#example2').DataTable({
      "oLanguage": {
        "sLengthMenu": "每页显示 _MENU_ 条记录",
        "sZeroRecords": "对不起，查询不到任何相关数据",
        "sInfo": "当前显示 _START_ 到 _END_ 条，共 _TOTAL_条记录",
        "sInfoEmtpy": "找不到相关数据",
        "sInfoFiltered": "数据表中共为 _MAX_ 条记录)",
        "sProcessing": "正在加载中...",
        "sSearch": "搜索",
        "oPaginate": {
          "sFirst": "第一页",
          "sPrevious": " 上一页 ",
          "sNext": " 下一页 ",
          "sLast": " 最后一页 "
        }
        },
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
    }
  });
});
</script>
@endsection