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
            <form method="POST" action="{{ url($moduleRoute.'/audit_form_submit') }}">
 {!! csrf_field() !!} 
<table class="table table-bordered">
<tbody>
    <tr>
        <td width="20%" align="right">ID </td>
        <td>{{$object->apkid}}</td>
    </tr> 
    <tr>
        <td width="20%" align="right">游戏包名</td>
        <td>{{$object->Apkname}}</td>
    </tr>
     <tr>
        <td width="20%" align="right">游戏名</td>
        <td>{{$object->gameName}}</td>
    </tr>
    
    <tr>
        <td width="20%" align="right">开发者姓名</td>
        <td>{{$object->developerName}}</td>
    </tr>
    <tr>
        <td width="20%" align="right">游戏包类型</td>
        <td>{{$object->type}}</td>
    </tr>
    <tr>
        <td width="20%" align="right">更新类型</td>
        <td>{{$object->Apkuptype}}</td>
    </tr>
    <tr>
        <td width="20%" align="right">开放下载时间</td>
        <td>{{$object->Opendowndate}}</td>
    </tr>
    <tr>
        <td width="20%" align="right">开服时间</td>
        <td>{{$object->OpeServndate}}</td>
    </tr>
    <tr>
        <td width="20%" align="right">上传时间</td>
        <td>{{$object->Uploaddate}}</td>
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
<input type="hidden" value="{{$object->apkid}}" name="id">
<input type="hidden" value="{{$object->Gameid}}" name="gid">
            </form>
</div>
</div>
@endsection
