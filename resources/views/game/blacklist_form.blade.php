@extends('dashboard')
@section('content')
<div class="box">
<div class="box-header with-border">
    <a href="{{url($moduleRoute.'/blacklist')}}" class="btn btn-default btn-sm active" role="button">返回</a>
    @if(session('message'))
    <p class="bg-success">{{session('message')}}</p>
    @endif    
</div>
<div class="box-body">
            <!-- form start -->
            <form method="POST" action="{{ url($moduleRoute.'/blacklist_form_submit') }}">
 {!! csrf_field() !!} 
<table class="table table-bordered">
<tbody>
    <tr>
        <td width="20%" align="right">ID </td>
        <td>{{$object->id}}</td>
    </tr> 
    <tr>
        <td width="20%" align="right">游戏名</td>
        <td>{{$object->Gamename}}</td>
    </tr>
    
    <tr>
        <td width="20%" align="right">开发者姓名</td>
        <td>{{$object->developer->username}}</td>
    </tr>
     <tr>
        <td width="20%" align="right">分类</td>
        <td>{{$object->Typeid}}</td>
    </tr>
    <tr>
        <td width="20%" align="right">版本</td>
        <td>{{$object->Version}}</td>
    </tr>
    <tr>
        <td width="20%" align="right">备案号</td>
        <td>{{$object->Casenumber}}</td>
    </tr>
    <tr>
        <td width="20%" align="right">介绍</td>
        <td>{{$object->Brief}}</td>
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
            <button name="submit" type="submit" class="btn btn-primary" value="{{$type}}">{{$button}}</button>
        </td>
    </tr>


</tbody>

</table>
<input type="hidden" value="{{$object->id}}" name="id">
<input type="hidden" value="{{$object->Cpid}}" name="cpid">
            </form>
</div>
</div>
@endsection
