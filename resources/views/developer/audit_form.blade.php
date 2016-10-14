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
        <td>{{$object->cpid}}</td>
    </tr>
    <tr>
        <td width="20%" align="right">开发者姓名</td>
        <td>{{$object->username}}</td>
    </tr>
     <tr>
        <td width="20%" align="right">公司名</td>
        <td>{{$object->compname}}</td>
    </tr>
    <tr>
        <td width="20%" align="right">公司网址</td>
        <td>{{$object->compweb}}</td>
    </tr>
    <tr>
        <td width="20%" align="right">公司地址</td>
        <td>{{$object->compaddr}}</td>
    </tr>
    <tr>
        <td width="20%" align="right">认证号</td>
        <td>{{$object->certificateno}}</td>
    </tr>
    <tr>
        <td width="20%" align="right">认证图</td>
        <td><img src="{{$object->certificateimg}}"/></td>
    </tr>   
    <tr>
        <td width="20%" align="right">税号</td>
        <td>{{$object->taxno}}</td>
    </tr>
    <tr>
        <td width="20%" align="right">纳税图</td>
        <td><img src="{{$object->taximg}}" /></td>
    </tr>
     <tr>
        <td width="20%" align="right">联系人姓名</td>
        <td>{{$object->conname}}</td>
    </tr>
    <tr>
        <td width="20%" align="right">联系人职位</td>
        <td>{{$object->conposition}}</td>
    </tr>
    <tr>
        <td width="20%" align="right">联系人手机号</td>
        <td>{{$object->conmobile}}</td>
    </tr>
    <tr>
        <td width="20%" align="right">联系人QQ</td>
        <td>{{$object->conqq}}</td>
    </tr>
    <tr>
        <td width="20%" align="right">联系人E-mail</td>
        <td>{{$object->conemail}}</td>
    </tr>
    <tr>
        <td width="20%" align="right">注册时间</td>
        <td>{{$object->adddate}}</td>
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
<input type="hidden" value="{{$object->cpid}}" name="cpid">
            </form>
</div>
</div>
@endsection
