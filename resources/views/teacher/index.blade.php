@extends('layouts.adminlte')

@section('csrf')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('pageChineseTitle', '仪表盘')

@section('pageEnglishTitle', 'Dashboard')

@section('title', '教师后台')

@if ($user['email'])
	@section('avatar', Auth::guard('teacher')->user()->gravatar)
@endif

@section('menu')
<ul class="sidebar-menu">
	<!-- Optionally, you can add icons to the links -->
	<li class="active"><a href="/"><i class="fa fa-dashboard"></i> <span>仪表盘</span></a></li>
	<li><a href="/lesson"><i class="fa fa-book"></i> <span>课程管理</span></a></li>
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
	<div class="col-lg-6 col-xs-6">
		<div class="small-box bg-aqua">
			<div class="inner">
				<h3 id="classnum"></h3>
				<p>教授班级数量</p>
			</div>
			<div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="/lesson" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
		</div>
	</div>
	<div class="col-lg-6 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3 id="subjectnum"></h3>

          <p>教授课程数量</p>
        </div>
        <div class="icon">
          <i class="ion ion-stats-bars"></i>
        </div>
        <a href="/lesson" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$.ajax({
	type: "GET",
	//cache: false,
	url: "{{ action('LessonController@getSubject') }}",
	success: function (msg) {
		$('#subjectnum').html(msg.length);
	}
});
$.ajax({
	type: "GET",
	//cache: false,
	url: "{{ action('LessonController@getTeach') }}",
	success: function (msg) {
		var teach_list = [];
		var num = 0;
		for (var key in msg) {
			if (!$.isArray(teach_list[msg[key]['class']])) {
				teach_list[msg[key]['class']] = [];
			}
			teach_list[msg[key]['class']].push(msg[key]['subject']);
		}
		for (var key in teach_list) {
			num++;
		}
		$('#classnum').html(num);
	}
});
</script>
@endsection