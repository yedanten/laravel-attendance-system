@extends('layouts.adminlte')

@section('csrf')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('pageChineseTitle', '开始点名')

@section('pageEnglishTitle', 'Named')

@section('title', '教师后台')

@if ($user['email'])
	@section('avatar', Auth::guard('teacher')->user()->gravatar)
@endif

@section('menu')
<ul class="sidebar-menu">
	<li><a href="/"><i class="fa fa-dashboard"></i> <span>仪表盘</span></a></li>
	<li><a href="/lesson"><i class="fa fa-book"></i> <span>课程管理</span></a></li>
	<!-- <li class="active"><a href="/attend"><i class="fa fa-file"></i> <span>出勤管理</span></a></li> -->
	<li class="treeview active">
		<a href="#"><i class="fa fa-book"></i> <span>出勤管理</span>
			<span class="pull-right-container">
				<i class="fa fa-angle-left pull-right"></i>
			</span>
		</a>
		<ul class="treeview-menu">
      <li class="active"><a href="/attend/named">开始点名</a></li>
			<li><a href="/attend/history">历史记录</a></li>
		</ul>
	</li>
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
		        <h3 class="box-title" id="first-box-title">请选择班级信息</h3>
		    </div>
		    <div class="box-body" id="first-box-body">
		      
            <div class="form-group">
                <label for="class" class="col-sm-2 control-label">班级</label>
                <div class="col-sm-10">
                  <select class="form-control" name="class" id="sel-class">
                    
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="lesson" class="col-sm-2 control-label">课程</label>
                <div class="col-sm-10">
                  <select class="form-control" name="subject" id="sub"></select>
                </div>
              </div>
              <div class="form-group">
                <label for="area" class="col-sm-2 control-label">校区</label>
                <div class="col-sm-10">
                  <select class="form-control" name="area" id="area">
                    <option>排下校区</option>
                    <option>首山校区</option>
                  </select>
                </div>
              </div>
              <button type="button" class="btn btn-block btn-info" id="putclsub">确定</button>
          
		    </div>
		    <!--/.box-body-->
		</div>
	</div>
</div>
<div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">学生名单</h3>
        </div>
        <div class="box-body">
          <form class="form-horizontal" id="stulist">
            
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('modal')
<div class="modal" id="checkResult">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">以下人员缺勤</h4>
      </div>
      <form role="form" method="POST" action="{{ action('AttendController@storeNamed') }}">
      {{ csrf_field() }}
      <div class="modal-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>学号</th>
              <th>姓名</th>
            </tr>
          </thead>
          <tbody id="absence">
            
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">取消</button>
        <button type="submit" class="btn btn-primary">确定</button>
      </div>
      </form>
    </div>
  </div>
</div>
<div class="modal" id="sendSuccess">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        点名完成，可以在查询页面查询本次点名记录
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">确定</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
<script src="/plugins/chartjs/Chart.min.js"></script>
<script src="/plugins/fastclick/fastclick.js"></script>
<script src="/dist/js/demo.js"></script>
<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
@if (session('message'))
  $('#sendSuccess').modal('show');
@endif
@if (session('notclasstime'))
  $('#sendSuccess').find('.modal-body').html("{{ session('notclasstime') }}");
  $('#sendSuccess').modal('show')
@endif
var teach_list = [];
$.ajax({
  type: "GET",
  url: "{{ action('LessonController@getTeach') }}",
  cache: false,
  async: false,
  success: function (msg) {
    if (msg) {
      for (var key in msg) {
        if (!$.isArray(teach_list[msg[key]['class']])) {
          teach_list[msg[key]['class']] = [];
        }
        teach_list[msg[key]['class']].push(msg[key]['subject']);
      }
      var html = '<option>请选择班级</option>';
      for (var key in teach_list) {
        html += '<option>'+key+'</option>'
      }
    $('#sel-class').html(html);
    } else {
      $('#first-box-title').html('提示');
      $('#first-box-body').html("请先到 <a href=\"{{ action('LessonController@index') }}\">课程管理</a> 添加授课信息");
    }
  }
});
$('body').on('click', '#sel-class', function (e) {
    var classes = $('#sel-class').find("option:selected").text();
    if ($.isArray(teach_list[classes])) {
      var html = '';
      for (var key in teach_list[classes]) {
        html += '<option>'+teach_list[classes][key]+'</option>'
      }
      $('#sub').html(html);
    }
});
$('body').on('click', '#putclsub', function (e) {
  var $btn = $(this).button('loading');
  $.ajax({
    type: "POST",
    url: "{{ action('InfoController@getStudent') }}",
    data: {"class":$('#sel-class').find("option:selected").text(), 'subject': $('#sub').find("option:selected").text()},
    //async: false,
    success: function (msg) {
      $btn.button('reset');
      var html = '<button type="button" class="btn btn-info btn-sm" id="checkAll">全选</button><button type="button" class="btn btn-info btn-sm" id="checkNon">全不选</button><table class="table table-bordered"><thead><tr><th>#</th><th>学号</th><th>姓名</th></tr></thead><tbody>';
      if (msg != 'error') {
        for (var key in msg) {
          html += '<tr><td><label><input type="checkbox" class="minimal" name="stuid[]" value="'+msg[key]['stuid']+'" data-name="'+msg[key]['username']+'" /></label></td><td>'+msg[key]['stuid']+'</td><td>'+msg[key]['username']+'</td></tr>';
        }
        html += '</tbody></table><button type="button" class="btn btn-block btn-info" id="named">点名</button>';
        $('#stulist').html(html);
      }
    }
  });
});
$('body').on('click', '#checkAll', function (e) {
  $("input[name='stuid[]']").each(function () {
    $(this).prop("checked", true);
  });
});
$('body').on('click', '#checkNon', function (e) {
  $("input[name='stuid[]']").each(function () {
    $(this).removeAttr("checked", false);
  });
});
$('body').on('click', '#named', function (e) {
  var res = [];
  var key = 0;
  var html = '';
  $("input[name='stuid[]']").each(function () {
    if (!$(this).is(':checked')) {
      res[key] = [];
      res[key]['stuid'] = $(this).val();
      res[key]['username'] = $(this).data('name');
      key++;
    }
  });
  for (var k in res) {
    html += '<tr><input type="hidden" name="stuid[]" value="'+res[k]['stuid']+'" /><td>'+res[k]['stuid']+'</td><td>'+res[k]['username']+'</td></tr>';
  }
  html += '<input type="hidden" name="class" value="'+$('#sel-class').find("option:selected").text()+'" />';
  html += '<input type="hidden" name="subject" value="'+$('#sub').find("option:selected").text()+'" />';
  html += '<input type="hidden" name="area" value="'+$('#area').find("option:selected").text()+'" />';
  $('#absence').html(html);
  $('#checkResult').modal('toggle');
});
</script>
@endsection