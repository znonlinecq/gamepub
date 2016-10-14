@extends('dashboard')
@section('content')
<div class="box">
<div class="box-header with-border">
    <a href="{{url($moduleRoute)}}" class="btn btn-default btn-sm" role="button">返回</a>
    @if(session('message'))
    <p class="bg-success">{{session('message')}}</p>
    @endif    
</div>
<div class="box-body">
            <!-- form start -->
            <form method="POST" action="{{ url($moduleRoute.'/order_pay_form_submit') }}">
 {!! csrf_field() !!} 
<table class="table table-bordered">
<tbody> 
    <tr>
        <td width="20%" align="right">订单号</td>
        <td>{{$order->orderid}}</td>
    </tr>
     <tr>
        <td width="20%" align="right">公会名称</td>
        <td>{{$guild->Name}}</td>
    </tr>   

    <tr>
        <td width="20%" align="right">支付金额</td>
        <td>{{$order->money}}</td> 
    </tr>
    <tr>
        <td width="20%" align="right">支付密码</td>
        <td>
        <input type="password" name="password" value="" class="form-control" style="width:15%" >
        @if ($errors->has('password'))
            <strong>支付密码错误!</strong>
        @endif
 
        </td> 
    </tr>
     <tr>
        <td width="20%" align="right">验证码</td>
        <td>
        <div class="form-group form-inline"><input type="text" name="captcha"  class="form-control" style="width:15%">
          <a onclick="javascript:re_captcha();" >
            <img src="{{ URL('kit/captcha/1') }}"  alt="验证码" title="刷新图片" width="100" height="35" id="c2c98f0de5a04167a9e427d883690ff6" border="0">
        </a></div>
        @if ($errors->has('captcha'))
            <strong>验证码错误!</strong>
        @endif
 
        </td> 
    </tr>
 
    <tr>
        <td width="20%" align="right">备注</td>
        <td>
            <textarea class="form-control" name="description">{{old('description')}}</textarea>
        @if ($errors->has('description'))
            <strong>{{ $errors->first('description') }}</strong>
        @endif
        </td>
    </tr>
    <tr>
        <td width="20%" align="right"></td>
        <td>
            <input name="submit" type="submit" class="btn btn-primary" value="支付">
        </td>
    </tr>

</tbody>

</table>
<input type="hidden" name="oid" value="{{$order->id}}" >
<input type="hidden" name="gid" value="{{$order->gid}}" >
            </form>
</div>
</div>

<script>  
  function re_captcha() {
    $url = "{{ URL('kit/captcha') }}";
        $url = $url + "/" + Math.random();
        document.getElementById('c2c98f0de5a04167a9e427d883690ff6').src=$url;
  }
</script>
@endsection
