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
    @foreach($fields as $key => $value)
    <tr>
        <td width="20%" align="right"> {{$value}}</td>
        @if(preg_match('/img/', $object->$key))
            <td>{!! $object->$key !!}</td>
        @else
            <td>{{$object->$key}}</td>
        @endif
    </tr>
    @endforeach 
</tbody>
</table>
</div>
<br>
</div>
@endsection
