@extends('layouts.adminlte')

@section('style')
<link rel="stylesheet" href="/plugins/iCheck/all.css">
@endsection

@section('csrf')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('pageChineseTitle', '个人信息')

@section('pageEnglishTitle', 'MyInfo')

@section('title', '教师后台')

@if ($user['email'])
	@section('avatar', Auth::guard('student')->user()->gravatar)
@endif

@section('menu')
<ul class="sidebar-menu">
	<li><a href="/attend/history"><i class="fa fa-file"></i> <span>我的出勤</span></a></li>
	<li class="treeview active">
		<a href="#"><i class="fa fa-reorder"></i> <span>个人中心</span>
			<span class="pull-right-container">
				<i class="fa fa-angle-left pull-right"></i>
			</span>
		</a>
		<ul class="treeview-menu">
			<li class="active"><a href="/myinfo">我的信息</a></li>
			<li><a href="/password">修改密码</a></li>
			<li><a href="/email">绑定邮箱</a></li>
		</ul>
	</li>
</ul>
@endsection

@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="box box-info">
			<div class="box-header with-border">
				<h3 class="box-title">个人信息</h3>
			</div>
			<div class="box-body">
				<form class="form-horizontal">
				{{ csrf_field() }}
              <div class="box-body">
                <div class="form-group">
                  <label for="inputWorkId" class="col-sm-2 control-label">学号</label>
                  <div class="col-sm-10">
                  	<p class="form-control-static">{{ $info->stuid }}</p>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputName" class="col-sm-2 control-label">姓名</label>
                  <div class="col-sm-10">
                    <p class="form-control-static">{{ $info->username }}</p>
                  </div>
                </div>
				<div class="form-group">
                  <label for="inputEmail" class="col-sm-2 control-label">邮箱</label>
                  <div class="col-sm-10">
                    <p class="form-control-static">{{ $info->email }}</p>
                  </div>
                </div>


				<div class="form-group">
					<label class="col-sm-2 control-label">性别</label>
					<div class="col-sm-10">
                    <p class="form-control-static">{{ $info->sex }}</p>
                  </div>
				</div>


              </div> 
            </form>
			</div>
			<div class="box-footer clearfix">
			</div>
		</div>
	</div>
</div>
@endsection

@section('js')
<script src="/plugins/iCheck/icheck.min.js"></script>
<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$('input[type="radio"].minimal').iCheck({
	radioClass: 'iradio_minimal-blue'
});
</script>
@endsection