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
            <form method="POST" action="{{ url($moduleRoute.'/recharge_form_submit') }}">
 {!! csrf_field() !!} 
<table class="table table-bordered">
<tbody> 
    <tr>
        <td width="20%" align="right">类型</td>
        <td>
                  <select class="form-control" name="gid" >
                    <option value="" > - 选择 - </option>
                    @foreach($guilds as $guild)
                    <option value="{{$guild->Id}}" >{{$guild->Name}}-{{$guild->GuildType}}</option>
                    @endforeach
                  </select>
                 @if ($errors->has('gid'))
                    <strong>{{ $errors->first('gid') }}</strong>
                 @endif
        </td>
    </tr>
    

    <tr>
        <td width="20%" align="right">充值金额</td>
        <td><input type="textfield" name="money" value="" class="form-control" >       
        @if ($errors->has('money'))
            <strong>{{ $errors->first('money') }}</strong>
        @endif
        </td> 
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
            <button name="submit" type="submit" class="btn btn-primary" value="yes">提交</button>
        </td>
    </tr>

</tbody>

</table>
            </form>
</div>
</div>
@endsection
