@extends('dashboard')
@section('content')
<div class="box box-primary">
    <div class="box-header with-border">
        <a href="{{url($moduleRoute.'/types')}}" class="btn btn-default btn-sm" >返回</a>
        @if(session('message'))
        <p class="bg-success">{{session('message')}}</p>
        @endif    
    </div>
{{ Form::open(array('url' => url($moduleRoute.'/types_add_submit'), 'method' => 'POST')) }}
    <div class="box-body">
                <div class="form-group">
                  <label >类型名</label>
                  <input type="textfield" name="name" value="{{old('name')}}" class="form-control" >
                  @if($errors->has('name'))
                    {{$errors->first('name')}}
                  @endif 
                </div>
                <div class="form-group">
                   <label>比重</label>
                   <input type="textfield" name="weight" value="{{old('weight')}}" class="form-control" >
                  @if($errors->has('weight'))
                    {{$errors->first('weight')}}
                  @endif 
                </div>
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">提交</button>
              </div>
{!!Form::close()!!} 
         </div>
@endsection
