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
<table class="table table-bordered">
<tbody>
    <tr>
        <td width="20%" align="right">订单ID </td>
        <td>{{$object->order_id}}</td>
    </tr> 
    <tr>
        <td width="20%" align="right">游戏名称</td>
        <td>{{$object->gamename}}</td>
    </tr>
    
    <tr>
        <td width="20%" align="right">推广员类型</td>
        <td>{{$object->type}}</td>
    </tr>
    <tr>
        <td width="20%" align="right">推广员等级</td>
        <td>{{$object->level}}</td>
    </tr>
    <tr>
        <td width="20%" align="right">推广员ID</td>
        <td>{{$object->username}}</td>
    </tr>
    <tr>
        <td width="20%" align="right">支付方式</td>
        <td>{{$object->paymethod}}</td>
    </tr>
    <tr>
        <td width="20%" align="right">充值金额</td>
        <td>{{$object->rmb}}</td>
    </tr>
    <tr>
        <td width="20%" align="right">状态</td>
        <td>{{$object->status}}</td>
    </tr> 
    <tr>
        <td width="20%" align="right">是否充返</td>
        <td>{{$object->isrebate}}</td>
    </tr>
    <tr>
        <td width="20%" align="right">充返金额</td>
        <td>{{$object->rebate}}</td>
    </tr>
     <tr>
        <td width="20%" align="right">充值时间</td>
        <td>{{$object->createdate}}</td>
    </tr>
    
    <tr>
        <td width="20%" align="right">备注</td>
        <td>{{$object->remark}}</td>
    </tr>
</tbody>
</table>
</div>
</div>
@endsection
