@extends('dashboard')
@section('content')
<div class="box">
<div class="box-header with-border">
    <a href="{{url($moduleRoute)}}" class="btn btn-default btn-sm " role="button">返回</a>
    @if(session('message'))
    <p class="bg-success">{{session('message')}}</p>
    @endif    
</div>
<div class="box-body">
            <!-- form start -->
            <form method="POST" action="{{ url($moduleRoute.'/audit_form_submit') }}">
 {!! csrf_field() !!} 
<table class="table table-bordered">
<tbody>
    <tr>
        <td width="20%" align="right">ID </td>
        <td>{{$object->id}}</td>
    </tr> 
    <tr>
        <td width="20%" align="right">开发者</td>
        <td>{{$object->username}}</td>
    </tr>
     <tr>
        <td width="20%" align="right">游戏名称</td>
        <td>{{$object->Gamename}}</td>
    </tr>
    
    <tr>
        <td width="20%" align="right">APIKey</td>
        <td>{{$object->Apikey}}</td>
    </tr>
    <tr>
        <td width="20%" align="right">测试支付URL</td>
        <td>{{$object->Testpayturl}}</td>
    </tr>
    <tr>
        <td width="20%" align="right">正式支付URL</td>
        <td>{{$object->Payturl}}</td>
    </tr>
    <tr>
        <td width="20%" align="right">生成时间</td>
        <td>{{$object->Adddate}}</td>
    </tr>
    <tr>
        <td width="20%" align="right">更新时间</td>
        <td>{{$object->Lastupdate}}</td>
    </tr>
    <tr>
        <td width="20%" align="right">状态</td>
        <td>{{$object->status}}</td>
    </tr>
    <tr>
        <td width="20%" align="right">备注</td>
        <td>
            <textarea class="form-control" name="description"></textarea>
        </td>
    </tr>
    <tr>
        <td width="20%" align="right"></td>
        <td>
            <button name="submit" type="submit" class="btn btn-primary" value="yes">通过</button>
            <button name="submit" type="submit" class="btn btn-default" value="no">驳回</button>
        </td>
    </tr>


</tbody>

</table>
<input type="hidden" value="{{$object->id}}" name="id">
            </form>
</div>
</div>
@endsection
