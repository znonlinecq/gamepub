@extends('dashboard')
@section('content')
<div class="box">
<div class="box-header with-border">
    <a href="{{url($moduleRoute.'/list_not_audit')}}" class="btn btn-default btn-sm active" role="button">返回</a>
    @if(session('message'))
    <p class="bg-success">{{session('message')}}</p>
    @endif    
</div>
<div class="box-body">
            <!-- form start -->
            <form method="POST" action="{{ url('audit_form_submit') }}">
 {!! csrf_field() !!} 
<table class="table table-bordered">
<tbody>
    <tr>
        <td width="20%" align="right">ID </td>
        <td>{{$object->id}}</td>
    </tr>
    <tr>
        <td width="20%" align="right">登录账号</td>
        <td>{{$object->acount}}</td>
    </tr>
     <tr>
        <td width="20%" align="right">工会ID</td>
        <td>{{$object->guildid}}</td>
    </tr>
    <tr>
        <td width="20%" align="right">推广游戏</td>
        <td>{{$object->games}}</td>
    </tr>
    <tr>
        <td width="20%" align="right">姓名</td>
        <td>{{$object->name}}</td>
    </tr>
    <tr>
        <td width="20%" align="right">身份证</td>
        <td>{{$object->namecard}}</td>
    </tr>
    <tr>
        <td width="20%" align="right">QQ</td>
        <td>{{$object->qq}}</td>
    </tr>
    <tr>
        <td width="20%" align="right">注册时间</td>
        <td>{{$object->created}}</td>
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
            </form>
</div>
</div>
@endsection
