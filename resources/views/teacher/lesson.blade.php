@extends('layouts.adminlte')

@section('csrf')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('pageChineseTitle', '课程管理')

@section('pageEnglishTitle', 'Lesson')

@section('title', '教师后台')

@if ($user['email'])
	@section('avatar', Auth::guard('teacher')->user()->gravatar)
@endif

@section('menu')
<ul class="sidebar-menu">
	<li><a href="/"><i class="fa fa-dashboard"></i> <span>仪表盘</span></a></li>
	<li class="active"><a href="/lesson"><i class="fa fa-book"></i> <span>课程管理</span></a></li>
	<li class="treeview">
		<a href="#"><i class="fa fa-book"></i> <span>出勤管理</span>
			<span class="pull-right-container">
				<i class="fa fa-angle-left pull-right"></i>
			</span>
		</a>
		<ul class="treeview-menu">
			<li><a href="/attend/named">开始点名</a></li>
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
	<div class="col-md-6">
		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title">我的授课</h3>
			</div>

			<div class="box-body no-padding">
				<div class="dataTables_wrapper form-inline dt-bootstrap">
					<table class="table table-striped">
						<tbody id="teach">
							<tr>
								<th>课程名称</th>
								<th>班级</th>
								<th style="width: 60px">操作</th>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="box-footer clearfix">
			</div>
		</div>
	</div>

	<div class="col-md-6">
		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title">课程管理</h3>
				<div class="box-tools pull-right">
					<div class="input-group input-group-sm" style="width: 150px;">
					    <input type="text" name="add_subject" class="form-control pull-right" placeholder="新增">
						<div class="input-group-btn">
	                    	<button id="addsubject" type="submit" class="btn btn-default"><i class="fa fa-plus"></i></button>
	                	</div>
					</div>
				</div>
			</div>

			<div class="box-body">
				<table class="table table-hover table-striped">
					<thead>
						<tr>
							<th>#</th>
							<th>课程名称</th>
							<th>操作</th>
						</tr>
					</thead>
					<tbody id="allsubject">
						
					</tbody>
				</table>
			</div>
			<div class="box-footer clearfix">
			</div>
		</div>
	</div>

	<div class="col-xs-12">
		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title">所有课程</h3>
				<div class="box-tools pull-right">
					<div class="input-group input-group-sm" style="width: 150px;">
					    <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">
						<div class="input-group-btn">
	                    	<button id="search" type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
	                	</div>
					</div>
				</div>
			</div>

			<div class="box-body">
				<div class="dataTables_wrapper form-inline dt-bootstrap">
					<div class="row">
						<div class="col-sm-12">
							<table class="table table-hover table-bordered table-striped dataTable">
								<thead>
									<tr>
										<th>班级</th>
										<th>所属系部</th>
										<th>操作</th>
									</tr>
								</thead>
								<tbody id="allclass">
									@foreach ($class as $value)
										<tr>
											<td>{{ $value->major }}</td>
											<td>{{ $value->department }}</td>
											<td>
												<button data-add="{{ $value->major }}" type="button" class="btn btn-primary addll">加入我的授课</button>
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
<div id="pagecontro">
{{ $class->links() }}
</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('modal')
<div class="modal fade bs-example-modal-sm" id="sendsuccess" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <p>添加成功&hellip;</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade bs-example-modal-sm" id="nonerror" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <p>请先添加课程&hellip;</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal" id="add">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">完善信息</h4>
      </div>
      <form role="form" method="POST" action="{{ action('LessonController@storeTeach') }}">
      {{ csrf_field() }}
      <div class="modal-body">
      <input id="hidden-class" type="text" name="classname" value="" hidden />
        <div class="form-group">
        	<label>年级</label>
        	<select class="form-control" name="year">
        		@for ($year = 1; $year < 5; $year++)
        			<option>{{ date('Y')-4+$year }}</option>
        		@endfor
        	</select>
        </div>
        <div class="form-group">
        	<label>班级</label>
        	<select class="form-control" name="classnum">
        		<option>1班</option>
        		<option>2班</option>
        		<option>3班</option>
        		<option>4班</option>
        	</select>
        </div>
        <div class="form-group">
        	<label>课程</label>
        	<select id="subject" class="form-control" name="subject">
        		
        	</select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">取消</button>
        <button type="submit" class="btn btn-primary">确定</button>
      </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
