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
        <td width="20%" align="right">推广语</td>
        <td>{{$object->Spread}}</td>
    </tr>   
    <tr>
        <td width="20%" align="right">客服电话</td>
        <td>{{$object->serviceTel}}</td>
    </tr>
    <tr>
        <td width="20%" align="right">游戏属性</td>
        <td>{{$object->Isself}}</td>
    </tr>
    <tr>
        <td width="20%" align="right">宣传视频URL</td>
        <td>{{$object->Videourl}}</td>
    </tr>
    <tr>
        <td width="20%" align="right">备注</td>
        <td>{{$object->Remark}}</td>
    </tr>
    <tr>
        <td width="20%" align="right">大图</td>
        <td><img src="{{$object->Logo_big}}" /></td>
    </tr> 
    <tr>
        <td width="20%" align="right">小图</td>
        <td><img src="{{$object->Logo_small}}" /></td>
    </tr>
     <tr>
        <td width="20%" align="right">游戏截图</td>
        <td><img src="{{$object->Gameimg}}" /></td>
    </tr>
    
    <tr>
        <td width="20%" align="right">著作权证书</td>
        <td><img src="{{$object->Copyrightimg}}" /></td>
    </tr> 
    <tr>
        <td width="20%" align="right">独代授权证书</td>
        <td><img src="{{$object->Agencyimg}}" /></td>
    </tr>
     <tr>
        <td width="20%" align="right">专题大图</td>
        <td><img src="{{$object->Specialimg}}" /></td>
    </tr>
    <tr>
        <td width="20%" align="right">期望上线时间</td>
        <td>{{$object->Onlinedate}}</td>
    </tr>
    <tr>
        <td width="20%" align="right">注册时间</td>
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
