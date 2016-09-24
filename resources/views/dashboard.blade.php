<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>管理后台</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="{{ asset('resources/bootstrap/css/bootstrap.min.css') }}">
  <!-- Font Awesome -->
<!--  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css"> -->
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ asset('resources/css/ionicons.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('resources/dist/css/AdminLTE.min.css') }}">
  <link rel="stylesheet" href="{{ asset('resources/dist/css/skins/skin-blue.min.css') }}">
  <link rel="stylesheet" href="{{ asset('resources/plugins/datatables/dataTables.bootstrap.css') }}">
  <link rel="stylesheet" href="{{ asset('resources/plugins/jQueryUI/jquery-ui.css') }}">
  <link rel="stylesheet" href="{{ asset('resources/plugins/jQueryUI/jquery-ui.theme.css') }}">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="resources/js/html5shiv.min.js"></script>
  <script src="resources/js/respond.min.js"></script>
  <![endif]-->
<!-- jQuery 2.2.3 -->
<script src="{{ asset('resources/plugins/jQuery/jquery-2.2.3.min.js') }}"></script>
<script src="{{ asset('resources/plugins/jQueryUI/jquery-ui.js') }}"></script>
<script src="{{ asset('resources/plugins/jQueryCookie/jquery.cookie.js') }}"></script>
<!-- Bootstrap 3.3.6 -->
<script src="{{ asset('resources/bootstrap/js/bootstrap.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('resources/dist/js/app.min.js') }}"></script>


</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <!-- Main Header -->
  @include('layouts/header') 

  <!-- Left side column. contains the logo and sidebar -->
  @include('layouts/sidebar')
  <!-- sidebar: style can be found in sidebar.less -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        {{ $title or "页面标题" }}
        <small>{{ $page_description or null }}</small>
      </h1>
        <!-- You can dynamically generate breadcrumbs here -->
<!--        <ol class="breadcrumb">
            @if(!empty($breadcrumbs))
                @foreach($breadcrumbs as $key => $value)
                    <li><a href="{{url($key)}}">{{$value}}</a></li>
                @endforeach
            @endif
        </ol> -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Your Page Content Here -->
    @yield('content')
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  @include('layouts/footer')

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane active" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Recent Activity</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript::;">
              <i class="menu-icon fa fa-birthday-cake bg-red"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                <p>Will be 23 on April 24th</p>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

        <h3 class="control-sidebar-heading">Tasks Progress</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript::;">
              <h4 class="control-sidebar-subheading">
                Custom Template Design
                <span class="pull-right-container">
                  <span class="label label-danger pull-right">70%</span>
                </span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

      </div>
      <!-- /.tab-pane -->
      <!-- Stats tab content -->
      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
      <!-- /.tab-pane -->
      <!-- Settings tab content -->
      <div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">
          <h3 class="control-sidebar-heading">General Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Report panel usage
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Some information about this general settings option
            </p>
          </div>
          <!-- /.form-group -->
        </form>
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

</body>
</html>