@endsection

@section('js')
<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$('#search').click(function () {
	var input = $('input[name="table_search"]').val();
	if (input != "") {
		$.ajax({
			type: "POST",
			url: "{{ action('LessonController@search') }}",
			data: {'input':input},
			success: function(msg) {
				var html = "";
				for (var i in msg) {
					html += "<tr><td>"+msg[i]['major']+"</td><td>"+msg[i]['department']+"</td><td><button type=\"button\" class=\"btn btn-primary addll\" data-add=\""+msg[i]['major']+"\">加入我的授课</button></td></tr>"
				}
				$('#allclass').html(html);
				$('#pagecontro').html("");
			}
		});
	}
});

var subject_list = [];
var num = subject_list.length;
$.ajax({
	type: "GET",
	url: "{{ action('LessonController@getSubject') }}",
	cache: false,
	async: false,
	success: function (msg) {
		if (msg != 'null') {
			subject_list = msg;
			var html = '';
			for (var key in subject_list) {
				html += '<tr><td>'+(Number(key)+1)+'</td><td>'+subject_list[key]+'</td><td>'+'<button type="button" class="btn btn-block btn-danger btn-xs delsubject" data-index="'+key+'">删除</button></td>'
			}
			$('#allsubject').html(html);
		}
	}
});
$.ajax({
	type: "GET",
	url: "{{ action('LessonController@getTeach') }}",
	cache: false,
	//async: false,
	success: function (msg) {
		if (msg) {
			var html = '';
			for (var key in msg) {
				html += '<tr><td>'+msg[key]['class']+'</td><td>'+msg[key]['subject']+'</td><td><button type="button" class="btn btn-block btn-danger btn-xs delpro" data-prodel="'+key+'"">删除</button></td>';
			}
			$('#teach').append(html);
		}
	}
});
$('body').on('click','.addll',function (e) {
	if (!subject_list) {
		$('#nonerror').modal('toggle');
	} else {
		for (var key in subject_list) {
			$('#subject').append("<option>"+subject_list[key]+"</option>");
		}
		$('#hidden-class').attr('value', $(e.target).data('add'));
		$('#add').modal('toggle');
	}
});
$('#addsubject').on('click',function () {
	var input = $("input[name='add_subject']").val();
	if (input != "") {
		$.ajax({
			type: "POST",
			url: "{{ action('LessonController@storeSubject') }}",
			data: {'input':input},
			success: function (msg) {
				var html = "";
				if (msg == 'success') {
					$("#sendsuccess").modal("toggle");
					html += "<tr><td>"+(++num)+"</td><td>"+input+"</td><td><button type=\"button\" class=\"btn btn-block btn-danger btn-xs dellesson\" data-index=\""+(num-1)+"\">删除</button></td></tr>";
					$('#allsubject').append(html);
					num = subject_list.push(input);
				}
			}
		});
	}
});
$('body').on('click','.delsubject',function (e) {
	var input = $(e.target).data('index');
	$.ajax({
		type: "DELETE",
		url: "/lesson/subject/"+input,
		cache: false,
		success: function (msg) {
			var html = "";
			if (msg == 'success') {
				$('.modal-body>p').html("删除成功&hellip;");
				$("#sendsuccess").modal("show");
				$("#sendsuccess").on('hidden.bs.modal', function (e) {
					window.location.reload(true);
				})
			}
		}
	});
});
$('body').on('click','.delpro',function (e) {
	var input = $(e.target).data('prodel');
	$.ajax({
		type: "DELETE",
		url: "/teach/"+input,
		cache: false,
		success: function (msg) {
			var html = "";
			if (msg == 'success') {
				$('.modal-body>p').html("删除成功&hellip;");
				$("#sendsuccess").modal("show");
				$("#sendsuccess").on('hidden.bs.modal', function (e) {
					window.location.reload(true);
				})
			}
		}
	});
});
</script>
@endsection