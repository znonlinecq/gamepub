<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>忘记密码</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="{{ asset('resources/bootstrap/css/bootstrap.min.css') }}">
  <!-- Font Awesome -->
  <!-- Ionicons -->
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('resources/dist/css/AdminLTE.min.css') }}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset('resources/plugins/iCheck/square/blue.css') }}">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition login-page">
<div class="login-box">

  <div class="login-logo">
    <a href="#">忘记密码</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">

    <form action="{{ url('/password/email') }}" method="post">
    {!! csrf_field() !!} 
    <div class="form-group has-feedback">
        <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="Email">
        <span class="glyphicon glyphicon-envelope form-control-feedback"> </span>
        @if ($errors->has('email'))
            <strong>{{ $errors->first('email') }}</strong>
        @endif
      </div>
        <div class="row">
            <div class="col-xs-8 text-right" style="padding-top:10px;">
                <a href="{{ url('/auth/login') }}" class="text-center">登录</a>
            </div>
            <div class="col-xs-4">
            <button type="submit" class="btn btn-primary btn-block btn-flat">发送</button>
            </div>
        </div>
        <div style="clear:both;"></div>
        <!-- /.col -->
      </div>
    </form>



  </div>

  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
<script src="{{ asset('resources/plugins/jQuery/jquery-2.2.3.min.js') }}"></script>
<!-- Bootstrap 3.3.6 -->
<script src="{{ asset('resources/bootstrap/js/bootstrap.min.js') }}"></script>
</body>
</html>
