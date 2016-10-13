@extends('dashboard')
@section('content')
<div class="box">
<div class="box-header with-border">
    <a href="{{url($moduleRoute)}}" class="btn btn-default btn-sm active" role="button">返回</a>
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
        <td width="20%" align="right">充值金额</td>
        <td>{{$order->money}}</td> 
    </tr>
    <tr>
        <td width="20%" align="right">备注</td>
        <td>
            <textarea class="form-control" name="description"></textarea>
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
@endsection
