<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
  @section('csrf')
  @show
  <title>教学考勤管理 - @yield('title')</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" type="text/css" href="/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/other/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="/other/ionicons.min.css">
	<link rel="stylesheet" type="text/css" href="/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" type="text/css" href="/dist/css/skins/skin-blue.min.css">
  @section('style')
  @show
  <script src="/plugins/jQuery/jquery-2.2.3.min.js"></script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  
  <header class="main-header">

    
    <a href="/" class="logo">
      
      <span class="logo-mini"><b>考</b>勤</span>
      
      <span class="logo-lg"><b>教学</b>考勤</span>
    </a>

    
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          
          <li class="dropdown user user-menu">
            
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- The user image in the navbar-->
              <img src="@yield('avatar', '/dist/img/avatar5.png')" class="user-image" alt="User Image">
              
              <span class="hidden-xs">@yield('teacher', $user['username'])</span>
            </a>
            <ul class="dropdown-menu">
              
              <li class="user-header">
                <img src="@yield('avatar', '/dist/img/avatar5.png')" class="img-circle" alt="User Image">

                <p>
                  @yield('teacher', $user['username'])
                  <small>{{ date('Y年 m月 d日 l',time()) }}</small>
                </p>
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="/myinfo" class="btn btn-default btn-flat">我的信息</a>
                </div>
                <div class="pull-right">
                  <a href="/logout" class="btn btn-default btn-flat">退出</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  
  <aside class="main-sidebar">

    
    <section class="sidebar">

      
      <div class="user-panel">
        <div class="pull-left image">
          <img src="@yield('avatar', '/dist/img/avatar5.png')" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>@yield('teacher', $user['username'])</p>
          
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>

      
      @section('menu')
        侧边栏
      @show
      
    </section>
    
  </aside>

  
  <div class="content-wrapper">
    
    <section class="content-header">
      <h1>
        @yield('pageChineseTitle')
        <small>@yield('pageEnglishTitle')</small>
      </h1>
    </section>

    
    <section class="content">
@section('content')
内容
@show
      

    </section>
    
  </div>
  

  
  <footer class="main-footer">
    
    <div class="pull-right hidden-xs">
      <strong>Version</strong> 1.0.0
    </div>
    
    <strong>Copyright &copy; 2016 <a href="#">CZJT</a>.</strong> All rights reserved.
  </footer>

</div>
@section('modal')
@show
<script src="/bootstrap/js/bootstrap.min.js"></script>
<script src="/dist/js/app.min.js"></script>
@section('js')
@show
</html>