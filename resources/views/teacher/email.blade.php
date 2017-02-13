@extends('layouts.adminlte')

@section('csrf')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('pageChineseTitle', '绑定邮箱')

@section('pageEnglishTitle', 'Email')

@section('title', '教师后台')

@if ($user['email'])
	@section('avatar', Auth::guard('teacher')->user()->gravatar)
@endif

@section('menu')
<ul class="sidebar-menu">
	<!-- Optionally, you can add icons to the links -->
	<li><a href="/"><i class="fa fa-dashboard"></i> <span>仪表盘</span></a></li>
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
	<li class="treeview active">
		<a href="#"><i class="fa fa-reorder"></i> <span>个人中心</span>
			<span class="pull-right-container">
				<i class="fa fa-angle-left pull-right"></i>
			</span>
		</a>
		<ul class="treeview-menu">
			<li><a href="/myinfo">我的信息</a></li>
			<li><a href="/password">修改密码</a></li>
			<li class="active"><a href="/email">绑定邮箱</a></li>
		</ul>
	</li>
</ul>
@endsection

@section('content')
<div class="row">
	<div class="col-md-6">
		<div class="box">
		    <div class="box-header with-border">
		        <h3 class="box-title">绑定状态</h3>
		    </div>
		    <div class="box-body">
		    	@if (empty($email))
					<div class="alert alert-danger alert-dismissable" style="margin-top: 20px">
	                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	                    <h4>
	                    	<i class="icon fa fa-check-circle-o"></i> 
	                    	暂未绑定邮箱
	                    </h4>
	                </div>
		    	@else
					<div class="alert alert-success alert-dismissable" style="margin-top: 20px">
	                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	                    <h4>
	                    	<i class="icon fa fa-check-circle-o"></i> 
	                    	你已成功绑定邮箱{{ $email }}
	                    </h4>
	                </div>
		    	@endif
		    </div>
		    <div class="box-footer clearfix">
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="box">
			<div class="box-header">
			@if (empty($email))
				绑定
			@else
				修改
			@endif
				邮箱
			</div>
			<form class="form-horizontal">
			<div class="box-body">		
				<div class="">
					<div class="input-group">
					@if (empty($email))
						<input name="email" type="email" class="form-control" id="email" placeholder="邮箱">
					@else
						<input name="email" type="oldemail" class="form-control" id="email" placeholder="新邮箱">
					@endif
						<span class="input-group-btn">
							<button type="button" id="send" class="btn btn-info btn-flat">发送</button>
						</span>
					</div>
				</div>
				<div class="">
					<div class="input-group">
						<input name="verify" type="text" class="form-control" id="checkcode" placeholder="请输入您收到的验证码">
						<span class="input-group-addon"><i class="fa fa-question"></i></span>
					</div>
				</div>
			</div>
				<br>
				<div class="box-footer">
	            	<button type="button" class="btn btn-info pull-right" id="bindmail">保存</button>
	        	</div>
	        </form>
		</div>
	</div>
</div>
@endsection

@section('modal')
<div class="modal fade bs-example-modal-sm" id="error" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <p><span id="emailerrorinfo">请输入正确的邮箱地址</span>&hellip;</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade bs-example-modal-sm" id="sendsuccess" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <p>发送成功，请到邮箱内查看&hellip;</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
      </div>
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
</script>
<script type="text/javascript">
function checkEmail(address) {
	var reg = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/;
	res = reg.test(address);
	if (!res) {
		return false;
	} else {
		return true;
	}
}
$("#send").click(function () {
	var res = checkEmail($("#email").val());
	if (!res) {
		$("#error").modal("toggle");
		return;
	}
	$('#send').attr("disabled",true);
	$("#email").attr("disabled",true);
	$("#send").html("<span id=\"second\">60</span>s后重试");
	var timer = 60;
	countdown();
	$.ajax({
		type: "post",
		url: "{{ action('InfoController@sendMail') }}",
		cache: false,
		data: {"email":$("#email").val()},
		success: function(msg) {
			if (msg != "success") {
				$("#error").modal("toggle");
			} else {
				$("#sendsuccess").find(".modal-body").html('发送成功，请到邮箱内查看&hellip;');
				$("#sendsuccess").modal("toggle");
			}
		}
	});
	function countdown () {
		if (timer > 0) {
			timer --;
			setTimeout(function () {
				$("#second").html(timer);
				countdown();
			}, 1000);
		} else {
			$("#send").html("发送");
			$("#send").removeAttr("disabled");
			$("#email").removeAttr("disabled");
		}
	}
});
$('body').on('click', '#bindmail', function (e) {
	$.ajax({
		type: "POST",
		url: "{{ action('InfoController@bindMail') }}",
		data: {"email": $("#email").val(), "verify": $("#checkcode").val()},
		success: function (msg) {
			if (msg == 'success') {
				$("#sendsuccess").find(".modal-body").html('绑定成功');
				$("#sendsuccess").modal("toggle");
				$("#sendsuccess").find("button").click(function (e) {
					window.location.reload(true);
				});
			} else {
				$("#sendsuccess").find(".modal-body").html('邮箱或验证码错误');
				$("#sendsuccess").modal("toggle");
			}
			
		},
		error: function (msg) {
			$("#sendsuccess").find(".modal-body").html('邮箱或验证码错误');
			$("#sendsuccess").modal("toggle");
		}
	});
});
</script>
@endsection