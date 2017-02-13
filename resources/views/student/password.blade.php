@extends('layouts.adminlte')

@section('style')
<link rel="stylesheet" href="/plugins/iCheck/all.css">
@endsection

@section('csrf')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('pageChineseTitle', '修改密码')

@section('pageEnglishTitle', 'Password')

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
			<li><a href="/myinfo">我的信息</a></li>
			<li class="active"><a href="/password">修改密码</a></li>
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
				<h3 class="box-title">修改密码</h3>
			</div>
			<div class="box-body">
				<form class="form-horizontal">
					{{ csrf_field() }}
					<div class="form-group">
						<label for="current_password" class="col-sm-2 control-label">当前密码</label>
						<div class="col-sm-10">
							<input name="current_password" type="password" class="form-control" id="current_password" placeholder="当前密码(必填)">
						</div>
					</div>
					<div class="form-group">
						<label for="new_password" class="col-sm-2 control-label">新密码</label>
						<div class="col-sm-10">
							<input name="new_password" type="password" class="form-control" id="new_password" placeholder="新密码(必填)">
						</div>
					</div>
					<div class="form-group">
						<label for="again_password" class="col-sm-2 control-label">确认新密码</label>
						<div class="col-sm-10">
							<input name="again_password" type="password" class="form-control" id="again_password" placeholder="确认新密码(必填)">
						</div>
					</div>
					<button type="button" class="btn btn-block btn-info" id="update">修改</button>
            	</form>
            </div>
			<div class="box-footer clearfix"></div>
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
        <p>修改成功&hellip;</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">确定</button>
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
$('body').on('click', '#update', function (e) {
	if (!$("input[name='current_password']").val() || !$("input[name='new_password']").val() || !$("input[name='again_password']").val() || $("input[name='new_password']").val() != $("input[name='again_password']").val()) {
		$('#sendsuccess').find('.modal-body').html('请检查填写内容');
		$('#sendsuccess').modal('toggle');
		return;
	}
	$.ajax({
		type: "POST",
		url: "{{ action('InfoController@updatePassword') }}",
		data: {"current_password": $("input[name='current_password']").val(), "new_password": $("input[name='new_password']").val(), "again_password": $("input[name='again_password']").val()},
		success: function (msg) {
			if (msg == 'success') {
				$('#sendsuccess').find('.modal-body').html('修改成功');
			} else {
				$('#sendsuccess').find('.modal-body').html('请检查填写内容');
			}
			$('#sendsuccess').modal('toggle');
		},
		error: function (msg) {
			console.log(msg);
			$('#sendsuccess').find('.modal-body').html('请检查填写内容');
			$('#sendsuccess').modal('toggle');
		}
	});
});
$('input[type="radio"].minimal').iCheck({
	radioClass: 'iradio_minimal-blue'
});
</script>
@endsection