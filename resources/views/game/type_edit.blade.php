@extends('dashboard')
@section('content')
<div class="box box-primary">
    <div class="box-header with-border">
        <a href="{{url($moduleRoute.'/types')}}" class="btn btn-default btn-sm" >返回</a>
        @if(session('message'))
        <p class="bg-success">{{session('message')}}</p>
        @endif    
    </div>
{{ Form::open(array('url' => url($moduleRoute.'/types_edit_submit'), 'method' => 'POST')) }}
    <div class="box-body">
                <div class="form-group">
                  <label >类型名</label>
                  <input type="textfield" name="name" value="{{$object->Typename}}" class="form-control" >
                  @if($errors->has('name'))
                    {{$errors->first('name')}}
                  @endif 
                </div>
                <div class="form-group">
                   <label>比重</label>
                   <input type="textfield" name="weight" value="{{$object->ordernum}}" class="form-control" >
                  @if($errors->has('weight'))
                    {{$errors->first('weight')}}
                  @endif 
                </div>
<input type="hidden" name="tid" value="{{$object->Typeid}}" >
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">提交</button>
              </div>
{!!Form::close()!!} 
         </div>
@endsection
