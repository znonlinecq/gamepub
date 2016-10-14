@extends('dashboard')
@section('content')
<div class="box">
<div class="box-header with-border">
    <a href="{{url($moduleRoute.'/rebates')}}" class="btn btn-default btn-sm" role="button">返回</a>
    @if(session('message'))
    <p class="bg-success">{{session('message')}}</p>
    @endif    
</div>
<div class="box-body">
            <!-- form start -->
            <form method="POST" action="{{ url($moduleRoute.'/rebate_setup_form_submit') }}">
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
        <td width="20%" align="right">介绍</td>
        <td>{{$object->Brief}}</td>
    </tr>
    <tr>
        <td width="20%" align="right">返点</td>
        <td>
            <input class="form-control" name="rebate" value="{{$object->Rebate}}">
        </td>
    </tr>
    <tr>
        <td width="20%" align="right"></td>
        <td>
            <button name="submit" type="submit" class="btn btn-primary" value="yes">设置</button>
        </td>
    </tr>


</tbody>

</table>
<input type="hidden" value="{{$object->id}}" name="id">
            </form>
</div>
</div>
@endsection
