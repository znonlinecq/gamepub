@extends('dashboard')
@section('content')
<div class="box box-primary">
    <div class="box-header with-border">
        <a href="{{url($moduleRoute)}}" class="btn btn-default btn-sm" >返回</a>
        @if(session('message'))
        <p class="bg-success">{{session('message')}}</p>
        @endif    
    </div>
{{ Form::open(array('route' => array($moduleRoute.'.store'), 'method' => 'POST')) }}
    <div class="box-body">
                <div class="form-group">
                  <label >模块名</label>
                  <input type="textfield" name="name" value="{{old('name')}}" class="form-control" >
                  @if($errors->has('name'))
                    {{$errors->first('name')}}
                  @endif 
                </div>
                <div class="form-group">
                  <label >控制器</label>
                  <input type="textfield" name="controller" value="{{old('controller')}}" class="form-control" >
                  @if($errors->has('controller'))
                    {{$errors->first('controller')}}
                  @endif 
                </div>
                <div class="form-group">
                   <label>比重</label>
                   <input type="textfield" name="weight" value="{{old('weight')}}" class="form-control" >
                  @if($errors->has('weight'))
                    {{$errors->first('weight')}}
                  @endif 
                </div>
                <div class="form-group">
                   <label>CSS样式</label>
                   <input type="textfield" name="font" value="{{old('font')}}" class="form-control" >
                  @if($errors->has('font'))
                    {{$errors->first('font')}}
                  @endif 
                </div>
 
               <div class="form-group">
                 {{ Form::label('title', '生成菜单') }}<br>
                 {{ Form::radio('menu', '1', true) }} 是 
                 {{ Form::radio('menu', '0') }} 否
                 @if($errors->has('menu'))
                    {{$errors->first('menu')}}
                  @endif 
                </div>
              <div class="form-group">
                {{ Form::label('title', '描述') }}<br>
               <textarea name="description" class="form-control" >{{old('description')}}</textarea>
                </div>
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">提交</button>
              </div>
{!!Form::close()!!} 
         </div>
@endsection
